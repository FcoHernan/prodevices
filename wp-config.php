<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'prodevices_db');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'rsu2015');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to uselocalhost in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '_x*[*]_Ly:~Xp5D^F4!Iz`mOZ+qT;Lu{?dojP_-TabI{,G-H;m9*YY)FG7BNbS0X');
define('SECURE_AUTH_KEY',  'A.q@>ujHO|>h/@P?/N:27?xQ$;L;oGroqeiPV1@+z u%?0T&VPUWa&g-~oh ]Pso');
define('LOGGED_IN_KEY',    '+&+)Qj+b-^BOjXUVZzvninIcu?RO-S4kmDX@dO-aP5]1Am3Q:xSH|3>3-U1Z=4E9');
define('NONCE_KEY',        '8fGw]>V?uWt=q#d-Mat*P4Y5bLa+c/!QU<E0rzQp8%rJF|k`CL=^!1hQ$]uWFC|-');
define('AUTH_SALT',        '-nHPu{x32[5XKVO.%h=g1U{_{{!2B/b`D1-h@2z4+%+3f4xu&N-]kv5?Rs*|AQlM');
define('SECURE_AUTH_SALT', '1m9czza9MIvzfu`v}|!+Q{07*9>/$&z/=Tmn9yw }{2bX=Z+4*O5-T0rUWnQD`z]');
define('LOGGED_IN_SALT',   'e0+T::*@:0h5qNmQbLL/@ 0+#0#H;MC8n=+`cog=Hwb@>72.S-8Gv]gH|#R+SH8A');
define('NONCE_SALT',       's{.=-O|SC#qO^)fsX-Z)[Dz6%JqE(f)L&m2w%6%nJ=W6YQO IrBz!cL~cCDpGmO?');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
