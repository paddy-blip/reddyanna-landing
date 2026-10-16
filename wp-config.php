<?php
/** WordPress configuration for the reddyanna site (local XAMPP). */

define( 'DB_NAME', 'reddyanna' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

define( 'AUTH_KEY', 'A&,A$<H+)k+VEPb=XOukYMZ{^K;jsporZ5MRquR>sF)?0[z:KPqvaWCIc/7$#l5<' );
define( 'SECURE_AUTH_KEY', 'be>U}=U<ii(nvh,%AgTv{IxRBzSobw>2LM+!WVVrS2zKI/rFtK{W}a-+k,=8o?yY' );
define( 'LOGGED_IN_KEY', 'qyCO$ERmfT1>oe%!>@@v==h&s<E}uN)j)3(Gi1!bU6#dPUt=_he>yij^K.Zc5jZC' );
define( 'NONCE_KEY', 'A3ut{QfNC?&/-R^YH<lFIv^*15NYF+w1>r|:cs]C+^,^T=H^UArHZnJ53Lx@|m@d' );
define( 'AUTH_SALT', 'cmf,N+:IIOB58T1z8Plh~UGuS?()X%c+`n.<c7mPCvUuGJo(2vhV},8|OVK./3t<' );
define( 'SECURE_AUTH_SALT', '&NU%bo-R0o2@9|rcQg=zEQ5/l22|qRwv9h|jXl3}~)Mjm(IH=22!j;`/OMT7iS2t' );
define( 'LOGGED_IN_SALT', 'JJT2]IA/Kq`t0K:PP>4[f1+C_oB.GF!)wa=7PT?>$~d6-fU!`OzipW+aM<uKbd=0' );
define( 'NONCE_SALT', 'ql+ZWEOJN,6T[eQJvwVtrqkCbMI!HgfsR`*bp2,h4pU&D!=|!8I=9-RSJj]wc!V9' );

$table_prefix = 'wp_';

define( 'WP_DEBUG', false );

/*
 * Site URL follows the domain the site is served from (localhost now, the live domain later),
 * so no localhost URLs leak after moving to the live server.
 * Optional hardening on live: define RA_ALLOWED_HOSTS, e.g. array( 'reddyanna.com', 'www.reddyanna.com' ).
 */
// Behind an HTTPS proxy (Cloudflare Tunnel, load balancer) the request reaches Apache as HTTP;
// tell WordPress it was HTTPS so is_ssl() is correct and there is no redirect loop.
if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' === strtolower( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ) {
	$_SERVER['HTTPS'] = 'on';
}

if ( ! defined( 'WP_CLI' ) && ! empty( $_SERVER['HTTP_HOST'] ) ) {
	$ra_host  = strtolower( preg_replace( '/[^A-Za-z0-9.\-:]/', '', $_SERVER['HTTP_HOST'] ) );
	$ra_https = ( ! empty( $_SERVER['HTTPS'] ) && 'off' !== strtolower( $_SERVER['HTTPS'] ) )
		|| ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' === strtolower( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) );
	$ra_root  = isset( $_SERVER['DOCUMENT_ROOT'] ) ? realpath( $_SERVER['DOCUMENT_ROOT'] ) : false;
	$ra_dir   = realpath( __DIR__ );
	$ra_path  = ( $ra_root && 0 === stripos( $ra_dir, $ra_root ) ) ? rtrim( str_replace( '\\', '/', substr( $ra_dir, strlen( $ra_root ) ) ), '/' ) : '';
	$ra_ok    = ! defined( 'RA_ALLOWED_HOSTS' ) || in_array( $ra_host, (array) RA_ALLOWED_HOSTS, true );
	if ( $ra_host && $ra_ok ) {
		define( 'WP_HOME', ( $ra_https ? 'https://' : 'http://' ) . $ra_host . $ra_path );
		define( 'WP_SITEURL', WP_HOME );
	}
	unset( $ra_host, $ra_https, $ra_root, $ra_dir, $ra_path, $ra_ok );
}

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';