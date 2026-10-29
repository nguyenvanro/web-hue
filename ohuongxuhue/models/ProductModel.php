<?php
/**
 * Model quản lý sản phẩm đặc sản O Hương Xứ Huế & Tồn kho, Nhập hàng
 */

require_once __DIR__ . '/BaseModel.php';

class ProductModel extends BaseModel {
    private static $stocksBackupFile;
    private static $importsBackupFile;

    public function __construct() {
        parent::__construct();
        self::$stocksBackupFile = __DIR__ . '/../config/stocks_backup.json';
        self::$importsBackupFile = __DIR__ . '/../config/imports_backup.json';
        $this->initStockTables();
    }

    /**
     * Dữ liệu chi tiết chuẩn cho toàn bộ 24 sản phẩm đặc sản O Hương Xứ Huế
     * Tự động liên kết và đồng bộ trực tiếp với các sản phẩm trong WooCommerce
     */
    public function getMasterProductList() {
        static $memCache = null;
        if ($memCache !== null) {
            return $memCache;
        }
        if (function_exists('get_transient')) {
            $cached = get_transient('ohx_master_products');
            if ($cached !== false && is_array($cached) && !empty($cached)) {
                $memCache = $cached;
                return $cached;
            }
        }

        $imgBase = function_exists('get_template_directory_uri') 
            ? get_template_directory_uri() . '/assets/images/products/' 
            : './wp-content/themes/ohuongxuhue/assets/images/products/';

        $fallbackList = [
            1 => [
                'id'            => 1,
                'name'          => 'Bánh Ép Khô Huế',
                'price'         => '25.000 ₫',
                'raw_price'     => 25000,
                'category'      => 'Bánh Ép Huế',
                'image'         => $imgBase . 'p1_banh_ep_kho_hue.jpg',
                'rating'        => '5.0',
                'reviews_count' => 128,
                'origin'        => 'Thuận An, Thừa Thiên Huế',
                'packaging'     => 'Gói giòn rụm hút chân không',
                'shelf_life'    => '3 tháng',
                'short_desc'    => 'Bánh ép khô giòn rụm đậm đà hương vị biển Thuận An, thơm béo vị hành lá, tôm chấy và thịt ba chỉ.',
                'description'   => '<p>Bánh Ép Khô Huế là món quà vặt tuổi thơ nức tiếng của người dân Cố Đô. Bánh được làm từ tinh bột lọc hảo hạng, ép giòn rụm cùng nhân hành, tôm sấy thơm lừng. Cắn từng miếng giòn tan hòa quyện vị cay the của ớt bột Huế tạo cảm giác khó cưỡng.</p>',
                'ingredients'   => 'Tinh bột sắn lọc thủ công, tôm khô, thịt mỡ thơm giòn, hành lá, ớt bột Huế, dầu thực vật, muối hầm, tiêu sọ.',
                'instructions'  => 'Dùng trực tiếp ngay khi mở bao bì. Chấm cùng tương ớt xào hoặc sốt chua ngọt.',
                'storage'       => 'Bảo quản nơi khô ráo, thoáng mát. Buộc kín miệng túi sau khi mở để giữ độ giòn tan.'
            ],
            2 => [
                'id'            => 2,
                'name'          => 'Bánh Ép Dẻo Huế',
                'price'         => '20.000 ₫',
                'raw_price'     => 20000,
                'category'      => 'Bánh Ép Huế',
                'image'         => $imgBase . 'p2_banh_ep_deo_hue.jpg',
                'rating'        => '4.9',
                'reviews_count' => 95,
                'origin'        => 'TP. Huế',
                'packaging'     => 'Phần ăn kèm rau sống đồ chua',
                'shelf_life'    => '2 ngày (ngăn mát)',
                'short_desc'    => 'Bánh ép dẻo nóng hổi nhân trứng cút, pate, thịt heo tẩm vị ăn kèm rau sống và đồ chua giòn ngọt.',
                'description'   => '<p>Bánh Ép Dẻo Huế là phiên bản bánh ép truyền thống ăn liền được yêu thích bậc nhất. Chiếc bánh dẻo thơm, nóng hổi ép cùng trứng gà, pate thơm béo và hành hoa, cuốn cùng rau răm, dưa chuột và chấm nước mắm chua ngọt chuẩn vị Cố Đô.</p>',
                'ingredients'   => 'Bột lọc tươi, trứng gà ta, thịt nạc dăm, pate gia truyền, mỡ hành, ớt rim, đồ chua (cà rốt, đu đủ bào).',
                'instructions'  => 'Nên làm nóng lại bằng chảo chống dính hoặc nồi chiên không dầu 1-2 phút trước khi ăn. Cuốn cùng rau sống và đồ chua.',
                'storage'       => 'Dùng trong ngày hoặc bảo quản ngăn mát tủ lạnh trong 48 giờ.'
            ],
            3 => [
                'id'            => 3,
                'name'          => 'Bánh Ép Thuận An',
                'price'         => '15.000 ₫',
                'raw_price'     => 15000,
                'category'      => 'Bánh Ép Huế',
                'image'         => $imgBase . 'p3_banh_ep_thuan_an.jpg',
                'rating'        => '4.8',
                'reviews_count' => 110,
                'origin'        => 'Thị trấn Thuận An, Phú Vang, Huế',
                'packaging'     => 'Gói ăn liền tiện lợi',
                'shelf_life'    => '3 tháng',
                'short_desc'    => 'Đặc sản xứ biển Thuận An chính gốc với vị giòn xốp rôm rốp và hương thơm đặc trưng của mỡ hành.',
                'description'   => '<p>Thuận An là cái nôi khai sinh ra món bánh ép nức danh. Chiếc Bánh Ép Thuận An được đóng gói tiện lợi, giữ nguyên độ giòn xốp và mùi thơm quyến rũ của tép biển và hành phi thơm phức.</p>',
                'ingredients'   => 'Bột lọc tuyển chọn, tép đầm phá Tam Giang, mỡ heo, hành lá tươi, tỏi ớt, gia vị Cố Đô.',
                'instructions'  => 'Dùng ăn liền như snack hoặc nhâm nhi cùng nước ngọt, bia mát lạnh.',
                'storage'       => 'Để nơi thoáng mát, tránh ánh nắng trực tiếp.'
            ],
            4 => [
                'id'            => 4,
                'name'          => 'Mắm Tôm Chua Đu Đủ',
                'price'         => '50.000 ₫',
                'raw_price'     => 50000,
                'category'      => 'Mắm Huế',
                'image'         => $imgBase . 'p4_mam_tom_chua_du_du.jpg',
                'rating'        => '5.0',
                'reviews_count' => 184,
                'origin'        => 'Làng nghề Cầu Hai, Phú Lộc, Huế',
                'packaging'     => 'Hũ 500g niêm phong kín',
                'shelf_life'    => '6 tháng',
                'short_desc'    => 'Tôm đất đầm phá lên men tự nhiên giòn ngọt kết hợp đu đủ thái sợi dai giòn sần sật và riềng tỏi cay nồng.',
                'description'   => '<p>Mắm Tôm Chua Đu Đủ là sự giao thoa hài hòa giữa vị chua cay mặn ngọt. Tôm đất còn nguyên con đỏ au óng ả, quyện đều cùng sợi đu đủ xanh ngâm giòn rụm, riềng non thơm phức và ớt chỉ thiên cay xé lưỡi.</p>',
                'ingredients'   => 'Tôm đất tươi Tam Giang, đu đủ xanh tuyển chọn, riềng củ non, tỏi Lý Sơn, ớt sừng, rượu nếp, đường phèn, nước mắm nhĩ.',
                'instructions'  => 'Dùng ăn kèm thịt ba chỉ luộc, bún tươi, bánh tráng cuốn rau sống, dưa leo, vả chát.',
                'storage'       => 'Bảo quản nơi khô mát hoặc ngăn mát tủ lạnh sau khi mở nắp để giữ độ giòn của tôm và đu đủ.'
            ],
            5 => [
                'id'            => 5,
                'name'          => 'Trà Cung Đình Huế',
                'price'         => '90.000 ₫',
                'raw_price'     => 90000,
                'category'      => 'Trà Huế',
                'image'         => $imgBase . 'p5_tra_cung_dinh_hue.jpg',
                'rating'        => '5.0',
                'reviews_count' => 245,
                'origin'        => 'TP. Huế, Thừa Thiên Huế',
                'packaging'     => 'Gói 500g thảo mộc thượng hạng',
                'shelf_life'    => '12 tháng',
                'short_desc'    => 'Thức uống thảo mộc hoàng cung 16 vị bồi bổ long thể, thanh lọc cơ thể, ngủ ngon và điều hòa huyết áp.',
                'description'   => '<p>Trà Cung Đình Huế được bào chế theo phương thức cổ truyền dành riêng cho triều đình nhà Nguyễn. 16 vị thảo mộc thiên nhiên được sao tẩm hạ thổ công phu tạo nên tách trà màu vàng hổ phách, vị ngọt thanh tự nhiên và hương hoa thoang thoảng quý phái.</p>',
                'ingredients'   => 'Atiso, cúc hoa, cỏ ngọt, hoài sơn, đảng sâm, đại táo, hồng táo, hồi hoa, cam thảo bắc, hoa lài, hoa hòe, thảo quyết minh, khổ qua, kỷ tử, tim sen.',
                'instructions'  => 'Cho 15-20g trà vào ấm, châm 300ml nước sôi 95°C, ủ trà trong 5 phút là có thể thưởng thức. Uống nóng hoặc ướp lạnh đều ngon.',
                'storage'       => 'Đóng kín miệng túi sau mỗi lần lấy trà, để nơi khô ráo, tránh ẩm ướt.'
            ],
            6 => [
                'id'            => 6,
                'name'          => 'Trà Sen Huế',
                'price'         => '250.000 ₫',
                'raw_price'     => 250000,
                'category'      => 'Trà Huế',
                'image'         => $imgBase . 'p6_tra_sen_hue.jpg',
                'rating'        => '5.0',
                'reviews_count' => 168,
                'origin'        => 'Hồ Tịnh Tâm, TP. Huế',
                'packaging'     => 'Hộp 500g cao cấp',
                'shelf_life'    => '12 tháng',
                'short_desc'    => 'Đỉnh cao nghệ thuật trà Việt, ướp từ sen bách diệp hồ Tịnh Tâm với trà nõn tôm Tân Cương thượng hạng.',
                'description'   => '<p>Trà Sen Huế là báu vật ẩm thực của Cố Đô. Những búp trà mạn thượng hạng được ủ công phu qua nhiều lần gạo sen bách diệp hái lúc sớm mai khi sương đọng trên cánh sen. Tách trà sen mang hương thơm thanh khiết của đất trời Cố Đô và hậu vị ngọt sâu lắng.',
                'ingredients'   => 'Búp trà nõn tôm hảo hạng, gạo hoa sen bách diệp hồ Tịnh Tâm tự nhiên 100%.',
                'instructions'  => 'Dùng nước sôi 85-90°C để pha, tráng ấm chén bằng nước sôi. Hãm trà trong 2-3 phút, rót ra chén tống rồi chia đều thưởng thức.',
                'storage'       => 'Bảo quản trong hộp kín hoặc ngăn mát tủ lạnh để giữ trọn vẹn hương sen tinh tế.'
            ],
            7 => [
                'id'            => 7,
                'name'          => 'Mắm Ruốc',
                'price'         => '55.000 ₫',
                'raw_price'     => 55000,
                'category'      => 'Mắm Huế',
                'image'         => $imgBase . 'p7_mam_ruoc.jpg',
                'rating'        => '4.9',
                'reviews_count' => 205,
                'origin'        => 'Thuận An, Huế',
                'packaging'     => 'Hũ 400g truyền thống',
                'shelf_life'    => '12 tháng',
                'short_desc'    => 'Mắm ruốc cốt truyền thống thơm nồng nàn, bí quyết linh hồn cho món Bún Bò Huế và thịt kho ruốc sả.',
                'description'   => '<p>Mắm Ruốc Huế là gia vị không thể thiếu làm nên danh tiếng của ẩm thực miền Trung. Con khuyết biển tươi rói được làm sạch, quết nhuyễn và phơi sương nắng tự nhiên theo bí quyết cổ truyền, tạo nên mùi thơm đượm nồng và vị ngọt đằm thắm.</p>',
                'ingredients'   => 'Con moi biển (khuyết), muối biển tinh khiết hạt to hầm sạch.',
                'instructions'  => 'Dùng nấu nước dùng bún bò Huế, xào thịt ba chỉ ruốc sả ớt cay, hoặc nêm vào các món canh rau tập tàng.',
                'storage'       => 'Đậy kín nắp sau khi sử dụng, để nơi thoáng khí khô ráo.'
            ],
            8 => [
                'id'            => 8,
                'name'          => 'Mắm Nêm',
                'price'         => '50.000 ₫',
                'raw_price'     => 50000,
                'category'      => 'Mắm Huế',
                'image'         => $imgBase . 'p8_mam_nem.jpg',
                'rating'        => '4.8',
                'reviews_count' => 142,
                'origin'        => 'Phú Lộc, Huế',
                'packaging'     => 'Chai 500ml nguyên chất',
                'shelf_life'    => '9 tháng',
                'short_desc'    => 'Mắm nêm nguyên chất từ cá cơm than đầm phá Cố Đô, vị đậm đà thơm ngát khi pha cùng dứa và tỏi ớt.',
                'description'   => '<p>Mắm nêm Huế được ủ từ cá cơm than tươi nguyên con đượm muối hạt tinh. Nước mắm nêm sánh đặc, thơm ngậy, chỉ cần pha thêm chút thơm (dứa) băm nhuyễn, tỏi ớt và nước cốt chanh là trở thành chén nước chấm mê mẩn cho món bánh ướt thịt heo luộc.</p>',
                'ingredients'   => 'Cá cơm than biển Cố Đô, muối hầm, thính gạo rang thơm.',
                'instructions'  => 'Lắc đều trước khi dùng. Pha thêm đường, tỏi ớt băm nhuyễn, dứa băm và chanh tươi tùy khẩu vị.',
                'storage'       => 'Để nơi khô ráo, tránh ánh nắng trực tiếp.'
            ],
            9 => [
                'id'            => 9,
                'name'          => 'Mắm Cá Rò',
                'price'         => '55.000 ₫',
                'raw_price'     => 55000,
                'category'      => 'Mắm Huế',
                'image'         => $imgBase . 'p9_mam_ca_ro.jpg',
                'rating'        => '4.9',
                'reviews_count' => 96,
                'origin'        => 'Cửa biển Thuận An, Huế',
                'packaging'     => 'Hũ 400g đặc biệt',
                'shelf_life'    => '6 tháng',
                'short_desc'    => 'Cá rò biển non xương mềm rụm ủ men cùng muối ớt, ăn kèm thịt ba chỉ và vả thái mỏng ngon nức tiếng.',
                'description'   => '<p>Cá rò là loài cá nhỏ xương mềm như sụn sống ở vùng nước lợ đầm phá Tam Giang. Mắm cá rò lên men đỏ au, thịt cá bùi ngọt, xương tan mềm trong miệng, chấm cùng lát khế chua hay lát vả Huế thì hao cơm vô cùng.</p>',
                'ingredients'   => 'Cá rò biển tươi non, muối biển hầm, ớt bột Huế, tỏi tép, riềng củ.',
                'instructions'  => 'Trộn thêm chút đường, tỏi ớt tươi giã nhuyễn. Dùng ăn với cơm nóng, bún hoặc cuốn thịt luộc.',
                'storage'       => 'Bảo quản nơi mát mẻ, đậy kín miệng hũ sau khi lấy mắm.'
            ],
            10 => [
                'id'            => 10,
                'name'          => 'Mắm Sò Lăng Cô',
                'price'         => '85.000 ₫',
                'raw_price'     => 85000,
                'category'      => 'Mắm Huế',
                'image'         => $imgBase . 'p10_mam_so_lang_co.jpg',
                'rating'        => '5.0',
                'reviews_count' => 156,
                'origin'        => 'Vịnh Lăng Cô, Phú Lộc, Huế',
                'packaging'     => 'Hũ 500g cao cấp',
                'shelf_life'    => '6 tháng',
                'short_desc'    => 'Đặc sản trứ danh xứ Lăng Cô, thịt sò huyết bùi béo dai giòn thấm đẫm riềng ớt đỏ tươi cay nồng.',
                'description'   => '<p>Mắm Sò Lăng Cô là một trong những loại mắm đắt giá và cầu kỳ nhất xứ Huế. Sò lông, sò huyết tươi được cào từ đầm Lập An, tách vỏ lấy ruột béo múp, làm sạch cát rồi ủ cùng muối hột, riềng đỏ và ớt hiểm trong nhiều tháng ròng.</p>',
                'ingredients'   => 'Thịt sò huyết tươi vịnh Lăng Cô, muối hạt tinh khiết, ớt bột, riềng củ giã nhuyễn, rượu trắng.',
                'instructions'  => 'Mở nắp, giã thêm chút tỏi ớt tươi và đường, vắt nhẹ chút chanh, chấm kèm thịt heo cuốn bánh tráng rau rừng.',
                'storage'       => 'Để ngăn mát tủ lạnh để giữ được độ tươi giòn và màu đỏ tươi tự nhiên của mắm.'
            ],
            11 => [
                'id'            => 11,
                'name'          => 'Kẹo Đậu Phộng',
                'price'         => '25.000 ₫',
                'raw_price'     => 25000,
                'category'      => 'Kẹo Huế',
                'image'         => $imgBase . 'p11_keo_dau_phong.jpg',
                'rating'        => '4.8',
                'reviews_count' => 84,
                'origin'        => 'Làng nghề Nam Phổ, Huế',
                'packaging'     => 'Gói 250g',
                'shelf_life'    => '6 tháng',
                'short_desc'    => 'Kẹo đậu phộng giòn xốp thơm lừng mạch nha nếp, đậu phộng rang vàng giòn bùi ngậy.',
                'description'   => '<p>Kẹo đậu phộng Huế được làm từ những hạt đậu phụng ta tròn mẩy, rang vàng đều trên than hồng rồi quyện đều cùng mạch nha nếp và mè rang. Miếng kẹo cắn giòn rôm rốp, ngọt thanh vừa phải, thích hợp làm thức quà trà chiều tao nhã.</p>',
                'ingredients'   => 'Đậu phộng ta rang giòn, mạch nha nếp thơm, mè vàng rang, đường cát trắng, vani.',
                'instructions'  => 'Thưởng thức trực tiếp sau khi mở gói, nhâm nhi cùng trà xanh hoặc trà sen ấm.',
                'storage'       => 'Để nơi khô ráo, buộc kín miệng gói tránh không khí lọt vào.'
            ],
            12 => [
                'id'            => 12,
                'name'          => 'Kẹo Gừng',
                'price'         => '60.000 ₫',
                'raw_price'     => 60000,
                'category'      => 'Kẹo Huế',
                'image'         => $imgBase . 'p12_keo_gung.jpg',
                'rating'        => '4.9',
                'reviews_count' => 112,
                'origin'        => 'Kim Long, TP. Huế',
                'packaging'     => 'Gói 500g',
                'shelf_life'    => '6 tháng',
                'short_desc'    => 'Kẹo gừng dẻo thơm ấm nồng, vị ngọt dịu xoa dịu cổ họng và làm ấm cơ thể trong những ngày se lạnh.',
                'description'   => '<p>Kẹo gừng Huế nổi tiếng với vị cay ấm nồng nàn của củ gừng sẻ bản địa kết hợp với độ dẻo quánh của đường mạch nha. Đây vừa là món ăn chơi thú vị vừa là bài thuốc dân gian tuyệt vời giúp giữ ấm cổ họng và kích thích tiêu hóa.</p>',
                'ingredients'   => 'Gừng sẻ tươi Huế, mạch nha nếp, đường phèn tinh khiết, mè rang, bột nếp rang.',
                'instructions'  => 'Ăn trực tiếp, rất thích hợp khi uống cùng nước ấm hoặc trà nóng vào buổi sáng.',
                'storage'       => 'Bảo quản nơi thoáng mát, khô ráo.'
            ],
            13 => [
                'id'            => 13,
                'name'          => 'Kẹo Mè Xửng',
                'price'         => '22.000 ₫',
                'raw_price'     => 22000,
                'category'      => 'Kẹo Huế',
                'image'         => $imgBase . 'p13_keo_me_xung.jpg',
                'rating'        => '5.0',
                'reviews_count' => 320,
                'origin'        => 'Đường Huỳnh Thúc Kháng, TP. Huế',
                'packaging'     => 'Gói 250g',
                'shelf_life'    => '6 tháng',
                'short_desc'    => 'Biểu tượng quà quê Cố Đô, từng miếng mè xửng dẻo quánh thơm lừng mè rang và đậu phộng bùi béo.',
                'description'   => '<p>Mè Xửng là món quà truyền thống trường tồn cùng thời gian của Cố Đô Huế. Được nấu từ mạch nha dẻo quánh, phủ kín bởi lớp mè trắng rang vàng ruộm điểm xuyết đậu phộng bùi béo, tạo nên hương vị mộc mạc mà quyến luyến khó phai.</p>',
                'ingredients'   => 'Mạch nha nếp, mè rang vàng, đậu phộng hảo hạng, đường mía, dầu mè tinh khiết.',
                'instructions'  => 'Thưởng thức trực tiếp, ngon nhất khi nhâm nhi cùng ấm trà cung đình nóng hổi.',
                'storage'       => 'Bảo quản ở nhiệt độ phòng nơi râm mát, tránh tiếp xúc nhiệt độ cao.'
            ],
            14 => [
                'id'            => 14,
                'name'          => 'Tré Bò',
                'price'         => '110.000 ₫',
                'raw_price'     => 110000,
                'category'      => 'Tré Huế',
                'image'         => $imgBase . 'p14_tre_bo.jpg',
                'rating'        => '5.0',
                'reviews_count' => 175,
                'origin'        => 'Đường Đào Duy Từ, TP. Huế',
                'packaging'     => '10 xâu truyền thống',
                'shelf_life'    => '7 ngày (mát)',
                'short_desc'    => 'Đặc sản tré bò hoàng cung gói rơm bọc lá chuối, thịt bò bắp giòn ngọt dậy thơm mùi mè rang và riềng tỏi.',
                'description'   => '<p>Tré Bò là món ăn cung đình cầu kỳ bậc nhất của ẩm thực xứ Huế. Thịt bắp bò tươi sạch được luộc vừa chín tới, thái sợi mỏng như tơ rồi trộn đều với thính gạo rang, riềng non xắt chỉ, mè rang vàng, tỏi ta và tiêu sọ, ủ lên men tự nhiên tạo vị chua thanh ngọt đượm.</p>',
                'ingredients'   => 'Bắp bò tươi, da bò luộc giòn, riềng non thái chỉ, tỏi ta, thính gạo nếp, mè rang, ớt chỉ thiên, lá ổi, lá chuối.',
                'instructions'  => 'Mở lá chuối, dùng đũa đánh tơi từng sợi tré, vắt thêm chút chanh tươi, cuốn cùng bánh tráng, rau sống và tỏi tép.',
                'storage'       => 'Để nơi thoáng mát 2-3 ngày, sau đó bảo quản ngăn mát tủ lạnh dùng trong 10 ngày.'
            ],
            15 => [
                'id'            => 15,
                'name'          => 'Tré Heo',
                'price'         => '90.000 ₫',
                'raw_price'     => 90000,
                'category'      => 'Tré Huế',
                'image'         => $imgBase . 'p15_tre_heo.jpg',
                'rating'        => '4.9',
                'reviews_count' => 134,
                'origin'        => 'Đường Đào Duy Từ, TP. Huế',
                'packaging'     => '10 xâu truyền thống',
                'shelf_life'    => '7 ngày (mát)',
                'short_desc'    => 'Tai heo, mũi heo giòn sần sật lên men tự nhiên thơm lừng riềng sả ớt, món nhậu khoái khẩu Cố Đô.',
                'description'   => '<p>Tré Heo là thức quà đãi khách trân quý của người miền Trung. Tai heo giòn sần sật kết hợp thịt nạc heo tươi ngon và gia vị gia truyền, bọc trong lá ổi tươi và lá chuối bánh tẻ xanh ngắt.</p>',
                'ingredients'   => 'Tai heo, mũi heo, thịt nạc heo tươi, riềng non, tỏi Lý Sơn, mè rang, thính gạo thơm, gia vị bí truyền.',
                'instructions'  => 'Đánh tơi tré ra đĩa, ăn kèm tỏi ngâm chua ngọt, ớt trái và tương ớt Cố Đô.',
                'storage'       => 'Bảo quản ngăn mát tủ lạnh để giữ độ giòn sần sật của tai heo.'
            ],
            16 => [
                'id'            => 16,
                'name'          => 'Hạt Sen Khô',
                'price'         => '250.000 ₫',
                'raw_price'     => 250000,
                'category'      => 'Hạt Sen Huế',
                'image'         => $imgBase . 'p16_hat_sen_kho.jpg',
                'rating'        => '5.0',
                'reviews_count' => 220,
                'origin'        => 'Hồ Tịnh Tâm, TP. Huế',
                'packaging'     => 'Túi 1 kg hút chân không',
                'shelf_life'    => '12 tháng',
                'short_desc'    => 'Hạt sen Tịnh Tâm chính gốc phơi sấy sạch sẽ, hạt tròn đều bùi béo nấu nhanh mềm không sượng.',
                'description'   => '<p>Hạt Sen Khô Huế từ lâu đã nổi tiếng khắp cả nước nhờ thổ nhưỡng hồ Tịnh Tâm giàu phù sa bồi đắp. Hạt sen tròn đều, màu trắng ngà tự nhiên, khi hầm nhanh bở tơi mà không nát, vị ngọt bùi béo ngậy đặc trưng không nơi nào sánh bằng.</p>',
                'ingredients'   => '100% hạt sen khô hồ Tịnh Tâm xâu chuỗi thông tâm sạch sẽ.',
                'instructions'  => 'Không cần ngâm nước lạnh, cho thẳng hạt sen vào nước đang sôi hầm 15-20 phút là hạt sen chín mềm bùi ngậy. Dùng nấu chè, hầm gà hoặc nấu cháo dinh dưỡng.',
                'storage'       => 'Đóng kín túi, bảo quản nơi khô ráo thoáng gió, tránh ẩm mốc.'
            ],
            17 => [
                'id'            => 17,
                'name'          => 'Hạt Sen Tươi',
                'price'         => '420.000 ₫',
                'raw_price'     => 420000,
                'category'      => 'Hạt Sen Huế',
                'image'         => $imgBase . 'p17_hat_sen_tuoi.jpg',
                'rating'        => '5.0',
                'reviews_count' => 195,
                'origin'        => 'Hồ Tịnh Tâm, TP. Huế',
                'packaging'     => 'Túi 1 kg tươi bóc vỏ',
                'shelf_life'    => '1 tháng (cấp đông)',
                'short_desc'    => 'Hạt sen tươi bóc vỏ tách tâm trong ngày, giữ nguyên vẹn vị ngọt thanh mát mộc mạc của sen Cố Đô.',
                'description'   => '<p>Hạt Sen Tươi được thu hái từ sáng sớm tinh sương trên mặt hồ Tịnh Tâm thơ mộng. Hạt sen bóc vỏ lụa và rút tâm sen hoàn toàn thủ công, giữ trọn vẹn từng giọt sữa ngọt mát và hương sen dịu lành nguyên bản.</p>',
                'ingredients'   => '100% hạt sen tươi nguyên chất đã bóc vỏ lụa và tách tâm sen.',
                'instructions'  => 'Nấu chè sen long nhãn, chè hạt sen đường phèn, hoặc hấp chín trộn xôi hạt sen dẻo thơm.',
                'storage'       => 'Bảo quản ngăn mát từ 3-5 ngày hoặc cấp đông ngăn đá dùng trong 3 tháng.'
            ],
            18 => [
                'id'            => 18,
                'name'          => 'Hạt Sen Sấy',
                'price'         => '95.000 ₫',
                'raw_price'     => 95000,
                'category'      => 'Hạt Sen Huế',
                'image'         => $imgBase . 'p18_hat_sen_say.jpg',
                'rating'        => '4.9',
                'reviews_count' => 140,
                'origin'        => 'TP. Huế, Thừa Thiên Huế',
                'packaging'     => 'Hũ 250g giòn rụm',
                'shelf_life'    => '9 tháng',
                'short_desc'    => 'Hạt sen sấy giòn tan công nghệ sấy chân không hiện đại, thơm bùi béo ngậy không ngấy dầu.',
                'description'   => '<p>Hạt sen sấy giòn O Hương Xứ Huế áp dụng công nghệ sấy chân không tiên tiến, giữ nguyên hình dáng tròn đều và hàm lượng dưỡng chất quý giá của hạt sen Cố Đô. Từng hạt sen giòn rụm tan ngay trong khoang miệng, vị bùi béo tự nhiên kích thích vị giác.</p>',
                'ingredients'   => 'Hạt sen Huế tuyển chọn (98%), dầu thực vật tinh luyện, đường tinh luyện, muối hầm.',
                'instructions'  => 'Dùng ăn liền trực tiếp như snack cao cấp bổ dưỡng.',
                'storage'       => 'Đậy kín nắp sau khi dùng, để nơi khô mát.'
            ],
            19 => [
                'id'            => 19,
                'name'          => 'Mứt Hạt Sen',
                'price'         => '85.000 ₫',
                'raw_price'     => 85000,
                'category'      => 'Hạt Sen Huế',
                'image'         => $imgBase . 'p19_mut_hat_sen.jpg',
                'rating'        => '4.8',
                'reviews_count' => 98,
                'origin'        => 'Kim Long, TP. Huế',
                'packaging'     => 'Hộp 300g quà biếu',
                'shelf_life'    => '6 tháng',
                'short_desc'    => 'Thức quà Tết hoàng cung tao nhã, hạt sen bọc lớp đường phấn trắng tinh khôi ngọt thanh bùi ngậy.',
                'description'   => '<p>Mứt Hạt Sen Huế là nét đẹp văn hóa ẩm thực tao nhã trong ngày Tết Cố Đô. Từng viên mứt tròn trịa, bên ngoài phủ lớp đường trắng mịn màng như sương mai, bên trong bở bùi thơm ngát hương vani và hạt sen dịu dàng.',
                'ingredients'   => 'Hạt sen Tịnh Tâm tươi sạch, đường cát trắng tinh khiết, hương vani tự nhiên.',
                'instructions'  => 'Thưởng thức cùng trà mạn hoặc trà sen nóng hổi trong những dịp chuyện trò sum họp gia đình.',
                'storage'       => 'Đậy kín hộp, bảo quản nơi khô ráo, tránh kiến và độ ẩm.'
            ],
            20 => [
                'id'            => 20,
                'name'          => 'Nem Chua Huế',
                'price'         => '55.000 ₫',
                'raw_price'     => 55000,
                'category'      => 'Nem Chua Huế',
                'image'         => $imgBase . 'p20_nem_chua_hue.jpg',
                'rating'        => '5.0',
                'reviews_count' => 265,
                'origin'        => 'Đông Ba, TP. Huế',
                'packaging'     => 'Chùm 10 trĩu bọc lá chuối',
                'shelf_life'    => '7 ngày (mát)',
                'short_desc'    => 'Nem chua gói lá chuối truyền thống, màu hồng ngọc bắt mắt với vị chua thanh giòn sần sật kèm tỏi ớt.',
                'description'   => '<p>Nem Chua Huế mang hương vị đặc trưng không lẫn vào đâu được: không chua gắt như nem miền Bắc, không ngọt đậm như nem miền Nam mà hài hòa vị thanh dịu, da heo giòn sần sật điểm xuyết tép tỏi trắng nõn và lát ớt chỉ thiên đỏ au nồng nàn.',
                'ingredients'   => 'Thịt nạc đùi heo nóng mới mổ, bì heo thái sợi mỏng giòn, tỏi ta, ớt tiêu, lá ổi, thính gạo gia truyền.',
                'instructions'  => 'Bóc lá chuối ăn trực tiếp hoặc nướng sơ trên bếp than hồng dậy mùi thơm ngào ngạt.',
                'storage'       => 'Bảo quản nhiệt độ thường 2 ngày để nem chín, sau đó cho vào ngăn mát tủ lạnh.'
            ],
            21 => [
                'id'            => 21,
                'name'          => 'Nem Thính',
                'price'         => '45.000 ₫',
                'raw_price'     => 45000,
                'category'      => 'Nem Chua Huế',
                'image'         => $imgBase . 'p21_nem_thinh.jpg',
                'rating'        => '4.9',
                'reviews_count' => 118,
                'origin'        => 'Phú Hội, TP. Huế',
                'packaging'     => 'Gói 300g đóng hộp',
                'shelf_life'    => '5 ngày',
                'short_desc'    => 'Tai mũi heo trộn thính gạo rang vàng óng thơm nức mũi, cuốn bánh tráng rau sống chấm mắm nêm đậm đà.',
                'description'   => '<p>Nem Thính là món ăn dân dã khoái khẩu của người dân Cố Đô. Tai mũi heo được luộc chín giòn sần sật, thái mỏng tang rồi bóp cùng thính gạo nếp rang vàng cánh gián thơm lừng ngào ngạt, thêm chút lá chanh thái chỉ và ớt lát.',
                'ingredients'   => 'Tai heo, mũi heo luộc giòn, thính nếp rang thủ công, lá chanh non, tỏi ớt, tiêu sọ, nước mắm ngon.',
                'instructions'  => 'Cuốn cùng lá sung non, rau đinh lăng, bánh tráng và chấm kèm tương ớt hoặc nước mắm tỏi ớt chua ngọt.',
                'storage'       => 'Nên dùng trong 48 giờ để giữ trọn vẹn mùi thơm ngát của thính gạo.'
            ],
            22 => [
                'id'            => 22,
                'name'          => 'Nem Thịt',
                'price'         => '60.000 ₫',
                'raw_price'     => 60000,
                'category'      => 'Nem Chua Huế',
                'image'         => $imgBase . 'p22_nem_thit.jpg',
                'rating'        => '4.9',
                'reviews_count' => 142,
                'origin'        => 'Đường Đào Duy Từ, TP. Huế',
                'packaging'     => 'Chùm 10 trĩu',
                'shelf_life'    => '7 ngày',
                'short_desc'    => 'Nem thịt heo nạc nguyên chất đượm vị tiêu đen, kết cấu săn chắc ngọt thịt chuẩn vị tiệc mừng Cố Đô.',
                'description'   => '<p>Nem Thịt được giã tay nhuyễn mịn từ thịt nạc mông heo tươi nóng hổi, tẩm ướp gia vị đậm đà và hạt tiêu sọ cay nồng. Chiếc nem tròn trịa, chắc nịch, khi ăn cảm nhận rõ vị ngọt tự nhiên của thịt heo tươi quyện cùng hương thơm lá chuối.',
                'ingredients'   => 'Thịt heo nạc đùi (95%), bì heo sạch, tỏi tép, tiêu hạt Phú Quốc, muối đường gia truyền.',
                'instructions'  => 'Ăn liền kèm tỏi sống, hoặc thái lát mỏng bày lên đĩa khai vị ngày giỗ chạp, lễ tiệc.',
                'storage'       => 'Để nơi thoáng mát hoặc bảo quản ngăn mát tủ lạnh.'
            ],
            23 => [
                'id'            => 23,
                'name'          => 'Nem Tré',
                'price'         => '75.000 ₫',
                'raw_price'     => 75000,
                'category'      => 'Nem Chua Huế',
                'image'         => $imgBase . 'p23_nem_tre.jpg',
                'rating'        => '4.8',
                'reviews_count' => 88,
                'origin'        => 'Đông Ba, TP. Huế',
                'packaging'     => 'Chùm 10 trĩu',
                'shelf_life'    => '7 ngày',
                'short_desc'    => 'Sự kết hợp độc đáo giữa vị chua ngọt của nem và vị giòn cay nồng của tré Cố Đô truyền thống.',
                'description'   => '<p>Nem Tré là sáng tạo ẩm thực độc đáo giao hòa giữa hai món đặc sản trứ danh xứ Huế. Sự kết hợp giữa thịt nem chua mịn màng và tai heo thái sợi trộn riềng tỏi mè rang mang đến trải nghiệm hương vị đa tầng hấp dẫn khó quên.',
                'ingredients'   => 'Thịt heo nạc, tai heo thái sợi, riềng non, tỏi, mè rang, ớt tươi, lá ổi, lá chuối bọc ngoài.',
                'instructions'  => 'Bóc lớp lá chuối, dùng ăn trực tiếp kèm tỏi ngâm chua ngọt hoặc nhâm nhi cùng chén rượu sen nồng ấm.',
                'storage'       => 'Bảo quản ngăn mát tủ lạnh sau khi nem đạt độ chua vừa miệng.'
            ],
            24 => [
                'id'            => 24,
                'name'          => 'Nem Bò',
                'price'         => '85.000 ₫',
                'raw_price'     => 85000,
                'category'      => 'Nem Chua Huế',
                'image'         => $imgBase . 'p24_nem_bo.jpg',
                'rating'        => '5.0',
                'reviews_count' => 164,
                'origin'        => 'Đường Gia Hội, TP. Huế',
                'packaging'     => 'Chùm 10 trĩu',
                'shelf_life'    => '7 ngày',
                'short_desc'    => 'Nem bò nguyên chất màu đỏ sẫm óng ả, thịt bò săn chắc đậm đà vị tiêu sọ thơm lừng cay ấm.',
                'description'   => '<p>Nem Bò Cố Đô được chế biến từ thịt bò tươi loại một tuyển chọn kỹ lưỡng. Từng chiếc nem có màu đỏ mận đặc trưng, thớ thịt săn chắc, thơm lừng vị hạt tiêu sọ và tỏi tép cay ấm, là món quà biếu sang trọng và ý nghĩa.',
                'ingredients'   => 'Thịt nạc bò tươi nguyên chất, bì heo sợi mỏng giòn, tỏi Lý Sơn, tiêu sọ nguyên hạt, ớt sừng, gia vị bí truyền.',
                'instructions'  => 'Thưởng thức trực tiếp hoặc cắt lát vuông bày đĩa nhậu khai vị cùng bạn bè.',
                'storage'       => 'Bảo quản nơi thoáng mát 2 ngày rồi chuyển sang ngăn mát tủ lạnh.'
            ],
        ];

        // Tự động đồng bộ và nạp trực tiếp từ WooCommerce nếu có sản phẩm
        if (function_exists('wc_get_products')) {
            $wcProducts = wc_get_products([
                'status'  => 'publish',
                'limit'   => -1,
                'orderby' => 'menu_order title',
                'order'   => 'ASC'
            ]);

            if (!empty($wcProducts)) {
                $syncedList = [];
                foreach ($wcProducts as $p) {
                    $sku = $p->get_sku();
                    $id = 0;
                    if (preg_match('/OHX-(\d+)/i', $sku, $m)) {
                        $id = intval($m[1]);
                    } else {
                        $id = $p->get_id();
                    }

                    $fallback = $fallbackList[$id] ?? [];

                    // Lấy hình ảnh đại diện do người dùng cập nhật trong WooCommerce
                    $img_id = $p->get_image_id();
                    $img_url = $img_id ? wp_get_attachment_image_url($img_id, 'full') : $p->get_meta('_original_image');
                    if (empty($img_url) && !empty($fallback['image'])) {
                        $img_url = $fallback['image'];
                    }

                    // Lấy danh mục sản phẩm từ WooCommerce taxonomy
                    $terms = wp_get_post_terms($p->get_id(), 'product_cat');
                    $catName = (!empty($terms) && !is_wp_error($terms)) ? html_entity_decode($terms[0]->name, ENT_QUOTES, 'UTF-8') : ($fallback['category'] ?? 'Đặc Sản Huế');

                    // Lấy giá bán
                    $rawPrice = floatval($p->get_price());
                    if ($rawPrice <= 0 && isset($fallback['raw_price'])) {
                        $rawPrice = $fallback['raw_price'];
                    }
                    $priceFormatted = number_format($rawPrice, 0, ',', '.') . ' ₫';

                    // Lấy mô tả ngắn & chi tiết
                    $shortDesc = $p->get_short_description();
                    if (empty(trim($shortDesc)) && isset($fallback['short_desc'])) {
                        $shortDesc = $fallback['short_desc'];
                    }

                    $desc = $p->get_description();
                    if (empty(trim($desc)) && isset($fallback['description'])) {
                        $desc = $fallback['description'];
                    }

                    $syncedList[$id] = [
                        'id'            => $id,
                        'wc_id'         => $p->get_id(),
                        'sku'           => $sku,
                        'name'          => $p->get_name(),
                        'price'         => $priceFormatted,
                        'raw_price'     => $rawPrice,
                        'category'      => $catName,
                        'image'         => $img_url,
                        'rating'        => $p->get_average_rating() ?: ($fallback['rating'] ?? '5.0'),
                        'reviews_count' => $p->get_review_count() ?: ($fallback['reviews_count'] ?? 100),
                        'origin'        => $p->get_meta('_origin') ?: ($fallback['origin'] ?? 'Huế'),
                        'packaging'     => $p->get_meta('_packaging') ?: ($fallback['packaging'] ?? 'Gói tiêu chuẩn'),
                        'shelf_life'    => $p->get_meta('_shelf_life') ?: ($fallback['shelf_life'] ?? '6 tháng'),
                        'short_desc'    => $shortDesc,
                        'description'   => $desc,
                        'ingredients'   => $p->get_meta('_ingredients') ?: ($fallback['ingredients'] ?? ''),
                        'instructions'  => $p->get_meta('_instructions') ?: ($fallback['instructions'] ?? ''),
                        'storage'       => $p->get_meta('_storage') ?: ($fallback['storage'] ?? '')
                    ];
                }

                // Bảo toàn các sản phẩm dự phòng chưa có trong WC
                foreach ($fallbackList as $fId => $fItem) {
                    if (!isset($syncedList[$fId])) {
                        $syncedList[$fId] = $fItem;
                    }
                }

                ksort($syncedList);
                if (function_exists('set_transient')) {
                    set_transient('ohx_master_products', $syncedList, 12 * HOUR_IN_SECONDS);
                }
                $memCache = $syncedList;
                return $syncedList;
            }
        }

        $memCache = $fallbackList;
        return $fallbackList;
    }

    /**
     * Lấy danh sách Top đặc sản Huế nổi bật (4 món tiêu biểu)
     */
    public function getTopFeaturedProducts() {
        $all = $this->getAllProductsWithStock();
        // Lấy 4 món tiêu biểu đại diện: Bánh ép khô, Mắm tôm chua, Trà cung đình, Tré bò
        $topIds = [1, 4, 5, 14];
        $result = [];
        foreach ($topIds as $tid) {
            if (isset($all[$tid])) {
                $result[] = $all[$tid];
            }
        }
        if (count($result) < 4) {
            $result = array_slice(array_values($all), 0, 4);
        }
        return $result;
    }

    /**
     * Lấy sản phẩm nhóm Bánh Ép Huế
     */
    public function getBanhHueProducts() {
        $all = array_values($this->getAllProductsWithStock());
        $filtered = array_values(array_filter($all, function($p) {
            return (mb_stripos($p['category'], 'bánh') !== false || mb_stripos($p['name'], 'bánh') !== false);
        }));
        if (count($filtered) < 4) {
            // Lấy thêm Nem Chua hoặc Tré
            $more = array_filter($all, function($p) {
                return mb_stripos($p['category'], 'nem') !== false;
            });
            $filtered = array_merge($filtered, array_values($more));
        }
        return array_slice($filtered, 0, 4);
    }

    /**
     * Lấy sản phẩm nhóm Mè Xửng & Kẹo Huế
     */
    public function getMeXungProducts() {
        $all = array_values($this->getAllProductsWithStock());
        $filtered = array_values(array_filter($all, function($p) {
            return (mb_stripos($p['category'], 'kẹo') !== false || mb_stripos($p['name'], 'kẹo') !== false || mb_stripos($p['category'], 'mè') !== false || mb_stripos($p['name'], 'mè') !== false || mb_stripos($p['category'], 'trà') !== false);
        }));
        if (count($filtered) < 4) {
            $filtered = array_slice($all, 4, 4);
        }
        return array_slice($filtered, 0, 4);
    }

    /**
     * Tìm sản phẩm theo ID để hiển thị chi tiết (Product Detail)
     */
    public function findProductById($id) {
        $id = intval($id);
        $all = $this->getMasterProductList();

        if (isset($all[$id])) {
            return $all[$id];
        }

        foreach ($all as $item) {
            if (isset($item['wc_id']) && intval($item['wc_id']) === $id) {
                return $item;
            }
        }

        if (function_exists('wc_get_product')) {
            $p = wc_get_product($id);
            if ($p) {
                $img_id = $p->get_image_id();
                $img_url = $img_id ? wp_get_attachment_image_url($img_id, 'full') : $p->get_meta('_original_image');
                $terms = wp_get_post_terms($p->get_id(), 'product_cat');
                $catName = (!empty($terms) && !is_wp_error($terms)) ? html_entity_decode($terms[0]->name, ENT_QUOTES, 'UTF-8') : 'Đặc Sản Huế';
                $rawPrice = floatval($p->get_price());

                return [
                    'id'            => $p->get_id(),
                    'wc_id'         => $p->get_id(),
                    'sku'           => $p->get_sku(),
                    'name'          => $p->get_name(),
                    'price'         => number_format($rawPrice, 0, ',', '.') . ' ₫',
                    'raw_price'     => $rawPrice,
                    'category'      => $catName,
                    'image'         => $img_url,
                    'rating'        => $p->get_average_rating() ?: '5.0',
                    'reviews_count' => $p->get_review_count() ?: 0,
                    'origin'        => $p->get_meta('_origin') ?: 'Huế',
                    'packaging'     => $p->get_meta('_packaging') ?: 'Gói tiêu chuẩn',
                    'shelf_life'    => $p->get_meta('_shelf_life') ?: '6 tháng',
                    'short_desc'    => $p->get_short_description(),
                    'description'   => $p->get_description(),
                    'ingredients'   => $p->get_meta('_ingredients') ?: '',
                    'instructions'  => $p->get_meta('_instructions') ?: '',
                    'storage'       => $p->get_meta('_storage') ?: ''
                ];
            }
        }

        return reset($all) ?: [];
    }

    /**
     * Lấy các sản phẩm đặc sản liên quan
     */
    public function getRelatedProducts($currentId, $limit = 4) {
        $all = $this->getMasterProductList();
        unset($all[intval($currentId)]);
        return array_slice(array_values($all), 0, $limit);
    }

    /**
     * Khởi tạo bảng tồn kho (product_stocks) và bảng nhập hàng (product_imports)
     */
    private function initStockTables() {
        if ($this->db) {
            try {
                // 1. Tạo bảng tồn kho
                $this->db->exec("CREATE TABLE IF NOT EXISTS product_stocks (
                    product_id INT PRIMARY KEY,
                    stock_quantity INT NOT NULL DEFAULT 50,
                    sold_quantity INT NOT NULL DEFAULT 0,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

                // Đảm bảo có đủ 24 sản phẩm trong product_stocks
                $stmt = $this->db->query("SELECT COUNT(*) FROM product_stocks");
                $count = $stmt ? intval($stmt->fetchColumn()) : 0;
                if ($count < 24) {
                    $ins = $this->db->prepare("INSERT INTO product_stocks (product_id, stock_quantity, sold_quantity) 
                        VALUES (?, ?, ?) 
                        ON DUPLICATE KEY UPDATE stock_quantity = VALUES(stock_quantity), sold_quantity = VALUES(sold_quantity)");
                    for ($i = 1; $i <= 24; $i++) {
                        $stock = 40 + (($i * 7) % 30);
                        $sold  = 10 + (($i * 5) % 25);
                        $ins->execute([$i, $stock, $sold]);
                    }
                }

                // 2. Tạo bảng nhập hàng
                $this->db->exec("CREATE TABLE IF NOT EXISTS product_imports (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    import_code VARCHAR(50) NOT NULL,
                    product_id INT NOT NULL,
                    product_name VARCHAR(255) NOT NULL,
                    quantity INT NOT NULL,
                    import_price INT NOT NULL,
                    total_cost BIGINT NOT NULL,
                    supplier VARCHAR(255) DEFAULT '',
                    note TEXT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

                // Kiểm tra và khởi tạo dữ liệu phiếu nhập ban đầu nếu trống
                $stmtImp = $this->db->query("SELECT COUNT(*) FROM product_imports");
                if ($stmtImp && $stmtImp->fetchColumn() == 0) {
                    $initImports = [
                        [
                            'code'       => 'NH-260901-101',
                            'product_id' => 1,
                            'name'       => 'Bánh Ép Khô Huế',
                            'quantity'   => 100,
                            'price'      => 18000,
                            'supplier'   => 'Lò Bánh Ép Chị Mai - Thuận An, Huế',
                            'note'       => 'Nhập lô bánh ép khô mới ép giòn'
                        ],
                        [
                            'code'       => 'NH-260902-102',
                            'product_id' => 4,
                            'name'       => 'Mắm Tôm Chua Đu Đủ',
                            'quantity'   => 50,
                            'price'      => 35000,
                            'supplier'   => 'Xưởng Mắm Cầu Hai - Phú Lộc',
                            'note'       => 'Hũ 500g tôm đất nguyên con đu đủ giòn'
                        ],
                        [
                            'code'       => 'NH-260903-103',
                            'product_id' => 5,
                            'name'       => 'Trà Cung Đình Huế',
                            'quantity'   => 60,
                            'price'      => 60000,
                            'supplier'   => 'Hợp Tác Xã Thảo Dược Cung Đình Huế',
                            'note'       => 'Nhập gói 500g 16 vị thảo mộc hạ thổ'
                        ],
                        [
                            'code'       => 'NH-260904-104',
                            'product_id' => 14,
                            'name'       => 'Tré Bò',
                            'quantity'   => 40,
                            'price'      => 80000,
                            'supplier'   => 'Lò Tré Cổ Truyền Đào Duy Từ, Huế',
                            'note'       => 'Tré bọc rơm lên men chuẩn vị'
                        ],
                        [
                            'code'       => 'NH-260905-105',
                            'product_id' => 16,
                            'name'       => 'Hạt Sen Khô',
                            'quantity'   => 30,
                            'price'      => 180000,
                            'supplier'   => 'Đầm Sen Tịnh Tâm, TP. Huế',
                            'note'       => 'Sen khô xâu chuỗi thông tâm hảo hạng'
                        ],
                        [
                            'code'       => 'NH-260906-106',
                            'product_id' => 20,
                            'name'       => 'Nem Chua Huế',
                            'quantity'   => 50,
                            'price'      => 38000,
                            'supplier'   => 'Cơ Sở Nem Chả Đông Ba',
                            'note'       => 'Nem chùm 10 trĩu lá chuối tươi'
                        ]
                    ];
                    $insImp = $this->db->prepare("INSERT INTO product_imports 
                        (import_code, product_id, product_name, quantity, import_price, total_cost, supplier, note, created_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                    foreach ($initImports as $imp) {
                        $cost = $imp['quantity'] * $imp['price'];
                        $insImp->execute([
                            $imp['code'], $imp['product_id'], $imp['name'], $imp['quantity'], $imp['price'], $cost, $imp['supplier'], $imp['note']
                        ]);
                    }
                }
            } catch (Exception $e) {
                error_log("Error initializing stock tables: " . $e->getMessage());
            }
        }

        // Tự động đồng bộ ra file backup JSON
        $this->syncStocksBackup();
        $this->syncImportsBackup();
    }

    /**
     * Lấy danh sách tồn kho dạng associative array [product_id => [...]]
     */
    public function getAllStocksMap() {
        $stocks = [];
        if ($this->db) {
            try {
                $stmt = $this->db->query("SELECT * FROM product_stocks");
                while ($row = $stmt->fetch()) {
                    $stocks[intval($row['product_id'])] = [
                        'stock_quantity' => intval($row['stock_quantity']),
                        'sold_quantity'  => intval($row['sold_quantity']),
                        'updated_at'     => $row['updated_at']
                    ];
                }
            } catch (Exception $e) {}
        }

        // Nếu DB trống hoặc lỗi, đọc từ backup JSON
        if (empty($stocks) && file_exists(self::$stocksBackupFile)) {
            $json = json_decode(file_get_contents(self::$stocksBackupFile), true);
            if (is_array($json)) {
                $stocks = $json;
            }
        }

        // Nếu vẫn trống, tạo mặc định cho 24 sản phẩm
        if (empty($stocks)) {
            for ($i = 1; $i <= 24; $i++) {
                $stocks[$i] = [
                    'stock_quantity' => 50,
                    'sold_quantity'  => 10,
                    'updated_at'     => date('Y-m-d H:i:s')
                ];
            }
        }

        return $stocks;
    }

    /**
     * Lấy toàn bộ danh sách sản phẩm đã được ghép thông tin tồn kho
     */
    public function getAllProductsWithStock() {
        $products = $this->getMasterProductList();
        $stocks   = $this->getAllStocksMap();

        foreach ($products as $id => &$p) {
            $qty = 50;
            $sold = 0;
            if (!empty($p['wc_id']) && function_exists('wc_get_product')) {
                $wcP = wc_get_product($p['wc_id']);
                if ($wcP && $wcP->managing_stock()) {
                    $qty = intval($wcP->get_stock_quantity());
                }
            } elseif (isset($stocks[$id])) {
                $qty = $stocks[$id]['stock_quantity'];
                $sold = $stocks[$id]['sold_quantity'];
            }
            $p['stock']          = $qty;
            $p['stock_quantity'] = $qty;
            $p['sold']           = $sold;
            $p['sold_quantity']  = $sold;
        }
        unset($p);

        return $products;
    }

    public function getSellableProductsWithStock() {
        return array_filter($this->getAllProductsWithStock(), function ($product) {
            if (!empty($product['wc_id']) && function_exists('wc_get_product')) {
                $wcProduct = wc_get_product($product['wc_id']);
                return $wcProduct && $wcProduct->is_visible() && $wcProduct->is_purchasable() && $wcProduct->is_in_stock();
            }
            return (int) ($product['stock'] ?? 0) > 0;
        });
    }

    /**
     * Tìm chi tiết sản phẩm kèm số lượng tồn kho thực tế
     */
    public function findProductByIdWithStock($id) {
        $p = $this->findProductById($id);
        if (empty($p)) return [];

        $stocks = $this->getAllStocksMap();
        $realId = $p['id'];

        $qty = 50;
        $sold = 0;
        if (!empty($p['wc_id']) && function_exists('wc_get_product')) {
            $wcP = wc_get_product($p['wc_id']);
            if ($wcP && $wcP->managing_stock()) {
                $qty = intval($wcP->get_stock_quantity());
            }
        } elseif (isset($stocks[$realId])) {
            $qty = $stocks[$realId]['stock_quantity'];
            $sold = $stocks[$realId]['sold_quantity'];
        }

        $p['stock']          = $qty;
        $p['stock_quantity'] = $qty;
        $p['sold']           = $sold;
        $p['sold_quantity']  = $sold;
        return $p;
    }

    /**
     * Cập nhật số lượng tồn kho (Admin điều chỉnh)
     */
    public function updateStock($productId, $newQuantity) {
        $productId = intval($productId);
        $newQuantity = max(0, intval($newQuantity));

        $p = $this->findProductById($productId);
        if (!empty($p['wc_id']) && function_exists('wc_get_product')) {
            $wcP = wc_get_product($p['wc_id']);
            if ($wcP) {
                $wcP->set_manage_stock(true);
                $wcP->set_stock_quantity($newQuantity);
                $wcP->set_stock_status($newQuantity > 0 ? 'instock' : 'outofstock');
                $wcP->save();
            }
        }

        if ($this->db) {
            try {
                $stmt = $this->db->prepare("INSERT INTO product_stocks (product_id, stock_quantity, sold_quantity) 
                    VALUES (?, ?, 0) 
                    ON DUPLICATE KEY UPDATE stock_quantity = ?, updated_at = NOW()");
                $stmt->execute([$productId, $newQuantity, $newQuantity]);
            } catch (Exception $e) {
                error_log("Error updating stock in DB: " . $e->getMessage());
            }
        }

        $this->syncStocksBackup();
        return true;
    }

    /** Trừ tồn kho khi hệ thống MVC tạo đơn hàng. */
    public function deductStock($productId, $quantity = 1) {
        $productId = (int) $productId;
        $quantity = (int) $quantity;
        if ($productId <= 0 || $quantity <= 0) return false;
        $product = $this->findProductById($productId);
        if (!$product) return false;
        if ((int) ($product['id'] ?? 0) !== $productId && (int) ($product['wc_id'] ?? 0) !== $productId) return false;

        // Sản phẩm WooCommerce dùng kho của WooCommerce, không trừ thêm kho MVC.
        if (!empty($product['wc_id']) && function_exists('wc_get_product')) {
            $wcProduct = wc_get_product($product['wc_id']);
            if (!$wcProduct) return false;
            if (!$wcProduct->managing_stock()) return true;
            if (function_exists('wc_update_product_stock')) {
                $result = wc_update_product_stock($wcProduct, $quantity, 'decrease');
                return $result !== false && $result !== null && !(function_exists('is_wp_error') && is_wp_error($result));
            }
            return false;
        }

        $stocks = $this->getAllStocksMap();
        $initial = $stocks[$productId] ?? ['stock_quantity' => 50, 'sold_quantity' => 0];
        if ($this->db) {
            try {
                $stmt = $this->db->prepare('INSERT IGNORE INTO product_stocks (product_id, stock_quantity, sold_quantity) VALUES (?, ?, ?)');
                if (!$stmt || !$stmt->execute([$productId, (int) $initial['stock_quantity'], (int) $initial['sold_quantity']])) return false;
                $stmt = $this->db->prepare('UPDATE product_stocks SET sold_quantity = sold_quantity + LEAST(stock_quantity, ?), stock_quantity = GREATEST(0, stock_quantity - ?), updated_at = NOW() WHERE product_id = ?');
                if (!$stmt || !$stmt->execute([$quantity, $quantity, $productId])) return false;
                $this->syncStocksBackup();
                return true;
            } catch (Throwable $e) {
                error_log('Cannot deduct product stock: ' . $e->getMessage());
                return false;
            }
        }

        // Giữ cơ chế kho JSON của bản MVC khi không có kết nối MySQL.
        $handle = fopen(self::$stocksBackupFile, 'c+');
        if (!$handle) return false;
        try {
            if (!flock($handle, LOCK_EX)) return false;
            $saved = json_decode(stream_get_contents($handle), true);
            if (!is_array($saved)) $saved = $stocks;
            $row = $saved[$productId] ?? $initial;
            $deducted = min(max(0, (int) $row['stock_quantity']), $quantity);
            $row['stock_quantity'] = max(0, (int) $row['stock_quantity'] - $quantity);
            $row['sold_quantity'] = (int) $row['sold_quantity'] + $deducted;
            $row['updated_at'] = date('Y-m-d H:i:s');
            $saved[$productId] = $row;
            $json = json_encode($saved, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            if ($json === false) return false;
            rewind($handle);
            if (!ftruncate($handle, 0)) return false;
            return fwrite($handle, $json) === strlen($json) && fflush($handle);
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    /**
     * Ghi nhận nhập hàng mới
     */
    public function addImportReceipt($productId, $quantity, $importPrice, $supplier, $note) {
        $productId = intval($productId);
        $quantity = intval($quantity);
        $importPrice = intval($importPrice);
        if ($productId <= 0 || $quantity <= 0 || $importPrice <= 0) {
            return false;
        }

        $p = $this->findProductById($productId);
        $productName = $p['name'] ?? ('Sản phẩm #' . $productId);
        $totalCost = (float)$quantity * (float)$importPrice;
        $importCode = 'NH-' . date('ymd') . '-' . rand(100, 999);

        if ($this->db) {
            try {
                $stmt = $this->db->prepare("INSERT INTO product_imports 
                    (import_code, product_id, product_name, quantity, import_price, total_cost, supplier, note, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                $stmt->execute([$importCode, $productId, $productName, $quantity, $importPrice, $totalCost, $supplier, $note]);
            } catch (Exception $e) {
                error_log("Error saving import receipt: " . $e->getMessage());
            }
        }

        $currentStock = $this->findProductByIdWithStock($productId)['stock'] ?? 0;
        $this->updateStock($productId, $currentStock + $quantity);

        $this->syncImportsBackup();
        return true;
    }

    /**
     * Lấy lịch sử tất cả các phiếu nhập hàng
     */
    public function getImportHistory() {
        $list = [];
        if ($this->db) {
            try {
                $stmt = $this->db->query("SELECT * FROM product_imports ORDER BY created_at DESC");
                if ($stmt) {
                    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            } catch (Exception $e) {}
        }

        if (empty($list) && file_exists(self::$importsBackupFile)) {
            $json = json_decode(file_get_contents(self::$importsBackupFile), true);
            if (is_array($json)) {
                $list = $json;
            }
        }

        return $list;
    }

    /** Tổng hợp số liệu cho dashboard; giữ cách tính doanh thu của OrderModel. */
    public function getFinancialSummary($orderModel) {
        $stats = $orderModel->getStats();
        $imports = $this->getImportHistory();
        $importCost = 0;
        foreach ($imports as $receipt) {
            $importCost += (int) ($receipt['total_cost'] ?? ((int) ($receipt['quantity'] ?? 0) * (int) ($receipt['import_price'] ?? 0)));
        }

        $stock = 0;
        $sold = 0;
        foreach ($this->getAllProductsWithStock() as $product) {
            $stock += (int) ($product['stock_quantity'] ?? 0);
            $sold += (int) ($product['sold_quantity'] ?? 0);
        }

        $revenue = (int) ($stats['total_revenue'] ?? 0);
        return [
            'total_revenue' => $revenue,
            'total_orders' => (int) ($stats['total_orders'] ?? 0),
            'total_import_cost' => $importCost,
            'total_imports_count' => count($imports),
            'net_income' => $revenue - $importCost,
            'total_stock' => $stock,
            'total_sold' => $sold,
        ];
    }

    /**
     * Đồng bộ bảng tồn kho ra file JSON backup
     */
    private function syncStocksBackup() {
        if (!$this->db) return;
        try {
            $stmt = $this->db->query("SELECT * FROM product_stocks");
            $stocks = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $stocks[intval($row['product_id'])] = [
                    'stock_quantity' => intval($row['stock_quantity']),
                    'sold_quantity'  => intval($row['sold_quantity']),
                    'updated_at'     => $row['updated_at']
                ];
            }
            if (!empty($stocks)) {
                @file_put_contents(self::$stocksBackupFile, json_encode($stocks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        } catch (Exception $e) {}
    }

    /**
     * Đồng bộ bảng nhập hàng ra file JSON backup
     */
    private function syncImportsBackup() {
        if (!$this->db) return;
        try {
            $stmt = $this->db->query("SELECT * FROM product_imports ORDER BY created_at DESC");
            if ($stmt) {
                $imports = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($imports)) {
                    @file_put_contents(self::$importsBackupFile, json_encode($imports, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                }
            }
        } catch (Exception $e) {}
    }
}
