<?php
/** Persistent private conversations shared by WordPress staff and site visitors. */
class ChatModel {
    private $db;
    private $wp;
    private $threads;
    private $messages;

    public function __construct() {
        global $wpdb;
        $this->wp = isset($wpdb) && is_object($wpdb);
        if ($this->wp) {
            $this->db = $wpdb;
            $prefix = $wpdb->prefix . 'ohx_chat_';
            $sqlite = false;
        } else {
            $this->db = Database::getInstance()->getConnection();
            if (!$this->db) {
                $path = sys_get_temp_dir() . '/ohx-chat-' . substr(hash('sha256', dirname(__DIR__)), 0, 16) . '.sqlite';
                $this->db = new PDO('sqlite:' . $path, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            }
            $prefix = 'ohx_chat_';
            $sqlite = $this->db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite';
        }
        $this->threads = $prefix . 'threads';
        $this->messages = $prefix . 'messages';
        $auto = $sqlite ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY';
        $charset = $sqlite ? '' : ' CHARACTER SET utf8mb4';
        $this->execute("CREATE TABLE IF NOT EXISTS {$this->threads} (
            id VARCHAR(64) PRIMARY KEY, created_at VARCHAR(32) NOT NULL,
            updated_at VARCHAR(32) NOT NULL, preview TEXT NOT NULL,
            last_guest_id BIGINT NOT NULL DEFAULT 0, last_read_id BIGINT NOT NULL DEFAULT 0
        )" . $charset);
        $this->execute("CREATE TABLE IF NOT EXISTS {$this->messages} (
            id $auto, conversation_id VARCHAR(64) NOT NULL,
            sender VARCHAR(10) NOT NULL, body TEXT NOT NULL, created_at VARCHAR(32) NOT NULL,
            client_key VARCHAR(64) NOT NULL,
            UNIQUE (conversation_id, client_key)
        )" . $charset);
        // The unique index above also indexes conversation_id for history queries.
    }

    private function execute($sql, $params = []) {
        if ($this->wp) {
            if ($params) $sql = $this->db->prepare(str_replace('?', '%s', $sql), $params);
            $result = $this->db->query($sql);
        } else {
            $statement = $this->db->prepare($sql);
            $result = $statement && $statement->execute($params);
        }
        if ($result === false) throw new RuntimeException('Chat database operation failed.');
        return $result;
    }

    private function rows($sql, $params = []) {
        if ($this->wp) {
            if ($params) $sql = $this->db->prepare(str_replace('?', '%s', $sql), $params);
            $rows = $this->db->get_results($sql, ARRAY_A);
            if ($this->db->last_error) throw new RuntimeException('Chat query failed.');
            return $rows ?: [];
        }
        $statement = $this->db->prepare($sql);
        if (!$statement || !$statement->execute($params)) throw new RuntimeException('Chat query failed.');
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function exists($id) {
        return (bool) $this->rows("SELECT id FROM {$this->threads} WHERE id = ?", [$id]);
    }

    public function history($id, $after = 0) {
        $rows = $this->rows("SELECT id, sender, body, created_at FROM {$this->messages} WHERE conversation_id = ? AND id > ? ORDER BY id ASC LIMIT 101", [$id, $after]);
        $more = count($rows) > 100;
        $rows = array_slice($rows, 0, 100);
        foreach ($rows as &$row) $row['id'] = (int) $row['id'];
        return ['messages' => $rows, 'has_more' => $more];
    }

    public function send($id, $sender, $body, $key) {
        $duplicate = $this->rows("SELECT id FROM {$this->messages} WHERE conversation_id = ? AND client_key = ?", [$id, $key]);
        if ($duplicate) return (int) $duplicate[0]['id'];
        $now = gmdate('Y-m-d\TH:i:s\Z');
        if (!$this->exists($id)) {
            if ($sender !== 'guest') throw new RuntimeException('Conversation does not exist.');
            $this->execute("INSERT INTO {$this->threads} (id, created_at, updated_at, preview) VALUES (?, ?, ?, ?)", [$id, $now, $now, '']);
        }
        $this->execute("INSERT INTO {$this->messages} (conversation_id, sender, body, created_at, client_key) VALUES (?, ?, ?, ?, ?)", [$id, $sender, $body, $now, $key]);
        $messageId = $this->wp ? (int) $this->db->insert_id : (int) $this->db->lastInsertId();
        $extra = $sender === 'guest' ? ', last_guest_id = ?' : '';
        $params = [$now, mb_substr($body, 0, 140)];
        if ($sender === 'guest') $params[] = $messageId;
        $params[] = $id;
        $this->execute("UPDATE {$this->threads} SET updated_at = ?, preview = ? $extra WHERE id = ?", $params);
        return $messageId;
    }

    public function inbox() {
        $rows = $this->rows("SELECT id, created_at, updated_at, preview, last_guest_id, last_read_id FROM {$this->threads} ORDER BY updated_at DESC, id ASC LIMIT 100");
        foreach ($rows as &$row) {
            $row['unread'] = (int) $row['last_guest_id'] > (int) $row['last_read_id'];
            $row['label'] = 'Khách ' . strtoupper(substr($row['id'], 0, 6));
        }
        return $rows;
    }

    public function markRead($id, $lastId) {
        $this->execute("UPDATE {$this->threads} SET last_read_id = CASE WHEN last_read_id < ? THEN ? ELSE last_read_id END WHERE id = ?", [$lastId, $lastId, $id]);
    }
}
