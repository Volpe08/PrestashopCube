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

namespace AvanamBuilder\Core\Settings\Base;

use AvanamBuilder\Controls_Stack;
use AvanamBuilder\Wp_Helper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor settings base model.
 *
 * Elementor settings base model handler class is responsible for registering
 * and managing Elementor settings base models.
 *
 * @since 1.6.0
 * @abstract
 */
abstract class Model extends Controls_Stack {

	/**
	 * Get CSS wrapper selector.
	 *
	 * Retrieve the wrapper selector for the current panel.
	 *
	 * @since 1.6.0
	 * @access public
	 * @abstract
	 */
	abstract public function get_css_wrapper_selector();

	/**
	 * Get panel page settings.
	 *
	 * Retrieve the page setting for the current panel.
	 *
	 * @since 1.6.0
	 * @access public
	 * @abstract
	 */
	abstract public function get_panel_page_settings();
}
