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

define('DB_NAME', 'poweri33_phipro');
define('DB_USER', 'poweri33_phipro');
define('DB_PASSWORD', 'maCIXOIBu6&x');
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
define('AUTH_KEY',         '#NHX_S8p e}|?3~.1XEEIu}1=`[_ccl7,YT*9{qfiBBK!G;xObA9zTAZbY,<U=Nf');
define('SECURE_AUTH_KEY',  'a%Ao{$6a5l./2My/D85q}&~4(JQ!uZxY>=|:VzfC%0%5C|,!NJXeQT2}^MLan!qF');
define('LOGGED_IN_KEY',    ' X7f7+qutX&n`R.|DX!q.dKULT?A8fC![gFqT>q;BgG*Y_z+!*z>?1Sk`r?un:(@');
define('NONCE_KEY',        '8Dc&FE?L<@yy9vrCH )0-|ur9TVqX(X86v={Z@UuI+=b.PbJ`nqE$E-vr*Y?9]M&');
define('AUTH_SALT',        'UD_Nqj74)W4`lCtH/2K8Nb9dXId7@_2]KM+KnF|d4_{2_WS}r:b!C@TwvCd2/l`f');
define('SECURE_AUTH_SALT', 'M,<T xo}X@)7sL|#FH~Ct$Y;YsG-aauxbWcKg*t&A+e@TulQVx.eF<9t!y-Na1Ug');
define('LOGGED_IN_SALT',   'tU`x`Eq,OB]dGX6376UK=`Jh;+egNd+.r9h@V#n3q*R>_[H~M*k71Ro4-V:u+8zm');
define('NONCE_SALT',       'bhywq5yjkxmgnlmjc5zje0ywy5mzmwztgzmjuzytjimzkyyje1zda4nwjkmdg1y2');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_frank_';

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
define('FACETWP_DEBUG', true);

define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

function md($debug_var, $mode = 'print_r') {
	echo '<pre style="overflow:auto">';
	switch ($mode) {
		case 'var_dump':
			var_dump($debug_var);
			break;
		case 'print_r':
		default:
			print_r($debug_var);
			break;
	}
	echo '</pre>';
}

function mdd($debug_var, $mode = 'print_r') {
	md($debug_var, $mode);
	die;
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

# Disables all core updates. Added by SiteGround Autoupdate:
define( 'WP_AUTO_UPDATE_CORE', false );
