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
 * Elementor color scheme.
 *
 * Elementor color scheme class is responsible for initializing a scheme for
 * colors.
 *
 * @since 1.0.0
 */
class Scheme_Color extends Scheme_Base {

	/**
	 * 1st color scheme.
	 */
	const COLOR_1 = '1';

	/**
	 * 2nd color scheme.
	 */
	const COLOR_2 = '2';

	/**
	 * 3rd color scheme.
	 */
	const COLOR_3 = '3';

	/**
	 * 4th color scheme.
	 */
	const COLOR_4 = '4';

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
	 * 8th color scheme.
	 */
	const COLOR_8 = '8';

	/**
	 * 9th color scheme.
	 */
	const COLOR_9 = '9';
	
	/**
	 * 10th color scheme.
	 */
	const COLOR_10 = '10';

	/**
	 * 11th color scheme.
	 */
	const COLOR_11 = '11';

	/**
	 * Get color scheme type.
	 *
	 * Retrieve the color scheme type.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Color scheme type.
	 */
	public static function get_type() {
		return 'color';
	}

	/**
	 * Get color scheme title.
	 *
	 * Retrieve the color scheme title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Color scheme title.
	 */
	public function get_title() {
		return Wp_Helper::__( 'Colors', 'elementor' );
	}

	/**
	 * Get color scheme disabled title.
	 *
	 * Retrieve the color scheme disabled title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Color scheme disabled title.
	 */
	public function get_disabled_title() {
		return Wp_Helper::__( 'Color Palettes', 'elementor' );
	}

	/**
	 * Get color scheme titles.
	 *
	 * Retrieve the color scheme titles.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Color scheme titles.
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
	 * Get default color scheme.
	 *
	 * Retrieve the default color scheme.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Default color scheme.
	 */
	public function get_default_scheme() {
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
	 * Print color scheme content template.
	 *
	 * Used to generate the HTML in the editor using Underscore JS template. The
	 * variables for the class are available using `data` JS object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function print_template_content() {
		?>
		<div class="elementor-panel-scheme-content elementor-panel-box">
			<div class="elementor-panel-heading">
				<div class="elementor-panel-heading-title"><?php echo $this->_get_current_scheme_title(); ?></div>
			</div>
			<?php
			$description = static::get_description();

			if ( $description ) :
				?>
				<div class="elementor-panel-scheme-description elementor-descriptor"><?php echo $description; ?></div>
			<?php endif; ?>
			<div class="elementor-panel-scheme-items elementor-panel-box-content"></div>
		</div>
		<?php /* <div class="elementor-panel-scheme-colors-more-palettes elementor-panel-box">
			<div class="elementor-panel-heading">
				<div class="elementor-panel-heading-title"><?php echo Wp_Helper::__( 'More Palettes', 'elementor' ); ?></div>
			</div>
			<div class="elementor-panel-box-content">
				<?php foreach ( $this->_get_system_schemes_to_print() as $scheme_name => $scheme ) : ?>
					<div class="elementor-panel-scheme-color-system-scheme" data-scheme-name="<?php echo Wp_Helper::esc_attr( $scheme_name ); ?>">
						<div class="elementor-panel-scheme-color-system-items">
							<?php foreach ( $scheme['items'] as $color_value ) : ?>
								<div class="elementor-panel-scheme-color-system-item" style="background-color: <?php echo Wp_Helper::esc_attr( $color_value ); ?>;"></div>
							<?php endforeach; ?>
						</div>
						<div class="elementor-title"><?php echo $scheme['title']; ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div> */ ?>
		<?php
	}

	/**
	 * Init system color schemes.
	 *
	 * Initialize the system color schemes.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array System color schemes.
	 */
	protected function _init_system_schemes() {
		return [
			'joker' => [
				'title' => 'Joker',
				'items' => [
					self::COLOR_1 => '#202020',
					self::COLOR_2 => '#b7b4b4',
					self::COLOR_3 => '#707070',
					self::COLOR_4 => '#f6121c',
				],
			],
			'ocean' => [
				'title' => 'Ocean',
				'items' => [
					self::COLOR_1 => '#1569ae',
					self::COLOR_2 => '#b6c9db',
					self::COLOR_3 => '#545454',
					self::COLOR_4 => '#fdd247',
				],
			],
			'royal' => [
				'title' => 'Royal',
				'items' => [
					self::COLOR_1 => '#d5ba7f',
					self::COLOR_2 => '#902729',
					self::COLOR_3 => '#ae4848',
					self::COLOR_4 => '#302a8c',
				],
			],
			'violet' => [
				'title' => 'Violet',
				'items' => [
					self::COLOR_1 => '#747476',
					self::COLOR_2 => '#ebca41',
					self::COLOR_3 => '#6f1683',
					self::COLOR_4 => '#a43cbd',
				],
			],
			'sweet' => [
				'title' => 'Sweet',
				'items' => [
					self::COLOR_1 => '#6ccdd9',
					self::COLOR_2 => '#763572',
					self::COLOR_3 => '#919ca7',
					self::COLOR_4 => '#f12184',
				],
			],
			'urban' => [
				'title' => 'Urban',
				'items' => [
					self::COLOR_1 => '#db6159',
					self::COLOR_2 => '#3b3b3b',
					self::COLOR_3 => '#7a7979',
					self::COLOR_4 => '#2abf64',
				],
			],
			'earth' => [
				'title' => 'Earth',
				'items' => [
					self::COLOR_1 => '#882021',
					self::COLOR_2 => '#c48e4c',
					self::COLOR_3 => '#825e24',
					self::COLOR_4 => '#e8c12f',
				],
			],
			'river' => [
				'title' => 'River',
				'items' => [
					self::COLOR_1 => '#8dcfc8',
					self::COLOR_2 => '#565656',
					self::COLOR_3 => '#50656e',
					self::COLOR_4 => '#dc5049',
				],
			],
			'pastel' => [
				'title' => 'Pastel',
				'items' => [
					self::COLOR_1 => '#f27f6f',
					self::COLOR_2 => '#f4cd78',
					self::COLOR_3 => '#a5b3c1',
					self::COLOR_4 => '#aac9c3',
				],
			],
		];
	}

	/**
	 * Get system color schemes to print.
	 *
	 * Retrieve the system color schemes
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return string The system color schemes.
	 */
	protected function _get_system_schemes_to_print() {
		return $this->get_system_schemes();
	}

	/**
	 * Get current color scheme title.
	 *
	 * Retrieve the current color scheme title.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return string The current color scheme title.
	 */
	protected function _get_current_scheme_title() {
		return Wp_Helper::__( 'Color Palette', 'elementor' );
	}
}
