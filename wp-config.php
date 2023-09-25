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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */


if (strstr($_SERVER['SERVER_NAME'], 'localhost/new-proj')) {

	define('DB_NAME', 'example');

	define('DB_USER', 'root');

	define('DB_PASSWORD', '');

	define('DB_HOST', 'localhost');

} else {

	define('DB_NAME', 'example');  // DB Name in live site 

	define('DB_USER', 'root');   // DB User in live site

	define('DB_PASSWORD', '');  // DB Password in live site

	define('DB_HOST', 'localhost');
}




/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

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
define('AUTH_KEY',         '1BiGr0pdhbCJ2PqV%8D]`~(5!{;OCn&1.,_K^c8.q`A3VfD0HRNWS;OK$a`I.5%$');
define('SECURE_AUTH_KEY',  'w )xDZw2f_h>A_3/RbR,0Ui@eeX >tm79W^<PzHHiM?dFp9#JrFxO-]b6^Nv^.1o');
define('LOGGED_IN_KEY',    'b>r$+bW!JoR6+<+^OY96Awzh2VeVf ?%]z,:j5BMU&5I3G8M[ ?3jkA $]tP_&;@');
define('NONCE_KEY',        '6p@PvS-%F81 Sv5lSQ//HYEh7}H#Q`:4[IIeGk~p9Kq*(9Pyl6T#G@yeKYY^Zx5i');
define('AUTH_SALT',        '=7]Rt%n`gu4OQW@ t-}c2gT4lugT4t3@!rp~13Ae3I`~|v>C/a#~Dee8^4#*z>sd');
define('SECURE_AUTH_SALT', 'Z!s&)s+9,--@U8dK^n%`NEZ]zgN:C&J?N/Ygwyg/h,d$[T(OS?AP97Z%gc?G>V4o');
define('LOGGED_IN_SALT',   'X$|.]No~;?&c~*urO P8`:=[l|:7BGRr$0pD(h@vdDeq~JSX)Z+[}n3b[8R|%9&}');
define('NONCE_SALT',       'i>5xBciv`7$_==0<9`sOak$~ka {>yn42A{tQY`nX]4 Uxj3~GI8I= %MR!mqdw[');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
