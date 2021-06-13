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
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', 'ZgFK4sAp' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'rzh3jT.ju{ZL/I8r(bM&Kq_.d8U=;~HybGUlp&q!I}v3[zC(EIp#t;Lv?jJRK!~o' );
define( 'SECURE_AUTH_KEY',   '`-u{ZgGb#2lRz2M?LimYZ50MlOgcVg{*H0nK}B]*?_n3RnXR^PARV5N8Mc9c$)41' );
define( 'LOGGED_IN_KEY',     'Vd-4R6<uO:Lx(ohuVw[^64U!/K?h#P>:=0Yy7PDNWtiWLsP:|oub9iDN7*J55:%K' );
define( 'NONCE_KEY',         ')0$$4mi=,?gxe K>ImPKZN}>je|;l~hSi8T,!bF|c*9NCji)Q >Q$C,]%5bXP0NX' );
define( 'AUTH_SALT',         'g}q~=W8;]v>CwP{x4RdE(Pi}~-(cx4=8AX5{Dn@3+;F-5VNWP[3p=CGp-QVdxmFx' );
define( 'SECURE_AUTH_SALT',  '@J;A7`1i~mgXaHKgZZy}kOXV}rxDte5aEop?nE_NuGI>3f?2q./?%8.>]mB~(w/J' );
define( 'LOGGED_IN_SALT',    '-9OTgu&_R<1 .E!^>{<?h~eG:^Si}a4PXl`.}5b9;5;qG7wWZBffUlEQ6@Ad%h0_' );
define( 'NONCE_SALT',        '6k8PXIvdGK6W{~D. grdsfuc n|{O7(}e$gS]j0i?4=U^?5s3?Vj=0Gt#Ia-W.Es' );
define( 'WP_CACHE_KEY_SALT', 'U+3CNPP^|/l8P#&/5gbuo|cJyj]+s7O^}lSk+|5}1Mb`%ii&vbrzTI~]E<Pg>+q1' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
