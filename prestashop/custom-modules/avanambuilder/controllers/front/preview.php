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
use AvanamBuilder\Core\Files\CSS\Post as Post_CSS;

class AvanamBuilderPreviewModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        parent::init();
    }
	
    public function setMedia()
    {
        parent::setMedia();
    }

    public function initContent()
    {
        parent::initContent();

        if ( $id_post = (int) Tools::getValue('elementor_library') ){
            $obj =  new AvanamBuilderTemplate($id_post);
			
			if (Validate::isLoadedObject($obj)) {
				$data = (array) json_decode($obj->content, true);
				
				Wp_Helper::$id_post = $id_post;
				
				Wp_Helper::$is_template = true;
				
				$document = Plugin::instance()->documents->get_doc_for_frontend( $id_post );
				
				$css_file = Post_CSS::create( $id_post );

                Wp_Helper::add_filter( 'elementor/get_saved_settings', function() { 
                    $obj =  new AvanamBuilderTemplate(Wp_Helper::$id_post);

                    if (Validate::isLoadedObject($obj)) {
                        return (array) json_decode($obj->page_settings, true);
                    }
                } );

                Wp_Helper::add_filter( 'elementor/post_css/get_data', function() { 
                    $obj =  new AvanamBuilderTemplate(Wp_Helper::$id_post);

                    if (Validate::isLoadedObject($obj)) {
                        return (array) json_decode($obj->content, true);
                    }
                } );
				
				ob_start();
				
				$css_file->print_css();

				$document->print_elements_with_wrapper( $data );

				$content = ob_get_clean();
				
				Wp_Helper::$id_post = Wp_Helper::$is_template = null;
			}else{
				$content = '';
			}

            $this->context->smarty->assign(array(
                'content' => $content
            ));
			$this->setTemplate('module:'.$this->module->name.'/views/templates/front/preview_template.tpl');
        }else{
			$this->setTemplate('module:'.$this->module->name.'/views/templates/front/preview.tpl');
		}
    }
}
