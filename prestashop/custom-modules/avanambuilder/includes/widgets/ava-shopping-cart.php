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
use PrestaShop\PrestaShop\Adapter\Presenter\Cart\CartLazyArray;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Ava_Shopping_Cart extends Widget_Base {

    const REMOTE_RENDER = true;

    protected $context;

    protected $imageSize;

    public function get_name()
    {
        return 'ava-shopping-cart';
    }

    public function get_title()
    {
        return Wp_Helper::__('Shopping Cart', 'elementor' );
    }

    public function get_icon()
    {
        return 'eicon-cart';
    }

    public function get_categories()
    {
        return ['avanam-elements'];
    }

    public function get_keywords()
    {
        return [ 'shopping', 'cart', 'basket', 'bag', 'avanam' ];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_shopping_cart',
            [
                'label' => Wp_Helper::__('Shopping Cart'),
            ]
        );

        $this->add_control(
            'skin',
            [
                'label' => Wp_Helper::__('Skin'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'classic' => Wp_Helper::__('Classic'),
                    'sidebar' => Wp_Helper::__('Sidebar'),
                ],
                'default' => 'sidebar',
            ]
        );

        $this->add_control(
            'heading_toggle',
            [
                'type' => Controls_Manager::HEADING,
                'label' => Wp_Helper::__('Toggle'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_subtotal',
            [
                'label' => Wp_Helper::__('Subtotal'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => Wp_Helper::__('Show'),
                'label_off' => Wp_Helper::__('Hide'),
                'default' => 'yes',
                'prefix_class' => 'elementor-cart--show-subtotal-',
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
            'alignment',
            [
                'label' => Wp_Helper::__('Alignment'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => Wp_Helper::__('Left'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => Wp_Helper::__('Center'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => Wp_Helper::__('Right'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle' => 'justify-content: {{VALUE}}',
                ],
                /* 'condition' => [
                    '_element_width!' => 'auto',
                ], */
            ]
        );


        $this->add_control(
            'selected_icon',
            [
                'label' => Wp_Helper::__('Icon'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'fa4compatibility' => 'icon',
                'recommended' => [
                    'fa-brands' => [
                        'cart-light',
                        'cart-medium',
                        'cart-solid',
                        'trolley-light',
                        'trolley-medium',
                        'trolley-solid',
                        'trolley-bold',
                        'basket-light',
                        'basket-medium',
                        'basket-solid',
                        'bag-light',
                        'bag-medium',
                        'bag-solid',
                        'bag-rounded-o',
                        'bag-rounded',
                        'bag-trapeze-o',
                        'bag-trapeze',
                    ],
                    'fa-solid' => [
                        'bag-shopping',
                        'basket-shopping',
                        'cart-shopping',
                    ],
                ],
                'default' => [
                    'value' => 'las la-shopping-cart',
                    'library' => 'line-awesome',
                ],
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
                'prefix_class' => 'elementor-cart--align-icon-',
                'condition' => [
                    'show_subtotal!' => '',
                ],
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
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-button' => 'gap: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'show_subtotal!' => '',
                ],
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
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-cart-icon i' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-cart-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'items_indicator',
            [
                'label' => Wp_Helper::__('Items Indicator'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => Wp_Helper::__('None'),
                    'bubble' => Wp_Helper::__('Bubble'),
                    // 'plain' => Wp_Helper::__('Plain'),
                ],
                'prefix_class' => 'elementor-cart--items-indicator-',
                'default' => 'bubble',
            ]
        );

        $this->add_control(
            'hide_empty_indicator',
            [
                'label' => Wp_Helper::__('Hide Empty'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'hide',
                'prefix_class' => 'elementor-cart--empty-indicator-',
                'condition' => [
                    'items_indicator!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'heading_atc_action',
            [
                'type' => Controls_Manager::HEADING,
                'label' => Wp_Helper::__('Add to Cart Action'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'action_show_modal',
            [
                'label' => Wp_Helper::__('Show Modal'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => Wp_Helper::__('On'),
                'label_off' => Wp_Helper::__('Off'),
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'action_open_cart',
            [
                'label' => Wp_Helper::__('Open Shopping Cart'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => Wp_Helper::__('On'),
                'label_off' => Wp_Helper::__('Off'),
                'frontend_available' => true,
                'condition' => [
                    'skin' => 'sidebar',
                ],
            ]
        );

        $this->add_control(
            'modal_url',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => $this->context->link->getModuleLink('avanambuilder', 'ajax', [], true),
                'condition' => [
                    'action_show_modal!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_sidebar',
            [
                'label' => Wp_Helper::__('Sidebar'),
                'condition' => [
                    'skin' => 'sidebar',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => Wp_Helper::__('Title'),
                'type' => Controls_Manager::TEXT,
                'default' => Wp_Helper::__('Shopping Cart'),
            ]
        );

        $this->add_responsive_control(
            'title_display',
            [
                'label' => Wp_Helper::__('Title Display'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					]
				],
                'size_units' => ['px', 'em'],
                'default' => [
                    'size' => 20,
					'unit' => 'px',
				],
				'laptop_default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__title' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'empty_message',
            [
                'label' => Wp_Helper::__('Empty Message'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => Wp_Helper::__('No items in your cart. Go on, fill it up with something you love!'),
            ]
        );

        $this->add_control(
            'remove_item_icon',
            [
                'label' => Wp_Helper::__('Remove Item Icon'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'exclude_inline_options' => ['svg'],
                'default' => [
                    'value' => 'far fa-times-circle',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-brands' => [
                        'delete-left',
                        'close',
                        'times',
                    ],
                    'fa-solid' => [
                        'trash-alt',
                        'trash',
                        'times-circle',
                        'minus',
                        'eraser',
                    ],
                    'fa-regular' => [
                        'times-circle',
                        'trash-alt',
                        'eraser',
                    ],
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'show_shipping',
            [
                'label' => Wp_Helper::__('Shipping Price'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => Wp_Helper::__('Show'),
                'label_off' => Wp_Helper::__('Hide'),
                'default' => 'yes',
                'prefix_class' => 'elementor-cart--show-shipping-',
            ]
        );

        $this->add_control(
            'heading_buttons',
            [
                'type' => Controls_Manager::HEADING,
                'label' => Wp_Helper::__('Buttons'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_view_cart',
            [
                'label' => Wp_Helper::__('View Cart'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => Wp_Helper::__('Show'),
                'label_off' => Wp_Helper::__('Hide'),
                'prefix_class' => 'elementor-cart--show-view-cart-',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'view_cart',
            [
                'label' => Wp_Helper::__('Text'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => Wp_Helper::__('View Cart'),
                'condition' => [
                    'show_view_cart!' => '',
                ],
            ]
        );

        $this->add_control(
            'show_checkout',
            [
                'label' => Wp_Helper::__('Checkout'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => Wp_Helper::__('Show'),
                'label_off' => Wp_Helper::__('Hide'),
                'prefix_class' => 'elementor-cart--show-checkout-',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'checkout',
            [
                'label' => Wp_Helper::__('Text'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => Wp_Helper::__('Checkout'),
                'condition' => [
                    'show_checkout!' => '',
                ],
            ]
        );

        $this->add_control(
            'show_shopping_text',
            [
                'label' => Wp_Helper::__('Shopping Now'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => Wp_Helper::__('Show'),
                'label_off' => Wp_Helper::__('Hide'),
                'prefix_class' => 'elementor-cart--show-shopping-now-',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'shopping_text',
            [
                'label' => Wp_Helper::__('Text'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => Wp_Helper::__('Start Shopping Now'),
                'condition' => [
                    'show_shopping_text!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_toggle_style',
            [
                'label' => Wp_Helper::__('Toggle'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'toggle_button_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-cart__toggle .elementor-button',
            ]
        );

        $this->start_controls_tabs('toggle_button_colors');

        $this->start_controls_tab('toggle_button_normal_colors', ['label' => Wp_Helper::__('Normal')]);

        $this->add_control(
            'toggle_button_text_color',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle a.elementor-button:not(#e)' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_subtotal!' => '',
                ],
            ]
        );

        $this->add_control(
            'toggle_button_icon_color',
            [
                'label' => Wp_Helper::__('Icon Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-cart-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-cart-icon svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'toggle_button_background_color',
            [
                'label' => Wp_Helper::__('Background Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        /* $this->add_control(
            'toggle_button_border_color',
            [
                'label' => Wp_Helper::__('Border Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-button' => 'border-color: {{VALUE}}',
                ],
            ]
        ); */

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'toggle_button_border',
				'selector' => '{{WRAPPER}} .elementor-cart__toggle .elementor-button',
				'separator' => 'before',
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab('toggle_button_hover_colors', ['label' => Wp_Helper::__('Hover')]);

        $this->add_control(
            'toggle_button_hover_text_color',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle a.elementor-button:not(#e):hover' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_subtotal!' => '',
                ],
            ]
        );

        $this->add_control(
            'toggle_button_hover_icon_color',
            [
                'label' => Wp_Helper::__('Icon Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-button:hover .elementor-cart-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-button:hover .elementor-cart-icon svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'toggle_button_hover_background_color',
            [
                'label' => Wp_Helper::__('Background Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        /* $this->add_control(
            'toggle_button_hover_border_color',
            [
                'label' => Wp_Helper::__('Border Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-button:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        ); */

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'toggle_button_border_hover',
				'selector' => '{{WRAPPER}} .elementor-cart__toggle .elementor-button:hover',
				'separator' => 'before',
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        /* $this->add_control(
            'toggle_button_border_width',
            [
                'label' => Wp_Helper::__('Border Width'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 20,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-button' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );*/

        $this->add_control(
            'toggle_button_border_radius',
            [
                'label' => Wp_Helper::__('Border Radius'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        ); 

        $this->add_responsive_control(
            'toggle_button_padding',
            [
                'label' => Wp_Helper::__('Padding'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'items_indicator_style',
            [
                'type' => Controls_Manager::HEADING,
                'label' => Wp_Helper::__('Items Indicator'),
                'separator' => 'before',
                'condition' => [
                    'items_indicator!' => 'none',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'items_indicator_style_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-cart__toggle .elementor-cart-icon[data-counter]:before',
            ]
        );

        $this->add_control(
            'items_indicator_text_color',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-cart-icon[data-counter]:before' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'items_indicator!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'items_indicator_background_color',
            [
                'label' => Wp_Helper::__('Background Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-cart-icon[data-counter]:before' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'items_indicator' => 'bubble',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_indicator_distance_top',
            [
                'label' => Wp_Helper::__('Top Distance'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'em',
                ],
                'range' => [
                    'em' => [
                        'min' => -4,
                        'max' => 4,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-cart-icon[data-counter]:before' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'items_indicator' => 'bubble',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_indicator_distance_right',
            [
                'label' => Wp_Helper::__('Right Distance'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'em',
                ],
                'range' => [
                    'em' => [
                        'min' => -4,
                        'max' => 4,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-cart-icon[data-counter]:before' => 'right: calc(0em - {{SIZE}}{{UNIT}})',
                ],
                'condition' => [
                    'items_indicator' => 'bubble',
                ],
            ]
        );

        $this->end_controls_section();

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
                'selector' => '{{WRAPPER}} .elementor-cart__toggle .elementor-button .item-subtitle',
            ]
        );


        $this->add_control(
            'sublabel_button_text_color',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle a.elementor-button:not(#e) .item-subtitle' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'label_sub!' => '',
                ],
            ]
        );

        $this->add_control(
            'sublabel_button_hover_text_color',
            [
                'label' => Wp_Helper::__('Hover Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__toggle a.elementor-button:not(#e) .item-subtitle:hover' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .elementor-cart__toggle .elementor-button .item-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        // End Sub Label



        $this->start_controls_section(
            'section_cart_style',
            [
                'label' => Wp_Helper::__('Sidebar'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'skin' => 'sidebar',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'cart_background',
                'selector' => '{{WRAPPER}} .elementor-cart__main',
            ]
        );

        $this->add_control(
            'lightbox_color',
            [
                'label' => Wp_Helper::__('Overlay Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__container' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'lightbox_ui_color',
            [
                'label' => Wp_Helper::__('Close Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__close-button, {{WRAPPER}} .elementor-cart__product-remove' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'lightbox_ui_color_hover',
            [
                'label' => Wp_Helper::__('Close Hover Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__close-button:hover, {{WRAPPER}} .elementor-cart__product-remove:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'heading_title_style',
            [
                'type' => Controls_Manager::HEADING,
                'label' => Wp_Helper::__('Title'),
                'separator' => 'before',
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => Wp_Helper::__('Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__title' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-cart__title',
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->add_control(
            'heading_empty_message_style',
            [
                'type' => Controls_Manager::HEADING,
                'label' => Wp_Helper::__('Empty Message'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'empty_message_color',
            [
                'label' => Wp_Helper::__('Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__empty-message' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'empty_message_typography',
                'selector' => '{{WRAPPER}} .elementor-cart__empty-message',
            ]
        );

        $this->add_control(
            'heading_product_divider_style',
            [
                'type' => Controls_Manager::HEADING,
                'label' => Wp_Helper::__('Divider'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'divider_style',
            [
                'label' => Wp_Helper::__('Style'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => Wp_Helper::__('Default'),
                    'none' => Wp_Helper::__('None'),
                    'solid' => Wp_Helper::__('Solid'),
                    'double' => Wp_Helper::__('Double'),
                    'dotted' => Wp_Helper::__('Dotted'),
                    'dashed' => Wp_Helper::__('Dashed'),
                    'groove' => Wp_Helper::__('Groove'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__product:not(:last-of-type), {{WRAPPER}} .elementor-cart__products, {{WRAPPER}} .elementor-cart__summary' => 'border-bottom-style: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'divider_color',
            [
                'label' => Wp_Helper::__('Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__product:not(:last-of-type), {{WRAPPER}} .elementor-cart__products, {{WRAPPER}} .elementor-cart__summary' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'divider_style!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'divider_width',
            [
                'label' => Wp_Helper::__('Weight'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__product:not(:last-of-type), {{WRAPPER}} .elementor-cart__products, {{WRAPPER}} .elementor-cart__summary' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'divider_style!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'divider_gap',
            [
                'label' => Wp_Helper::__('Spacing'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__product, {{WRAPPER}} .elementor-cart__footer-buttons, {{WRAPPER}} .elementor-cart__summary' => 'padding-bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .elementor-cart__product:not(:first-of-type), {{WRAPPER}} .elementor-cart__footer-buttons, {{WRAPPER}} .elementor-cart__summary' => 'padding-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_product_tabs_style',
            [
                'label' => Wp_Helper::__('Products'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'skin' => 'sidebar',
                ],
            ]
        );

        $this->add_control(
            'heading_product_image_style',
            [
                'type' => Controls_Manager::HEADING,
                'label' => Wp_Helper::__('Product Image'),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'product_image_border',
                'selector' => '{{WRAPPER}} .elementor-cart__product-image img',
            ]
        );

        $this->add_responsive_control(
            'product_image_border_radius',
            [
                'label' => Wp_Helper::__('Border Radius'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__product-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'product_image_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .elementor-cart__product-image img',
            ]
        );

        $this->add_control(
            'heading_product_title_style',
            [
                'type' => Controls_Manager::HEADING,
                'label' => Wp_Helper::__('Product Title'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'product_title_color',
            [
                'label' => Wp_Helper::__('Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__product-name a:not(#e)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'product_title_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-cart__product-name a',
            ]
        );

        $this->add_control(
            'heading_product_attr_style',
            [
                'type' => Controls_Manager::HEADING,
                'label' => Wp_Helper::__('Product Attributes'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'product_attr_color',
            [
                'label' => Wp_Helper::__('Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__product-attrs' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'product_attr_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-cart__product-attr',
            ]
        );

        $this->add_control(
            'heading_product_price_style',
            [
                'type' => Controls_Manager::HEADING,
                'label' => Wp_Helper::__('Product Price'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'product_price_color',
            [
                'label' => Wp_Helper::__('Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__product-price' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'product_price_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-cart__product-price',
            ]
        );

        $this->add_control(
            'remove_icon_style',
            [
                'type' => Controls_Manager::HEADING,
                'label' => Wp_Helper::__('Remove Item Icon'),
                'separator' => 'before',
                'condition' => [
                    'remove_item_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'remove_icon_color',
            [
                'label' => Wp_Helper::__('Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__product-remove' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'remove_item_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'remove_icon_size',
            [
                'label' => Wp_Helper::__('Size'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px', 'em'],
                'default' => [
                    'size' => 18,
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__product-remove' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'remove_item_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_summary',
            [
                'label' => Wp_Helper::__('Summary'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'skin' => 'sidebar',
                ],
            ]
        );

        $this->add_control(
            'subtotal_color',
            [
                'label' => Wp_Helper::__('Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__summary' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtotal_typography',
                'selector' => '{{WRAPPER}} .elementor-cart__summary',
            ]
        );

        $this->add_control(
            'heading_total_style',
            [
                'type' => Controls_Manager::HEADING,
                'label' => Wp_Helper::__('Total'),
                'separator' => 'before',
                'condition' => [
                    'show_shipping!' => '',
                ],
            ]
        );

        $this->add_control(
            'total_color',
            [
                'label' => Wp_Helper::__('Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__summary strong' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_shipping!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'total_typography',
                'selector' => '{{WRAPPER}} .elementor-cart__summary strong',
                'condition' => [
                    'show_shipping!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_buttons',
            [
                'label' => Wp_Helper::__('Buttons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'skin' => 'sidebar',
                ],
            ]
        );

        $this->add_control(
            'buttons_layout',
            [
                'label' => Wp_Helper::__('Layout'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'inline' => Wp_Helper::__('Inline'),
                    'stacked' => Wp_Helper::__('Stacked'),
                ],
                'default' => 'inline',
                'prefix_class' => 'elementor-cart--buttons-',
            ]
        );

        $this->add_control(
            'space_between_buttons',
            [
                'label' => Wp_Helper::__('Space Between'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__footer-buttons' => 'grid-column-gap: {{SIZE}}{{UNIT}}; grid-row-gap: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'show_view_cart!' => '',
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => Wp_Helper::__('Border Radius'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cart__footer-buttons .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'product_buttons_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-cart__footer-buttons .elementor-button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'heading_view_cart_style',
            [
                'type' => Controls_Manager::HEADING,
                'label' => Wp_Helper::__('View Cart'),
                'separator' => 'before',
                'condition' => [
                    'show_view_cart!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'view_cart_border',
                'selector' => '{{WRAPPER}} .elementor-button--view-cart',
                'condition' => [
                    'show_view_cart!' => '',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_view_cart_style',
            [
                'condition' => [
                    'show_view_cart!' => '',
                ],
            ]
        );

        $this->start_controls_tab(
            'tabs_view_cart_normal',
            [
                'label' => Wp_Helper::__('Normal'),
            ]
        );

        $this->add_control(
            'view_cart_text_color',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button--view-cart:not(#e)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'view_cart_background_color',
            [
                'label' => Wp_Helper::__('Background Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button--view-cart' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_view_cart_hover',
            [
                'label' => Wp_Helper::__('Hover'),
            ]
        );

        $this->add_control(
            'view_cart_text_color_hover',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button--view-cart:not(#e):hover, {{WRAPPER}} a.elementor-button--view-cart:not(#e):focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'view_cart_background_color_hover',
            [
                'label' => Wp_Helper::__('Background Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button--view-cart:hover, {{WRAPPER}} .elementor-button--view-cart:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'view_cart_border_color_hover',
            [
                'label' => Wp_Helper::__('Border Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button--view-cart:hover, {{WRAPPER}} .elementor-button--view-cart:focus' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'view_cart_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'heading_checkout_style',
            [
                'type' => Controls_Manager::HEADING,
                'label' => Wp_Helper::__('Checkout'),
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'checkout_border',
                'selector' => '{{WRAPPER}} .elementor-button--checkout',
            ]
        );

        $this->start_controls_tabs('tabs_checkout_style');

        $this->start_controls_tab(
            'tabs_checkout_normal',
            [
                'label' => Wp_Helper::__('Normal'),
            ]
        );

        $this->add_control(
            'checkout_text_color',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button--checkout:not(#e)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkout_background_color',
            [
                'label' => Wp_Helper::__('Background Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button--checkout' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_checkout_hover',
            [
                'label' => Wp_Helper::__('Hover'),
            ]
        );

        $this->add_control(
            'checkout_text_color_hover',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button--checkout:not(#e):hover, {{WRAPPER}} a.elementor-button--checkout:not(#e):focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkout_background_color_hover',
            [
                'label' => Wp_Helper::__('Background Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button--checkout:hover, {{WRAPPER}} .elementor-button--checkout:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkout_border_color_hover',
            [
                'label' => Wp_Helper::__('Border Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button--checkout:hover, {{WRAPPER}} .elementor-button--checkout:focus' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'checkout_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $controller = $this->context->controller;
        $cart_is_hidden = 'sidebar' !== $settings['skin']
            || $controller instanceof \CartController
            || $controller instanceof \OrderController
            || $controller instanceof \OrderConfirmationController;
        $cart = $controller->cart_presenter->present($this->context->cart, true);
        $toggle_button_link = $this->context->link->getPageLink('cart', true, null, ['action' => 'show'], false, null, true);
        $toggle_button_classes = 'elementor-button btn-canvas ' . ($cart_is_hidden ? ' elementor-cart-hidden' : '');
        $shopping_link = $this->context->link->getPageLink('index',true);

        if (!$cart_is_hidden) { ?>
            <div class="cart_overlay"></div>
            <div class="elementor-cart__container elementor-lightbox">
                <div class="elementor-cart__main">

                    <div class="elementor-cart__title <?php //echo Wp_Helper::esc_attr($display_class); ?>">
                        <?php echo $settings['title']; ?>
                    </div>

                    <div class="elementor-cart__close-button eicon-close"></div>
                    
                    <div class="elementor-cart__empty-message <?php $cart['products'] && print ' elementor-hidden'; ?>">
                        <span><?php echo $settings['empty_message']; ?></span>
                        <a href="<?php echo Wp_Helper::esc_attr($shopping_link); ?>" class="btn btn-primary elementor-button--shopping-text">
                            <span class="elementor-button-text"><?php echo !empty($settings['shopping_text']) ? $settings['shopping_text'] : Wp_Helper::__('Start Shopping Now'); ?></span>
                        </a>
                    </div>
                    
                    <div class="elementor-cart__inner<?php !$cart['products'] && print ' elementor-hidden'; ?>">
                        <?php $this->renderCartContent($cart, $settings, $toggle_button_link); ?>
                    </div>
                </div>
            </div><?php
        } ?>
        <div class="elementor-cart__toggle">
            <a href="<?php echo Wp_Helper::esc_attr($toggle_button_link); ?>" class="<?php echo $toggle_button_classes; ?>">
                <span class="elementor-cart-icon elementor-icon" data-counter="<?php //echo (int) $cart['products_count']; ?>">
       
                    <?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                    <span class="elementor-screen-only"><?php Wp_Helper::_e('Shopping Cart'); ?></span>
                </span>
                <?php if (!empty($settings['label_sub'])) { ?>
                    <div class="label-content">
                    <span class="item-subtitle"><?php echo $settings['label_sub']; ?></span>
                    <span class="elementor-button-text"><?php //echo $cart['subtotals']['products']['value']; ?></span>
                </div>
                <?php } else { ?>
                    <span class="elementor-button-text"><?php //echo $cart['subtotals']['products']['value']; ?></span>
                <?php } ?>
            </a>
        </div>
        <?php
    }

    protected function renderCartContent(CartLazyArray &$cart, array &$settings, $view_cart_link)
    {
        $checkout_link = $this->context->smarty->tpl_vars['urls']->value['pages']['order'];
        $checkout_disabled = $cart['minimalPurchaseRequired'] || !$cart['products'] ? ' av-disabled' : '';
        ?>
        
        <div class="elementor-cart__products the-scrollbar--auto" data-gift="<?php Wp_Helper::esc_attr_e('Gift'); ?>">
            <?php
            foreach ($cart['products'] as $product) {
                $this->renderCartItem($product, $settings);
            } ?>
        </div>
        <div class="elementor-cart__footer">
        <div class="elementor-cart__summary">
            <div class="elementor-cart__summary-label"><?php echo $cart['summary_string']; ?></div>
            <div class="elementor-cart__summary-value"><?php echo $cart['subtotals']['products']['value']; ?></div>
        <?php if ($cart['subtotals']['discounts']) { ?>
            <div class="elementor-cart__summary-label"><?php echo $cart['subtotals']['discounts']['label']; ?></div>
            <div class="elementor-cart__summary-value">-<?php echo $cart['subtotals']['discounts']['value']; ?></div>
        <?php } ?>
            <span class="elementor-cart__summary-label"><?php echo $cart['subtotals']['shipping']['label']; ?></span>
            <span class="elementor-cart__summary-value"><?php echo $cart['subtotals']['shipping']['value']; ?></span>
            <strong class="elementor-cart__summary-label"><?php echo $cart['totals']['total']['label']; ?></strong>
            <strong class="elementor-cart__summary-value"><?php echo $cart['totals']['total']['value']; ?></strong>
        </div>
        <div class="elementor-alert elementor-alert-warning<?php $cart['minimalPurchaseRequired'] || print ' elementor-hidden'; ?>" role="alert">
            <span class="elementor-alert-description"><?php echo $cart['minimalPurchaseRequired']; ?></span>
        </div>
        <div class="elementor-cart__footer-buttons">
            <div class="elementor-align-justify">
                <a href="<?php echo Wp_Helper::esc_attr($view_cart_link); ?>" class="btn btn-primary elementor-button elementor-button--view-cart">
                    <span class="elementor-button-text"><?php echo !empty($settings['view_cart']) ? $settings['view_cart'] : Wp_Helper::__('View Cart'); ?></span>
                </a>
            </div>
            <div class="elementor-align-justify">
                <a href="<?php echo Wp_Helper::esc_attr($checkout_link); ?>" class="btn btn-secondary elementor-button elementor-button--checkout">
                    <span class="elementor-button-text"><?php echo !empty($settings['checkout']) ? $settings['checkout'] : Wp_Helper::__('Checkout'); ?></span>
                </a>
            </div>
        </div>
        </div>
        <?php
        echo \Hook::exec('displayHookShoppingCartFooter');
    }

    protected function renderCartItem($product, array &$settings)
    {        
        $cover = $product['default_image'] ?? $product['cover'] ?? null;        
        if (is_array($cover)) {
            $cover_image = $cover['bySize'][$this->imageSize] ?? $cover['small'] ?? null;
        }

        if (empty($cover_image)) {
            $id_lang = (int) \Context::getContext()->language->id;
            $cover_image['url'] = \Context::getContext()->link->getImageLink(
                'default',
                \Language::getIsoById($id_lang) . '-default',
                $this->imageSize
            );
        }
        
        ?>

        <div class="elementor-cart__product">
            <div class="elementor-cart__product-image">
                <img src="<?php echo Wp_Helper::esc_attr($cover_image['url']); ?>" <?php if (is_array($cover)) {?> alt="<?php echo Wp_Helper::esc_attr($cover['legend']); ?>" <?php } ?>>
            </div>
            <div class="elementor-cart__product-name">
                <a href="<?php echo Wp_Helper::esc_attr($product['url']); ?>">
                    <?php echo $product['name']; ?>
                </a>
                <div class="elementor-cart__product-attrs">
                <?php foreach ($product['attributes'] as $attribute => $value) { ?>
                  <div class="elementor-cart__product-attr">
                    <span class="elementor-cart__product-attr-label"><?php echo $attribute; ?>:</span>
                    <span class="elementor-cart__product-attr-value"><?php echo $value; ?></span>
                  </div>
                <?php } ?>
                <?php foreach ($product['customizations'] as $customization) { ?>
                    <?php foreach ($customization['fields'] as &$field) { ?>
                        <div class="elementor-cart__product-attr">
                            <span class="elementor-cart__product-attr-label"><?php echo $field['label']; ?>:</span>
                            <span class="elementor-cart__product-attr-value">
                            <?php if ('image' === $field['type']) { ?>
                                <img src="<?php echo $field['image']['small']['url']; ?>" alt="">
                            <?php } elseif ('text' === $field['type']) { ?>
                                <?php echo $field['text']; ?>
                            <?php }  ?>
                            </span>
                        </div>
                    <?php } ?>
                <?php } ?>
                </div>
            </div>
            <div class="elementor-cart__product-price">
                <span class="elementor-cart__product-quantity"><?php echo $product['quantity']; ?></span> &times; <?php echo $product['is_gift'] ? Wp_Helper::__('Gift') : $product['price']; ?>
            <?php if ($product['has_discount']) { ?>
                <del><?php echo $product['regular_price']; ?></del>
            <?php } ?>
            </div>
        <?php if (!empty($settings['remove_item_icon']['value'])) { ?>
            <i class="elementor-cart__product-remove <?php echo Wp_Helper::esc_attr($settings['remove_item_icon']['value']); ?>">
                <a href="<?php echo Wp_Helper::esc_attr($product['remove_from_cart_url']); ?>" rel="nofollow"
                    data-id-product="<?php echo (int) $product['id_product']; ?>"
                    data-id-product-attribute="<?php echo (int) $product['id_product_attribute']; ?>"
                    data-id-customization="<?php echo (int) $product['id_customization']; ?>"
                    title="<?php Wp_Helper::esc_attr_e('Remove this item'); ?>"></a>
            </i>
        <?php } ?>
        </div>
        <?php
    }

    public function renderPlainContent()
    {
    }

    public function __construct($data = [], $args = [])
    {
        $this->context = \Context::getContext();
        $this->imageSize = \ImageType::getFormattedName('cart');

        parent::__construct($data, $args);
    }
}