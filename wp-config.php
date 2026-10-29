<?php
define( 'WP_CACHE', true );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'eglmfcyohosting_Bigbang' );

/** Database username */
define( 'DB_USER', 'eglmfcyohosting_Bigbang' );

/** Database password */
define( 'DB_PASSWORD', 'Bigbang@123' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '8V([CFMKGgm[^V|VWT]~+FkZ#/-.V>!}fBr,JduyY1Ry`bzajFs&I*A|Dr/L5Qlj' );
define( 'SECURE_AUTH_KEY',  'MJvC44bf1y7%4%1rT6+H[i>m(GU|,uWyBvOn][B^$MgT0ww+P*)$u!-p*2&<RQWo' );
define( 'LOGGED_IN_KEY',    'TKv@B k5p5THYfwptC)3/d-W;usel]8?c:#/90&y~p7)rOnO ~2[f7$VkoB|#+q>' );
define( 'NONCE_KEY',        'M6^E/kOlv/n-J,LpZtg%l=u>VrOlK<CtVJA3K1gVY^H.aWgE?,9ZtR&HVR<Gvj[6' );
define( 'AUTH_SALT',        '{FZ9H-D<h=`jV7X91]*VRm*5aZAJnF/D}t;V!&sR[N:0lkb?,_U1<@;*gowF7yx9' );
define( 'SECURE_AUTH_SALT', '`H3)(>.$v;8]iz;d;k$~i~YGfiF?L|2gMJ?pnL4d+8qb/x;:njmV(ce[l$KhDjf^' );
define( 'LOGGED_IN_SALT',   '(LZ(i@>/wyCi-.)xnUMgQdm?BpD0w>Po>v0`x?jR:O~m`;t<Czia(.dV-^NM,FrC' );
define( 'NONCE_SALT',       '^H,+HE$75]yq0cDYsV;.fT)<NrxOq]2bl;-NhaYs!rlz^Od1mJpc@_RTN[2Y:Nr/' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define('WP_DEBUG', true);

/* Add any custom values between this line and the "stop editing" line. */

// Cấu hình tự động tương thích cả Localhost và Ngrok / Cloudflare Tunnel
if ( isset( $_SERVER['HTTP_HOST'] ) ) {
    $is_https = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ) || ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' );
    $http_protocol = $is_https ? 'https://' : 'http://';
    if ( $is_https ) {
        $_SERVER['HTTPS'] = 'on';
    }
    define( 'WP_HOME', $http_protocol . $_SERVER['HTTP_HOST'] );
    define( 'WP_SITEURL', $http_protocol . $_SERVER['HTTP_HOST'] );
}

define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
