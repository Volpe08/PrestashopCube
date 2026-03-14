<?php
/**
 * Module: Payment Selector
 * Description: Affiche SumUp ou Stripe selon le montant du panier
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class PaymentSelector extends Module
{
    public function __construct()
    {
        $this->name = 'paymentselector';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.0-cube';
        $this->author = '3D Painters';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Payment Selector');
        $this->description = $this->l('Affiche SumUp ou Stripe selon le montant du panier');
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('paymentOptions') &&
            $this->registerHook('header') &&  // AJOUT IMPORTANT
            $this->registerHook('actionFrontControllerSetMedia') &&  // AJOUT IMPORTANT
            Configuration::updateValue('PAYMENT_SELECTOR_AMOUNT', 100);
    }

    public function uninstall()
    {
        return parent::uninstall() &&
            Configuration::deleteByName('PAYMENT_SELECTOR_AMOUNT');
    }

    /**
     * Configuration dans l'admin
     */
    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('submit' . $this->name)) {
            $amount = (float)Tools::getValue('PAYMENT_SELECTOR_AMOUNT');

            if ($amount < 0) {
                $output .= $this->displayError($this->l('Le montant doit être positif'));
            } else {
                Configuration::updateValue('PAYMENT_SELECTOR_AMOUNT', $amount);
                $output .= $this->displayConfirmation($this->l('Configuration enregistrée'));
            }
        }

        return $output . $this->displayForm();
    }

    /**
     * Formulaire de configuration
     */
    protected function displayForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Configuration'),
                    'icon' => 'icon-cogs'
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => $this->l('Montant seuil (€)'),
                        'name' => 'PAYMENT_SELECTOR_AMOUNT',
                        'size' => 20,
                        'required' => true,
                        'desc' => $this->l('En dessous de ce montant : SumUp. Au-dessus ou égal : Stripe')
                    ]
                ],
                'submit' => [
                    'title' => $this->l('Enregistrer'),
                    'class' => 'btn btn-default pull-right'
                ]
            ]
        ];

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submit' . $this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = [
            'fields_value' => [
                'PAYMENT_SELECTOR_AMOUNT' => Configuration::get('PAYMENT_SELECTOR_AMOUNT')
            ],
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        ];

        return $helper->generateForm([$fields_form]);
    }

    /**
     * Hook pour charger le JavaScript - VERSION CORRIGÉE
     */
    public function hookActionFrontControllerSetMedia($params)
    {
        // Vérifier qu'on est sur une page de checkout
        $controller = $this->context->controller->php_self;

        if ($controller != 'order' && $controller != 'orderopc' && !($this->context->controller instanceof OrderControllerCore)) {
            return;
        }

        $cart = $this->context->cart;
        if (!Validate::isLoadedObject($cart)) {
            return;
        }

        $total = (float)$cart->getOrderTotal(true, Cart::BOTH);
        $threshold = (float)Configuration::get('PAYMENT_SELECTOR_AMOUNT');

        // Passer les données au JavaScript AVANT de charger le fichier JS
        Media::addJsDef([
            'paymentSelectorThreshold' => $threshold,
            'paymentSelectorTotal' => $total,
            'paymentSelectorUseSumup' => ($total < $threshold)
        ]);

        // Charger le JavaScript
        $this->context->controller->registerJavascript(
            'module-paymentselector-filter',
            'modules/' . $this->name . '/views/js/payment-filter.js',
            [
                'position' => 'bottom',
                'priority' => 200
            ]
        );
    }

    /**
     * Alternative avec hookHeader
     */
    public function hookHeader($params)
    {
        // Vérifier qu'on est sur une page de checkout
        $controller = $this->context->controller->php_self;

        if ($controller != 'order' && $controller != 'orderopc') {
            return;
        }

        $cart = $this->context->cart;
        if (!Validate::isLoadedObject($cart)) {
            return;
        }

        $total = (float)$cart->getOrderTotal(true, Cart::BOTH);
        $threshold = (float)Configuration::get('PAYMENT_SELECTOR_AMOUNT');

        // Ajouter les variables JavaScript inline
        $js_vars = sprintf(
            '<script type="text/javascript">
                var paymentSelectorThreshold = %s;
                var paymentSelectorTotal = %s;
                var paymentSelectorUseSumup = %s;
            </script>',
            json_encode($threshold),
            json_encode($total),
            json_encode($total < $threshold)
        );

        return $js_vars;
    }
}
