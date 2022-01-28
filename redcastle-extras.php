<?php
/**
 * Plugin Name:     Red Castle Extras
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     Enhancers for the Red Castle website
 * Author:          Michael Wender
 * Author URI:      https://mwender.com
 * Text Domain:     redcastle-extras
 * Domain Path:     /languages
 * Version:         1.1.0
 *
 * @package         Redcastle_Extras
 */

// Your code starts here.
$css_dir = ( stristr( site_url(), '.local' ) || SCRIPT_DEBUG )? 'css' : 'dist' ;
define( 'REDCASTLE_CSS_DIR', $css_dir );
define( 'REDCASTLE_DEV_ENV', stristr( site_url(), '.local' ) );
define( 'REDCASTLE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'REDCASTLE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Load Composer dependencies
if( file_exists( REDCASTLE_PLUGIN_PATH . 'vendor/autoload.php' ) ){
  require_once REDCASTLE_PLUGIN_PATH . 'vendor/autoload.php';
} else if( file_exists( REDCASTLE_PLUGIN_PATH . 'composer.json' ) ) {
  add_action( 'admin_notices', function(){
    $class = 'notice notice-error';
    $message = __( '<strong>Missing Composer Files:</strong> Missing required Composer libraries. Please run <code>composer install</code> from the root directory of this plugin.', 'redcastle-extras' );
    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
  } );
}

// Load required files
require_once( REDCASTLE_PLUGIN_PATH . 'lib/fns/acf.php' );
require_once( REDCASTLE_PLUGIN_PATH . 'lib/fns/acf-json-save-point.php' );
require_once( REDCASTLE_PLUGIN_PATH . 'lib/fns/classes.php' );

/**
 * Enhanced logging.
 *
 * @param      string  $message  The log message
 */
if( ! function_exists( 'uber_log' ) ){
  function uber_log( $message = null ){
    static $counter = 1;

    $bt = debug_backtrace();
    $caller = array_shift( $bt );

    if( 1 == $counter )
      error_log( "\n\n" . str_repeat('-', 25 ) . ' STARTING DEBUG [' . date('h:i:sa', current_time('timestamp') ) . '] ' . str_repeat('-', 25 ) . "\n\n" );
    error_log( "\n" . $counter . '. ' . basename( $caller['file'] ) . '::' . $caller['line'] . "\n" . $message . "\n---\n" );
    $counter++;
  }
}