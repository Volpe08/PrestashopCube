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

class AvanamBuilderAjaxModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        parent::init();
    }

    public function postProcess()
    {
		parent::initContent();

        define( 'DOING_AJAX', true );
				
        if (Tools::getValue('type') == 'product') {
            $this->_renderProducts();
        }elseif (Tools::getValue('type') == 'blog') {
            $this->_renderBlogs();
        }

		$this->action = Tools::getValue('action');
		method_exists($this, "ajaxProcess{$this->action}") && $this->{"ajaxProcess{$this->action}"}();
    }

    public function _renderProducts()
    {
		header('Content-Type: application/json');
		$options = Tools::getValue('options');
		
		if($options['source'] != 's'){
			$data = $this->module->_prepProducts($options);		
		}else{
			$data = $this->module->_prepProductsSelected($options);	
		}
		
		$content = array_merge($options, $data);
		
		$theme = \Context::getContext()->shop->theme;
		$parentThemeDir = _PS_ROOT_DIR_ . '/themes/' . ($theme->get('parent') ?: $theme->getName());

		$params = array(
		    'content'             => $content,
		    'theme_template_path' => $parentThemeDir . '/templates/catalog/_partials/miniatures/product.tpl',
		);

		$this->context->smarty->assign($params);
		
		$template = 'module:' . $this->module->name . '/views/templates/widgets/products.tpl';
								
        $this->ajaxRender(json_encode(array(
			'lastPage' => $content['lastPage'],
            'html' => $this->context->smarty->fetch($template)
        )));	
		
		exit;
	}
	
    public function _renderBlogs()
    {
		header('Content-Type: application/json');
		$options = Tools::getValue('options');

		$data = $this->module->_prepBlogs($options);		
		
		$content = array_merge($options, $data);
		
       	$this->context->smarty->assign(array('content' => $content));
		
		$template = 'module:' . $this->module->name . '/views/templates/widgets/blogs.tpl';
								
        $this->ajaxRender(json_encode(array(
            'html' => $this->context->smarty->fetch($template)
        )));	
		
		exit;
	}

    public function ajaxProcessUpdateWishlist() {
        
        $current_user = (int)$this->context->cookie->id_customer;

        $id_wishlist = Db::getInstance()->getValue("SELECT id_wishlist FROM `"._DB_PREFIX_."wishlist` WHERE id_customer = '$current_user'");
        $count_products = Db::getInstance()->getValue("SELECT COUNT(id_wishlist_product) FROM `"._DB_PREFIX_."wishlist_product` WHERE id_wishlist = '$id_wishlist'");
        

        $this->ajaxDie(json_encode($count_products));

    }
	public function ajaxProcessAddToCartModal()
    {
        $cart = $this->cart_presenter->present($this->context->cart, true);
        $product = null;
        $id_product = (int) Tools::getValue('id_product');
        $id_product_attribute = (int) Tools::getValue('id_product_attribute');
        $id_customization = (int) Tools::getValue('id_customization');

        foreach ($cart['products'] as &$p) {
            if ($id_product === (int) $p['id_product'] && $id_product_attribute === (int) $p['id_product_attribute'] && $id_customization === (int) $p['id_customization']) {
                $product = $p;
                break;
            }
        }

        $this->context->smarty->assign([
            'configuration' => $this->getTemplateVarConfiguration(),
            'product' => $product,
            'cart' => $cart,
            'cart_url' => $this->context->link->getPageLink('cart', null, $this->context->language->id, [
                'action' => 'show',
            ], false, null, true),
        ]);

        $this->ajaxDie([
            'modal' => $this->context->smarty->fetch('module:ps_shoppingcart/modal.tpl'),
        ]);
    }

    protected function ajaxDie($value = null, $controller = null, $method = null)
    {
        if (null === $controller) {
            $controller = get_class($this);
        }
        if (null === $method) {
            $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $method = $bt[1]['function'];
        }
        Hook::exec('actionAjaxDie' . $controller . $method . 'Before', ['value' => $value]);

        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');

        exit(json_encode($value));
    }
			
}
