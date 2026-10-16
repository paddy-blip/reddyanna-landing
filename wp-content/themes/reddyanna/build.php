<?php
/**
 * Tiny asset build: creates main.min.css and main.min.js.
 * Run after editing assets:  php build.php
 *
 * @package ReddyAnna
 */

if ( 'cli' !== PHP_SAPI ) {
	exit;
}

$dir = __DIR__ . '/assets';

// CSS: strip comments and collapse whitespace.
$css = file_get_contents( "$dir/css/main.css" );
$css = preg_replace( '#/\*.*?\*/#s', '', $css );
$css = preg_replace( '/\s+/', ' ', $css );
$css = preg_replace( '/\s*([{}:;,>])\s*/', '$1', $css );
$css = str_replace( ';}', '}', $css );
// Restore the space required in media queries ("and(" is invalid).
$css = preg_replace( '/\band\(/', 'and (', $css );
file_put_contents( "$dir/css/main.min.css", trim( $css ) );

// JS: conservative — remove block/line comments and indentation only.
$js = file_get_contents( "$dir/js/main.js" );
$js = preg_replace( '#/\*.*?\*/#s', '', $js );
$js = preg_replace( '#^\s*//.*$#m', '', $js );
$js = preg_replace( '/^\s+/m', '', $js );
$js = preg_replace( "/\n+/", "\n", $js );
file_put_contents( "$dir/js/main.min.js", trim( $js ) );

printf( "css %d -> %d bytes\njs %d -> %d bytes\n", filesize( "$dir/css/main.css" ), filesize( "$dir/css/main.min.css" ), filesize( "$dir/js/main.js" ), filesize( "$dir/js/main.min.js" ) );
