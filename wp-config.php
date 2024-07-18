<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
define( 'DB_NAME', 'dreamsap_sru1' );

/** MySQL database username */
define( 'DB_USER', 'srsiter' );

/** MySQL database password */
define( 'DB_PASSWORD', 'M!Ks-^8a3KTJ' );

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
define( 'AUTH_KEY',         '.kf3%@Vu9/4C:?TTi3wIDkF}nCa?L#{uIGI-h?!+~*!m~$a~Q6SR}kzkWHFk-nGD' );
define( 'SECURE_AUTH_KEY',  'baoA!?DE$_#_*$YcPflay{*zBXO0nd448eaz5h6Hu9J{3Z0ZX@v]?ouKPZVbEzNO' );
define( 'LOGGED_IN_KEY',    '!3:Pp;XRry5Xkn^l}B+x^DBB%X%yJB?:A#SrCacPBsO42Mk/B+3AGROY?9L:lY?A' );
define( 'NONCE_KEY',        'MyEoQv$1k$v,BJsBz&?fmvBx;tv==Y/Srx&`tpeFaIj)%)R4Nevb9)437j<1PJp,' );
define( 'AUTH_SALT',        'K~#PY M+C_o4>I754{%ZP[k7v/-vRV~>naGI08>JRQHC%uo-QL:[V<0Qn^XRNr9m' );
define( 'SECURE_AUTH_SALT', '<KMAmN>Pqq@10MYCd3.Lhua*`6GG7YI<E@iF{%[Z[S:ZOh#KHp4 @m(:T_Rby,q<' );
define( 'LOGGED_IN_SALT',   'FiEdr4~Y^u*f9]gP^uTmi{ PluE-*aZG@>kJOoUq[u7vjFH^42jJN`Hsv`H5e%`G' );
define( 'NONCE_SALT',       '[z.EcxD./_,TiORp[C<-e!B*cP/[ey5}/jP64W40yq_T7{@A59FaR?T(=tyk6}& ' );

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
define( 'WP_DEBUG', false );


/* Add any custom values between this line and the "stop editing" line. */

define( 'DISALLOW_FILE_EDIT', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
