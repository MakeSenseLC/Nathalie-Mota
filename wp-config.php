<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '7MWK]F5-Q*Ycm53|JA`6cwp`n>sH0>o_8mlx(&2O[araS>V9GT&=|YvIW%,z$2D>' );
define( 'SECURE_AUTH_KEY',   '%y4l@hV+>nIR%W5Y0=X!8)tFb wi@[s?6wT3WkFkY->r75ia~Bv<bHAd-W9(lEDv' );
define( 'LOGGED_IN_KEY',     '7^NTv*ydFIrQ|:6_j8A~4Gk4VVwzGDpr]x,C0YDVlgr8l@Qg?w[IP8cmDsl/=z<^' );
define( 'NONCE_KEY',         '.V4TMRaweQ~;}KZA G$T]{lG~L3[F;n|t@klQeeC8siBkdka2<};Z7nEOo=:[Y~,' );
define( 'AUTH_SALT',         'l:~]0#vwl.,.%Hd0P29GB_mcoo#P&5Gw%BkMlg[zw=(f0,.ZKTp}j v =s=ey.)2' );
define( 'SECURE_AUTH_SALT',  ',RTSUBRC{Un[C&Q9tQD+6BXUd <)+XOV[!To~F]+mh+{H?,#UO^VFYee42MNdD9n' );
define( 'LOGGED_IN_SALT',    ',kG$*@g=]=AMf&<y]=|0FmvJs%!-aDh&++B7FPzhq;7a&?KKNyI5pq4j*Qe:=D|y' );
define( 'NONCE_SALT',        'qAMHu:vlcjkI1HiWhr3fRekqB_$u XVdGvc>;JS31YJbIk/Mti0p??o_A=^=60tc' );
define( 'WP_CACHE_KEY_SALT', 'Wwr?b}`%(79=?3u$q0OkMS6U8?)}+`ZH;?26T%LBCD=(s/D!7`s056xu(23$n&.@' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
