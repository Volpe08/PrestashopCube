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

class AdminAvanamBuilderHomeController extends ModuleAdminController
{
    public $name;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->className = 'AvanamBuilderPost';
        $this->table = 'avanam_builder_post';

        $this->addRowAction('edit');
        $this->addRowAction('delete');
		
        parent::__construct();
		
        if (!$this->module->active) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminDashboard'));
        }
		
        $this->_orderBy = 'id_avanam_builder_post';
        $this->identifier = 'id_avanam_builder_post';
		
		$list_pages = array();
		
		$list_pages[0] = array(
			'id' => 0,
			'name' => $this->trans(' - Choose (optional) - ', [], 'Modules.Avanambuilder.Admin')
		);
		
        $this->fields_options = array(
            'general' => array(
                'title' =>    $this->trans('Settings', [], 'Admin.Global'),
                'fields' =>    array(
                    'active_home_layout' => array(
                        'title' => $this->trans('Home layout', [], 'Modules.Avanambuilder.Admin'),
                        'desc' => $this->trans('Choose your home layout. You can create multiple layouts in list above. So you can change them fast when needed.', [], 'Modules.Avanambuilder.Admin'),
                        'cast' => 'intval',
                        'type' => 'select',
                        'list' => array_merge($list_pages, $this->module->getListByPostType('home')),
                        'identifier' => 'id'
                    ),
                ),
                'submit' => array('title' => $this->trans('Save', [], 'Admin.Actions'))
            )
        );

        $this->fields_list = array(
            'id_avanam_builder_post' => array('title' => $this->trans('ID', [], 'Admin.Global'), 'align' => 'center', 'class' => 'fixed-width-xs'),
            'title' => array('title' => $this->trans('Name', array(), 'Admin.Global'), 'width' => 'auto'),
            'active' => array('title' => $this->trans('Active', [], 'Admin.Global'), 'align' => 'center', 'search' => false, 'active' => 'status', 'type' => 'bool')
        );
		
		$this->_where = ' AND `post_type` = "home"';
		
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->trans('Delete selected', [], 'Modules.Avanambuilder.Admin'),
                'icon' => 'icon-trash',
                'confirm' => $this->trans('Delete selected items?', [], 'Modules.Avanambuilder.Admin'),
            ),
        );

        $this->name = 'AdminAvanamBuilderHome';
    }
	
    public function initToolBarTitle()
    {
        $this->toolbar_title[] = $this->trans('Avanam - Home', [], 'Modules.Avanambuilder.Admin');
    }
	
    public function renderList()
    {				
        return Wp_Helper::api_get_notification() . parent::renderList();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submit' . $this->className)) {
            $returnObject = $this->processSave();
            if (!$returnObject) {
                return false;
            }
			if( count( $this->module->getListByPostType('home') ) == 1 ){
				Configuration::updateValue( 'active_home_layout', $returnObject->id );
			}
			Tools::redirectAdmin($this->context->link->getAdminLink($this->name) . '&id_avanam_builder_post='.$returnObject->id .'&updateavanam_builder_post');
        }		

        return parent::postProcess();
    }

    public function renderForm()
    {
		$id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
		
        $obj = new $this->className((int) Tools::getValue('id_avanam_builder_post'));
		
        if ($obj->id){
            $url = $this->context->link->getAdminLink('AvanamBuilderEditor').'&post_type=home&id_post=' . $obj->id . '&id_lang='. $id_lang;
        }
        else{
            $url = false;
        }
		
		$obj->post_type = 'home';
		$obj->id_employee = (int) $this->context->employee->id;
		
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => isset($obj->id) ? $this->trans('Edit layout', [], 'Modules.Avanambuilder.Admin') : $this->trans('New layout', [], 'Modules.Avanambuilder.Admin'),
                'icon' => isset($obj->id) ? 'icon-edit' : 'icon-plus-square',
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_avanam_builder_post',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Title', [], 'Admin.Global'),
                    'name' => 'title',
                    'required' => true,
                ),
				array(
					'type' => 'hidden',
					'name' => 'post_type'
				),
				array(
					'type' => 'hidden',
					'name' => 'id_employee'
				),
				array(
					'type'     => 'switch',
					'label'    => $this->trans('Status', [], 'Admin.Global'),
					'name'     => 'active',
					'is_bool'  => true,
					'values'   => array(
						array(
							'id'    => 'active',
							'value' => 1,
							'label' => $this->trans('Enabled', [], 'Admin.Global'),
						),
						array(
							'id'    => 'active',
							'value' => 0,
							'label' => $this->trans('Disabled', [], 'Admin.Global'),
						),
					),
				),
                array(
                    'type' => 'page_trigger',
                    'label' => '',
                    'url'  => $url,
                )
            ),
            'buttons' => array(
                'cancelBlock' => array(
                    'title' => $this->trans('Back', [], 'Admin.Actions'),
                    'href' => (Tools::safeOutput(Tools::getValue('back', false)))
                        ?: $this->context->link->getAdminLink($this->name),
                    'icon' => 'process-icon-cancel',
                ),
            ),
            'submit' => array(
                'name' => 'submit' . $this->className,
                'title' => $this->trans('Save', [], 'Admin.Actions'),
            ),
        );


        if (Tools::getValue('name')) {
            $obj->title = Tools::getValue('name');
        }

        $helper = $this->buildHelper();
        $helper->fields_value = (array) $obj;
        return Wp_Helper::api_get_notification() . $helper->generateForm($this->fields_form);
    }

    protected function buildHelper()
    {
        $helper = new HelperForm();

        $helper->module = $this->module;
        $helper->identifier = $this->className;
        $helper->token = Tools::getAdminTokenLite($this->name);
        $helper->languages = $this->_languages;
        $helper->currentIndex = $this->context->link->getAdminLink($this->name);
        $helper->default_form_language = $this->default_form_language;
        $helper->allow_employee_form_lang = $this->allow_employee_form_lang;
        $helper->toolbar_scroll = true;
        $helper->toolbar_btn = $this->initToolbar();

        return $helper;
    }
			
}
