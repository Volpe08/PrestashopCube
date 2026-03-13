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

require_once _PS_MODULE_DIR_   . 'avanambuilder/src/Wp_Helper.php';
require_once AVANAM_BUILDER_PATH . 'includes/plugin.php';
require_once AVANAM_BUILDER_PATH . 'src/AvanamBuilderPost.php';
require_once AVANAM_BUILDER_PATH . 'src/AvanamBuilderRelated.php';
require_once AVANAM_BUILDER_PATH . 'src/AvanamBuilderTemplate.php';
require_once AVANAM_BUILDER_PATH . 'src/AvanamBuilderRevisions.php';

use AvanamBuilder\Wp_Helper;
use AvanamBuilder\Plugin;

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use Symfony\Component\HttpFoundation\Request;

use PrestaShop\PrestaShop\Adapter\NewProducts\NewProductsProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\PricesDrop\PricesDropProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\BestSales\BestSalesProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Manufacturer\ManufacturerProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

use PrestaShop\PrestaShop\Adapter\ObjectPresenter as LegacyObjectPresenter; //Prestashop 8
use PrestaShop\PrestaShop\Adapter\Presenter\Object\ObjectPresenter as NewObjectPresenter; //Prestashop 9
use Symfony\Component\Yaml\Yaml;

class AvanamBuilder extends Module implements WidgetInterface
{			
	private static $ava_hooks =   ['header' => null, 
                                // 'header_sticky' => null, 
                                'home' => null, 
                                'footer' => null, 
                                'hooks' => ['displayLeftColumn' => null, 
                                            'displayRightColumn' => null, 
                                            'displayProductAccessories' => null, 
                                            'displayProductSameCategory' => null, 
                                            'displayFooterProduct' => null, 
                                            'displayLeftColumnProduct' => null, 
                                            'displayRightColumnProduct' => null, 
                                            'displayContactPageBuilder' => null, 
                                            'displayShoppingCartFooter' => null, 
                                            'displayProductSummary' => null,
                                            'displayProductSidebar' => null,
											'displayHeaderCategory' => null,
                                            'displayFooterCategory' => null,
                                            'display404PageBuilder' => null], 
                                'id_editor' => null ];
	protected $avanam_tplfile;
	protected $css_js_avanam_tplfile;
    private static $ava_overrided = [];
    public static $hook_current;
	private $default_palette = [];
    
    public function __construct()
    {
        $this->name = 'avanambuilder';
		$this->version = AVANAM_BUILDER_VERSION;
		$this->tab = 'front_office_features';
        $this->author = 'AvanamOrg';
		$this->bootstrap = true;
		$this->controllers = array('preview', 'ajax_editor', 'ajax', 'subscription', 'contact');
		$this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->trans('AvanamBuilder - Prestashop Elementor Page Builder', [], 'Modules.Avanambuilder.Admin');
        $this->description = $this->trans('Drag and Drop prestashop page builder based on Elementor.', [], 'Modules.Avanambuilder.Admin');

        $this->ps_versions_compliancy = array('min' => '8.0.0', 'max' => _PS_VERSION_);
        $this->avanam_tplfile = 'module:' . $this->name . '/views/templates/hook/page_content.tpl';
		$this->css_js_avanam_tplfile = 'module:' . $this->name . '/views/templates/hook/css_js_unique.tpl';

		if (!isset($this->context->smarty->registered_plugins['modifier']['file_exists'])) {
			$this->context->smarty->registerPlugin('modifier', 'file_exists', 'file_exists');
		}

		// Get current theme used for license
		$activeTheme = $this->context->shop->theme->getName();
		$activeThemeName = $this->context->shop->theme->get('display_name');
		$parentTheme = $this->context->shop->theme->get('parent');

		// Fetch color palette from
		if ($activeTheme == 'avanamclassic' || $parentTheme == 'avanamclassic') {
			
			$themeYmlPath = _PS_ROOT_DIR_ . '/themes/' . $activeTheme . '/config/theme.yml';
			if (file_exists($themeYmlPath)) {
				$themeData = Yaml::parseFile($themeYmlPath);
				$global_settings = $themeData['global_settings'] ?? [];	
			}

			$customVariables = $global_settings['custom_variables'] ?? [];
			if (!empty($customVariables)) {
				$this->default_palette = [
					'1'  => $customVariables['palette1'],
					'2'  => $customVariables['palette2'],
					'3'  => $customVariables['palette3'],
					'4'  => $customVariables['palette4'],
					'5'  => $customVariables['palette5'],
					'6'  => $customVariables['palette6'],
					'7'  => $customVariables['palette7'],
					'8'  => $customVariables['palette8'],
					'9'  => $customVariables['palette9'],
					'10'  => $customVariables['palette10'],
					'11'  => $customVariables['palette11'],
				];
			}
		}

    }

    public function install()
    {
        return parent::install()
            && $this->create_backoffice_tabs()
			&& $this->create_db_tables()
			&& $this->registerHook('actionObjectBlogDeleteAfter')
			&& $this->registerHook('actionObjectCategoryDeleteAfter')
			&& $this->registerHook('actionObjectCmsDeleteAfter')
			&& $this->registerHook('actionObjectManufacturerDeleteAfter')
			&& $this->registerHook('actionObjectProductDeleteAfter')
			&& $this->registerHook('actionObjectSupplierDeleteAfter')
			&& $this->registerHook('display404PageBuilder')
			&& $this->registerHook('displayBackOfficeHeader')
			&& $this->registerHook('displayContactPageBuilder')
			&& $this->registerHook('displayFooterPageBuilder')
			&& $this->registerHook('displayFooterProduct')
			&& $this->registerHook('displayHome')
			&& $this->registerHook('displayHomePageBuilder')
			&& $this->registerHook('displayIncludePageBuilder')
			&& $this->registerHook('displayProductSummary')
			&& $this->registerHook('displayProductSidebar')
			&& $this->registerHook('displayHeaderCategory')
			&& $this->registerHook('displayFooterCategory')
			&& $this->registerHook('displayLeftColumn')
			&& $this->registerHook('displayLeftColumnProduct')
			&& $this->registerHook('displayNavFullWidth')
			&& $this->registerHook('displayProductAccessories')
			&& $this->registerHook('displayProductSameCategory')
			&& $this->registerHook('displayRightColumn')
			&& $this->registerHook('displayRightColumnProduct')
			&& $this->registerHook('displayShoppingCartFooter')
			&& $this->registerHook('displayHeaderPageBuilder')			
			&& $this->registerHook('displayHeader')
            && $this->registerHook('overrideLayoutTemplate');
    }

    public function uninstall()
    {
        return parent::uninstall()
			&& $this->_deleteConfigs()
            && $this->delete_tab_Bo();
			//&& $this->delete_tab_Db();

    }

	public function enable($force_all = false)
	{
		if (!parent::enable($force_all)) {
			return false;
		}

		// Enable the tab in the database
		Db::getInstance()->execute("UPDATE "._DB_PREFIX_."tab SET active = 1 WHERE class_name = 'AdminAvanamBuilderFirst'");

		return true;
	}
	public function disable($force_all = false)
	{
		if (!parent::disable($force_all)) {
			return false;
		}
	
		// Disable the tab in the database
		Db::getInstance()->execute("UPDATE "._DB_PREFIX_."tab SET active = 0 WHERE class_name = 'AdminAvanamBuilderFirst'");
	
		return true;
	}

    public function create_backoffice_tabs()
    {
        $response = true;
		$langs = Language::getLanguages(false);

        $id_improve = Tab::getIdFromClassName('IMPROVE');
		
        // First check for parent tab
        $parentTabID = Tab::getIdFromClassName('AdminAvanamBuilderFirst');
		
        if ($parentTabID) {
            $parentTab = new Tab($parentTabID);
        } else {
            $parentTab = new Tab();
            $parentTab->active = 1;
            $parentTab->name = array();
            $parentTab->class_name = "AdminAvanamBuilderFirst";
            foreach($langs as $lang) {
            	$parentTab->name[$lang['id_lang']] = "Avanam Builder";
            }
            $parentTab->id_parent = $id_improve;
            $parentTab->module ='';
			$parentTab->icon = 'avanam-logo';
            $response &= $parentTab->add();
        }
		
		if( !Tab::getIdFromClassName('AvanamBuilderEditor') ) {
			// Created tab
			$tab = new Tab();
			$tab->active = 1;
			$tab->class_name = "AvanamBuilderEditor";
			$tab->name = array();
			foreach($langs as $lang) {
				$tab->name[$lang['id_lang']] = "AvanamBuilderEditor";
			}
			$tab->id_parent = -1;
			$tab->module = $this->name;
			$response &= $tab->add();
		}
		
		if( !Tab::getIdFromClassName('AdminAvanamBuilderParent') ) {
			// Created tab
			$tab_3 = new Tab();
			$tab_3->active = 1;
			$tab_3->class_name = "AdminAvanamBuilderParent";
			$tab_3->name = array();
			foreach (Language::getLanguages(true) as $lang) {
				$tab_3->name[$lang['id_lang']] = "Theme Builder";
			}
			$tab_3->id_parent = $parentTab->id;
			$tab_3->module = '';
			$response &= $tab_3->add();
		}
		
		if( !Tab::getIdFromClassName('AdminAvanamBuilderHeader') ) {
			// Created tab
			$tab = new Tab();
			$tab->active = 1;
			$tab->class_name = "AdminAvanamBuilderHeader";
			$tab->name = array();
			foreach (Language::getLanguages() as $lang) {
				$tab->name[$lang['id_lang']] = "Header";
			}
			$tab->id_parent = $tab_3->id;
			$tab->module = $this->name;
			$response &= $tab->add();
		}
		
		if( !Tab::getIdFromClassName('AdminAvanamBuilderFooter') ) {
			// Created tab
			$tab = new Tab();
			$tab->active = 1;
			$tab->class_name = "AdminAvanamBuilderFooter";
			$tab->name = array();
			foreach (Language::getLanguages() as $lang) {
				$tab->name[$lang['id_lang']] = "Footer";
			}
			$tab->id_parent = $tab_3->id;
			$tab->module = $this->name;
			$response &= $tab->add();
		}
		
		if( !Tab::getIdFromClassName('AdminAvanamBuilderHome') ) {
			// Created tab
			$tab = new Tab();
			$tab->active = 1;
			$tab->class_name = "AdminAvanamBuilderHome";
			$tab->name = array();
			foreach (Language::getLanguages() as $lang) {
				$tab->name[$lang['id_lang']] = "Home";
			}
			$tab->id_parent = $tab_3->id;
			$tab->module = $this->name;
			$response &= $tab->add();
		}
		
		if( !Tab::getIdFromClassName('AdminAvanamBuilderHook') ) {
			// Created tab
			$tab = new Tab();
			$tab->active = 1;
			$tab->class_name = "AdminAvanamBuilderHook";
			$tab->name = array();
			foreach (Language::getLanguages() as $lang) {
				$tab->name[$lang['id_lang']] = "Hook";
			}
			$tab->id_parent = $tab_3->id;
			$tab->module = $this->name;
			$response &= $tab->add();
		}
		
		if( !Tab::getIdFromClassName('AdminAvanamBuilderParent2') ) {
			// Created tab
			$tab_4 = new Tab();
			$tab_4->active = 1;
			$tab_4->class_name = "AdminAvanamBuilderParent2";
			$tab_4->name = array();
			foreach (Language::getLanguages(true) as $lang) {
				$tab_4->name[$lang['id_lang']] = "Settings & License";
			}
			$tab_4->id_parent = $parentTab->id;
			$tab_4->module = '';
			$response &= $tab_4->add();
		}
		
		if( !Tab::getIdFromClassName('AdminAvanamBuilderLicense') ) {
			// Created tab
			$tab = new Tab();
			$tab->active = 1;
			$tab->class_name = "AdminAvanamBuilderLicense";
			$tab->name = array();
			foreach (Language::getLanguages() as $lang) {
				$tab->name[$lang['id_lang']] = "License";
			}
			$tab->id_parent = $tab_4->id;
			$tab->module = $this->name;
			$response &= $tab->add();
		}

		if( !Tab::getIdFromClassName('AdminAvanamBuilderSettings') ) {
			// Created tab
			$tab = new Tab();
			$tab->active = 1;
			$tab->class_name = "AdminAvanamBuilderSettings";
			$tab->name = array();
			foreach (Language::getLanguages() as $lang) {
				$tab->name[$lang['id_lang']] = "General";
			}
			$tab->id_parent = $tab_4->id;
			$tab->module = $this->name;
			$response &= $tab->add();
		}
				
        return $response;
    }

    public function delete_tab_Bo()
    {			
        $id_tab = (int)Tab::getIdFromClassName('AdminAvanamBuilderHeader');
        $tab = new Tab($id_tab);
        $tab->delete();
		
        $id_tab = (int)Tab::getIdFromClassName('AdminAvanamBuilderFooter');
        $tab = new Tab($id_tab);
        $tab->delete();
	
        $id_tab = (int)Tab::getIdFromClassName('AdminAvanamBuilderHome');
        $tab = new Tab($id_tab);
        $tab->delete();
		
        $id_tab = (int)Tab::getIdFromClassName('AdminAvanamBuilderHook');
        $tab = new Tab($id_tab);
        $tab->delete();
				
        $id_tab = (int)Tab::getIdFromClassName('AdminAvanamBuilderParent');
        $tab = new Tab($id_tab);
        $tab->delete();
		
        $id_tab = (int)Tab::getIdFromClassName('AvanamBuilderEditor');
        $tab = new Tab($id_tab);
        $tab->delete();
		
        $id_tab = (int)Tab::getIdFromClassName('AdminAvanamBuilderLicense');
        $tab = new Tab($id_tab);
        $tab->delete();
		
        $id_tab = (int)Tab::getIdFromClassName('AdminAvanamBuilderSettings');
        $tab = new Tab($id_tab);
        $tab->delete();
		
        $id_tab = (int)Tab::getIdFromClassName('AdminAvanamBuilderParent2');
        $tab = new Tab($id_tab);
        $tab->delete();		

        // Get the number of tabs inside our parent tab
        // If there is no tabs, remove the parent
		$parentTabID = Tab::getIdFromClassName('AdminAvanamBuilderFirst');
        $tabCount = Tab::getNbTabs($parentTabID);
        if ($tabCount == 0) {
            $parentTab = new Tab($parentTabID);
            $parentTab->delete();
        }

        return true;
    }

	
    public function create_db_tables()
    {
        $return = true;
        //$this->delete_tab_Db();
		
		//////////////////Post////////////////////
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'avanam_builder_post` (
                `id_avanam_builder_post` int(10) NOT NULL auto_increment,
                `id_employee` int(10) unsigned NOT NULL,
                `title` varchar(40) NOT NULL,
				`post_type` varchar(40) NOT NULL,
                `active` tinyint(1) unsigned NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_avanam_builder_post`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'avanam_builder_post_lang` (
                `id_avanam_builder_post` int(10) NOT NULL,
                `id_lang` int(10) NOT NULL ,
                `content` longtext default NULL,
                `content_autosave` longtext default NULL,
                PRIMARY KEY (`id_avanam_builder_post`, `id_lang`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		//////////////////Hook////////////////////
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'avanam_builder_related` (
                `id_avanam_builder_related` int(10) NOT NULL auto_increment,
				`id_post` int(10) unsigned NOT NULL,
                `post_type` varchar(255) NOT NULL,
                `key_related` varchar(255) NOT NULL,
                PRIMARY KEY (`id_avanam_builder_related`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');		
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'avanam_builder_related_shop` (
                `id_avanam_builder_related` int(10) NOT NULL,
                `id_shop` int(10) NOT NULL ,
                PRIMARY KEY (`id_avanam_builder_related`, `id_shop`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		//////////////////Template////////////////////
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'avanam_builder_template` (
                `id_avanam_builder_template` int(10) NOT NULL auto_increment,
                `id_employee` int(10) unsigned NOT NULL,
                `title` varchar(40) NOT NULL,
				`type` varchar(40) NOT NULL,
                `content` longtext default NULL,
                `page_settings` longtext default NULL,
                `date_add` datetime NOT NULL,
                PRIMARY KEY (`id_avanam_builder_template`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		//////////////////Meta////////////////////
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'avanam_builder_meta` (
                `id_avanam_builder_meta` int(10) NOT NULL auto_increment,
                `id` int(10) unsigned NOT NULL,
                `name` varchar(255) DEFAULT NULL,
                `value` longtext,
                PRIMARY KEY (`id_avanam_builder_meta`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		//////////////////Revisions////////////////////
        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'avanam_builder_revisions` (
                `id_avanam_builder_revisions` int(10) NOT NULL auto_increment,
                `id_post` int(10) unsigned NOT NULL,
                `id_lang` int(10) unsigned NOT NULL,
                `id_employee` int(10) unsigned NOT NULL,
                `content` longtext default NULL,
                `page_settings` longtext default NULL,
                `date_add` datetime NOT NULL,
                PRIMARY KEY (`id_avanam_builder_revisions`, `id_post`, `id_lang`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		// Default setting
		Wp_Helper::$id_shop = (int)Tools::getValue( 'id_shop', $this->context->shop->id );
		Wp_Helper::update_option( 'elementor_disable_color_schemes', 'yes' );
		Wp_Helper::update_option( 'elementor_disable_typography_schemes', 'yes' );

		if ( !empty( $this->default_palette ) ) {
			$scheme_value = Wp_Helper::get_option( 'elementor_scheme_color' );
			if ( ! $scheme_value ) {
				Wp_Helper::update_option( 'elementor_scheme_color', $this->default_palette );
			}
			$scheme_picker_value = Wp_Helper::get_option( 'elementor_scheme_colorpicker' );
			if ( ! $scheme_picker_value ) {
				Wp_Helper::update_option( 'elementor_scheme_colorpicker', $this->default_palette );
			}
		}

        return $return;
    }

    public function delete_tab_Db()
    {
		return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'avanam_builder_post`') && 
			   Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'avanam_builder_post_lang`') && 
			   Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'avanam_builder_related`') && 
			   Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'avanam_builder_related_shop`') && 
			   Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'avanam_builder_template`') &&
			   Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'avanam_builder_meta`') &&
			   Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'avanam_builder_revisions`');
    }

	/**
     * Delete Configs
     */
    private function _deleteConfigs()
	{

		$response = true;

		$response &= Configuration::deleteByName('AVABUILDER_LAST_UPDATE_CHECK');
		$response &= Configuration::deleteByName('AVABUILDER_LATEST_VERSION');
		$response &= Configuration::deleteByName('_mod_avatheme_partner_api_requests_lock');
		$response &= Configuration::deleteByName('mod_avatheme_partner_license_email');
		$response &= Configuration::deleteByName('_mod_avatheme_partner_license_data');
		$response &= Configuration::deleteByName('_mod_avatheme_partner_license_data_fallback');

		return $response;	

	}
	
    public function hookDisplayBackOfficeHeader($params)
    {

		if ( !empty( $this->default_palette ) ) {
			$scheme_value = Wp_Helper::get_option( 'elementor_scheme_color' );
			if ( ! $scheme_value ) {
				Wp_Helper::update_option( 'elementor_scheme_color', $this->default_palette );
			}
			$scheme_picker_value = Wp_Helper::get_option( 'elementor_scheme_colorpicker' );
			if ( ! $scheme_picker_value ) {
				Wp_Helper::update_option( 'elementor_scheme_colorpicker', $this->default_palette );
			}
		}

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		$this->context->controller->addCSS( AVANAM_BUILDER_ASSETS_URL . 'css/ava-admin' . $suffix . '.css' );
				
		$id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
		
		$controller_name = $this->context->controller->controller_name;
		
		$controllers = [ 'AdminCategories', 'AdminProducts', 'AdminCmsContent', 'AdminManufacturers', 'AdminSuppliers', 'AdminBlogPost' ];
		
        if ( in_array( $controller_name, $controllers ) ) {
			global $kernel;

			$request = $kernel->getContainer()->get('request_stack')->getCurrentRequest();

			switch ( $controller_name ) {
				case 'AdminCategories':
					$id_page = (int) Tools::getValue('id_category');
					if( !$id_page ){
						if ( !isset( $request->attributes ) ) { return; }
						$id_page = (int) $request->attributes->get('categoryId');
					}
					$post_type = 'category';
				break;
				case 'AdminProducts':
					$id_page = (int) Tools::getValue('id_product');
					if( !$id_page ){
						if ( !isset( $request->attributes ) ) { return; }
						$id_page = (int) $request->attributes->get('id');
					}
					$post_type = 'product';
				break;
				case 'AdminCmsContent':
					$id_page = (int) Tools::getValue('id_cms');
					if( !$id_page ){
						if ( !isset( $request->attributes ) ) { return; }
						$id_page = (int) $request->attributes->get('cmsPageId');
					}
					$post_type = 'cms';
				break;
				case 'AdminManufacturers':
					$id_page = (int) Tools::getValue('id_manufacturer');
					if( !$id_page ){
						if ( !isset( $request->attributes ) ) { return; }
						$id_page = (int) $request->attributes->get('manufacturerId');
					}
					$post_type = 'manufacturer';
				break;
				case 'AdminSuppliers':
					$id_page = (int) Tools::getValue('id_supplier');
					if( !$id_page ){
						if ( !isset( $request->attributes ) ) { return; }
						$id_page = (int) $request->attributes->get('supplierId');
					}
					$post_type = 'supplier';
				break;
				case 'AdminBlogPost':
					$id_page = (int) Tools::getValue('id_smart_blog_post');
					$post_type = 'blog';
				break;	
			}

			if (!$id_page) {
				$this->context->smarty->assign(array(
					'urlPageBuilder' => ''
				));
			} else{
				$url = $this->context->link->getAdminLink('AvanamBuilderEditor').'&post_type=' . $post_type . '&key_related=' . $id_page . '&id_lang=' . $id_lang;

				$this->context->smarty->assign(array(
					'urlPageBuilder' => $url
				));
			}

			return $this->fetch(_PS_MODULE_DIR_ .'/'. $this->name . '/views/templates/admin/backoffice_header.tpl');
		}
	}

    public function getContent()
    {
        Tools::redirectAdmin( $this->context->link->getAdminLink('AdminAvanamBuilderHeader') );
    }
	
    public function hookDisplayHeader()
    {	
        if( Wp_Helper::is_preview_mode() ) {
            header_register_callback(function () {
                header_remove('X-Frame-Options');
                header_remove('X-Content-Type-Options');
                header_remove('X-Xss-Protection');
                header_remove('Content-Security-Policy');
            });  
        }
        
		Wp_Helper::reset_post_var();

		$cssAndJs = $this->getCssAndJs();
		
		$cssFiles = $cssAndJs['ava_styles'];
		
		$jsFiles = $cssAndJs['ava_javascripts'];
		
		foreach( $cssFiles as $css ){
		   $this->context->controller->registerStylesheet( $css['id'], $css['url'], [ 'media' => $css['media'], 'priority' => $css['priority'] ] );
		}

		foreach( $jsFiles as $js ){
			$this->context->controller->registerJavascript( $js['id'], $js['url'], [ 'position' => $js['position'], 'priority' => $js['priority'] ] );
		}
		
		$languages = [];
		$data_languages = $this->getListLanguages();
		
		$currencies = [];
		$data_currencies = $this->getListCurrencies();
		
		if( $data_languages ){
			foreach( $data_languages['languages'] as $language ){
				$languages[$language['id_lang']] = $this->context->link->getLanguageLink($language['id_lang']);
			}
			$languages['length'] = count( $data_languages['languages'] );
		}
		
		if( $data_currencies ){
			foreach( $data_currencies['currencies'] as $currency ){
				$currencies[$currency['id']] = $currency['url'];
			}
			$currencies['length'] = count( $data_currencies['currencies'] );
		}

		if (empty($this->context->cookie->contactFormToken) || empty($this->context->cookie->contactFormTokenTTL) || $this->context->cookie->contactFormTokenTTL < time()) {
			$this->context->cookie->contactFormToken = md5(uniqid());
			$this->context->cookie->contactFormTokenTTL = time() + 600;
		}
			
        Media::addJsDef(
			['opAvanamBuilder' => ['ajax' => $this->context->link->getModuleLink('avanambuilder', 'ajax', [], null, null, null, true),
							     'contact' => $this->context->link->getModuleLink('avanambuilder', 'contact', [], null, null, null, true),
								 'contact_token' => $this->context->cookie->contactFormToken,
							     'subscription' => $this->context->link->getModuleLink('avanambuilder', 'subscription', [], null, null, null, true),
							     'languages' => $languages,
							     'currencies' => $currencies,
								 'ava_id_product' => (int)Tools::getValue('id_product'),
								 'ava_id_category' => (int)Tools::getValue('id_category'),
								 'ava_is_editor' => (Wp_Helper::is_preview_mode() || Dispatcher::getInstance()->getController() == 'ajax_editor' || (int)Tools::getValue( 'wp_preview' ))?1:0]
		]);	
					
	 	self::$ava_hooks['header'] = Wp_Helper::apply_filters( 'avanambuilder_header_layout', (int) Configuration::get('active_header_layout') );
		// self::$ava_hooks['header_sticky'] = Wp_Helper::apply_filters( 'avanambuilder_header_sticky_layout', (int) Configuration::get('active_header_sticky_layout') );
		self::$ava_hooks['home'] = Wp_Helper::apply_filters( 'avanambuilder_home_layout', (int) Configuration::get('active_home_layout') );
		self::$ava_hooks['footer'] = Wp_Helper::apply_filters( 'avanambuilder_footer_layout', (int) Configuration::get('active_footer_layout') );
		
		$post_type = Tools::getValue( 'post_type' );
		
		if( (int)Tools::getValue( 'id_post' ) && Wp_Helper::is_preview_mode() && in_array( $post_type, array( 'header', 'home', 'footer', 'hook' ) ) ){
			$id_post = (int)Tools::getValue( 'id_post' );
			
			if( $post_type == 'header' ){
				self::$ava_hooks['header'] = $id_post;
				// self::$ava_hooks['header_sticky'] = null;
			}
			
			if( $post_type == 'home' ){
				self::$ava_hooks['home'] = $id_post;
			}
			
			if( $post_type == 'footer' ){
				self::$ava_hooks['footer'] = $id_post;
			}
			
			self::$ava_hooks['id_editor'] = $id_post;
		}elseif( (int)Tools::getValue( 'wp_preview' ) ){
			$post = new AvanamBuilderPost( (int)Tools::getValue( 'wp_preview' ), Wp_Helper::$id_lang );
			
			$id_post = (int) $post->id;
			
			$post_type = $post->post_type;
			
			if( $post_type == 'header' ){
				self::$ava_hooks['header'] = $id_post;
				// self::$ava_hooks['header_sticky'] = null;
			}
			
			if( $post_type == 'home' ){
				self::$ava_hooks['home'] = $id_post;
			}
			
			if( $post_type == 'footer' ){
				self::$ava_hooks['footer'] = $id_post;
			}
		}
		
		foreach( self::$ava_hooks['hooks'] as $key => $value ){
			Wp_Helper::$post_type = 'hook';
			Wp_Helper::$key_related = $key;
			
			$related = Wp_Helper::getRelatedByKey();
            if($related){
                self::$ava_hooks['hooks'][$key] = (int) $related['id_post'];
            }
		}
			
		$cacheIdCssJs = 'pageBuilder|GlobalJs';
				
		if( !Wp_Helper::is_preview_mode() ) {
			$cacheIdCssJs = 'pageBuilder|GlobalCssJs';
		} 

		if (!$this->isCached($this->css_js_avanam_tplfile, $this->getCacheId($cacheIdCssJs))){		
			$css_unique = '';
			
			if( !Wp_Helper::is_preview_mode() ) {
				$css_unique = Plugin::instance()->frontend->parse_global_css_code();
			}
			
			$js_unique = '
			<script type="text/javascript">
				var elementorFrontendConfig = ' . json_encode(Plugin::instance()->frontend->get_init_settings()) . ';
			</script>';

			$this->smarty->assign(['css_js_unique' => $css_unique . $js_unique]);
		}

		return $this->fetch($this->css_js_avanam_tplfile, $this->getCacheId($cacheIdCssJs));
    }
		
    public function getCssAndJs()
	{

		$activeTheme = $this->context->shop->theme->getName();

		$parentTheme = $this->context->shop->theme->get('parent');

		$dir_rtl = $this->context->language->is_rtl ? '-rtl' : '';
		
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		$ava_styles = [];
		
		$ava_styles[] = [
			'id' => 'css_ava_eicons', 
			'url' => 'modules/' . $this->name . '/assets/lib/eicons/css/elementor-icons.min.css', 
			'media' => 'all', 
			'priority' => -1
		];
		
		$ava_styles[] = [
			'id' => 'css_ava_font_awesome', 
			'url' => 'modules/' . $this->name . '/assets/lib/font-awesome/css/font-awesome.min.css', 
			'media' => 'all', 
			'priority' => -1
		];
		
		$ava_styles[] = [
			'id' => 'css_ava_fontawesome', 
			'url' => 'modules/' . $this->name . '/assets/lib/font-awesome/css/fontawesome.min.css', 
			'media' => 'all', 
			'priority' => -1
		];
		
		$ava_styles[] = [
			'id' => 'css_ava_regular', 
			'url' => 'modules/' . $this->name . '/assets/lib/font-awesome/css/regular.min.css', 
			'media' => 'all', 
			'priority' => -1
		];
		
		$ava_styles[] = [
			'id' => 'css_ava_solid', 
			'url' => 'modules/' . $this->name . '/assets/lib/font-awesome/css/solid.min.css', 
			'media' => 'all', 
			'priority' => -1
		];
		
		$ava_styles[] = [
			'id' => 'css_ava_brands', 
			'url' => 'modules/' . $this->name . '/assets/lib/font-awesome/css/brands.min.css', 
			'media' => 'all', 
			'priority' => -1
		];
		
		$ava_styles[] = [
			'id' => 'css_ava_line_awesome', 
			'url' => 'modules/' . $this->name . '/assets/lib/line-awesome/line-awesome.min.css', 
			'media' => 'all', 
			'priority' => -1
		];

		$ava_styles[] = [
			'id' => 'css_ava_pe_icon', 
			'url' => 'modules/' . $this->name . '/assets/lib/pe-icon/Pe-icon-7-stroke.min.css', 
			'media' => 'all', 
			'priority' => -1
		];
		
		$ava_styles[] = [
			'id' => 'css_ava_animations', 
			'url' => 'modules/' . $this->name . '/assets/lib/animations/animations.min.css', 
			'media' => 'all', 
			'priority' => 150
		];
		
		$ava_styles[] = [
			'id' => 'css_ava_flatpickr', 
			'url' => 'modules/' . $this->name . '/assets/lib/flatpickr/flatpickr.min.css', 
			'media' => 'all', 
			'priority' => 150
		];
		
		$ava_styles[] = [
			'id' => 'css_ava_frontend', 
			'url' => 'modules/' . $this->name . '/assets/css/frontend' . $dir_rtl . '.min.css', 
			'media' => 'all', 
			'priority' => 150
		];

		// Skip loading Swiper if 'avanamclassic' is the active theme
		if ($activeTheme != 'avanamclassic' && $parentTheme != 'avanamclassic') {
			$ava_styles[] = [
				'id' => 'css_ava_swiper', 
				'url' => 'modules/' . $this->name . '/assets/lib/swiper/swiper-bundle.min.css', 
				'media' => 'all', 
				'priority' => 150
			];
		}

		$ava_styles[] = [
			'id' => 'css_ava_widgets', 
			'url' => 'modules/' . $this->name . '/assets/widgets/css/ava-widgets' . $dir_rtl . $suffix . '.css', 
			'media' => 'all', 
			'priority' => 150
		];
		
		if( Wp_Helper::is_preview_mode() ) {
			$ava_styles[] = [
				'id' => 'css_ava_e_select2', 
				'url' => 'modules/' . $this->name . '/assets/lib/e-select2/css/e-select2.min.css', 
				'media' => 'all', 
				'priority' => 150
			];

			$ava_styles[] = [
				'id' => 'css_ava_editor_preview', 
				'url' => 'modules/' . $this->name . '/assets/css/editor-preview' . $dir_rtl . '.min.css', 
				'media' => 'all', 
				'priority' => 150
			];

			$ava_styles[] = [
				'id' => 'css_ava_preview', 
				'url' => 'modules/' . $this->name . '/assets/css/ava-preview' . $suffix . '.css', 
				'media' => 'all', 
				'priority' => 150
			];
		}
		
		$ava_javascripts = [];
		
		$ava_javascripts[] = [
			'id' => 'js_ava_frontend_modules', 
			'url' => 'modules/' . $this->name . '/assets/js/frontend-modules.min.js', 
			'position' => 'bottom', 
			'priority' => 51
		];
				
		$ava_javascripts[] = [
			'id' => 'js_ava_waypoints', 
			'url' => 'modules/' . $this->name . '/assets/lib/waypoints/waypoints.min.js', 
			'position' => 'bottom', 
			'priority' => 51
		];
		
		$ava_javascripts[] = [
			'id' => 'js_ava_flatpickr', 
			'url' => 'modules/' . $this->name . '/assets/lib/flatpickr/flatpickr.min.js', 
			'position' => 'bottom', 
			'priority' => 51
		];
		
		$ava_javascripts[] = [
			'id' => 'js_ava_imagesloaded', 
			'url' => 'modules/' . $this->name . '/assets/lib/imagesloaded/imagesloaded.min.js', 
			'position' => 'bottom', 
			'priority' => 51
		];
		
		$ava_javascripts[] = [
			'id' => 'js_ava_jquery_numerator', 
			'url' => 'modules/' . $this->name . '/assets/lib/jquery-numerator/jquery-numerator.min.js', 
			'position' => 'bottom', 
			'priority' => 51
		];

		// Skip loading Swiper if 'avanamclassic' is the active theme
		if ($activeTheme != 'avanamclassic' && $parentTheme != 'avanamclassic') {
			$ava_javascripts[] = [
				'id' => 'js_ava_swiper', 
				'url' => 'modules/' . $this->name . '/assets/lib/swiper/swiper-bundle.min.js', 
				'position' => 'bottom', 
				'priority' => 51
			];
		}

		$ava_javascripts[] = [
			'id' => 'js_ava_dialog', 
			'url' => 'modules/' . $this->name . '/assets/lib/dialog/dialog.min.js', 
			'position' => 'bottom', 
			'priority' => 51
		];
		
		$ava_javascripts[] = [
			'id' => 'js_ava_countdown', 
			'url' => 'modules/' . $this->name . '/assets/lib/countdown/countdown.min.js', 
			'position' => 'bottom', 
			'priority' => 51
		];
				
		$ava_javascripts[] = [
			'id' => 'js_ava_widgets', 
			'url' => 'modules/' . $this->name . '/assets/widgets/js/ava-widgets' . $suffix . '.js', 
			'position' => 'bottom', 
			'priority' => 51
		];
		
		$ava_javascripts[] = [
			'id' => 'js_ava_frontend', 
			'url' => 'modules/' . $this->name . '/assets/js/frontend.min.js', 
			'position' => 'bottom', 
			'priority' => 51
		];
		
		if( Wp_Helper::is_preview_mode() ) {
			$ava_javascripts[] = [
				'id' => 'js_ava_inline_editor', 
				'url' => 'modules/' . $this->name . '/assets/lib/inline-editor/js/inline-editor.min.js', 
				'position' => 'bottom', 
				'priority' => 51
			];
		}
		
		return [ 'ava_styles' => $ava_styles, 'ava_javascripts' => $ava_javascripts ];
    }
		
    public function hookOverrideLayoutTemplate()
    {
		Wp_Helper::render_widget();

        Wp_Helper::reset_post_var();

        if ( !isset($this->context->smarty->tpl_vars['configuration']) || isset( self::$ava_overrided[ Wp_Helper::$id_post ] ) ) {
            return;
        }
        
        switch ( Wp_Helper::$post_type ) {
            case 'category':
				if(isset($this->context->smarty->tpl_vars['category'])){
					self::$ava_overrided[ Wp_Helper::$id_post ] = true;
					$cacheId = 'pageBuilder|' . Wp_Helper::$id_post;	

					$content = $this->context->smarty->tpl_vars['category'];
					$content_replace = &$this->context->smarty->tpl_vars['category'];
	
					$content->value['description'] .= $this->_avaRenderContent($cacheId);
					$content_replace = $content;
				}
                break;
            case 'product':
				if(isset($this->context->smarty->tpl_vars['product'])){
					self::$ava_overrided[ Wp_Helper::$id_post ] = true;
					$cacheId = 'pageBuilder|' . Wp_Helper::$id_post;

					$content = $this->context->smarty->tpl_vars['product'];
					$content_replace = &$this->context->smarty->tpl_vars['product'];
	
					$content->value['description'] .= $this->_avaRenderContent($cacheId);
					$content_replace = $content;
				}
                break;
            case 'manufacturer':
				if(isset($this->context->smarty->tpl_vars['manufacturer'])){
					self::$ava_overrided[ Wp_Helper::$id_post ] = true;
					$cacheId = 'pageBuilder|' . Wp_Helper::$id_post;

					$content = $this->context->smarty->tpl_vars['manufacturer'];
					$content_replace = &$this->context->smarty->tpl_vars['manufacturer'];
	
					$content->value['description'] .= $this->_avaRenderContent($cacheId);
					$content_replace = $content;
				}
                break;
            case 'supplier':
				if(isset($this->context->smarty->tpl_vars['supplier'])){
					self::$ava_overrided[ Wp_Helper::$id_post ] = true;
					$cacheId = 'pageBuilder|' . Wp_Helper::$id_post;

					$content = $this->context->smarty->tpl_vars['supplier'];
					$content_replace = &$this->context->smarty->tpl_vars['supplier'];
	
					$content->value['description'] .= $this->_avaRenderContent($cacheId);
					$content_replace = $content;
				}
                break;
			case 'blog':
				if(isset($this->context->smarty->tpl_vars['post'])){
					self::$ava_overrided[ Wp_Helper::$id_post ] = true;
					$cacheId = 'pageBuilder|' . Wp_Helper::$id_post;

					$content = $this->context->smarty->tpl_vars['post'];
					$content_replace = &$this->context->smarty->tpl_vars['post'];
					
					$content->value['content'] .= $this->_avaRenderContent($cacheId);
					$content_replace = $content;
				}
				break;
			case 'cms':
				if(isset($this->context->smarty->tpl_vars['cms'])){
					self::$ava_overrided[ Wp_Helper::$id_post ] = true;
					$cacheId = 'pageBuilder|' . Wp_Helper::$id_post;

					$content = $this->context->smarty->tpl_vars['cms'];
					$content_replace = &$this->context->smarty->tpl_vars['cms'];
					
					$content->value['content'] = '<div class="container container-parent">' . $content->value['content'] . '</div>' . $this->_avaRenderContent($cacheId);
					$content_replace = $content;
				}
				break;
        }

		$this->context->smarty->assign('theme_dir',  _PS_THEME_DIR_.'/templates');
		if(isset($this->context->smarty->registered_resources['parent'])){
			if(!empty($this->context->smarty->registered_resources['parent']->paths)){
				$this->context->smarty->assign('parent_theme_dir',  $this->context->smarty->registered_resources['parent']->paths['parent']);
			}
		}

		$tpl_dir = $this->context->smarty->getTemplateDir();
		array_unshift($tpl_dir, _PS_MODULE_DIR_ . 'avanambuilder/views/templates/front/');
		$this->context->smarty->setTemplateDir($tpl_dir);


		$ids_post = [];
     
		if( self::$ava_hooks['header'] ){ 
			$ids_post[] = [ 'type' => 'header', 'id' => self::$ava_hooks['header'], 'before' => '<div id="header-normal">', 'after' => '</div>' ];
		}

		if( self::$ava_hooks['home'] ){ 
			$ids_post[] = [ 'type' => 'home','id' => self::$ava_hooks['home'], 'before' => '', 'after' => '' ];
		}

		if( self::$ava_hooks['footer'] ){ 
			$ids_post[] = [ 'type' => 'footer','id' => self::$ava_hooks['footer'], 'before' => '', 'after' => '' ];
		}

		foreach( $ids_post as $value ){ 

			$content = '';
			$get_content = '';
			Wp_Helper::$id_post = $value['id'];
			
			if( Wp_Helper::$id_post && Validate::isLoadedObject( new AvanamBuilderPost( Wp_Helper::$id_post, Wp_Helper::$id_lang ) ) ){	            
				$get_content = Plugin::instance()->frontend->get_builder_content_for_display( Wp_Helper::$id_post, true );
			}
			
			if( $get_content ){ $content = true; }
			
			$this->context->smarty->assign('parsed_'.$value['type'],  $content);

		}

    }
		
    public function renderWidget($hookName = null, array $configuration = []) {	
        if ($hookName == null && isset($configuration['hook'])) {
            $hookName = $configuration['hook'];
        }

		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)$this->context->shop->id;

		Wp_Helper::$id_editor = self::$ava_hooks['id_editor'];
		
		if ( preg_match('/^displayProductSameCategory\d*$/', $hookName) ){
            if ( isset( $configuration['smarty']->tpl_vars['product']->value['id_product'] ) ) {    

				$id_product = (int) $configuration['smarty']->tpl_vars['product']->value['id_product'];

				$product =  new Product($id_product, true, $id_lang, $id_shop, $this->context);

				if (!Validate::isLoadedObject($product)) {
					return;
				}
				
				$category = new Category($product->id_category_default);

				$searchProvider = new CategoryProductSearchProvider($this->context->getTranslator(), $category);
	
				$context = new ProductSearchContext($this->context);
				$query = new ProductSearchQuery();
				$query->setResultsPerPage(2)->setPage(1);
				$query->setIdCategory($category->id)->setSortOrder(
					new SortOrder('product', 'name', 'desc')
				);
				$result = $searchProvider->runQuery($context, $query);
				$products = $result->getProducts();

				if( count($products) < 2 && !( Wp_Helper::is_preview_mode() || Dispatcher::getInstance()->getController() == 'ajax_editor' || (int)Tools::getValue( 'wp_preview' ) ) ){
					return;
				}
            }
        } else if ( preg_match('/^displayProductAccessories\d*$/', $hookName) ){
			if( ( !isset($this->context->smarty->tpl_vars['accessories']->value) || !$this->context->smarty->tpl_vars['accessories']->value ) && 
                !( Wp_Helper::is_preview_mode() || Dispatcher::getInstance()->getController() == 'ajax_editor' || (int)Tools::getValue( 'wp_preview' ) ) ){
				return;
			}
        }

        if (preg_match('/^displayHeaderPageBuilder\d*$/', $hookName)) {			
			if( !self::$ava_hooks['header'] ){ return; }

			Wp_Helper::$id_post = self::$ava_hooks['header'];

			$cacheId = 'pageBuilder|' . Wp_Helper::$id_post;

			return '<div id="header-normal">' . $this->_avaRenderContent($cacheId) . '</div>';
        } else if (preg_match('/^displayHeaderSticky\d*$/', $hookName)) {			
			if( !self::$ava_hooks['header_sticky'] ){ return; }
		
			Wp_Helper::$id_post = self::$ava_hooks['header_sticky'];

			$cacheId = 'pageBuilder|' . Wp_Helper::$id_post;

			return '<div id="header-sticky" class="has-sticky">' . $this->_avaRenderContent($cacheId) . '</div>';
        } else if (preg_match('/^displayHomePageBuilder\d*$/', $hookName)) {
			if( !self::$ava_hooks['home'] ){ return; }
			
			Wp_Helper::$id_post = self::$ava_hooks['home'];

			$cacheId = 'pageBuilder|' . Wp_Helper::$id_post;

			return $this->_avaRenderContent($cacheId);
        } else if (preg_match('/^displayFooterPageBuilder\d*$/', $hookName)) {
			if( !self::$ava_hooks['footer'] ){ return; }
			
			Wp_Helper::$id_post = self::$ava_hooks['footer'];

			$cacheId = 'pageBuilder|' . Wp_Helper::$id_post;

			return $this->_avaRenderContent($cacheId);
        } else {			
			if( !isset( self::$ava_hooks['hooks'][$hookName] ) || !self::$ava_hooks['hooks'][$hookName] ){ return; }
			
			Wp_Helper::$post_type = 'hook';
			Wp_Helper::$key_related = $hookName;
			Wp_Helper::$id_post = self::$ava_hooks['hooks'][$hookName];

			$cacheId = 'pageBuilder|' . Wp_Helper::$id_post;

			if ( preg_match('/^displayProductAccessories\d*$/', $hookName) || preg_match('/^displayProductSameCategory\d*$/', $hookName) || 
				preg_match('/^displayFooterProduct\d*$/', $hookName) || preg_match('/^displayLeftColumnProduct\d*$/', $hookName) || 
				preg_match('/^displayRightColumnProduct\d*$/', $hookName) || preg_match('/^displayProductSummary\d*$/', $hookName) ||
				preg_match('/^displayProductSidebar\d*$/', $hookName)
			) {
			if ( isset( $configuration['smarty']->tpl_vars['product']->value['id_product'] ) ) {
				$this->context->smarty->tpl_vars['ava_id_product'] = $id_product = (int) $configuration['smarty']->tpl_vars['product']->value['id_product'];
		}
			} else if ( preg_match('/^displayHeaderCategory\d*$/', $hookName) || preg_match('/^displayFooterCategory\d*$/', $hookName) ) {
			if ( isset( $configuration['smarty']->tpl_vars['category']->value['id'] ) ){
				$this->context->smarty->tpl_vars['ava_id_category'] = $id_category = (int) $configuration['smarty']->tpl_vars['category']->value['id'];
	
				$cacheId .= '|category|' . $id_category;
			}
		}

			return $this->_avaRenderContent($cacheId);
			}
    }

	public function _avaRenderContent($cacheId = '')
    {	
        if( !Wp_Helper::$id_post ){ return ''; }
			
		if( Wp_Helper::$id_post == Wp_Helper::$id_editor ){		
			return $this->_avaRenderEditor();
		}
		
        if (!$this->isCached($this->avanam_tplfile, $this->getCacheId($cacheId))){			
			$content = '';
						
			$get_content = $this->getWidgetVariables();

			if( $get_content ){ $content .= $get_content; }
						
            $this->smarty->assign(['content' => $content]);
        }
										
		return $this->fetch($this->avanam_tplfile, $this->getCacheId($cacheId));
    }

    public function _avaRenderEditor()
    {	
        if( !Wp_Helper::$id_post || !Wp_Helper::$id_editor ){ return ''; }
			
		if( Wp_Helper::$id_post == Wp_Helper::$id_editor ){		
			$content = '';
						
			$get_content = $this->getWidgetVariables();

			if( $get_content ){ $content .= $get_content; }
						
			$this->smarty->assign(['content' => $content]);
										
			return $this->fetch($this->avanam_tplfile);
		}
    }

	public function getWidgetVariables( $hookName = null, array $configuration = [] )
    {		
		if( !Wp_Helper::$id_post ){ return; }
		
		$with_css = Wp_Helper::$id_post != Wp_Helper::$id_editor;

		$content = '';

		if( Wp_Helper::$id_post && Validate::isLoadedObject( new AvanamBuilderPost( Wp_Helper::$id_post, Wp_Helper::$id_lang ) ) ){	            
			$content .= Plugin::instance()->frontend->get_builder_content( Wp_Helper::$id_post, $with_css );
		}

		Wp_Helper::reset_post_var();
				
		return $content;
    }
	
    public function deleteRelated( $id, $type ) 
	{
		Wp_Helper::$id_shop = (int) $this->context->shop->id;
		Wp_Helper::$post_type = $type;
		Wp_Helper::$key_related = $id;
		
		$related = Wp_Helper::getRelatedByKey();
		
		if( $related ){
			$obj = new AvanamBuilderRelated( $related['id_avanam_builder_related'] );
			$obj->delete();
			
			$post = new AvanamBuilderPost( $related['id_post'] );
			$post->delete();
		}
    }

    public function clearGlobalCssCache() 
	{
        $this->_clearCache($this->css_js_avanam_tplfile);
    }
		
    public function clearElementorCache( $postId ) 
	{
        $this->_clearCache($this->avanam_tplfile, $this->getCacheId('pageBuilder|' . $postId));
    }
	
    public function hookActionObjectCategoryDeleteAfter($params)
    {
        if (!isset($params['object']->id)) {
            return;
        }
		
		$id = (int)$params['object']->id;
		
		$this->deleteRelated( $id, 'category' );
    }
	
    public function hookActionObjectProductDeleteAfter($params)
    {
        if (!isset($params['object']->id)) {
            return;
        }
		$id = (int)$params['object']->id;
		
		$this->deleteRelated( $id, 'product' );
    }
	
    public function hookActionObjectCmsDeleteAfter($params) 
	{
        if (!isset($params['object']->id)) {
            return;
        }
		$id = (int)$params['object']->id;
		
		$this->deleteRelated( $id, 'cms' );
    }
	
    public function hookActionObjectManufacturerDeleteAfter($params) 
	{
        if (!isset($params['object']->id)) {
            return;
        }
		$id = (int)$params['object']->id;
		
		$this->deleteRelated( $id, 'manufacturer' );
    }
	
    public function hookActionObjectSupplierDeleteAfter($params) 
	{
        if (!isset($params['object']->id)) {
            return;
        }
		$id = (int)$params['object']->id;
		
		$this->deleteRelated( $id, 'supplier' );
    }

    public function hookActionObjectBlogDeleteAfter($params) 
	{
        if (!isset($params['object']->id)) {
            return;
        }
		$id = (int)$params['object']->id;
		
		$this->deleteRelated( $id, 'blog' );
    }
	
    public function _prepBlogs($settings)
	{
		$content = array();
		
		$source = $settings['source'];
		$limit = (int)$settings['limit'] <= 0 ? 10 : (int)$settings['limit'];
		$image_size = $settings['image_size'];
		$order_by = $settings['order_by'];
		$order_way = $settings['order_way'];

		if($order_by == 'name'){
			$order_by = 'pl.meta_title';
		}elseif($order_by == 'date_add'){
			$order_by = 'p.created';
		}else{
			$order_by = 'p.id_smart_blog_post';
		}

		$content['blogs'] = $this->execBlogs($source, $limit, $image_size, $order_by, $order_way);
		
		//$content['items_type_path'] = $this->_getBlogsPath($settings['items_type']);

		return $content;
	}
	
    public function execBlogs($source, $limit, $image_size, $order_by, $order_way)
	{	
		$blogs = array();
		
		if($source == 'n'){
			$blogs = SmartBlogPost::GetPostLatestHome($limit, $image_size, null, $order_by, $order_way);
		}else{
			$blogs = SmartBlogPost::GetPostByCategory($source, $limit, $image_size, null, $order_by, $order_way);
		}
		
		return $blogs;	
	}
	
    public function _getBlogsPath($items_type)
	{		
		$items_type_path = [];

		for( $i = 1; $i <= 30; $i++ ){
			$items_type_path[$i] = 'module:avanambuilder/views/templates/front/catalog/_partials/miniatures/_partials/_blog/blog-' . $i . '.tpl';
		}

		$items_type_path = Wp_Helper::apply_filters( 'avanambuilder_blogs_type_path', $items_type_path );	
		
		return $items_type_path[$items_type];
	}
	
    public function _getProductsPath($items_type)
	{		
		$items_type_path = [];

		for( $i = 1; $i <= 30; $i++ ){
			$items_type_path[$i] = 'module:avanambuilder/views/templates/front/catalog/_partials/miniatures/_partials/_product/product-' . $i . '.tpl';
		}

		$items_type_path = Wp_Helper::apply_filters( 'avanambuilder_products_type_path', $items_type_path );	
		
		return $items_type_path[$items_type];
	}
	
    public function _prepProductsSelected($settings)
	{	
		$content = array();
		$data = array();
		
		$content['products'] = $this->execProducts('s', $settings, 0, null, null, 1);	
		$content['lastPage'] = true;
		
		//$content['items_type_path'] = $this->_getProductsPath($settings['items_type']);
		
		return $content;
	}
		
    public function _prepProducts($settings)
	{	
		$content = array();
		
		$source = $settings['source'];
		$limit = (int)$settings['limit'] <= 0 ? 10 : (int)$settings['limit'];
		$order_by = $settings['order_by'];
		$order_way = $settings['order_way'];
		
		if($source == 'c'){
			$source = $settings['category'];
			if ($settings['randomize']) {
				$order_by = 'rand';
			}
		}
				
		$page = $settings['paged'];
				
		$content['products'] = $this->execProducts($source,  $settings, $limit, $order_by, $order_way, $page);
		
		$content['lastPage'] = true;
		
		if( $page > 1 ){
			$content['lastPage'] = !(bool)$this->execProducts($source,  $settings, $limit, $order_by, $order_way, $page + 1);
		}
		
		//$content['items_type_path'] = $this->_getProductsPath($settings['items_type']);
		
		return $content;
	}
	
    public function execProducts($source, $settings, $limit, $order_by, $order_way, $page = 1)
	{	
		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)$this->context->shop->id;

		$exclude_id_product = 0;

		$products = [];
				
        switch ($source) {
            case 'n':
				$searchProvider = new NewProductsProductSearchProvider($this->context->getTranslator());
				
				$context = new ProductSearchContext($this->context);
				$query = new ProductSearchQuery();
				$query->setResultsPerPage($limit)->setPage($page);
				$query->setQueryType('new-products')->setSortOrder(new SortOrder('product', $order_by, $order_way));
				$result = $searchProvider->runQuery($context, $query);
				$products = $result->getProducts();	
				
                break;
            case 'p':
				$searchProvider = new PricesDropProductSearchProvider($this->context->getTranslator());
				
				$context = new ProductSearchContext($this->context);
				$query = new ProductSearchQuery();
				$query->setResultsPerPage($limit)->setPage($page);
				$query->setQueryType('prices-drop')->setSortOrder(new SortOrder('product', $order_by, $order_way));
				$result = $searchProvider->runQuery($context, $query);
				$products = $result->getProducts();		
				
                break;
            case 'm':			
				$manufacturer = new Manufacturer($settings['manufacturer']);
								
				$searchProvider = new ManufacturerProductSearchProvider($this->context->getTranslator(), $manufacturer);
				
				$context = new ProductSearchContext($this->context);
				$query = new ProductSearchQuery();
				$query->setResultsPerPage($limit)->setPage($page);
				$query->setQueryType('manufacturer')->setIdManufacturer($manufacturer->id)->setSortOrder(new SortOrder('product', $order_by, $order_way));
				$result = $searchProvider->runQuery($context, $query);
				$products = $result->getProducts();
								
                break;
            case 'sl':			
				$supplier = new Supplier($settings['supplier']);
								
				$searchProvider = new SupplierProductSearchProvider($this->context->getTranslator(), $supplier);
				
				$context = new ProductSearchContext($this->context);
				$query = new ProductSearchQuery();
				$query->setResultsPerPage($limit)->setPage($page);
				$query->setQueryType('supplier')->setIdSupplier($supplier->id)->setSortOrder(new SortOrder('product', $order_by, $order_way));
				$result = $searchProvider->runQuery($context, $query);
				$products = $result->getProducts();
								
                break;
            case 'b':
				if($order_by == 'position'){
					$order_by = 'sales';
				}	
									
				$searchProvider = new BestSalesProductSearchProvider($this->context->getTranslator());
				
				$context = new ProductSearchContext($this->context);
				$query = new ProductSearchQuery();
				$query->setResultsPerPage($limit)->setPage($page);
				$query->setQueryType('best-sales')->setSortOrder(new SortOrder('product', $order_by, $order_way));
				$result = $searchProvider->runQuery($context, $query);
				$products = $result->getProducts();		
                break;
            case 's':
				if(!is_array($settings['product_ids'])){
					return $products;
				}
				foreach($settings['product_ids'] as $product_id){
					$arr = explode('_', $product_id);
		
					if(isset($arr[1])){
						$id_p = $arr[1];
					}else{
						$id_p = $product_id;
					}

					if((int)$id_p){
						$id_product = (int)$id_p;
						$product =  new Product($id_product, true, $id_lang, $id_shop, $this->context);
						if (Validate::isLoadedObject($product)) {
							$product->id_product = (int)$id_product;
							$products[]= (array)$product;
						}
					}
				}	
				
                break;
            case 'p_s':
				if((isset($settings['ava_id_product']) && $settings['ava_id_product']) || isset($this->context->smarty->tpl_vars['ava_id_product'])){
					$id_product = isset($this->context->smarty->tpl_vars['ava_id_product']) ? (int)$this->context->smarty->tpl_vars['ava_id_product'] : (int)$settings['ava_id_product'];
					$product =  new Product($id_product, true, $id_lang, $id_shop, $this->context);

					if (!Validate::isLoadedObject($product)) {
						return;
					}
					
					$category = new Category($product->id_category_default);

					$searchProvider = new CategoryProductSearchProvider($this->context->getTranslator(), $category);

					$context = new ProductSearchContext($this->context);
					$query = new ProductSearchQuery();
					$query->setResultsPerPage((int)$limit + 1)->setPage($page);
					$query->setIdCategory($category->id)->setSortOrder(
						$order_by == 'rand'
						? SortOrder::random()
						: new SortOrder('product', $order_by, $order_way)
					);
					$result = $searchProvider->runQuery($context, $query);
					$products = $result->getProducts();

					$exclude_id_product = $id_product;
				}
				
                break;
            case 'p_a':
				if((isset($settings['ava_id_product']) && $settings['ava_id_product']) || isset($this->context->smarty->tpl_vars['ava_id_product'])){
					$id_product = isset($this->context->smarty->tpl_vars['ava_id_product']) ? (int)$this->context->smarty->tpl_vars['ava_id_product'] : (int)$settings['ava_id_product'];
					$product =  new Product($id_product, true, $id_lang, $id_shop, $this->context);

					if (!Validate::isLoadedObject($product)) {
						return;
					}

					$products = $product->getAccessories($id_lang);
				}
				
                break;
            default:
                $id_category_arr = explode('_', $source);

                if(isset($id_category_arr[1])){
                    $id_category = $id_category_arr[1];
                }else{
                    $id_category = $source;
                }

				$category = new Category((int)$id_category);
		
				$searchProvider = new CategoryProductSearchProvider($this->context->getTranslator(), $category);
				
				$context = new ProductSearchContext($this->context);
				$query = new ProductSearchQuery();
				$query->setResultsPerPage($limit)->setPage($page);
                $query->setQueryType('category')->setIdCategory($category->id)->setSortOrder(
                    $order_by == 'rand'
                    ? SortOrder::random()
                    : new SortOrder('product', $order_by, $order_way)
                );
				$result = $searchProvider->runQuery($context, $query);
				$products = $result->getProducts();		
				
                break;
        }

		if( ($source == 'p_s' || $source == 'p_a') && (Wp_Helper::is_preview_mode() || Dispatcher::getInstance()->getController() == 'ajax_editor' || (int)Tools::getValue( 'wp_preview' ) || (isset($settings['ava_is_editor']) && (int)$settings['ava_is_editor'])) ){
			$order_by = 'position';
			$order_way = 'ASC';

			$searchProvider = new NewProductsProductSearchProvider($this->context->getTranslator());
			$context = new ProductSearchContext($this->context);
			$query = new ProductSearchQuery();
			$query->setResultsPerPage($limit)->setPage($page);
			$query->setSortOrder(new SortOrder('product', $order_by, $order_way));
			$result = $searchProvider->runQuery($context, $query);
			$products = $result->getProducts();	
		}

		$products = $this->convertProducts($products, $exclude_id_product, $limit);
			
		return $products;
	}

	public function aProduct( $id_product )	
	{	
		$products = [];
		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)$this->context->shop->id;
		$product =  new Product( $id_product, false, $id_lang, $id_shop, $this->context );
		if ( Validate::isLoadedObject($product) ) {
			$products[]= ['id_product' => (int)$id_product];
		}else{
			return;
		}
		$products = $this->convertProducts( $products, 0, 1 );
		return $products[0];
	}

	public function convertProducts($products, $exclude_id_product, $limit)	
	{		
		$assembler = new ProductAssembler($this->context);
		$presenterFactory = new ProductPresenterFactory($this->context);
		$presentationSettings = $presenterFactory->getPresentationSettings();
		$presenter = new ProductListingPresenter(
			new ImageRetriever(
				$this->context->link
			),
			$this->context->link,
			new PriceFormatter(),
			new ProductColorsRetriever(),
			$this->context->getTranslator()
		);
		$products_for_template = [];
		if(is_array($products)){
			foreach ($products as $rawProduct) {
				if ($rawProduct['id_product'] != $exclude_id_product && (count($products_for_template) < (int) $limit || !(int) $limit)) {
					$product = $presenter->present(
						$presentationSettings,
						$assembler->assembleProduct($rawProduct),
						$this->context->language
					);
					$products_for_template[] = $product;
				}
			}
		}
		
		return 	$products_for_template;
	}
			
    public function getListByPostType($postType)
    {
		$query = new DbQuery();
		$query->select('*');
		$query->from('avanam_builder_post', 'p');
		$query->where('p.post_type = "' . $postType . '"');
		$sqlResult = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        $arrays = array();
        foreach ($sqlResult as $p) {
            $arrays[$p['id_avanam_builder_post']] = array(
                'id' => $p['id_avanam_builder_post'],
                'name' => $p['title']
            );
        }

        return  $arrays;
    }
	
	/*------------------get Languages----------------------------*/
	
    public function getListLanguages()
    {
		$languages = Language::getLanguages( true, $this->context->shop->id );
		
		if( count( $languages ) < 2 ){
			return;
		}
		
        foreach ( $languages as &$lang ) {
            $lang['name_simple'] = preg_replace( '/\s\(.*\)$/', '', $lang['name'] );
        }
				
		$params = [
			'languages' => $languages,
			'current_language' => [
				'id_lang' => $this->context->language->id,
				'name' => $this->context->language->name,
				'name_simple' => preg_replace( '/\s\(.*\)$/', '', $this->context->language->name ),
				'iso_code' => $this->context->language->iso_code
			]
		];

        return $params;
    }
	
	/*------------------get Currencies----------------------------*/
	
    public function getListCurrencies()
    {
		if( Configuration::isCatalogMode() || !Currency::isMultiCurrencyActivated() ) {
			return;
		}
		
		$current_currency = null;
        //$serializer = new ObjectPresenter();
        if (class_exists(LegacyObjectPresenter::class)) {
            $serializer = new LegacyObjectPresenter();
        } else {
            $serializer = new NewObjectPresenter();
        }
        $currencies = array_map(
            function ($currency) use ($serializer, &$current_currency) {				
                $currencyArray = $serializer->present($currency);

                // serializer doesn't see 'sign' because it is not a regular
                // ObjectModel field.
                $currencyArray['sign'] = $currency->sign;

                $url = $this->context->link->getLanguageLink($this->context->language->id);

                $parsedUrl = parse_url($url);
                $urlParams = [];
                if (isset($parsedUrl['query'])) {
                    parse_str($parsedUrl['query'], $urlParams);
                }
                $newParams = array_merge(
                    $urlParams,
                    [
                        'SubmitCurrency' => 1,
                        'id_currency' => $currency->id,
                    ]
                );
                $newUrl = sprintf('%s://%s%s%s?%s',
                    $parsedUrl['scheme'],
                    $parsedUrl['host'],
                    isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '',
                    $parsedUrl['path'],
                    http_build_query($newParams)
                );

                $currencyArray['url'] = $newUrl;

                if ($currency->id == $this->context->currency->id) {
                    $currencyArray['current'] = true;
                    $current_currency = $currencyArray;
                } else {
                    $currencyArray['current'] = false;
                }

                return $currencyArray;
            },
            Currency::getCurrencies(true, true)
        );
				
		$params = [
			'currencies' => $currencies,
			'current_currency' => $current_currency,
		];

        return $params;
    }
	
	/*------------------get Product Categories----------------------------*/
	
	public function getCategories()
    {
		$category = new Category((int)Configuration::get('PS_HOME_CATEGORY'), $this->context->language->id);
			
        $range = '';
        $maxdepth = 0;
        if (Validate::isLoadedObject($category)) {
            if ($maxdepth > 0) {
                $maxdepth += $category->level_depth;
            }
            $range = 'AND nleft >= '.(int)$category->nleft.' AND nright <= '.(int)$category->nright;
        }

        $resultIds = array();
        $resultParents = array();
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite
			FROM `'._DB_PREFIX_.'category` c
			INNER JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = '.(int)$this->context->language->id.Shop::addSqlRestrictionOnLang('cl').')
			INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = '.(int)$this->context->shop->id.')
			WHERE (c.`active` = 1 OR c.`id_category` = '.(int)Configuration::get('PS_HOME_CATEGORY').')
			AND c.`id_category` != '.(int)Configuration::get('PS_ROOT_CATEGORY').'
			'.((int)$maxdepth != 0 ? ' AND `level_depth` <= '.(int)$maxdepth : '').'
			'.$range.'
			ORDER BY `level_depth` ASC, cs.`position` ASC');
        foreach ($result as &$row) {
            $resultParents[$row['id_parent']][] = &$row;
            $resultIds[$row['id_category']] = &$row;
        }
		
		$categoriesSource = array();
		
		$this->getTree($resultParents, $resultIds, $maxdepth, ($category ? $category->id : null), 0, $categoriesSource);

        return $categoriesSource;
    }

    public function getTree($resultParents, $resultIds, $maxDepth, $id_category, $currentDepth, &$categoriesSource)
    {
        if (is_null($id_category)) {
            $id_category = $this->context->shop->getCategory();
        }

        if (isset($resultIds[$id_category])) {
            $link = $this->context->link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']);
            $name = str_repeat('&nbsp;&nbsp;', 1 * $currentDepth).$resultIds[$id_category]['name'];
            $desc = $resultIds[$id_category]['description'];
        } else {
            $link = $name = $desc = '';
        }
		
		$categoriesSource[$currentDepth . '_' . $id_category] = $name;
		
        if (isset($resultParents[$id_category]) && count($resultParents[$id_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth)) {
            foreach ($resultParents[$id_category] as $subcat) {
                $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_category'], $currentDepth + 1, $categoriesSource);
            }
        }
		
    }
}
