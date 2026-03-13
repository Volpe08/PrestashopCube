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

namespace AvanamBuilder;

use AvanamBuilder\Wp_Helper;

if ( ! defined( '_PS_VERSION_' ) ) {
    exit; // Exit if accessed directly
}

use AvanamBuilder\Widget_Traits_Nav as NavTrait;

class Widget_Ava_Sign_In extends Widget_Base
{
    use NavTrait;

    const REMOTE_RENDER = true;

    private $context;

    public function get_name() {
		return 'ava-sign-in';
	}

	public function get_title() {
		return Wp_Helper::__( 'Sign In', 'elementor' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

	public function get_categories() {
		return [ 'avanam-elements' ];
	}

    public function get_keywords()
    {
        return [ 'login', 'user', 'account', 'logout', 'avanam' ];
    }

    public function getLinkToOptions()
    {
        $t = $this->context->getTranslator();

        return [
            'my-account' => $t->trans('My account', [], 'Shop.Navigation'),
            'identity' => $t->trans('Personal Information', [], 'Shop.Theme.Checkout'),
            'address' => $t->trans('New address', [], 'Shop.Theme.Customeraccount'),
            'addresses' => $t->trans('Addresses', [], 'Shop.Navigation'),
            'history' => $t->trans('Order history', [], 'Shop.Navigation'),
            'order-slip' => $t->trans('Credit slip', [], 'Shop.Navigation'),
            'discount' => $t->trans('Vouchers', [], 'Shop.Theme.Customeraccount'),
            'logout' => $t->trans('Sign out', [], 'Shop.Theme.Actions'),
            'custom' => Wp_Helper::__('Custom URL'),
        ];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_selector',
            [
                'label' => $this->get_title(),
            ]
        );

        $this->start_controls_tabs('tabs_label_content');

        $this->start_controls_tab(
            'tab_label_sign_in',
            [
                'label' => Wp_Helper::__('Sign in'),
            ]
        );

        $this->add_control(
            'label',
            [
                'label' => Wp_Helper::__('Label'),
                'type' => Controls_Manager::TEXT,
                'default' => Wp_Helper::__('Sign in'),
            ]
        );

        $this->add_control(
            'label_sub',
            [
                'label' => Wp_Helper::__('Sub Label'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label' => Wp_Helper::__('Icon'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'exclude_inline_options' => ['svg'],
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'las la-user',
                    'library' => 'line-awesome',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'icon_align',
            [
                'label' => Wp_Helper::__('Icon Position'),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => Wp_Helper::__('Before'),
                    'right' => Wp_Helper::__('After'),
                ],
                'prefix_class' => 'elementor-account--align-icon-',
                /* 'condition' => [
                    'label!' => '',
                ], */
            ]
        );

        $this->add_responsive_control(
            'toggle_icon_spacing',
            [
                'label' => Wp_Helper::__('Icon Spacing'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'size-units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-account .elementor-button' => 'gap: {{SIZE}}{{UNIT}}',                    
                ],
                /* 'condition' => [
                    'label!' => '',
                ], */
            ]
        );

        $this->add_responsive_control(
            'toggle_icon_size',
            [
                'label' => Wp_Helper::__('Icon Size'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					]
				],
                'size_units' => ['px', 'em'],
                'default' => [
                    'size' => 27,
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .elementor-account .elementor-button i' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .elementor-account .elementor-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_label_signed_in',
            [
                'label' => Wp_Helper::__('Signed in'),
            ]
        );

        $this->add_control(
            'account',
            [
                'label' => Wp_Helper::__('Label'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'default' => ['icon', 'firstname'],
                'multiple' => true,
                'options' => [
                    'icon' => Wp_Helper::__('Icon'),
                    'before' => Wp_Helper::__('Before'),
                    'firstname' => Wp_Helper::__('First Name'),
                    'lastname' => Wp_Helper::__('Last Name'),
                    'after' => Wp_Helper::__('After'),
                ],
            ]
        );

        $this->add_control(
            'before',
            [
                'label' => Wp_Helper::__('Before'),
                'type' => Controls_Manager::TEXT,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'account',
                            'operator' => 'contains',
                            'value' => 'before',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'after',
            [
                'label' => Wp_Helper::__('After'),
                'type' => Controls_Manager::TEXT,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'account',
                            'operator' => 'contains',
                            'value' => 'after',
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->registerNavContentControls();

        $this->add_control(
            'heading_usermenu',
            [
                'label' => Wp_Helper::__('Usermenu'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'link_to',
            [
                'label' => Wp_Helper::__('Link'),
                'type' => Controls_Manager::SELECT,
                'default' => 'identity',
                'options' => $this->getLinkToOptions(),
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label_block' => true,
                'type' => Controls_Manager::URL,
                'placeholder' => Wp_Helper::__('http://your-link.com'),
                'options' => false,
                'condition' => [
                    'link_to' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'text',
            [
                'label' => Wp_Helper::__('Text'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'selected_icon',
            [
                'label' => Wp_Helper::__('Icon'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'exclude_inline_options' => ['svg'],
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'las la-user',
                    'library' => 'line-awesome',
                ],
            ]
        );

        $this->add_control(
            'usermenu',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'link_to' => 'my-account',
                        'selected_icon' => [
                            'value' => 'far fa-user',
                            'library' => 'fa-regular',
                        ],
                    ],
                    [
                        'link_to' => 'addresses',
                        'selected_icon' => [
                            'value' => 'far fa-address-book',
                            'library' => 'fa-regular',
                        ],
                    ],
                    [
                        'link_to' => 'history',
                        'selected_icon' => [
                            'value' => 'fas fa-list',
                            'library' => 'fa-solid',
                        ],
                    ],
                    [
                        'link_to' => 'logout',
                        'selected_icon' => [
                            'value' => 'fas fa-sign-out-alt',
                            'library' => 'fa-solid',
                        ],
                    ],
                ],
                'title_field' => '<#
                    var controls = elementor.panel.currentView.currentPageView.model.get("settings").controls,
                        migrated = "undefined" !== typeof __fa4_migrated,
                        icon = "undefined" !== typeof icon ? icon : false; #>
                    <i class="{{ icon && !migrated ? icon : selected_icon.value }}"></i>
                    {{{ text || controls.usermenu.fields.link_to.options[link_to] }}}',
            ]
        );

        $this->end_controls_section();

        $this->registerNavStyleSection([
            'show_icon' => true,
            'active_condition' => [
                'hide!' => '',
            ],
            'space_between_condition' => [
                'hide!' => '',
            ],
        ]);


        // Sub Label

        $this->start_controls_section(
            'section_sublabel_style',
            [
                'label' => Wp_Helper::__('Sub Label'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sublabel_button_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .item-subtitle',
            ]
        );


        $this->add_control(
            'sublabel_button_text_color',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .item-subtitle' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'label_sub!' => '',
                ],
            ]
        );

        $this->add_control(
            'sublabel_button_hover_text_color',
            [
                'label' => Wp_Helper::__('Text Hover Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .item-subtitle:hover' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'label_sub!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'sublabel_button_padding',
            [
                'label' => Wp_Helper::__('Padding'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .item-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        // End Sub Label

        $this->registerDropdownStyleSection([
            'active_condition' => [
                'hide!' => '',
            ],
        ]);
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-nav-menu';
    }

    public function getUrl(&$item)
    {
        if ('custom' === $item['link_to']) {
            return $item['link']['url'];
        }
        if ('logout' === $item['link_to']) {
            return $this->context->link->getPageLink('index', true, null, 'mylogout');
        }

        return $this->context->link->getPageLink($item['link_to'], true);
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $customer = $this->context->customer;
        $icon = isset($settings['icon']) && !isset($settings['__fa4_migrated']['selected_icon'])
            ? $settings['icon']
            : $settings['selected_icon'];
        $this->indicator = isset($settings['indicator']) && !isset($settings['__fa4_migrated']['submenu_icon'])
            ? $settings['indicator']
            : $settings['submenu_icon']['value'];

        if ($customer->isLogged()) {
            $options = $this->getLinkToOptions();
            $account = &$settings['account'];
            $menu = [
                [
                    'id' => 0,
                    'icon' => in_array('icon', $account) ? $icon : '',
                    'label' => call_user_func(function () use ($settings, $account, $customer) {
                        $label = '';

                        in_array('before', $account) && $label .= $settings['before'];
                        in_array('firstname', $account) && $label .= " {$customer->firstname}";
                        in_array('lastname', $account) && $label .= " {$customer->lastname}";
                        in_array('after', $account) && $label .= $settings['after'];

                        return trim($label);
                    }),
                    'label_sub' => $settings['label_sub'],
                    'url' => $this->context->link->getPageLink('my-account', true),
                    'children' => [],
                ],
            ];
            foreach ($settings['usermenu'] as $i => &$item) {
                $menu[0]['children'][] = [
                    'id' => $i + 1,
                    'icon' => !empty($item['icon']) && !isset($item['__fa4_migrated']['selected_icon'])
                        ? $item['icon']
                        : $item['selected_icon'],
                    'label' => $item['text'] ?: $options[$item['link_to']],
                    'url' => $this->getUrl($item),
                ];
            }
        } else {
            $menu = [
                [
                    'id' => 0,
                    'icon' => $icon,
                    'label' => $settings['label'],
                    'label_sub' => $settings['label_sub'],
                    'url' => $this->context->link->getPageLink('my-account', true),
                    'children' => [],
                ],
            ];
        }
        $settings['show_submenu_on'] = 'click'; // default submenu on click
        $ul_class = 'ava-dropdown-wrapper' . ' ' . $settings['show_submenu_on'];

        // General Menu.
        ob_start();
        $this->accountList($menu, 0, $ul_class);
        $menu_html = ob_get_clean();

        $this->add_render_attribute('main-menu', 'class', [
            'elementor-sign-in',
            'elementor-account',
            'elementor-nav--main',
            'elementor-nav__container',
            'elementor-nav--layout-horizontal',
        ]);

        /* if ('none' !== $settings['pointer']) {
            $animation_type = self::getPointerAnimationType($settings['pointer']);

            $this->add_render_attribute('main-menu', 'class', [
                'e--pointer-' . $settings['pointer'],
                'e--animation-' . $settings[$animation_type],
            ]);
        } */
        ?>
        <nav <?php $this->print_render_attribute_string('main-menu'); ?>><?php echo $menu_html; ?></nav>
        <?php
    }

    protected function accountList(array &$nodes, $depth = 0, $ul_class = '')
    {
        $customer = $this->context->customer;
        ?>
        <div <?php echo $depth ? 'class="sub-menu ava-dropdown-menu elementor-nav--dropdown"' : 'id="usermenu-' . $this->get_id() . '" class="' . $ul_class . '"'; ?>>
        <?php foreach ($nodes as &$node) { ?>
            <?php if ($customer->isLogged()) { ?>
            <a class="<?php echo $depth ? 'elementor-sub-item' : 'elementor-item ava-dropdown-toggle btn-canvas elementor-button ava-dropdown-account'; ?>" href="<?php echo Wp_Helper::esc_attr($node['url']); ?>"   <?php echo $depth ? '' : 'data-toggle="ava-dropdown-widget"'; ?>>
            <?php } else { ?>
            <a class="<?php echo $depth ? 'elementor-sub-item' : 'elementor-item ava-dropdown-toggle btn-canvas elementor-button ava-dropdown-account'; ?>" href="<?php echo Wp_Helper::esc_attr($node['url']); ?>"   <?php echo $depth ? '' : ''; ?>>
            <?php } ?>
            <?php if ($node['icon']) { ?>
                <?php Icons_Manager::render_icon($node['icon'], ['aria-hidden' => 'true']); ?>
            <?php } ?>
            <?php if ($node['label']) { ?>
                <?php if (!empty($node['label_sub'])) { ?>
                    <div class="label-content">
                        <span class="item-subtitle"><?php echo $node['label_sub']; ?></span>
                        <span class="item-label"><?php echo $node['label']; ?></span>
                    </div>
                <?php } else { ?>
                    <span><?php echo $node['label']; ?></span>
                <?php } ?>
            <?php } ?>
            <?php if ($this->indicator && !empty($node['children'])) { ?>
                <span class="icon-toggle <?php echo Wp_Helper::esc_attr($this->indicator); ?>"></span>
            <?php } ?>
            </a>
            <?php empty($node['children']) || $this->accountList($node['children'], $depth + 1); ?>
        <?php } ?>
        </div>
        <?php
    }

    public function __construct($data = [], $args = [])
    {
        $this->context = \Context::getContext();

        parent::__construct($data, $args);
    }
}
