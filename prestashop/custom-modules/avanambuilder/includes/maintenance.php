<?php
/**
 * AvanamBuilder - Website Builder
 *
 * NOTICE OF LICENSE
 *
 * @author    avanam.org
 * @copyright avanam.org
 * @license   You can not resell or redistribute this software.
 *
 * https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace AvanamBuilder;

use AvanamBuilder\Wp_Helper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor maintenance.
 *
 * Elementor maintenance handler class is responsible for setting up Elementor
 * activation and uninstallation hooks.
 *
 * @since 1.0.0
 */
class Maintenance {

	/**
	 * Activate Elementor.
	 *
	 * Set Elementor activation hook.
	 *
	 * Fired by `register_activation_hook` when the plugin is activated.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function activation( $network_wide ) {
		wp_clear_scheduled_hook( 'elementor/tracker/send_event' );

		wp_schedule_event( time(), 'daily', 'elementor/tracker/send_event' );
		flush_rewrite_rules();

		if ( is_multisite() && $network_wide ) {
			return;
		}

		Wp_Helper::set_transient( 'elementor_activation_redirect', true, AVANAM_MINUTE_IN_SECONDS );
	}

	/**
	 * Uninstall Elementor.
	 *
	 * Set Elementor uninstallation hook.
	 *
	 * Fired by `register_uninstall_hook` when the plugin is uninstalled.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function uninstall() {
		wp_clear_scheduled_hook( 'elementor/tracker/send_event' );
	}

	/**
	 * Init.
	 *
	 * Initialize Elementor Maintenance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function init() {
		register_activation_hook( AVANAM_BUILDER_PLUGIN_BASE, [ __CLASS__, 'activation' ] );
		register_uninstall_hook( AVANAM_BUILDER_PLUGIN_BASE, [ __CLASS__, 'uninstall' ] );
	}
}