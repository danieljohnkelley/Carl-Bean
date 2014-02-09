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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'radish84');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
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
define('AUTH_KEY',         'Ww9>3E3zAJ 1Nq>w/$QvC6~*rC){:sUrZ^Vpd/<iM e) aH?6oImvugtGh]?5O=$');
define('SECURE_AUTH_KEY',  '{yU7X HuI=,W;uDQP[WWFS-LXGB#VoOS(CzH1!VOEz8Ur->Cr@`@poVpIOcaF:V}');
define('LOGGED_IN_KEY',    'K9=zS~QZ!{-*TJzqA8^?4WFUEk*4odr8_TTZ?gJ @,2r;Ur&;H-:[[;d&c57q6P$');
define('NONCE_KEY',        '+<36W$F(XDFo{d@ry&`TbOY;M6nE@f%&!0w6+]^E*IihONtUD`&%I;MZ>)Y)*]$g');
define('AUTH_SALT',        'j]YZ ^!OWvJFg>gzoRWT!-&W0^ci.VF6.~S-Nfe6P5~c_*!=!nwU~fJASh`=DI?/');
define('SECURE_AUTH_SALT', 'C/sbu{myg9fkU1{-kZ(J!<$},;x.q$3)tDnkvu2{X)[fV-#Z(uy}xs+l)MRuVI>8');
define('LOGGED_IN_SALT',   '%{+:HTtN[h`a)h|Cj t<z=HA,4t>DyyY:?9zqfx{4Re3UbbL%iz{J,j;m#oWAEM)');
define('NONCE_SALT',       '#yayAO?rV@:Q)Iz{(}c4kX1=!c{+-Vu+KVpV3UGAU;y/3OfG$Jnwb:sTDV?9`410');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

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
