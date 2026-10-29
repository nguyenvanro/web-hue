<?php
/**
 * Controller quản lý trang Liên hệ & Hỗ trợ khách hàng (ContactController)
 */

class ContactController extends BaseController {
    public function submit() {
        if (!defined('DONOTCACHEPAGE')) define('DONOTCACHEPAGE', true);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        $reply = function ($success, $message, $status = 200) {
            http_response_code($status);
            echo json_encode(['success' => $success, 'message' => $message], JSON_UNESCAPED_UNICODE);
            exit;
        };
        if (empty($_SESSION['consultation_token'])) $_SESSION['consultation_token'] = bin2hex(random_bytes(32));
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            echo json_encode(['token' => $_SESSION['consultation_token']]);
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $reply(false, 'Phương thức không hợp lệ.', 405);
        $token = $_POST['token'] ?? '';
        if (!is_string($token) || !hash_equals($_SESSION['consultation_token'], $token)) $reply(false, 'Phiên gửi đã hết hạn. Vui lòng thử lại.', 403);
        if (!empty($_POST['website'])) $reply(false, 'Không thể gửi yêu cầu này.', 422);
        $data = [];
        foreach (['fullname', 'phone', 'email', 'subject', 'message'] as $field) {
            $value = $_POST[$field] ?? '';
            if (function_exists('wp_unslash')) $value = wp_unslash($value);
            $data[$field] = is_string($value) ? trim(strip_tags($value)) : '';
        }
        $isNewsletter = ($_POST['intent'] ?? '') === 'newsletter';
        if ($isNewsletter) {
            if (($_POST['consent'] ?? '') !== '1') $reply(false, 'Vui lòng đồng ý nhận tin khuyến mãi trước khi đăng ký.', 422);
            if ($data['email'] === '') $reply(false, 'Vui lòng nhập email để đăng ký nhận tin.', 422);
            $data['fullname'] = 'Khách đăng ký nhận tin';
            $data['subject'] = 'newsletter';
            $data['message'] = 'Khách đồng ý sử dụng email và số điện thoại đã cung cấp để liên hệ và nhận tin khuyến mãi từ O Hương Xứ Huế.';
        } elseif ($data['fullname'] === '' || mb_strlen($data['fullname']) > 100 || $data['message'] === '' || mb_strlen($data['message']) > 5000) {
            $reply(false, 'Vui lòng nhập họ tên và lời nhắn hợp lệ.', 422);
        }
        $digits = preg_replace('/\D/', '', $data['phone']);
        if ((!$isNewsletter || $data['phone'] !== '') && (!preg_match('/^\+?[0-9\s().-]+$/', $data['phone']) || strlen($digits) < 9 || strlen($digits) > 15 || strlen($data['phone']) > 30)) $reply(false, 'Vui lòng kiểm tra số điện thoại liên hệ.', 422);
        if ($data['email'] !== '' && (strlen($data['email']) > 254 || !filter_var($data['email'], FILTER_VALIDATE_EMAIL))) $reply(false, 'Địa chỉ email chưa hợp lệ.', 422);
        if (!in_array($data['subject'], ['order', 'gift', 'agency', 'feedback'], true) && !$isNewsletter) $reply(false, 'Vui lòng chọn chủ đề tư vấn.', 422);
        if (time() - ($_SESSION['consultation_sent_at'] ?? 0) < 60) $reply(false, 'Yêu cầu trước đã được tiếp nhận. Vui lòng chờ một phút nếu cần gửi thêm.', 429);
        require_once __DIR__ . '/../models/ConsultationModel.php';
        if (!(new ConsultationModel())->save($data)) $reply(false, 'Chưa lưu được lời nhắn. Vui lòng thử lại sau hoặc gọi hotline 0342.684.980.', 503);
        $_SESSION['consultation_sent_at'] = time();
        $reply(true, $isNewsletter ? 'Đăng ký thành công! O Hương Xứ Huế đã lưu thông tin nhận tin khuyến mãi của bạn.' : 'Đã tiếp nhận lời nhắn. O Hương Xứ Huế sẽ liên hệ lại theo thông tin quý khách cung cấp.');
    }

    public function index() {
        $successMessage = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name    = sanitize_text_field($_POST['fullname'] ?? '');
            $phone   = sanitize_text_field($_POST['phone'] ?? '');
            $subject = sanitize_text_field($_POST['subject'] ?? '');
            $message = sanitize_textarea_field($_POST['message'] ?? '');

            if (!empty($name) && !empty($phone)) {
                $successMessage = 'Cảm ơn quý khách ' . htmlspecialchars($name) . '! Lời nhắn của bạn đã được gửi thành công. Đội ngũ O Hương Xứ Huế sẽ liên hệ lại trong thời gian sớm nhất!';
            }
        }

        $this->render('contact/index', [
            'pageTitle'      => 'Liên Hệ Với O Hương Xứ Huế - Đặt Hàng & Tư Vấn Quà Biếu',
            'currentNav'     => 'contact',
            'successMessage' => $successMessage
        ]);
    }
}
