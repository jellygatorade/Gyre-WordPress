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
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         'M}]~@*%q.4| RkU#{&I@mn(FQhND>?&Ut_<NX|#]#+;z]1wJa42HITATy[U7dZfF' );
define( 'SECURE_AUTH_KEY',  '1mHehA`e9A}h9dih`j6J6aj6G~jy<KU!8!{PsGwp-g(5s5+S@Hj?bs3M~mS4Sf<|' );
define( 'LOGGED_IN_KEY',    'AZMJoc=Ml&k5Y!G~f%/vQklLI/urR6_FL*sXk+uX_E@ll,qaMU,Sag~gj,m#J. l' );
define( 'NONCE_KEY',        'qSvAk}IR.~-js6r73}AgA*23)8YQOoC/4;H;-SC>4#n<taWRmUa fZn}nB(]=d3>' );
define( 'AUTH_SALT',        'rrzL]>H%iIHD09Ss `EauX:sGQjBW-{}v1ccoO>qiLl2biu5!Nw*cu5H3YKFN`XU' );
define( 'SECURE_AUTH_SALT', 'x%d 5w`~SK$>.bLhE&FT< _J=5;y77BBOl9GR)<+/}LP@cyKZzLn|;o#r1<.3._(' );
define( 'LOGGED_IN_SALT',   '>hr8,VPJ,@ux;K&o:Fo*IcTx-XyzCuA4p.)so@+Fl%ly*@i2Q5Fy#|3u9*l7jYL0' );
define( 'NONCE_SALT',       'Nhd>fteqyacFS%qtV]St-(!*2ZtTL/16vxv(_ZmX*xZ]k?Cm5,`P|u3eSJ#7$fd}' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
// Enable Debug logging to the /wp-content/debug.log file
define( 'WP_DEBUG_LOG', true );
// Enable Debug errors to display in browser
define( 'WP_DEBUG_DISPLAY', true );

/* Add any custom values between this line and the "stop editing" line. */

/* Enable multisite */
define( 'WP_ALLOW_MULTISITE', true );
define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', false );
define( 'DOMAIN_CURRENT_SITE', 'localhost' ); // Original
//define( 'DOMAIN_CURRENT_SITE', '192.168.50.236' ); // For contacting REST API over network at IP address 3/22/2022, see https://wpengine.com/support/how-to-change-a-multi-site-primary-domain/
define( 'PATH_CURRENT_SITE', '/wordpress/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
