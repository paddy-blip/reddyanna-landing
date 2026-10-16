<?php
/**
 * Reddy Anna theme bootstrap.
 *
 * @package ReddyAnna
 */

defined( 'ABSPATH' ) || exit;

define( 'RA_VERSION', '1.0.0' );
define( 'RA_DIR', get_template_directory() );
define( 'RA_URI', get_template_directory_uri() );

require RA_DIR . '/inc/setup.php';
require RA_DIR . '/inc/icons.php';
require RA_DIR . '/inc/helpers.php';
require RA_DIR . '/inc/enqueue.php';
require RA_DIR . '/inc/performance.php';
require RA_DIR . '/inc/acf-fields.php';
require RA_DIR . '/inc/theme-options.php';
require RA_DIR . '/inc/seo.php';
require RA_DIR . '/inc/cf7.php';
