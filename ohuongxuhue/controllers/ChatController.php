<?php
class ChatController {
    public static function isStaff() {
        if (function_exists('current_user_can')) return current_user_can('manage_options') || current_user_can('manage_woocommerce');
        return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
    }

    private function reply($payload, $status = 200) {
        http_response_code($status);
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
    }

    public function handle($action) {
        if (!defined('DONOTCACHEPAGE')) define('DONOTCACHEPAGE', true);
        header('Cache-Control: no-store, private');
        header('X-Content-Type-Options: nosniff');
        if ($action === 'inbox') {
            if (!self::isStaff()) { http_response_code(403); echo 'Bạn cần đăng nhập tài khoản quản trị để mở hộp chat.'; return; }
            $chatAdminEndpoint = function_exists('home_url') ? home_url('/?controller=chat') : 'index.php?controller=chat';
            $chatAssetUrl = function_exists('get_template_directory_uri') ? get_template_directory_uri() : '.';
            echo '<!doctype html><html lang="vi"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Chat khách hàng — O Hương Xứ Huế</title></head><body>';
            require __DIR__ . '/../views/chat/admin.php';
            echo '</body></html>';
            return;
        }
        header('Content-Type: application/json; charset=utf-8');
        $allowed = ['bootstrap', 'messages', 'send', 'admin_bootstrap', 'admin_threads', 'admin_messages', 'admin_send'];
        if (!in_array($action, $allowed, true)) $this->reply(['message' => 'Không tìm thấy chức năng chat.'], 404);
        $admin = strpos($action, 'admin_') === 0;
        if ($admin && !self::isStaff()) $this->reply(['message' => 'Bạn không có quyền đọc hoặc trả lời hội thoại.'], 403);
        $write = in_array($action, ['send', 'admin_send'], true);
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== ($write ? 'POST' : 'GET')) $this->reply(['message' => 'Phương thức không hợp lệ.'], 405);
        if (empty($_SESSION['hue_chat_token'])) $_SESSION['hue_chat_token'] = bin2hex(random_bytes(32));
        if ($write) {
            $token = $_POST['token'] ?? '';
            if (!is_string($token) || !hash_equals($_SESSION['hue_chat_token'], $token)) $this->reply(['message' => 'Phiên chat đã thay đổi. Hãy đóng rồi mở lại cửa sổ chat.'], 403);
        }
        try {
            require_once __DIR__ . '/../models/ChatModel.php';
            $model = new ChatModel();
            if ($action === 'admin_bootstrap') $this->reply(['token' => $_SESSION['hue_chat_token']]);
            if ($action === 'admin_threads') $this->reply(['threads' => $model->inbox()]);
            if ($admin) {
                $id = $write ? ($_POST['conversation'] ?? '') : ($_GET['conversation'] ?? '');
                if (!is_string($id) || !preg_match('/^[a-f0-9]{64}$/D', $id) || !$model->exists($id)) $this->reply(['message' => 'Không tìm thấy hội thoại.'], 404);
            } else {
                if (empty($_SESSION['hue_chat_id'])) $_SESSION['hue_chat_id'] = bin2hex(random_bytes(32));
                $id = $_SESSION['hue_chat_id'];
            }
            if ($write) {
                $body = $_POST['body'] ?? '';
                if (function_exists('wp_unslash')) $body = wp_unslash($body);
                $body = is_string($body) ? trim(str_replace("\0", '', $body)) : '';
                $key = $_POST['client_key'] ?? '';
                if ($body === '' || mb_strlen($body) > 2000) $this->reply(['message' => 'Vui lòng nhập tin nhắn từ 1 đến 2.000 ký tự.'], 422);
                if (!is_string($key) || !preg_match('/^[a-zA-Z0-9-]{16,64}$/D', $key)) $this->reply(['message' => 'Mã tin nhắn không hợp lệ.'], 422);
                $now = time();
                $bucketKey = $admin ? 'hue_chat_staff_rate' : 'hue_chat_rate';
                $rate = $_SESSION[$bucketKey] ?? ['start' => $now, 'count' => 0, 'last' => 0, 'key' => ''];
                if ($now - $rate['start'] >= 600) $rate = ['start' => $now, 'count' => 0, 'last' => 0, 'key' => ''];
                if ($key !== $rate['key'] && (($now - $rate['last'] < 1) || $rate['count'] >= ($admin ? 300 : 60))) $this->reply(['message' => 'Bạn đang gửi quá nhanh. Vui lòng chờ một chút.'], 429);
                $messageId = $model->send($id, $admin ? 'staff' : 'guest', $body, $key);
                $_SESSION[$bucketKey] = ['start' => $rate['start'], 'count' => $rate['count'] + 1, 'last' => $now, 'key' => $key];
                $this->reply(['success' => true, 'message_id' => $messageId]);
            }
            $after = max(0, (int) ($_GET['after'] ?? 0));
            $history = $model->history($id, $after);
            if ($admin && $history['messages']) $model->markRead($id, end($history['messages'])['id']);
            if ($action === 'bootstrap') $history['token'] = $_SESSION['hue_chat_token'];
            $this->reply($history);
        } catch (Throwable $error) {
            error_log('Hue chat unavailable: ' . $error->getMessage());
            $this->reply(['message' => 'Chat tạm thời chưa kết nối được. Vui lòng thử lại hoặc liên hệ Zalo.'], 503);
        }
    }
}
