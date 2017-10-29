<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package  gaston
 * @author   Christian Hapgood <christian.hapgood@gmail.com>
 * @license  GPL-2.0+
 * @link     TODO
 * @version  1.0.0
 * @since    0.0.2
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// TODO: Define uninstall functionality here