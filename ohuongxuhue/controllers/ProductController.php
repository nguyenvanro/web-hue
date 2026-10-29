<?php
/**
 * Controller Quản lý sản phẩm (ProductController)
 * Xử lý hiển thị trang chi tiết sản phẩm, tìm kiếm AJAX thông minh và đánh giá phản hồi
 */

class ProductController extends BaseController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    /**
     * Trang chi tiết sản phẩm đặc sản kèm Đánh giá & Phản hồi thực tế
     */
    public function detail() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 1;

        // Lấy thông tin sản phẩm theo ID kèm số lượng tồn kho
        $product = $this->productModel->findProductByIdWithStock($id);

        // Lấy danh sách sản phẩm tương tự
        $relatedProducts = $this->productModel->getRelatedProducts($id, 4);

        // Phần đánh giá là tùy chọn; thiếu module không làm dừng trang sản phẩm.
        $reviewFile = __DIR__ . '/../models/ReviewModel.php';
        $reviewsEnabled = is_file($reviewFile);
        $reviews = [];
        $ratingStats = ['average' => 0, 'total' => 0, 'star_counts' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0]];
        if ($reviewsEnabled) {
            require_once $reviewFile;
            $reviewModel = new ReviewModel();
            $reviews = $reviewModel->getReviewsByProductId($id);
            $ratingStats = $reviewModel->getProductRatingStats($id);
        }

        // Render ra view product/detail
        $this->render('product/detail', [
            'pageTitle'       => $product['name'] . ' - O Hương Xứ Huế',
            'currentNav'      => 'product',
            'product'         => $product,
            'relatedProducts' => $relatedProducts,
            'reviews'         => $reviews,
            'reviewsEnabled'  => $reviewsEnabled,
            'ratingStats'     => $ratingStats
        ]);
    }

    /**
     * API Tìm kiếm thông minh tức thì (Live AJAX Search)
     */
    public function ajax_search() {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        $query = sanitize_text_field($_GET['q'] ?? '');
        $results = [];
        if ($query !== '') {
            foreach ($this->productModel->getSellableProductsWithStock() as $product) {
                if (!$this->matchesSearch($product, $query)) continue;
                $product['link'] = function_exists('home_url') ? home_url('/?controller=product&id=' . $product['id']) : 'index.php?controller=product&id=' . $product['id'];
                $results[] = array_intersect_key($product, array_flip(['id', 'name', 'price', 'image', 'category', 'stock', 'link']));
            }
        }
        echo json_encode(['success' => true, 'count' => count($results), 'products' => array_slice($results, 0, 8)], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
    }

    /**
     * Tiếp nhận gửi đánh giá & ảnh thực tế từ khách hàng
     */
    public function submit_review() {
        header('Content-Type: application/json; charset=utf-8');

        if (!is_file(__DIR__ . '/../models/ReviewModel.php')) {
            http_response_code(503);
            echo json_encode(['success' => false, 'message' => 'Tính năng đánh giá hiện chưa khả dụng.'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
            exit;
        }

        $productId = intval($_POST['product_id'] ?? 0);
        $userName  = sanitize_text_field($_POST['user_name'] ?? '');
        $userPhone = sanitize_text_field($_POST['user_phone'] ?? '');
        $rating    = intval($_POST['rating'] ?? 5);
        $comment   = sanitize_text_field($_POST['comment'] ?? '');
        $photoUrl  = sanitize_text_field($_POST['photo_url'] ?? '');

        if ($productId <= 0 || empty($userName) || empty($comment)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ họ tên và nội dung cảm nhận!']);
            exit;
        }

        require_once __DIR__ . '/../models/ReviewModel.php';
        $reviewModel = new ReviewModel();
        $ok = $reviewModel->addReview($productId, $userName, $userPhone, $rating, $comment, $photoUrl);

        if ($ok) {
            echo json_encode([
                'success' => true,
                'message' => 'Cảm ơn bạn đã gửi đánh giá! Phản hồi của bạn đã được hiển thị công khai.'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi lưu đánh giá, vui lòng thử lại!']);
        }
        exit;
    }

    /**
     * Trang toàn bộ danh mục Đặc Sản Huế kèm Bộ lọc đa tiêu chí
     */
        public function all() {
        $allProducts = $this->productModel->getAllProductsWithStock();
        $q           = sanitize_text_field($_GET['q'] ?? '');
        $catFilter   = isset($_GET['cat']) ? sanitize_text_field(trim($_GET['cat'])) : '';
        $priceFilter = isset($_GET['price']) ? sanitize_text_field(trim($_GET['price'])) : '';
        $sortBy      = isset($_GET['sort']) ? sanitize_text_field(trim($_GET['sort'])) : 'default';

        // Tìm kiếm chỉ dùng sản phẩm đang bán, cùng bộ lọc với gợi ý AJAX.
        if ($q !== '') {
            $allProducts = array_values(array_filter($this->productModel->getSellableProductsWithStock(), function ($product) use ($q) {
                return $this->matchesSearch($product, $q);
            }));
        }

        // 2. Lọc theo danh mục (cat)
        if (!empty($catFilter)) {
            $catFilterPlain = $this->stripAccents($catFilter);
            $allProducts = array_filter($allProducts, function($p) use ($catFilter, $catFilterPlain) {
                $catPlain = $this->stripAccents($p['category'] ?? '');
                $namePlain = $this->stripAccents($p['name'] ?? '');

                if ($catFilter === 'ban-chay' || $catFilter === 'best_seller' || $catFilterPlain === 'ban chay' || $catFilterPlain === 'ban chay nhat') {
                    $rating = floatval($p['rating'] ?? 0);
                    $reviews = intval($p['reviews_count'] ?? 0);
                    return ($rating >= 4.9 || $reviews >= 110);
                }
                if ($catFilter === 'banh-ep-hue' || $catFilter === 'banh-ep' || $catFilter === 'banh-hue' || $catFilter === 'banh-loc' || $catFilter === 'banh-nam' || $catFilter === 'Bánh' || $catFilterPlain === 'banh') {
                    return strpos($catPlain, 'banh') !== false || strpos($namePlain, 'banh') !== false;
                }
                if ($catFilter === 'mam-hue' || $catFilter === 'mam' || $catFilter === 'mam-tom-chua' || $catFilter === 'Mắm' || $catFilterPlain === 'mam') {
                    return strpos($catPlain, 'mam') !== false || strpos($namePlain, 'mam') !== false;
                }
                if ($catFilter === 'tra-hue' || $catFilter === 'tra-cung-dinh' || $catFilter === 'tra' || $catFilter === 'Trà' || $catFilterPlain === 'tra') {
                    return strpos($catPlain, 'tra') !== false || strpos($namePlain, 'tra') !== false || strpos($namePlain, 'ca phe') !== false;
                }
                if ($catFilter === 'keo-hue' || $catFilter === 'me-xung' || $catFilter === 'keo' || $catFilter === 'Mè Xửng' || $catFilterPlain === 'me xung') {
                    return strpos($catPlain, 'keo') !== false || strpos($catPlain, 'me') !== false || strpos($namePlain, 'keo') !== false || strpos($namePlain, 'me') !== false;
                }
                if ($catFilter === 'tre-hue' || $catFilter === 'tre' || $catFilter === 'Tré' || $catFilterPlain === 'tre') {
                    return strpos($catPlain, 'tre') !== false || strpos($namePlain, 'tre') !== false;
                }
                if ($catFilter === 'hat-sen-hue' || $catFilter === 'hat-sen' || $catFilter === 'sen' || $catFilter === 'Sen' || $catFilterPlain === 'sen') {
                    return strpos($catPlain, 'sen') !== false || strpos($namePlain, 'sen') !== false;
                }
                if ($catFilter === 'nem-chua-hue' || $catFilter === 'nem-chua' || $catFilter === 'nem' || $catFilter === 'Nem' || $catFilterPlain === 'nem') {
                    return strpos($catPlain, 'nem') !== false || strpos($namePlain, 'nem') !== false;
                }
                if ($catFilter === 'an-vat' || $catFilter === 'Ăn Vặt' || $catFilterPlain === 'an vat') {
                    return strpos($catPlain, 'an vat') !== false || strpos($namePlain, 'kho') !== false || strpos($namePlain, 'banh ep') !== false;
                }
                return strpos($catPlain, $catFilterPlain) !== false || strpos($namePlain, $catFilterPlain) !== false;
            });
            $allProducts = array_values($allProducts);

            if ($catFilter === 'ban-chay' || $catFilter === 'best_seller' || $catFilterPlain === 'ban chay' || $catFilterPlain === 'ban chay nhat') {
                usort($allProducts, function($a, $b) {
                    return ($b['reviews_count'] ?? 0) <=> ($a['reviews_count'] ?? 0);
                });
            }
        }

        // 3. Lọc theo khoảng giá
        if (!empty($priceFilter)) {
            $allProducts = array_filter($allProducts, function($p) use ($priceFilter) {
                $rawPrice = preg_replace('/[^0-9]/', '', (string)$p['price']);
                $val = intval($rawPrice);

                if ($priceFilter === 'under_50k') return $val > 0 && $val < 50000;
                if ($priceFilter === '50_100k') return $val >= 50000 && $val <= 100000;
                if ($priceFilter === '100_200k') return $val > 100000 && $val <= 200000;
                if ($priceFilter === 'above_200k') return $val > 200000;
                return true;
            });
            $allProducts = array_values($allProducts);
        }

        // 4. Sắp xếp sản phẩm
        if ($sortBy === 'price_asc') {
            usort($allProducts, function($a, $b) {
                $pA = intval(preg_replace('/[^0-9]/', '', (string)$a['price']));
                $pB = intval(preg_replace('/[^0-9]/', '', (string)$b['price']));
                return $pA <=> $pB;
            });
        } elseif ($sortBy === 'price_desc') {
            usort($allProducts, function($a, $b) {
                $pA = intval(preg_replace('/[^0-9]/', '', (string)$a['price']));
                $pB = intval(preg_replace('/[^0-9]/', '', (string)$b['price']));
                return $pB <=> $pA;
            });
        } elseif ($sortBy === 'rating') {
            usort($allProducts, function($a, $b) {
                return (floatval($b['rating'] ?? 0) <=> floatval($a['rating'] ?? 0));
            });
        }

        $this->render('product/catalog', [
            'pageTitle'    => !empty($q) ? ('Tìm kiếm: ' . $q . ' - O Hương Xứ Huế') : 'Đặc Sản Xứ Huế Trứ Danh - Bánh, Mè Xửng, Trà, Mắm Cố Đô',
            'currentNav'   => 'dac-san',
            'products'     => $allProducts,
            'searchQuery'  => $q,
            'catFilter'    => $catFilter,
            'priceFilter'  => $priceFilter,
            'sortBy'       => $sortBy
        ]);
    }

    private function matchesSearch($product, $query) {
        $text = $this->stripAccents(html_entity_decode(strip_tags(implode(' ', [
            $product['name'] ?? '', $product['category'] ?? '',
            $product['short_desc'] ?? '', $product['description'] ?? ''
        ])), ENT_QUOTES, 'UTF-8'));
        $tokens = preg_split('/\s+/u', trim($this->stripAccents($query)), -1, PREG_SPLIT_NO_EMPTY);
        foreach ($tokens as $token) {
            if (mb_strpos($text, $token, 0, 'UTF-8') === false) return false;
        }
        return true;
    }

    private function stripAccents($str) {
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/[áàảãạăắằẳẵặâấầẩẫậ]/u', 'a', $str);
        $str = preg_replace('/[éèẻẽẹêếềểễệ]/u', 'e', $str);
        $str = preg_replace('/[íìỉĩị]/u', 'i', $str);
        $str = preg_replace('/[óòỏõọôốồổỗộơớờởỡợ]/u', 'o', $str);
        $str = preg_replace('/[úùủũụưứừửữự]/u', 'u', $str);
        $str = preg_replace('/[ýỳỷỹỵ]/u', 'y', $str);
        $str = preg_replace('/[đ]/u', 'd', $str);
        return mb_strtolower($str, 'UTF-8');
    }
}

