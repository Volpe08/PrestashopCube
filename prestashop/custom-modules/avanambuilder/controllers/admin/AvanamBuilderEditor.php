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

if (!defined('_PS_VERSION_')) {
	exit;
}

use AvanamBuilder\Wp_Helper;
use AvanamBuilder\Plugin;

class AvanamBuilderEditorController extends ModuleAdminController
{
    public $name = 'AvanamBuilderEditor';

    public $display_header = false;

    public $content_only = true;

    public function initContent()
    {
        if ( ( !Tools::getValue('id_post') && !Tools::getValue('key_related') ) || !Tools::getValue('post_type') ) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminAvanamBuilderHome'));
        }
		if( Wp_Helper::set_global_var() ){
			Plugin::instance()->editor->init();
		}
		die();
    }
	
    public function initProcess() {}

    public function initBreadcrumbs( $tab_id = null, $tabs = null ) {}

    public function initModal() {}

    public function initToolbarFlags() {}

    public function initNotifications() {}
	
}
