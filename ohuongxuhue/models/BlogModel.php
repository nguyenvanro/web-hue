<?php
/**
 * Model quản lý bài viết Blog O Hương Xứ Huế
 * Gồm 2 chuyên mục: Góc Ẩm Thực & Góc Du Lịch
 * Nội dung chuyên mục được nạp từ các file JSON
 */

class BlogModel extends BaseModel {
    private static $blogs = [
        [
            'id'           => 101,
            'slug'         => 'kham-pha-dai-noi-va-7-lang-tam-trieu-nguyen',
            'title'        => 'Khám Phá Đại Nội & 7 Lăng Tẩm Triều Nguyễn Tráng Lệ Giữa Lòng Cố Đô',
            'category'     => 'Góc Du Lịch',
            'category_slug'=> 'goc-du-lich',
            'author'       => 'Hướng Dẫn Viên Hoàng Nam',
            'date'         => '16/09/2026',
            'read_time'    => '7 phút đọc',
            'featured'     => true,
            'image'        => 'https://images.unsplash.com/photo-1599707367072-cd6ada2bc375?auto=format&fit=crop&w=800&q=80',
            'summary'      => 'Hành trình chiêm ngưỡng kiến trúc cung đình đỉnh cao qua Kinh Thành Huế, Ngọ Môn, Điện Thái Hòa và hệ thống lăng tẩm vua Tự Đức, Khải Định, Minh Mạng.',
            'content'      => 'Đại Nội Huế cùng quần thể di tích Cố Đô là di sản văn hóa thế giới đầu tiên của Việt Nam được UNESCO vinh danh. Đến với Huế, du khách không chỉ đắm chìm trong vẻ trầm mặc của những bức tường thành rêu phong mà còn ngỡ ngàng trước nghệ thuật kiến trúc đỉnh cao kết hợp giữa phong thủy phương Đông và thẩm mỹ cung đình triều Nguyễn. Đừng quên thuê một bộ cổ phục Nhật Bình hay áo ngũ thân để có những bức ảnh kỷ niệm tuyệt đẹp nơi Ngọ Môn, Tử Cấm Thành.'
        ],
        [
            'id'           => 102,
            'slug'         => 'hanh-trinh-don-binh-minh-tren-pha-tam-giang',
            'title'        => 'Hành Trình Đón Bình Minh Rực Rỡ Trên Phá Tam Giang & Rừng Ngập Mặn Rú Chá',
            'category'     => 'Góc Du Lịch',
            'category_slug'=> 'goc-du-lich',
            'author'       => 'Phượt Thủ Lê Minh',
            'date'         => '14/09/2026',
            'read_time'    => '5 phút đọc',
            'featured'     => false,
            'image'        => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=700&q=80',
            'summary'      => 'Khám phá đầm phá nước lợ lớn nhất Đông Nam Á khi mặt trời vừa ló dạng và lạc bước vào khu rừng ngập mặn nguyên sinh Rú Chá ma mị.',
            'content'      => 'Nằm cách trung tâm thành phố Huế khoảng 15km, phá Tam Giang mở ra một bức tranh thủy mặc bao la với nhịp sống bình dị của ngư dân làng chài đầm Chuồn. 5 giờ sáng, khi mặt trời đỏ ửng nhuộm vàng mặt nước lấp lánh cũng là lúc những chiếc thuyền nan chở đầy tôm cá trở về bến. Kế bên là rừng ngập mặn Rú Chá với những thân cây chá cổ thụ vươn cành đan xen rực rỡ sắc vàng sắc đỏ mỗi độ sang thu.'
        ],
        [
            'id'           => 103,
            'slug'         => 'ngam-hoang-hon-song-huong-check-in-cau-trang-tien',
            'title'        => 'Ngắm Hoàng Hôn Sông Hương & Check-in Cầu Tràng Tiền 120 Năm Lịch Sử',
            'category'     => 'Góc Du Lịch',
            'category_slug'=> 'goc-du-lich',
            'author'       => 'Thu Thảo Xứ Huế',
            'date'         => '10/09/2026',
            'read_time'    => '4 phút đọc',
            'featured'     => false,
            'image'        => 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=700&q=80',
            'summary'      => 'Thong dong tản bộ bên bờ sông Hương thơ mộng, lắng nghe câu hò Huế trên thuyền rồng và ngắm nhìn nhịp cầu sáu vài mười hai nhịp lung linh khi thành phố lên đèn.',
            'content'      => 'Cầu Tràng Tiền bắc qua dòng sông Hương êm đềm đã trở thành chứng nhân lịch sử và biểu tượng thi vị bậc nhất của đất Thần Kinh. Khi ráng chiều buông xuống, dòng Hương Giang ửng tím huyền ảo, mặt nước phẳng lặng soi bóng những hàng phượng vĩ. Du khách có thể lên thuyền rồng nghe ca Huế, thả hoa đăng nguyện ước hay ngồi thưởng thức ly chè bột lọc bọc heo quay ngay chân cầu.'
        ]
    ];

    public function getAll($category = null) {
        $authoredArticles = array_merge(
            json_decode(file_get_contents(__DIR__ . '/food-articles.json'), true) ?: [],
            json_decode(file_get_contents(__DIR__ . '/travel-articles.json'), true) ?: []
        );
        $imageBase = function_exists('get_template_directory_uri') ? get_template_directory_uri() . '/assets/images/' : './assets/images/';
        foreach ($authoredArticles as &$article) {
            if (!preg_match('~^https?://~', $article['image'])) {
                $article['image'] = $imageBase . $article['image'];
            }
            $article['url'] = self::articleUrl($article['id']);
            $article['content'] = preg_replace_callback('/href="\?controller=blog&amp;id=(\d+)"/', function ($match) {
                return 'href="' . htmlspecialchars(self::articleUrl((int) $match[1]), ENT_QUOTES, 'UTF-8') . '"';
            }, $article['content']);
        }
        unset($article);
        $authoredIds = array_column($authoredArticles, 'id');
        $legacyBlogs = array_filter(self::$blogs, function ($blog) use ($authoredIds) {
            return !in_array($blog['id'], $authoredIds, true);
        });
        $allBlogs = array_merge($authoredArticles, $legacyBlogs);
        if ($category) {
            $filtered = [];
            foreach ($allBlogs as $b) {
                if ($b['category'] === $category || ($b['category_slug'] ?? '') === $category) {
                    $filtered[] = $b;
                }
            }
            return $filtered;
        }
        return $allBlogs;
    }

    public function getCategories() {
        return [
            ['name' => 'Tất Cả', 'slug' => 'all'],
            ['name' => 'Góc Ẩm Thực', 'slug' => 'goc-am-thuc'],
            ['name' => 'Góc Du Lịch', 'slug' => 'goc-du-lich']
        ];
    }

    public function getFeatured() {
        foreach ($this->getAll() as $blog) {
            if (!empty($blog['featured'])) {
                return $blog;
            }
        }
        return $this->getAll()[0] ?? null;
    }

    public static function articleUrl($id, $absolute = false) {
        $query = '?controller=blog&id=' . (int) $id;
        if (function_exists('home_url')) return home_url('/' . $query);
        if (!$absolute) return 'index.php' . $query;
        $scheme = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://';
        $directory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
        return $scheme . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim($directory, '/') . '/index.php' . $query;
    }

    public function getById($id) {
        $id = (int)$id;
        foreach ($this->getAll() as $blog) {
            if ($blog['id'] === $id) {
                return $blog;
            }
        }
        return null;
    }
}
