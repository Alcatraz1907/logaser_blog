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
define('DB_NAME', 'logaster.blog');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost:3306');

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
define('AUTH_KEY',         'D]i(OVrv&1OqDo&Yq{1pgw#1.g5s36kH*K_W)]kQO3}DhYvkE(BQ4_95dg#kfz:$');
define('SECURE_AUTH_KEY',  '3Gvn4_m8+y/>M7^4GaG~2Ba>J|Ki3:z0Bzd^?J[pwlMHk@hG#.nbEgd$<+[5I1~+');
define('LOGGED_IN_KEY',    'G?h{|VZ5,xZBEDPAZ>c)@M}Jx>r)_m6dY<*}To,f%|/^d5?(P)D!D 1:?En*_>0g');
define('NONCE_KEY',        'X2yKYX9_~$Y%b2|X5+Q-kZr|6)c{8j;!Njl}:Zew *@wanD-_uMlT|iHA&fKB,z5');
define('AUTH_SALT',        '^ICU w9Ig?]Af7<%UFY&DSpoEC*}0Qn3NnKoADAO*Fo,4U=h?x3nE2O|>__!zNv?');
define('SECURE_AUTH_SALT', 'cTzFxA3%>8]M]()%cv-$P_Xz+ :);pPF`@|2U|$fG(T!r|W=;H^ZRs}IHH]1GsW!');
define('LOGGED_IN_SALT',   '-OI3@Vs(4^+i^uK8<YT1j;srkI#wrI|{^jn[CK X+L:ZqkW0p.G_qCDBMCkK>)`2');
define('NONCE_SALT',       'zW3-m!fNCFn,hv<Io3R)gA>I=R`&rMeL`cwP&/oS<jUEbCPV$J&H+&w]Zzs<a*1A');

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
define('WPLANG', 'ru_RU');

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
