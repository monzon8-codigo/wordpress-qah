<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
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
define('DB_USER', 'wordpress');

/** MySQL database password */
define('DB_PASSWORD', 'q4h_p4ss');

/** MySQL hostname */
define('DB_HOST', 'wordpress.clgfsgayn0y6.eu-west-1.rds.amazonaws.com');

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
define('AUTH_KEY',         '2H+d,JRDXCi;+PN`6gf(P/bsyp2Ki_9}?Kfx_[wQglc9j8I<-]mEj}bu+]:#8KU9');
define('SECURE_AUTH_KEY',  'U-@yRUz?V-DKc7~:|g{f|<1~JjvAK6v<?vdu3@%PsgEx~VN{;^;upQ!Mp<S0Kn|[');
define('LOGGED_IN_KEY',    'Pd+~#oGBASKRv|@br NVHu*zR!2-`{4;w9O|Nw/,5.]+b?u&^&_n-ca=paS+B./#');
define('NONCE_KEY',        'ANK<L_p2;VtPA[.p#S]FG8P|;x{WSB)+TNj1yFIfDiYOF 3h^5#H[odtmXw)?=3K');
define('AUTH_SALT',        'K(=|-Cr?ozqN]!b.M?wN8Y_&tKP5J/6l]v.P%6^<V>+[P+QFA(@>1P$5k0Vl;lR,');
define('SECURE_AUTH_SALT', 'S]j#|P5E{Ibe.Tzo{zY-z$brH1Mmn{I,/@^,-r+El^025zy|V] AnUa!#~Md<D|f');
define('LOGGED_IN_SALT',   '*2xp@a$djH887H Y[@%#9db.QEG!Ds1Ca!Vn)Z4Hj0`V P-=|%d#:J)MNfU~;?gC');
define('NONCE_SALT',       ';+rS#P%5I7k=IDz)yf|RWs!BzkcPf>t|+|)g%$a,=uhRt([VZze.5j35Ag#( irh');

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
