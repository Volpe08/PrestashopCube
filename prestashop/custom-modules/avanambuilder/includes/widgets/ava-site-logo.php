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
	exit; // Exit if accessed directly
}

class Widget_Ava_Site_Logo extends Widget_Image {


	public function get_name() {
		return 'ava-site-logo';
	}

	public function get_title() {
		return Wp_Helper::__( 'Site Logo', 'elementor' );
	}

	public function get_icon() {
		return 'eicon-site-logo';
	}

	public function get_categories() {
		return [ 'avanam-elements' ];
	}

	public function get_keywords() {
		return [ 'site', 'logo', 'branding', 'avanam' ];
	}

	protected function _register_controls() {
		parent::_register_controls();

        $this->update_control(
			'image',
			[
                'default' => [
                    'id' => '',
                    'url' => \Context::getContext()->shop->getBaseURL(true) . 'img/' . \Configuration::get('PS_LOGO'),
					'alt' => \Configuration::get('PS_SHOP_NAME'),
                ],
				'dynamic' => [
					'active' => true,
					'default' => Plugin::$instance->dynamic_tags->tag_data_to_tag_text(null, 'site-logo'),
				],
			]
		);

		$this->update_control(
			'link_to',
			[
                'options' => [
                    'none' => Wp_Helper::__('None'),
                    'custom' => Wp_Helper::__('Site URL'),
                ],
				'default' => 'custom',
			]
		);

		$this->update_control(
			'link',
			[
				'default' => [
                    'url' => \Context::getContext()->shop->getBaseURL(true),
                ],
				'dynamic' => [
					'active' => true,
					'default' => Plugin::$instance->dynamic_tags->tag_data_to_tag_text(null, 'site-url'),
				],
			]
		);

		$this->update_control(
			'caption_source',
			[
				'options' => $this->get_caption_source_options(),
			]
		);

		$this->remove_control( 'caption' );
	}

	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' elementor-widget-' . parent::get_name();
	}

	private function get_caption_source_options() {
		$caption_source_options = $this->get_controls( 'caption_source' )['options'];

		unset( $caption_source_options['custom'] );

		return $caption_source_options;
	}
}
