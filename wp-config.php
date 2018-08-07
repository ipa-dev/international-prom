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
define('DB_NAME', 'internationalpro_staging');

/** MySQL database username */
define('DB_USER', 'internat_ipa');

/** MySQL database password */
define('DB_PASSWORD', '.p$DcUwMd^KC');

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
define('AUTH_KEY',         'J:.DRvMIaF4-*m6%UTdGT|Z!^XW8;*6x+A|]z73u7*5Be##3XWR]-UJ]&nh_d-+P');
define('SECURE_AUTH_KEY',  ']b[70kuUnWTZ@;^>=Ii[yJ.{WGOMMYfNssQQu++9VNV3VC]/PsBIO1Sz~9y0gu^|');
define('LOGGED_IN_KEY',    '*Nr>Tlmg(Tv*MgzM.K>Ny6(),wKGc1{]vf+gxwlc^T6Oo]=sNf~r>[>6~T{*}RA|');
define('NONCE_KEY',        '/F--=-0E(-]L;ozmCVx,swbOpSQlQ1%H.%pK%aD)@kR;t`<-|?WvR^?gl8iS&<JY');
define('AUTH_SALT',        '&tpC@kK,Eb)~^Gvr)?26Li@qI]^4vW$OrlSFXua?+R_vq9{+IWftvU`MkU=TJOgk');
define('SECURE_AUTH_SALT', 'TJFryVl|,u3N({rs!RaU)@*ad&@JTxS89TFe_?=Brj|1i[X!.5i}D|a-8uFVdrDk');
define('LOGGED_IN_SALT',   'h-K-27hi+Pp2vN.;^`cU9a6`bW^MjJtE4|T9+<t{+(|)BIS`#HwUbJ=*+42nR<6W');
define('NONCE_SALT',       '+:`D-2/2^7-&0//7d)|A9^NRNkmwD[9I]IPTDyYOb+}@RCesoVvwKi4%/2h8*|&Z');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'internationalprom_';

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
// Enable WP_DEBUG mode
define( 'WP_DEBUG', true );


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');