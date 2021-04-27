<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'dmwizard_blog');

/** MySQL database username */
define('DB_USER', 'dmwizard_admin');

/** MySQL database password */
define('DB_PASSWORD', 'sql@blog123');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'I?NPTfy^.}je_q;FRO,Yh_>RV,iz0EZ,$.P5O#`yP(RR$2PE4px!<wRFFXs-nZxA');
define('SECURE_AUTH_KEY',  ';|.G]#{s:ZvoRF?gdoQqzWsT8p5Qaw7j4?Cb%Y,QK6~KMNDnqP ]tSeR>8:V7yO5');
define('LOGGED_IN_KEY',    '&#+#L? Sxi%Y(a`=m,gQT^5M0vEvX]kl8y0W|JBPrsAsH/?Z`C7f{-B^@HUGT6CJ');
define('NONCE_KEY',        's[g8zmf).nJ(V>4|TYOb_tE/*jo(+{B%OsA$o?ED@OnT`|k9`k([pA+WIe$4O,:V');
define('AUTH_SALT',        '#w2s{vkzs`.N?DqwAn>rYig?5kU_1uh~tG`OwuS70TL`S#^=][Ni5itBWX/;o?F[');
define('SECURE_AUTH_SALT', '+##jU%!9~|cD7`hX<]< C[Dre/+XLWPE&)/iH,N7,?H)qlYX}LnIEpRk66<WyPUO');
define('LOGGED_IN_SALT',   '4r6MeQM_Wv4ASEIF XU#L^iB#RT-J0Qh7*6(9U3T9@| #ZdqWS_l}G(`[Um;SnRm');
define('NONCE_SALT',       'X2zcIt>5A<N&-|D`l`8L0&nHKBm|B=x~%I|p2f{r6?T6uqItm0I{:>4)LXu,wIv/');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'dmw_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
