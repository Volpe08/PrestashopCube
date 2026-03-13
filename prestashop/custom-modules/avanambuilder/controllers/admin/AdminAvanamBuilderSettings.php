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

use AvanamBuilder\Wp_Helper;
use AvanamBuilder\Plugin;

class AdminAvanamBuilderSettingsController extends ModuleAdminController
{
    public $name;

    public function __construct()
    {		
        $this->bootstrap = true;
		
        parent::__construct();
		
        if (!$this->module->active) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminDashboard'));
        }

		Wp_Helper::$id_shop = (int)Tools::getValue( 'id_shop', $this->context->shop->id );
		
		$disable_color_schemes = Tools::getValue( 'elementor_disable_color_schemes', Wp_Helper::get_option( 'elementor_disable_color_schemes' ) );
		$disable_typography_schemes = Tools::getValue( 'elementor_disable_typography_schemes', Wp_Helper::get_option( 'elementor_disable_typography_schemes' ) );
		$editor_break_lines = Tools::getValue( 'elementor_editor_break_lines', Wp_Helper::get_option( 'elementor_editor_break_lines' ) );
		$css_print_method = Tools::getValue( 'elementor_css_print_method', Wp_Helper::get_option( 'elementor_css_print_method' ) );
		$max_saved_revision = (int)Tools::getValue( 'elementor_max_saved_revision', Wp_Helper::get_option( 'elementor_max_saved_revision' ) );
		
        $this->fields_options = array(
            'general' => array(
                'title' => $this->trans('Settings', [], 'Admin.Global'),
                'fields' => array(
                    'elementor_disable_color_schemes' => array(
                        'title' => $this->trans('Disable Default Colors', [], 'Modules.Avanambuilder.Admin'),
                        'desc' => $this->trans('Checking this box will disable AvanamBuilder\'s Default Colors, and make AvanamBuilder inherit the colors from your theme.', [], 'Modules.Avanambuilder.Admin'),
                        'validation' => 'isBool',
                        'cast' => 'intval',
                        'type' => 'bool',
						'defaultValue' => $disable_color_schemes ? true : false
                    ),
					'elementor_disable_typography_schemes' => array(
                        'title' => $this->trans('Disable Default Fonts', [], 'Modules.Avanambuilder.Admin'),
                        'desc' => $this->trans('Checking this box will disable AvanamBuilder\'s Default Fonts, and make AvanamBuilder inherit the fonts from your theme.', [], 'Modules.Avanambuilder.Admin'),
                        'validation' => 'isBool',
                        'cast' => 'intval',
                        'type' => 'bool',
						'defaultValue' => $disable_typography_schemes ? true : false
                    ),
					'elementor_editor_break_lines' => array(
                        'title' => $this->trans('Switch Editor Loader Method', [], 'Modules.Avanambuilder.Admin'),
                        'desc' => $this->trans('For troubleshooting server configuration conflicts.', [], 'Modules.Avanambuilder.Admin'),
                        'validation' => 'isBool',
                        'cast' => 'intval',
                        'type' => 'bool',
						'defaultValue' => $editor_break_lines ? true : false
                    ),
                    'elementor_css_print_method' => array(
                        'title' => $this->trans('CSS Print Method', [], 'Modules.Avanambuilder.Admin'),
                        'desc' => $this->trans('Use external CSS files for all generated stylesheets. Choose this setting for better performance (recommended).', [], 'Modules.Avanambuilder.Admin') . '<br/>' . 
								  $this->trans('Use internal CSS that is embedded in the head of the page. For troubleshooting server configuration conflicts and managing development environments.', [], 'Modules.Avanambuilder.Admin') ,
                        'type' => 'select',
						'list' => [
							[ 'id' => 'external', 'name' => $this->trans('External File', [], 'Modules.Avanambuilder.Admin') ],
							[ 'id' => 'internal', 'name' => $this->trans('Internal Embedding', [], 'Modules.Avanambuilder.Admin') ]
						],
                        'identifier' => 'id',
						'defaultValue' => $css_print_method == 'external' ? 'external' : 'internal'
                    ),
					'elementor_max_saved_revision' => array(
                        'title' => $this->trans('Max Saved Revision History', [], 'Modules.Avanambuilder.Admin'),
                        'desc' => $this->trans('Automatically delete revision history when the number of records is exceeded.', [], 'Modules.Avanambuilder.Admin'),
                        'validation' => 'isUnsignedInt',
                        'type' => 'text',
                        'cast' => 'intval',
						'class' => 'fixed-width-xxl',
						'defaultValue' => $max_saved_revision
                    ),
                ),
                'submit' => array('name' => 'submitAvanamSettingsGeneral', 'title' => $this->trans('Save', [], 'Admin.Actions'))
            )
        );
		
        $this->name = 'AdminAvanamBuilderSettings';
		
		$license_key = Wp_Helper::api_get_license_key();
		
		if ( empty( $license_key ) ) {
			$this->errors[] = Wp_Helper::__( 'Activate your AvanamBuilder license to unlock feature updates and premium support.', 'elementor' ) . ' <a href="' . Wp_Helper::get_exit_to_dashboard( 'AdminAvanamBuilderLicense' ) . '">' . Wp_Helper::__( '[Click here].', 'elementor' ) . '</a>';
		}else{
			$license_data = Wp_Helper::api_get_license_data();
			
			if( !isset( $license_data['license'] ) ){
				$license_data = Wp_Helper::api_get_license_data( true );
			}
			
			if ( Wp_Helper::STATUS_EXPIRED === $license_data['license'] ) {
				$this->errors[] = Wp_Helper::__( 'Your License Has Expired. Renew your license today to keep getting feature updates, premium support and unlimited access to the template library.', 'elementor' ) . ' <a href="' . Wp_Helper::get_exit_to_dashboard( 'AdminAvanamBuilderLicense' ) . '">' . Wp_Helper::__( '[Click here].', 'elementor' ) . '</a>';
			}
			
			if ( Wp_Helper::STATUS_SITE_INACTIVE === $license_data['license'] ) {
				$this->errors[] = Wp_Helper::__( 'Your license key doesn\'t match your current domain. This is most likely due to a change in the domain URL of your site (including HTTPS/SSL migration). Please deactivate the license and then reactivate it again.', 'elementor' ) . ' <a href="' . Wp_Helper::get_exit_to_dashboard( 'AdminAvanamBuilderLicense' ) . '">' . Wp_Helper::__( '[Click here].', 'elementor' ) . '</a>';
			}
			
			if ( Wp_Helper::STATUS_INVALID === $license_data['license'] ) {
				$this->errors[] = Wp_Helper::__( 'Your license key doesn\'t match your current domain. This is most likely due to a change in the domain URL of your site (including HTTPS/SSL migration). Please deactivate the license and then reactivate it again.', 'elementor' ) . ' <a href="' . Wp_Helper::get_exit_to_dashboard( 'AdminAvanamBuilderLicense' ) . '">' . Wp_Helper::__( '[Click here].', 'elementor' ) . '</a>';
			}
		}
    }
	
    public function initToolBarTitle()
    {
        $this->toolbar_title[] = $this->trans('Avanam - General', [], 'Modules.Avanambuilder.Admin');
    }
		
    public function postProcess()
    {
		if (Tools::isSubmit('submitAvanamSettingsGeneral')) {
			if( Tools::getValue( 'elementor_disable_color_schemes' ) ){
				Wp_Helper::update_option( 'elementor_disable_color_schemes', 'yes' );
			}else{
				Wp_Helper::delete_option( 'elementor_disable_color_schemes' );
			}
			
			if( Tools::getValue( 'elementor_disable_typography_schemes' ) ){
				Wp_Helper::update_option( 'elementor_disable_typography_schemes', 'yes' );
			}else{
				Wp_Helper::delete_option( 'elementor_disable_typography_schemes' );
			}
			
			if( Tools::getValue( 'elementor_editor_break_lines' ) ){
				Wp_Helper::update_option( 'elementor_editor_break_lines', 'yes' );
			}else{
				Wp_Helper::delete_option( 'elementor_editor_break_lines' );
			}
						
			if( Tools::getValue( 'elementor_css_print_method' ) == 'external' ){
				Wp_Helper::update_option( 'elementor_css_print_method', 'external' );
			}else{
				Wp_Helper::delete_option( 'elementor_css_print_method' );
			}

			if( Tools::getIsset( 'elementor_max_saved_revision' ) && (int)Tools::getValue( 'elementor_max_saved_revision' ) >= 0 && Validate::isUnsignedInt(Tools::getValue( 'elementor_max_saved_revision' )) ){
				Wp_Helper::update_option( 'elementor_max_saved_revision', (int)Tools::getValue( 'elementor_max_saved_revision' ) );

				$sql = 'SELECT `id_avanam_builder_post` FROM `'._DB_PREFIX_.'avanam_builder_post`';

				$posts = Db::getInstance()->executeS( $sql );

				$languages = Language::getLanguages();

				foreach ( $posts as $post ) {
					foreach ( $languages as $lang ) {
						$this->delete_revisions_old($post['id_avanam_builder_post'], $lang['id_lang'], (int)Tools::getValue( 'elementor_max_saved_revision' ));
					}
				}
			} else {
				$this->errors[] = Wp_Helper::__( 'Max Saved Revision History: Invalid number.', 'elementor' );
			}
						
			Plugin::instance()->files_manager->clear_cache();
		}
    }		
	
    public function delete_revisions_old($id_post, $id_lang, $limit) {	
		$res = true;
		
        $sql = 'SELECT `id_avanam_builder_revisions` FROM `'._DB_PREFIX_.'avanam_builder_revisions` 
				WHERE `id_post` = ' . $id_post . '
				AND `id_lang` = ' . $id_lang . '
				ORDER BY `date_add` ASC';
		
		$revisions = Db::getInstance()->executeS( $sql );

		$count = count($revisions);

		if( $count <= $limit ){
			return true;
		}
		
		foreach ( $revisions as $key => $revision ) {
			$revi = new AvanamBuilderRevisions( $revision['id_avanam_builder_revisions'] );
			$res &= $revi->delete();
			$count--;
			if( $count <= $limit ){
				break;
			}
		}
		
		return $res;
	}
}
