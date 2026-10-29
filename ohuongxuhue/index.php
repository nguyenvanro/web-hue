<?php
/**
 * Front Controller & Router cho mô hình MVC O Hương Xứ Huế
 * Template Name: O Hương Xứ Huế MVC
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Polyfill các hàm tiện ích nếu chạy độc lập ngoài WordPress
if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($str) {
        return is_string($str) ? trim(strip_tags($str)) : '';
    }
}
if (!function_exists('sanitize_textarea_field')) {
    function sanitize_textarea_field($str) {
        if (!is_string($str)) {
            return '';
        }
        // Giữ xuống dòng trong ghi chú khi chạy XAMPP ngoài WordPress.
        return trim(str_replace(["\r\n", "\r"], "\n", strip_tags($str)));
    }
}
if (!function_exists('esc_attr')) {
    function esc_attr($text) {
        return htmlspecialchars((string) $text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
if (!function_exists('esc_url')) {
    function esc_url($url) {
        return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    }
}

// 1. Nạp tệp cấu hình cơ sở dữ liệu
require_once __DIR__ . '/config/database.php';

// 2. Nạp các Model
require_once __DIR__ . '/models/BaseModel.php';
require_once __DIR__ . '/models/CategoryModel.php';
require_once __DIR__ . '/models/ProductModel.php';
require_once __DIR__ . '/models/UserModel.php';
require_once __DIR__ . '/models/BlogModel.php';
require_once __DIR__ . '/models/StoreModel.php';
require_once __DIR__ . '/models/OrderModel.php';

// 3. Nạp các Controller
require_once __DIR__ . '/controllers/BaseController.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/AboutController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/BlogController.php';
require_once __DIR__ . '/controllers/ContactController.php';
require_once __DIR__ . '/controllers/StoreController.php';
require_once __DIR__ . '/controllers/CartController.php';

// 4. Router điều hướng request
$controller = isset($_GET['controller']) ? strtolower(trim($_GET['controller'])) : '';
$page       = isset($_GET['page']) ? strtolower(trim($_GET['page'])) : '';
$action     = isset($_GET['action']) ? strtolower(trim($_GET['action'])) : 'index';

// Điều hướng theo controller và action
if ($controller === 'chat') {
    require_once __DIR__ . '/controllers/ChatController.php';
    (new ChatController())->handle($action);
} elseif ($controller === 'auth') {
    $auth = new AuthController();
    if ($action === 'register') {
        $auth->register();
    } elseif ($action === 'logout') {
        $auth->logout();
    } else {
        $auth->login();
    }
} elseif ($controller === 'login' || $page === 'login') {
    $auth = new AuthController();
    $auth->login();
} elseif ($controller === 'register' || $page === 'register') {
    $auth = new AuthController();
    $auth->register();
} elseif ($controller === 'logout' || $page === 'logout') {
    $auth = new AuthController();
    $auth->logout();
} elseif ($controller === 'admin' || $page === 'admin') {
    $admin = new AdminController();
    if ($action === 'update_order_status') {
        $admin->updateOrderStatus();
    } elseif ($action === 'import_product' || $action === 'import') {
        $admin->importProduct();
    } else {
        $admin->index();
    }
} elseif ($controller === 'cart' || $controller === 'gio-hang' || $page === 'cart' || $page === 'gio-hang') {
    $cart = new CartController();
    if ($action === 'add') {
        $cart->add();
    } elseif ($action === 'update') {
        $cart->update();
    } elseif ($action === 'checkout') {
        $cart->checkout();
    } elseif ($action === 'orders') {
        $cart->orders();
    } else {
        $cart->index();
    }
} elseif ($controller === 'order' || $controller === 'don-hang' || $controller === 'orders' || $page === 'don-hang') {
    $cart = new CartController();
    $cart->orders();
} elseif ($controller === 'product' || $controller === 'detail' || $page === 'product' || $page === 'detail') {
    $prod = new ProductController();
    if ($action === 'ajax_search') {
        $prod->ajax_search();
    } elseif ($action === 'all' || $action === 'catalog') {
        $prod->all();
    } else {
        $prod->detail();
    }
} elseif ($controller === 'dac-san' || $controller === 'dacsan' || $page === 'dac-san' || $page === 'dacsan' || $controller === 'san-pham') {
    $prod = new ProductController();
    $prod->all();
} elseif ($controller === 'blog' || $controller === 'tin-tuc' || $page === 'blog' || $page === 'tin-tuc') {
    $blog = new BlogController();
    $blog->index();
} elseif ($controller === 'lien-he' || $controller === 'contact' || $page === 'lien-he' || $page === 'contact') {
    $contact = new ContactController();
    if ($action === 'submit') $contact->submit();
    else $contact->index();
} elseif ($controller === 'cua-hang' || $controller === 'store' || $controller === 'stores' || $page === 'cua-hang' || $page === 'store') {
    $store = new StoreController();
    $store->index();
} elseif ($controller === 'about' || $controller === 'gioi-thieu' || $page === 'gioi-thieu' || $page === 'about') {
    $app = new AboutController();
    $app->index();
} else {
    // Mặc định chạy HomeController
    $app = new HomeController();
    $app->index();
}
