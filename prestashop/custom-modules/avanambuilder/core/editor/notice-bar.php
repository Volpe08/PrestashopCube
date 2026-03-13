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

namespace AvanamBuilder\Core\Editor;

use AvanamBuilder\Core\Base\Base_Object;
use AvanamBuilder\Core\Common\Modules\Ajax\Module as Ajax;
use AvanamBuilder\Plugin;
use AvanamBuilder\Utils;
use AvanamBuilder\Wp_Helper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly
}

class Notice_Bar extends Base_Object {

	protected function get_init_settings() {
		if ( Plugin::$instance->get_install_time() > strtotime( '-30 days' ) ) {
			return [];
		}

		return [
			'muted_period' => 90,
			'option_key' => '_elementor_editor_upgrade_notice_dismissed',
			'message' => Wp_Helper::__( 'Love using Elementor? <a href="%s">Learn how you can build better sites with Elementor Pro.</a>', 'elementor' ),
			'action_title' => Wp_Helper::__( 'Get Pro', 'elementor' ),
			'action_url' => Utils::get_pro_link( 'https://avanam.org/presta/pro/?utm_source=editor-notice-bar&utm_campaign=gopro&utm_medium=wp-dash' ),
		];
	}

	final public function get_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return null;
		}

		$settings = $this->get_settings();

		if ( empty( $settings['option_key'] ) ) {
			return null;
		}

		$dismissed_time = Wp_Helper::get_option( $settings['option_key'] );

		if ( $dismissed_time ) {
			if ( $dismissed_time > strtotime( '-' . $settings['muted_period'] . ' days' ) ) {
				return null;
			}

			$this->set_notice_dismissed();
		}

		return $settings;
	}

	public function __construct() {
		Wp_Helper::add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ] );
	}

	public function set_notice_dismissed() {
		Wp_Helper::update_option( $this->get_settings( 'option_key' ), time() );
	}

	public function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'notice_bar_dismiss', [ $this, 'set_notice_dismissed' ] );
	}
}
