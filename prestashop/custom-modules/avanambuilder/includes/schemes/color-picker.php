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
 * Elementor color picker scheme.
 *
 * Elementor color picker scheme class is responsible for initializing a scheme
 * for color pickers.
 *
 * @since 1.0.0
 */
class Scheme_Color_Picker extends Scheme_Color {

	/**
	 * 5th color scheme.
	 */
	const COLOR_5 = '5';

	/**
	 * 6th color scheme.
	 */
	const COLOR_6 = '6';

	/**
	 * 7th color scheme.
	 */
	const COLOR_7 = '7';

	/**
	 * 9th color scheme.
	 */
	const COLOR_8 = '8';

	/**
	 * 9th color scheme.
	 */
	const COLOR_9 = '9';

	/**
	 * 9th color scheme.
	 */
	const COLOR_10 = '10';

	/**
	 * 9th color scheme.
	 */
	const COLOR_11 = '11';

	/**
	 * Get color picker scheme type.
	 *
	 * Retrieve the color picker scheme type.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Color picker scheme type.
	 */
	public static function get_type() {
		return 'color-picker';
	}

	/**
	 * Get color picker scheme description.
	 *
	 * Retrieve the color picker scheme description.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Color picker scheme description.
	 */

	public static function get_description() {
		return Wp_Helper::__( 'Choose which colors appear in the editor\'s color picker. This makes accessing the colors you chose for the site much easier.', 'elementor' );
	}

	/**
	 * Get default color picker scheme.
	 *
	 * Retrieve the default color picker scheme.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Default color picker scheme.
	 */
	public function get_default_scheme() {
		/* return array_replace(
			parent::get_default_scheme(), [
				self::COLOR_1 => '#3182ce',
				self::COLOR_2 => '#2b6cb0',
				self::COLOR_3 => '#1b212d',
				self::COLOR_4 => '#2e3849',
				self::COLOR_5 => '#4b5669',
				self::COLOR_6 => '#728197',
				self::COLOR_7 => '#eef3f8',
				self::COLOR_8 => '#f8fbfd',
				self::COLOR_9 => '#ffffff',
				self::COLOR_10 => '#3182ce',
				self::COLOR_11 => '#2b6cb0',
			]
		); */
		return [
			self::COLOR_1 => '#3182ce',
			self::COLOR_2 => '#2b6cb0',
			self::COLOR_3 => '#1b212d',
			self::COLOR_4 => '#2e3849',
			self::COLOR_5 => '#4b5669',
			self::COLOR_6 => '#728197',
			self::COLOR_7 => '#eef3f8',
			self::COLOR_8 => '#f8fbfd',
			self::COLOR_9 => '#ffffff',
			self::COLOR_10 => '#3182ce',
			self::COLOR_11 => '#2b6cb0',
		];
	}

	/**
	 * Get color picker scheme titles.
	 *
	 * Retrieve the color picker scheme titles.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Color picker scheme titles.
	 */
	public function get_scheme_titles() {
		return [
			self::COLOR_1 => Wp_Helper::__( 'Palette 1', 'elementor' ),
			self::COLOR_2 => Wp_Helper::__( 'Palette 2', 'elementor' ),
			self::COLOR_3 => Wp_Helper::__( 'Palette 3', 'elementor' ),
			self::COLOR_4 => Wp_Helper::__( 'Palette 4', 'elementor' ),
			self::COLOR_5 => Wp_Helper::__( 'Palette 5', 'elementor' ),
			self::COLOR_6 => Wp_Helper::__( 'Palette 6', 'elementor' ),
			self::COLOR_7 => Wp_Helper::__( 'Palette 7', 'elementor' ),
			self::COLOR_8 => Wp_Helper::__( 'Palette 8', 'elementor' ),
			self::COLOR_9 => Wp_Helper::__( 'Palette 9', 'elementor' ),
			self::COLOR_10 => Wp_Helper::__( 'Palette 10', 'elementor' ),
			self::COLOR_11 => Wp_Helper::__( 'Palette 11', 'elementor' ),
		];
	}

	/**
	 * Init system color picker schemes.
	 *
	 * Initialize the system color picker schemes.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array System color picker schemes.
	 */
	protected function _init_system_schemes() {
		$schemes = parent::_init_system_schemes();

		$additional_schemes = [
			'joker' => [
				'items' => [
					self::COLOR_5 => '#4b4646',
					self::COLOR_6 => '#e2e2e2',
				],
			],
			'ocean' => [
				'items' => [
					self::COLOR_5 => '#154d80',
					self::COLOR_6 => '#8c8c8c',
				],
			],
			'royal' => [
				'items' => [
					self::COLOR_5 => '#ac8e4d',
					self::COLOR_6 => '#e2cea1',
				],
			],
			'violet' => [
				'items' => [
					self::COLOR_5 => '#9c9ea6',
					self::COLOR_6 => '#c184d0',
				],
			],
			'sweet' => [
				'items' => [
					self::COLOR_5 => '#41aab9',
					self::COLOR_6 => '#ffc72f',
				],
			],
			'urban' => [
				'items' => [
					self::COLOR_5 => '#aa4039',
					self::COLOR_6 => '#94dbaf',
				],
			],
			'earth' => [
				'items' => [
					self::COLOR_5 => '#aa6666',
					self::COLOR_6 => '#efe5d9',
				],
			],
			'river' => [
				'items' => [
					self::COLOR_5 => '#7b8c93',
					self::COLOR_6 => '#eb6d65',
				],
			],
			'pastel' => [
				'items' => [
					self::COLOR_5 => '#f5a46c',
					self::COLOR_6 => '#6e6f71',
				],
			],
		];

		$schemes = array_replace_recursive( $schemes, $additional_schemes );

		foreach ( $schemes as & $scheme ) {
			$scheme['items'] += [
				self::COLOR_7 => '#000',
				self::COLOR_8 => '#fff',
			];
		}

		return $schemes;
	}

	/**
	 * Get system color picker schemes to print.
	 *
	 * Retrieve the system color picker schemes
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return string The system color picker schemes.
	 */
	protected function _get_system_schemes_to_print() {
		$schemes = $this->get_system_schemes();

		$items_to_print = [
			self::COLOR_1,
			self::COLOR_5,
			self::COLOR_2,
			self::COLOR_3,
			self::COLOR_6,
			self::COLOR_4,
		];

		$items_to_print = array_flip( $items_to_print );

		foreach ( $schemes as $scheme_key => $scheme ) {
			$schemes[ $scheme_key ]['items'] = array_replace( $items_to_print, array_intersect_key( $scheme['items'], $items_to_print ) );
		}

		return $schemes;
	}

	/**
	 * Get current color picker scheme title.
	 *
	 * Retrieve the current color picker scheme title.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return string The current color picker scheme title.
	 */
	protected function _get_current_scheme_title() {
		return Wp_Helper::__( 'Color Picker', 'elementor' );
	}
}
