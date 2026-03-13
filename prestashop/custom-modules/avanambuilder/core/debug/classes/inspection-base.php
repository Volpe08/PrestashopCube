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

namespace AvanamBuilder\Core\Debug\Classes;

use AvanamBuilder\Wp_Helper; 

abstract class Inspection_Base {

	/**
	 * @return bool
	 */
	abstract public function run();

	/**
	 * @return string
	 */
	abstract public function get_name();

	/**
	 * @return string
	 */
	abstract public function get_message();

	/**
	 * @return string
	 */
	public function get_header_message() {
		return Wp_Helper::__( 'The preview could not be loaded', 'elementor' );
	}

	/**
	 * @return string
	 */
	abstract public function get_help_doc_url();
}
