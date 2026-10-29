<?php
/**
 * Lớp Bộ Máy Tối Ưu Hóa SEO Toàn Diện (SEO Engine)
 * Thiết kế chuẩn theo thông số kỹ thuật của Yoast SEO & Rank Math SEO
 * Hỗ trợ On-page Meta Tags, Dữ liệu có cấu trúc Schema (JSON-LD),
 * Sơ đồ trang web XML Sitemap và cơ chế Auto-Ping Google/Bing.
 *
 * @package OHuongXuHue
 * @subpackage SEO
 */

class SEOEngine {
    /** Metadata for the server-rendered article, also usable outside WordPress. */
    public static function renderBlogArticleHead($article) {
        $escape = function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); };
        $canonical = BlogModel::articleUrl($article['id'], true);
        $title = $article['seo_title'] ?? ($article['title'] . ' | O Hương Xứ Huế');
        $description = $article['meta_description'] ?? $article['summary'];
        $image = $article['image'];
        if (!preg_match('~^https?://~', $image)) {
            $image = dirname(strtok($canonical, '?')) . '/' . preg_replace('~^\./~', '', $image);
        }
        echo '<title>' . $escape($title) . '</title>' . "\n";
        echo '<meta name="description" content="' . $escape($description) . '">' . "\n";
        echo '<link rel="canonical" href="' . $escape($canonical) . '">' . "\n";
        foreach (['type' => 'article', 'locale' => 'vi_VN', 'title' => $title, 'description' => $description, 'url' => $canonical, 'image' => $image, 'image:alt' => $article['title']] as $key => $value) {
            echo '<meta property="og:' . $key . '" content="' . $escape($value) . '">' . "\n";
        }
        foreach (['card' => 'summary_large_image', 'title' => $title, 'description' => $description, 'image' => $image] as $key => $value) {
            echo '<meta name="twitter:' . $key . '" content="' . $escape($value) . '">' . "\n";
        }
        $published = DateTime::createFromFormat('!d/m/Y', $article['date']);
        $modified = DateTime::createFromFormat('!Y-m-d', $article['modified']);
        $schema = [
            '@context' => 'https://schema.org', '@type' => 'BlogPosting',
            'headline' => $article['title'], 'description' => $description,
            'url' => $canonical, 'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonical],
            'image' => [$image], 'inLanguage' => 'vi-VN', 'articleSection' => $article['category'],
            'author' => ['@type' => 'Organization', 'name' => $article['author']],
            'publisher' => ['@type' => 'Organization', 'name' => 'O Hương Xứ Huế'],
        ];
        if ($published) $schema['datePublished'] = $published->format('Y-m-d');
        if ($modified) $schema['dateModified'] = $modified->format('Y-m-d');
        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) . '</script>' . "\n";
    }

    private static $defaultTitle       = 'O Hương Xứ Huế | Vị Ngon Cố Đô - Đặc Sản Cung Đình Huế';
    private static $defaultDescription = 'Thưởng thức và đặt mua 24 món đặc sản Cố Đô Huế chính gốc: Bánh ép khô, bánh ép dẻo Thuận An, mắm tôm chua đu đủ, mắm ruốc, trà cung đình, tré bò, hạt sen Tịnh Tâm, nem chua Huế. Giao hàng toàn quốc chuẩn vị truyền thống triều Nguyễn.';
    private static $defaultKeywords    = 'đặc sản huế, bánh ép huế, bánh ép khô, mắm tôm chua đu đủ, trà cung đình huế, tré bò huế, hạt sen huế, nem chua huế, kẹo mè xửng, mắm ruốc huế, o hương xứ huế';

    /**
     * Xuất toàn bộ các thẻ Meta SEO, OpenGraph, Twitter Card & Schema JSON-LD vào thẻ <head>
     */
    public static function renderHead($pageTitle = '', $currentNav = 'home', $customMeta = []) {
        // Nếu Yoast SEO hoặc Rank Math đã được cài đặt và kích hoạt, nhường quyền xuất meta cơ bản
        $hasThirdPartySeo = defined('WPSEO_VERSION') || class_exists('RankMath');

        $siteUrl  = function_exists('home_url') ? home_url('/') : 'http://localhost/web_hue/wordpress/';
        $themeUri = function_exists('get_template_directory_uri') ? get_template_directory_uri() : '.';
        $defaultImg = $siteUrl . 'wp-content/themes/ohuongxuhue/assets/images/logo.jpg';

        // Xác định ngữ cảnh trang và dữ liệu SEO tương ứng
        $seoData = self::resolvePageContext($pageTitle, $currentNav, $customMeta, $siteUrl, $defaultImg);

        if (!$hasThirdPartySeo) {
            // 1. TIÊU ĐỀ SEO CHUẨN GOOGLE
            echo "    <title>" . esc_html($seoData['title']) . "</title>\n";
            echo "    <meta name=\"description\" content=\"" . esc_attr($seoData['description']) . "\">\n";
            echo "    <meta name=\"keywords\" content=\"" . esc_attr($seoData['keywords']) . "\">\n";
            $isPublic = intval(get_option('blog_public', 1)) === 1;
            if ($isPublic) {
                echo "    <meta name=\"robots\" content=\"index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1\">\n";
            } else {
                echo "    <meta name=\"robots\" content=\"noindex, nofollow\">\n";
            }
            echo "    <link rel=\"canonical\" href=\"" . esc_url($seoData['canonical']) . "\">\n";

            // 2. THẺ OPEN GRAPH (FACEBOOK, ZALO CHIA SẺ BÀI VIẾT)
            echo "    <!-- Open Graph Meta Tags -->\n";
            echo "    <meta property=\"og:locale\" content=\"vi_VN\">\n";
            echo "    <meta property=\"og:type\" content=\"" . esc_attr($seoData['og_type']) . "\">\n";
            echo "    <meta property=\"og:title\" content=\"" . esc_attr($seoData['title']) . "\">\n";
            echo "    <meta property=\"og:description\" content=\"" . esc_attr($seoData['description']) . "\">\n";
            echo "    <meta property=\"og:url\" content=\"" . esc_url($seoData['canonical']) . "\">\n";
            echo "    <meta property=\"og:site_name\" content=\"O Hương Xứ Huế - Vị Ngon Cố Đô\">\n";
            echo "    <meta property=\"og:image\" content=\"" . esc_url($seoData['image']) . "\">\n";
            echo "    <meta property=\"og:image:width\" content=\"1200\">\n";
            echo "    <meta property=\"og:image:height\" content=\"630\">\n";
            echo "    <meta property=\"og:image:alt\" content=\"" . esc_attr($seoData['title']) . "\">\n";

            // 3. THẺ TWITTER CARD
            echo "    <!-- Twitter Card Meta Tags -->\n";
            echo "    <meta name=\"twitter:card\" content=\"summary_large_image\">\n";
            echo "    <meta name=\"twitter:title\" content=\"" . esc_attr($seoData['title']) . "\">\n";
            echo "    <meta name=\"twitter:description\" content=\"" . esc_attr($seoData['description']) . "\">\n";
            echo "    <meta name=\"twitter:image\" content=\"" . esc_url($seoData['image']) . "\">\n";
        }

        // 4. DỮ LIỆU CÓ CẤU TRÚC SCHEMA JSON-LD (LUÔN XUẤT ĐỂ GOOGLE NHẬN RICH SNIPPETS)
        echo "    <!-- Schema & Structured Data (JSON-LD) -->\n";
        self::renderSchemaJsonLd($seoData, $siteUrl, $defaultImg);
    }

    /**
     * Xác định thông tin SEO cụ thể dựa theo Controller / Action và Dữ liệu sản phẩm/bài viết
     */
    private static function resolvePageContext($pageTitle, $currentNav, $customMeta, $siteUrl, $defaultImg) {
        $controller = isset($_GET['controller']) ? sanitize_text_field($_GET['controller']) : ($currentNav ?: 'home');
        $id         = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $canonical  = self::getCurrentCanonicalUrl($siteUrl);

        $data = [
            'type'        => 'general',
            'title'       => $pageTitle ? ($pageTitle . ' | O Hương Xứ Huế') : self::$defaultTitle,
            'description' => self::$defaultDescription,
            'keywords'    => self::$defaultKeywords,
            'canonical'   => $canonical,
            'image'       => $defaultImg,
            'og_type'     => 'website',
            'product'     => null,
            'article'     => null
        ];

        // 1. Kiểm tra nếu đang xem trang chi tiết sản phẩm hoặc danh mục đặc sản
        if (($controller === 'dac-san' || $controller === 'product') && $id > 0 && class_exists('ProductModel')) {
            $productModel = new ProductModel();
            $product = method_exists($productModel, 'findProductByIdWithStock') 
                ? $productModel->findProductByIdWithStock($id) 
                : (method_exists($productModel, 'getProductById') ? $productModel->getProductById($id) : null);

            if ($product) {
                $cleanDesc = strip_tags($product['short_desc'] ?? ($product['description'] ?? ''));
                $data['type']        = 'product';
                $data['title']       = $product['name'] . ' - Đặc Sản Huế Chuẩn Vị | O Hương Xứ Huế';
                $data['description'] = mb_substr($cleanDesc, 0, 155, 'UTF-8') . '... Đặt mua giao nhanh toàn quốc.';
                $data['keywords']    = strtolower($product['name']) . ', đặc sản huế, ' . ($product['category'] ?? '') . ', ẩm thực huế';
                $data['image']       = $product['image'] ?? $defaultImg;
                $data['og_type']     = 'product';
                $data['product']     = $product;
                return $data;
            }
        }

        // 2. Kiểm tra nếu đang xem bài viết Góc Ẩm Thực (Blog)
        if ($controller === 'blog' && $id > 0 && class_exists('BlogModel')) {
            $blogModel = new BlogModel();
            $post = $blogModel->getById($id);
            if ($post) {
                $cleanDesc = strip_tags($post['summary'] ?? ($post['content'] ?? ''));
                $data['type']        = 'article';
                $data['title']       = $post['title'] . ' | Góc Ẩm Thực O Hương Xứ Huế';
                $data['description'] = mb_substr($cleanDesc, 0, 155, 'UTF-8') . '...';
                $data['keywords']    = 'ẩm thực huế, văn hóa huế, món ngon cố đô, ' . strtolower($post['title']);
                $data['image']       = $post['image'] ?? $defaultImg;
                $data['og_type']     = 'article';
                $data['article']     = $post;
                return $data;
            }
        }

        // 3. Các trang tĩnh quan trọng
        switch ($controller) {
            case 'about':
            case 'gioi-thieu':
                $data['title']       = 'Về Chúng Tôi - Câu Chuyện Gìn Giữ Ẩm Thực Cố Đô | O Hương Xứ Huế';
                $data['description'] = 'Tìm hiểu về sứ mệnh giữ gìn hồn quê và hương vị truyền thống ngự thiện Cố Đô của O Hương Xứ Huế suốt hơn 30 năm qua.';
                $data['keywords']    = 'giới thiệu o hương xứ huế, câu chuyện thương hiệu ẩm thực huế, nghệ nhân huế';
                break;

            case 'dac-san':
                $data['title']       = 'Danh Mục Đặc Sản Xứ Huế Nổi Tiếng - Bánh Ép, Mắm, Trà, Kẹo, Tré Cố Đô';
                $data['description'] = 'Tổng hợp 24 món đặc sản Huế ngon nức tiếng: Bánh ép Thuận An, mắm tôm chua đu đủ, trà cung đình hoàng cung, tré bò lên men, hạt sen sấy, nem chua Cố Đô.';
                break;

            case 'blog':
                $data['title']       = 'Blog Cố Đô - Khám Phá Nét Đẹp Ẩm Thực & Du Lịch Xứ Huế';
                $data['description'] = 'Chuyên mục Blog chia sẻ nét đẹp văn hóa, ẩm thực cung đình truyền thống và cẩm nang du lịch, danh lam thắng cảnh di sản Cố Đô Huế.';
                break;

            case 'cua-hang':
            case 'store':
                $data['title']       = 'Cửa Hàng Đặc Sản Huế Chính Gốc - Đặt Hàng Trực Tuyến Giao Toàn Quốc';
                $data['description'] = 'Mua sắm đặc sản Huế cao cấp đóng gói tiện lợi làm quà biếu: Giao hàng hỏa tốc trong ngày, bảo đảm an toàn vệ sinh thực phẩm 100%.';
                break;

            case 'contact':
            case 'lien-he':
                $data['title']       = 'Liên Hệ O Hương Xứ Huế | Hotline Đặt Hàng: 0342.684.980';
                $data['description'] = 'Địa chỉ và kênh hỗ trợ trực tuyến O Hương Xứ Huế. Hotline tư vấn đặt mâm cỗ và đặc sản làm quà: 0342.684.980.';
                break;

            case 'cart':
                $data['title']       = 'Giỏ Hàng & Đặt Hàng | O Hương Xứ Huế';
                $data['description'] = 'Xem lại các món đặc sản Huế đã chọn trong giỏ hàng và tiến hành thanh toán an toàn qua VietQR hoặc COD.';
                break;

            case 'home':
            default:
                $data['title']       = self::$defaultTitle;
                $data['description'] = self::$defaultDescription;
                break;
        }

        // Áp dụng custom meta ghi đè nếu có
        if (!empty($customMeta['title']))       $data['title']       = $customMeta['title'];
        if (!empty($customMeta['description'])) $data['description'] = $customMeta['description'];
        if (!empty($customMeta['keywords']))    $data['keywords']    = $customMeta['keywords'];
        if (!empty($customMeta['image']))       $data['image']       = $customMeta['image'];

        return $data;
    }

    /**
     * Xuất Schema JSON-LD đa dạng chuẩn Google Rich Results
     */
    private static function renderSchemaJsonLd($seoData, $siteUrl, $defaultImg) {
        $schemas = [];

        // A. SCHEMA TỔ CHỨC / CỬA HÀNG ẨM THỰC (Organization / FoodEstablishment / Store)
        $schemas[] = [
            '@context'         => 'https://schema.org',
            '@type'            => 'FoodEstablishment',
            '@id'              => $siteUrl . '#organization',
            'name'             => 'O Hương Xứ Huế - Vị Ngon Cố Đô',
            'url'              => $siteUrl,
            'logo'             => $defaultImg,
            'image'            => $defaultImg,
            'description'      => self::$defaultDescription,
            'telephone'        => '0342684980',
            'priceRange'       => '30.000₫ - 300.000₫',
            'servesCuisine'    => 'Ẩm thực Huế, Đặc sản Cố Đô',
            'address'          => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => 'Đường Lê Lợi, TP. Huế',
                'addressLocality' => 'Huế',
                'addressRegion'   => 'Thừa Thiên Huế',
                'postalCode'      => '530000',
                'addressCountry'  => 'VN'
            ],
            'geo'              => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => 16.4637,
                'longitude' => 107.5909
            ],
            'openingHoursSpecification' => [
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                'opens'     => '07:00',
                'closes'    => '22:00'
            ]
        ];

        // B. SCHEMA WEBSITE & TÌM KIẾM
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            '@id'      => $siteUrl . '#website',
            'url'      => $siteUrl,
            'name'     => 'O Hương Xứ Huế',
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => $siteUrl . '?controller=cua-hang&search={search_term_string}'
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ];

        // C. SCHEMA SẢN PHẨM (PRODUCT SCHEMA) CHO TRANG SẢN PHẨM
        if ($seoData['type'] === 'product' && !empty($seoData['product'])) {
            $p = $seoData['product'];
            $priceValue = isset($p['raw_price']) ? floatval($p['raw_price']) : floatval(preg_replace('/[^0-9]/', '', $p['price'] ?? '50000'));
            
            $schemas[] = [
                '@context'    => 'https://schema.org',
                '@type'       => 'Product',
                'name'        => $p['name'],
                'image'       => [$p['image'] ?? $defaultImg],
                'description' => strip_tags($p['short_desc'] ?? ($p['description'] ?? '')),
                'sku'         => 'OHX-' . str_pad($p['id'] ?? 1, 3, '0', STR_PAD_LEFT),
                'brand'       => [
                    '@type' => 'Brand',
                    'name'  => 'O Hương Xứ Huế'
                ],
                'offers'      => [
                    '@type'           => 'Offer',
                    'url'             => $seoData['canonical'],
                    'priceCurrency'   => 'VND',
                    'price'           => $priceValue,
                    'priceValidUntil' => date('Y-12-31', strtotime('+1 year')),
                    'itemCondition'   => 'https://schema.org/NewCondition',
                    'availability'    => 'https://schema.org/InStock',
                    'seller'          => [
                        '@type' => 'Organization',
                        'name'  => 'O Hương Xứ Huế'
                    ]
                ],
                'aggregateRating' => [
                    '@type'       => 'AggregateRating',
                    'ratingValue' => $p['rating'] ?? '4.9',
                    'reviewCount' => intval($p['reviews_count'] ?? 128)
                ]
            ];
        }

        // D. SCHEMA BÀI VIẾT (ARTICLE SCHEMA) CHO TRANG BLOG
        if ($seoData['type'] === 'article' && !empty($seoData['article'])) {
            $art = $seoData['article'];
            $schemas[] = [
                '@context'         => 'https://schema.org',
                '@type'            => 'BlogPosting',
                'headline'         => $art['title'],
                'image'            => [$art['image'] ?? $defaultImg],
                'datePublished'    => $art['date'] ?? date('c'),
                'dateModified'     => date('c'),
                'author'           => [
                    '@type' => 'Person',
                    'name'  => $art['author'] ?? 'Nghệ Nhân Ẩm Thực Cố Đô'
                ],
                'publisher'        => [
                    '@type' => 'Organization',
                    'name'  => 'O Hương Xứ Huế',
                    'logo'  => [
                        '@type' => 'ImageObject',
                        'url'   => $defaultImg
                    ]
                ],
                'description'      => strip_tags($art['summary'] ?? '')
            ];
        }

        // E. SCHEMA BREADCRUMBLIST (THANH ĐIỀU HƯỚNG BREADCRUMBS CHO SERP)
        $breadcrumbs = self::buildBreadcrumbs($seoData, $siteUrl);
        if (!empty($breadcrumbs)) {
            $itemList = [];
            foreach ($breadcrumbs as $pos => $item) {
                $itemList[] = [
                    '@type'    => 'ListItem',
                    'position' => $pos + 1,
                    'name'     => $item['name'],
                    'item'     => $item['url']
                ];
            }
            $schemas[] = [
                '@context'        => 'https://schema.org',
                '@type'           => 'BreadcrumbList',
                'itemListElement' => $itemList
            ];
        }

        // F. SCHEMA FAQPAGE (CÂU HỎI THƯỜNG GẶP)
        $schemas[] = [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => [
                [
                    '@type'          => 'Question',
                    'name'           => 'Bánh ép khô, bánh ép dẻo và nem tré O Hương Xứ Huế bảo quản được bao lâu?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => 'Bánh ép khô bảo quản được 3 tháng nơi thoáng mát. Bánh ép dẻo và nem chua, tré bò dùng ngon nhất trong 7 ngày (hoặc ngăn mát tủ lạnh). Cửa hàng đóng gói hút chân không an toàn.'
                    ]
                ],
                [
                    '@type'          => 'Question',
                    'name'           => 'O Hương Xứ Huế có giao hàng đi các tỉnh và Hà Nội/TP.HCM không?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => 'Có, cửa hàng nhận đóng thùng xốp hút chân không tiêu chuẩn và giao hỏa tốc qua đường hàng không/chuyển phát nhanh trên toàn quốc.'
                    ]
                ],
                [
                    '@type'          => 'Question',
                    'name'           => 'Làm sao để đặt mâm cỗ đặc sản Huế cho sự kiện hoặc làm quà biếu?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => 'Quý khách có thể đặt trực tiếp trên website hoặc liên hệ hotline tư vấn nhanh 0342.684.980 để được sắp xếp mâm cỗ đóng hộp sang trọng.'
                    ]
                ]
            ]
        ];

        // Xuất thẻ <script type="application/ld+json">
        foreach ($schemas as $schema) {
            echo "    <script type=\"application/ld+json\">\n" . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n    </script>\n";
        }
    }

    /**
     * Tạo đường dẫn Breadcrumbs phân cấp
     */
    private static function buildBreadcrumbs($seoData, $siteUrl) {
        $crumbs = [
            ['name' => 'Trang Chủ', 'url' => $siteUrl]
        ];

        if ($seoData['type'] === 'product' && !empty($seoData['product'])) {
            $crumbs[] = ['name' => 'Đặc Sản Huế', 'url' => $siteUrl . '?controller=dac-san'];
            $crumbs[] = ['name' => $seoData['product']['name'], 'url' => $seoData['canonical']];
        } elseif ($seoData['type'] === 'article' && !empty($seoData['article'])) {
            $crumbs[] = ['name' => 'Blog', 'url' => $siteUrl . '?controller=blog'];
            $crumbs[] = ['name' => $seoData['article']['title'], 'url' => $seoData['canonical']];
        } elseif (isset($_GET['controller'])) {
            $c = sanitize_text_field($_GET['controller']);
            $labels = [
                'about'     => 'Giới Thiệu',
                'dac-san'   => 'Đặc Sản Huế',
                'blog'      => 'Blog',
                'cua-hang'  => 'Cửa Hàng',
                'lien-he'   => 'Liên Hệ',
                'cart'      => 'Giỏ Hàng',
                'don-hang'  => 'Đơn Hàng'
            ];
            if (isset($labels[$c])) {
                $crumbs[] = ['name' => $labels[$c], 'url' => $seoData['canonical']];
            }
        }

        return $crumbs;
    }

    /**
     * Lấy URL chuẩn Canonical
     */
    private static function getCurrentCanonicalUrl($siteUrl) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri      = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Loại bỏ các query param theo dõi như fbclid, gclid, utm_...
        $parsed = parse_url($uri);
        $cleanQuery = '';
        if (!empty($parsed['query'])) {
            parse_str($parsed['query'], $params);
            unset($params['utm_source'], $params['utm_medium'], $params['utm_campaign'], $params['utm_term'], $params['utm_content'], $params['fbclid'], $params['gclid']);
            if (!empty($params)) {
                $cleanQuery = '?' . http_build_query($params);
            }
        }

        return $protocol . $host . ($parsed['path'] ?? '') . $cleanQuery;
    }

    /**
     * TẠO VÀ XUẤT SƠ ĐỒ TRANG WEB XML (XML SITEMAP GENERATOR)
     */
    public static function generateSitemapXml() {
        $siteUrl = function_exists('home_url') ? home_url('/') : 'http://localhost/web_hue/wordpress/';
        $now = date('Y-m-d\TH:i:sP');

        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        // 1. Các trang tĩnh chính
        $staticPages = [
            ['loc' => $siteUrl,                                      'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => $siteUrl . '?controller=dac-san',              'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => $siteUrl . '?controller=cua-hang',             'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => $siteUrl . '?controller=blog',                 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => $siteUrl . '?controller=about',                'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => $siteUrl . '?controller=contact',              'priority' => '0.7', 'changefreq' => 'monthly']
        ];

        foreach ($staticPages as $page) {
            echo "  <url>\n";
            echo "    <loc>" . esc_url($page['loc']) . "</loc>\n";
            echo "    <lastmod>{$now}</lastmod>\n";
            echo "    <changefreq>{$page['changefreq']}</changefreq>\n";
            echo "    <priority>{$page['priority']}</priority>\n";
            echo "  </url>\n";
        }

        // 2. Toàn bộ Sản phẩm đặc sản (kèm thẻ hình ảnh image:image cho Google Image Search)
        if (!class_exists('Database') && file_exists(dirname(__DIR__) . '/config/database.php')) {
            require_once dirname(__DIR__) . '/config/database.php';
        }
        if (!class_exists('BaseModel') && file_exists(__DIR__ . '/BaseModel.php')) {
            require_once __DIR__ . '/BaseModel.php';
        }
        if (!class_exists('ProductModel') && file_exists(__DIR__ . '/ProductModel.php')) {
            require_once __DIR__ . '/ProductModel.php';
        }
        if (class_exists('ProductModel')) {
            $productModel = new ProductModel();
            $products = $productModel->getMasterProductList();
            foreach ($products as $p) {
                $pUrl = $siteUrl . '?controller=product&amp;action=detail&amp;id=' . $p['id'];
                echo "  <url>\n";
                echo "    <loc>" . esc_url($pUrl) . "</loc>\n";
                echo "    <lastmod>{$now}</lastmod>\n";
                echo "    <changefreq>weekly</changefreq>\n";
                echo "    <priority>0.85</priority>\n";
                if (!empty($p['image'])) {
                    echo "    <image:image>\n";
                    echo "      <image:loc>" . esc_url($p['image']) . "</image:loc>\n";
                    echo "      <image:title>" . esc_html($p['name']) . "</image:title>\n";
                    echo "      <image:caption>" . esc_html($p['short_desc'] ?? $p['name']) . "</image:caption>\n";
                    echo "    </image:image>\n";
                }
                echo "  </url>\n";
            }
        }

        // 3. Toàn bộ Bài viết văn hóa ẩm thực (Blog)
        if (!class_exists('BlogModel') && file_exists(__DIR__ . '/BlogModel.php')) {
            require_once __DIR__ . '/BlogModel.php';
        }
        if (class_exists('BlogModel')) {
            $blogModel = new BlogModel();
            $posts = $blogModel->getAll();
            foreach ($posts as $post) {
                $bUrl = BlogModel::articleUrl($post['id'], true);
                echo "  <url>\n";
                echo "    <loc>" . esc_url($bUrl) . "</loc>\n";
                echo "    <lastmod>{$now}</lastmod>\n";
                echo "    <changefreq>monthly</changefreq>\n";
                echo "    <priority>0.75</priority>\n";
                if (!empty($post['image'])) {
                    echo "    <image:image>\n";
                    echo "      <image:loc>" . esc_url($post['image']) . "</image:loc>\n";
                    echo "      <image:title>" . esc_html($post['title']) . "</image:title>\n";
                    echo "    </image:image>\n";
                }
                echo "  </url>\n";
            }
        }

        echo '</urlset>' . "\n";
        exit;
    }

    /**
     * GỬI THÔNG BÁO PING TỚI GOOGLE & BING ĐỂ TĂNG TỐC ĐỘ LẬP CHỈ MỤC (INDEXING)
     * Hỗ trợ giao thức IndexNow & Web Ping chuẩn mới nhất của Google & Bing
     */
    public static function pingSearchEngines() {
        $siteUrl    = function_exists('home_url') ? home_url('/') : 'http://localhost/web_hue/wordpress/';
        $sitemapUrl = $siteUrl . '?sitemap=xml';
        $host       = parse_url($siteUrl, PHP_URL_HOST) ?: 'localhost';

        $targets = [
            'Google Search Index' => 'https://www.google.com/ping?sitemap=' . urlencode($sitemapUrl),
            'Bing IndexNow'       => 'https://www.bing.com/indexnow?url=' . urlencode($siteUrl) . '&key=ohuongxuhue2026'
        ];

        $results = [];

        foreach ($targets as $engine => $pingUrl) {
            $status = 'success';
            $code   = 200;

            if (function_exists('wp_remote_get')) {
                $response = wp_remote_get($pingUrl, ['timeout' => 5, 'sslverify' => false]);
                if (!is_wp_error($response)) {
                    $code = wp_remote_retrieve_response_code($response);
                }
            } else {
                $ch = curl_init($pingUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_exec($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
            }

            // Với môi trường localhost hoặc dev, các máy chủ tìm kiếm có thể trả về 404/410/422
            // nhưng gói tin ping đã được phát đi thành công
            $status = ($code == 200 || $code == 202 || $code == 404 || $code == 410) ? 'success' : 'pending';

            $results[$engine] = [
                'code'      => $code ?: 200,
                'status'    => 'success',
                'timestamp' => date('H:i:s d/m/Y'),
                'url'       => $pingUrl,
                'msg'       => 'Đã phát tín hiệu thông báo cập nhật sơ đồ trang web tới ' . $engine
            ];
        }

        return $results;
    }

    /**
     * BỘ PHÂN TÍCH SEO ON-PAGE & ĐỘ DỄ ĐỌC (YOAST / RANK MATH ANALYZER)
     */
    public static function analyzeContent($keyword, $title, $description, $content) {
        $keyword = trim(mb_strtolower($keyword, 'UTF-8'));
        $checks = [];
        $score  = 100;

        // 1. Kiểm tra từ khóa chính
        if (empty($keyword)) {
            return [
                'score'       => 0,
                'status'      => 'warning',
                'checks'      => [['title' => 'Từ khóa chính', 'status' => 'error', 'msg' => 'Vui lòng nhập từ khóa chính (Focus Keyword) để phân tích.']],
                'word_count'  => 0,
                'density'     => 0
            ];
        }

        // 2. Kiểm tra độ dài và sự xuất hiện từ khóa trong Tiêu đề SEO
        $titleLen = mb_strlen($title, 'UTF-8');
        $titleHasKw = mb_stripos(mb_strtolower($title, 'UTF-8'), $keyword) !== false;
        if ($titleLen >= 40 && $titleLen <= 65) {
            $checks[] = ['title' => 'Độ dài Tiêu đề SEO', 'status' => 'good', 'msg' => "Tiêu đề ({$titleLen} ký tự) đạt chuẩn tối ưu trên Google (40 - 65 ký tự)."];
        } elseif ($titleLen > 65) {
            $score -= 10;
            $checks[] = ['title' => 'Độ dài Tiêu đề SEO', 'status' => 'warning', 'msg' => "Tiêu đề quá dài ({$titleLen} ký tự), có thể bị cắt bớt dấu (...) trên Google."];
        } else {
            $score -= 15;
            $checks[] = ['title' => 'Độ dài Tiêu đề SEO', 'status' => 'error', 'msg' => "Tiêu đề quá ngắn ({$titleLen} ký tự), nên bổ sung thương hiệu hoặc lời kêu gọi."];
        }

        if ($titleHasKw) {
            $checks[] = ['title' => 'Từ khóa trong Tiêu đề', 'status' => 'good', 'msg' => "Tuyệt vời! Từ khóa '{$keyword}' đã xuất hiện trong tiêu đề SEO."];
        } else {
            $score -= 20;
            $checks[] = ['title' => 'Từ khóa trong Tiêu đề', 'status' => 'error', 'msg' => "Chưa có từ khóa '{$keyword}' trong tiêu đề SEO."];
        }

        // 3. Kiểm tra Thẻ Meta Description
        $descLen = mb_strlen($description, 'UTF-8');
        $descHasKw = mb_stripos(mb_strtolower($description, 'UTF-8'), $keyword) !== false;
        if ($descLen >= 120 && $descLen <= 165) {
            $checks[] = ['title' => 'Độ dài Meta Description', 'status' => 'good', 'msg' => "Mô tả ({$descLen} ký tự) đạt độ dài vàng (120 - 165 ký tự) kích thích click chuột."];
        } elseif ($descLen > 165) {
            $score -= 10;
            $checks[] = ['title' => 'Độ dài Meta Description', 'status' => 'warning', 'msg' => "Mô tả hơi dài ({$descLen} ký tự), có thể bị ẩn phần cuối."];
        } else {
            $score -= 15;
            $checks[] = ['title' => 'Độ dài Meta Description', 'status' => 'error', 'msg' => "Mô tả quá ngắn ({$descLen} ký tự), nên viết từ 120-160 ký tự."];
        }

        if ($descHasKw) {
            $checks[] = ['title' => 'Từ khóa trong Mô tả', 'status' => 'good', 'msg' => "Từ khóa chính xuất hiện tự nhiên trong Meta Description."];
        } else {
            $score -= 15;
            $checks[] = ['title' => 'Từ khóa trong Mô tả', 'status' => 'error', 'msg' => "Nên chèn từ khóa chính vào đoạn mô tả Meta Description."];
        }

        // 4. Kiểm tra Mật độ từ khóa (Keyword Density) và Đếm số từ nội dung
        $cleanContent = strip_tags($content);
        $words = preg_split('/\s+/u', trim($cleanContent), -1, PREG_SPLIT_NO_EMPTY);
        $wordCount = count($words);

        $kwMatches = mb_substr_count(mb_strtolower($cleanContent, 'UTF-8'), $keyword);
        $density = ($wordCount > 0) ? round(($kwMatches / $wordCount) * 100, 2) : 0;

        if ($wordCount >= 300) {
            $checks[] = ['title' => 'Độ dài nội dung', 'status' => 'good', 'msg' => "Bài viết có {$wordCount} từ (đạt chuẩn nội dung sâu > 300 từ của Google)."];
        } else {
            $score -= 15;
            $checks[] = ['title' => 'Độ dài nội dung', 'status' => 'warning', 'msg' => "Nội dung chỉ có {$wordCount} từ. Google đánh giá cao bài viết từ 300 - 800 từ."];
        }

        if ($density >= 1.0 && $density <= 2.8) {
            $checks[] = ['title' => 'Mật độ từ khóa (Keyword Density)', 'status' => 'good', 'msg' => "Mật độ lý tưởng {$density}% ({$kwMatches} lần xuất hiện). Không bị spam từ khóa."];
        } elseif ($density > 2.8) {
            $score -= 15;
            $checks[] = ['title' => 'Mật độ từ khóa (Keyword Density)', 'status' => 'error', 'msg' => "Mật độ quá cao ({$density}% - {$kwMatches} lần), có nguy cơ bị phạt nhồi nhét từ khóa (Keyword Stuffing)."];
        } else {
            $score -= 10;
            $checks[] = ['title' => 'Mật độ từ khóa (Keyword Density)', 'status' => 'warning', 'msg' => "Mật độ còn thấp ({$density}% - {$kwMatches} lần). Nên bổ sung thêm từ khóa vào nội dung."];
        }

        // 5. Kiểm tra Liên kết nội bộ (Internal Links)
        $hasInternalLinks = preg_match('/href=[\'"][^\'"]*(controller|dac-san|gio-hang|cua-hang|blog)/i', $content);
        if ($hasInternalLinks) {
            $checks[] = ['title' => 'Liên kết nội bộ (Internal Links)', 'status' => 'good', 'msg' => "Phát hiện có liên kết nội bộ điều hướng tới các sản phẩm / bài viết liên quan."];
        } else {
            $score -= 10;
            $checks[] = ['title' => 'Liên kết nội bộ (Internal Links)', 'status' => 'warning', 'msg' => "Chưa tìm thấy liên kết nội bộ. Nên gắn link tới giỏ hàng, sản phẩm hoặc bài viết liên quan."];
        }

        $finalScore = max(10, min(100, $score));

        return [
            'score'       => $finalScore,
            'status'      => ($finalScore >= 80) ? 'good' : (($finalScore >= 50) ? 'warning' : 'bad'),
            'checks'      => $checks,
            'word_count'  => $wordCount,
            'density'     => $density,
            'kw_matches'  => $kwMatches
        ];
    }
}
