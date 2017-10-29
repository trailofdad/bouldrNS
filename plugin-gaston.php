<?php
/**
 * @package gaston-api
 * @author  Christian Hapgood <christian.hapgood@gmail.com>
 * @license GPL-2.0+
 * @link    TODO
 *
 * @wordpress-plugin
 * Plugin Name: gaston-api
 * Plugin URI: TODO
 * Description: A WordPress plugin for enabling a climbing area API
 * Version: 0.0.1
 * Author: Christian Hapgood
 * Author URI: http://christianhapgood.ca
 * Author Email: christian.hapgood@gmail.com
 * Text Domain: gaston-locale
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang/
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * The following constant is used to define a constant for this plugin to make it
 * easier to provide cache-busting functionality on loading stylesheets
 * and JavaScript.
 */
if ( ! defined( 'GASTON_VERSION' ) ) {

	define( 'GASTON_VERSION', '0.0.1' );

}

require_once( plugin_dir_path( __FILE__ ) . 'class-gaston.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'gaston', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'gaston', 'deactivate' ) );

gaston::get_instance();