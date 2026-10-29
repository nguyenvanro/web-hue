<?php
/**
 * O Hương Xứ Huế functions and definitions
 *
 * @package WordPress
 * @subpackage OHuongXuHue
 * @since 1.0.0
 */

if ( ! function_exists( 'ohuongxuhue_setup' ) ) :
	function ohuongxuhue_setup() {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	}
endif;
add_action( 'after_setup_theme', 'ohuongxuhue_setup' );

require_once __DIR__ . '/includes/chat-admin.php';

/**
 * Đăng ký và nạp stylesheet & javascript từ thư mục assets
 */
function ohuongxuhue_scripts() {
	wp_enqueue_style( 'ohuongxuhue-style', get_template_directory_uri() . '/assets/css/style.css', array(), filemtime(__DIR__ . '/assets/css/style.css') );
	wp_enqueue_script( 'ohuongxuhue-script', get_template_directory_uri() . '/assets/js/main.js', array(), filemtime(__DIR__ . '/assets/js/main.js'), true );
}
add_action( 'wp_enqueue_scripts', 'ohuongxuhue_scripts' );

// Hộp thư tư vấn riêng trong quản trị WordPress.
add_action('init', function () {
    register_post_type('ohx_consultation', [
        'labels' => ['name' => 'Tin nhắn tư vấn', 'singular_name' => 'Tin nhắn tư vấn'],
        'public' => false, 'show_ui' => true, 'show_in_rest' => false,
        'menu_icon' => 'dashicons-email-alt', 'supports' => ['title', 'editor'],
        'map_meta_cap' => false,
        'capabilities' => array_fill_keys(['edit_post', 'read_post', 'delete_post', 'edit_posts', 'edit_others_posts', 'publish_posts', 'read_private_posts', 'delete_posts', 'delete_private_posts', 'delete_published_posts', 'delete_others_posts', 'edit_private_posts', 'edit_published_posts', 'create_posts'], 'manage_options'),
    ]);
});
