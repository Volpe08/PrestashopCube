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

class Theme_Missing extends Inspection_Base {

	public function run() {
		$theme = wp_get_theme();
		return $theme->exists();
	}

	public function get_name() {
		return 'theme-missing';
	}

	public function get_message() {
		return Wp_Helper::__( 'Some of your theme files are missing.', 'elementor' );
	}

	public function get_help_doc_url() {
		return 'https://avanam.org/presta/preview-not-loaded/#theme-files';
	}
}
