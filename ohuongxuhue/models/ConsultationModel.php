<?php
/** Lưu yêu cầu tư vấn; không ghi thông tin liên hệ vào thư mục công khai. */
class ConsultationModel {
    public function save($data) {
        if (function_exists('wp_insert_post')) {
            $subjects = ['order' => 'Đặt mua đặc sản', 'gift' => 'Tư vấn giỏ quà', 'agency' => 'Đăng ký đại lý', 'feedback' => 'Góp ý dịch vụ', 'newsletter' => 'Đăng ký nhận tin khuyến mãi'];
            $subject = $subjects[$data['subject']];
            $body = "Họ tên: {$data['fullname']}\nĐiện thoại: {$data['phone']}\nEmail: {$data['email']}\nChủ đề: {$subject}\n\n{$data['message']}";
            $id = wp_insert_post(wp_slash([
                'post_type' => 'ohx_consultation', 'post_status' => 'private',
                'post_title' => $data['subject'] === 'newsletter' ? 'Nhận tin khuyến mãi — ' . $data['email'] : $data['fullname'] . ' — ' . $data['phone'],
                'post_content' => $body,
                'meta_input' => ['consultation_phone' => $data['phone'], 'consultation_email' => $data['email'], 'consultation_subject' => $data['subject']],
            ]), true);
            return !is_wp_error($id) && $id > 0;
        }
        // XAMPP: ưu tiên MySQL hiện có; SQLite dự phòng nằm ngoài web root.
        try {
            $db = Database::getInstance()->getConnection();
            if ($db) {
                $created = $db->exec('CREATE TABLE IF NOT EXISTS consultation_messages (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    fullname VARCHAR(100) NOT NULL, phone VARCHAR(30) NOT NULL,
                    email VARCHAR(254) NOT NULL, subject VARCHAR(30) NOT NULL,
                    message TEXT NOT NULL, created_at DATETIME NOT NULL
                ) CHARACTER SET utf8mb4');
            } else {
                $path = sys_get_temp_dir() . '/ohx-consultations-' . substr(hash('sha256', dirname(__DIR__)), 0, 16) . '.sqlite';
                $db = new PDO('sqlite:' . $path, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                $created = $db->exec('CREATE TABLE IF NOT EXISTS consultation_messages (
                    id INTEGER PRIMARY KEY AUTOINCREMENT, fullname TEXT NOT NULL,
                    phone TEXT NOT NULL, email TEXT NOT NULL, subject TEXT NOT NULL,
                    message TEXT NOT NULL, created_at TEXT NOT NULL
                )');
            }
            if ($created === false) return false;
            $statement = $db->prepare('INSERT INTO consultation_messages (fullname, phone, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?, ?)');
            return $statement && $statement->execute([$data['fullname'], $data['phone'], $data['email'], $data['subject'], $data['message'], date('Y-m-d H:i:s')]);
        } catch (Throwable $error) {
            error_log('Consultation storage unavailable: ' . $error->getMessage());
            return false;
        }
    }
}
