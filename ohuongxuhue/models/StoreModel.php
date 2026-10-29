<?php
/**
 * Model quản lý danh sách hệ thống Cửa hàng & Showroom O Hương Xứ Huế
 */

class StoreModel extends BaseModel {
    private static $stores = [
        [
            'id'          => 1,
            'name'        => 'Showroom Cố Đô (Trụ Sở Chính)',
            'type'        => 'Trụ sở & Showroom Trải Nghiệm',
            'badge'       => 'Trụ Sở Chính',
            'address'     => '128 Nguyễn Huệ, Phường Vĩnh Ninh, Thành phố Huế',
            'phone'       => '0234 388 9999 - 0905 123 456',
            'email'       => 'codo@ohuongxuhue.vn',
            'hours'       => '06:30 - 22:30 (Mở cửa tất cả các ngày)',
            'image'       => 'khong-gian-tra-banh-hue.png',
            'services'    => [
                'Không gian thưởng trà cung đình miễn phí',
                'Xem nghệ nhân làm mè xửng tại chỗ',
                'Đóng thùng xốp & hút chân không máy bay',
                'Chấp nhận thanh toán thẻ & QR Code'
            ]
        ],
        [
            'id'          => 2,
            'name'        => 'Không Gian Làng Nghề Bánh Ép Huế',
            'type'        => 'Cơ sở sản xuất & Showroom Quà Tặng',
            'badge'       => 'Làng Nghề Truyền Thống',
            'address'     => '45 Đường Kim Long, Phường Kim Long, Thành phố Huế (Cách chùa Thiên Mụ 500m)',
            'phone'       => '0234 359 8888',
            'email'       => 'kimlong@ohuongxuhue.vn',
            'hours'       => '07:00 - 21:00 (Mỗi ngày)',
            'image'       => 'nghe-nhan-lam-banh.jpeg',
            'services_title' => '✨ Tiện ích & Trải nghiệm bánh ép Huế:',
            'services'    => [
                'Tìm hiểu cách làm bánh ép Huế truyền thống',
                'Quan sát thao tác ép bánh trên khuôn gang của người thợ',
                'Gợi ý thưởng thức bánh ép cùng rau và nước chấm',
                'Tư vấn chọn bánh ép và đóng gói đặc sản làm quà'
            ]
        ],
        [
            'id'          => 3,
            'name'        => 'Đại Diện Phân Phối Miền Nam (TP. Hồ Chí Minh)',
            'type'        => 'Chi nhánh phân phối & Giao hàng hỏa tốc',
            'badge'       => 'Trung Tâm Miền Nam',
            'address'     => '268 Hai Bà Trưng, Phường Tân Định, Quận 1, TP. Hồ Chí Minh',
            'phone'       => '0938 789 101',
            'email'       => 'hcm@ohuongxuhue.vn',
            'hours'       => '08:00 - 21:30',
            'image'       => 'trung-bay-dac-san-hue.png',
            'services'    => [
                'Giao nhanh 2h nội thành TP.HCM',
                'Đầy đủ mặt hàng tươi & khô chuyển từ Huế vào mỗi sáng',
                'Tư vấn set quà doanh nghiệp số lượng lớn'
            ]
        ],
        [
            'id'          => 4,
            'name'        => 'Đại Diện Phân Phối Miền Bắc (Hà Nội)',
            'type'        => 'Chi nhánh phân phối & Giao hàng hỏa tốc',
            'badge'       => 'Trung Tâm Miền Bắc',
            'address'     => '88 Phố Huế, Phường Ngô Thì Nhậm, Quận Hai Bà Trưng, Hà Nội',
            'phone'       => '0912 345 678',
            'email'       => 'hanoi@ohuongxuhue.vn',
            'hours'       => '08:00 - 21:30',
            'image'       => 'dong-goi-qua-bieu-hue.png',
            'services'    => [
                'Giao hàng nội thành Hà Nội trong ngày',
                'Đặc sản gửi tàu hỏa & máy bay tươi ngon',
                'Hỗ trợ xuất hóa đơn VAT công ty'
            ]
        ]
    ];

    public function getAll() {
        $imageUrl = function_exists('get_template_directory_uri')
            ? get_template_directory_uri() . '/assets/images/stores-ai'
            : '../images/stores-ai';
        return array_map(function ($store) use ($imageUrl) {
            $store['image'] = $imageUrl . '/' . $store['image'];
            return $store;
        }, self::$stores);
    }
}
