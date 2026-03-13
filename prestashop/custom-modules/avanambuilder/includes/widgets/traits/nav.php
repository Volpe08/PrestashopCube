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

if (!defined('_PS_VERSION_')) {
    exit;
}

trait Widget_Traits_Nav
{
    protected static $li_class = 'menu-item menu-item-type-%s menu-item-%s%s%s';

    protected $indicator = 'fa fa-angle-down';

    public function getScriptDepends()
    {
        return ['smartmenus'];
    }

    /* public static function getPointerAnimationType($pointer)
    {
        return in_array($pointer, ['framed', 'background', 'text']) ? "animation_$pointer" : 'animation_line';
    } */

    protected function registerNavContentControls(array $args = [])
    {
        $layout_options = isset($args['layout_options']) ? $args['layout_options'] : [];

        if ($layout_options) {
            $this->add_control(
                'layout',
                [
                    'label' => Wp_Helper::__('Layout'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'horizontal',
                    'options' => &$layout_options,
                    'frontend_available' => true,
                    'separator' => 'before',
                ]
            );
        } else {
            $this->add_control(
                'layout',
                [
                    'type' => Controls_Manager::HIDDEN,
                    'default' => 'horizontal',
                    'frontend_available' => true,
                ]
            );
        }

        /* $this->add_control(
            'align_items',
            [
                'label' => Wp_Helper::__('Align'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => Wp_Helper::__('Left'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => Wp_Helper::__('Center'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => Wp_Helper::__('Right'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'justify' => [
                        'title' => Wp_Helper::__('Stretch'),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                ],
                'prefix_class' => 'elementor-nav--align-',
                'condition' => [
                    'layout!' => 'dropdown',
                ],
                'separator' => $layout_options ? '' : 'before',
            ]
        ); */

       /*  $this->add_control(
            'pointer',
            [
                'label' => Wp_Helper::__('Pointer'),
                'type' => Controls_Manager::SELECT,
                'default' => 'underline',
                'options' => [
                    'none' => Wp_Helper::__('None'),
                    'underline' => Wp_Helper::__('Underline'),
                    'overline' => Wp_Helper::__('Overline'),
                    'double-line' => Wp_Helper::__('Double Line'),
                    'framed' => Wp_Helper::__('Framed'),
                    'background' => Wp_Helper::__('Background'),
                    'text' => Wp_Helper::__('Text'),
                ],
                'condition' => [
                    'layout!' => 'dropdown',
                ],
            ]
        ); */

        /* $this->add_control(
            'animation_line',
            [
                'label' => Wp_Helper::__('Animation'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'fade' => 'Fade',
                    'slide' => 'Slide',
                    'grow' => 'Grow',
                    'drop-in' => 'Drop In',
                    'drop-out' => 'Drop Out',
                    'none' => 'None',
                ],
                'condition' => [
                    'layout!' => 'dropdown',
                    'pointer' => ['underline', 'overline', 'double-line'],
                ],
            ]
        );

        $this->add_control(
            'animation_framed',
            [
                'label' => Wp_Helper::__('Animation'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'fade' => 'Fade',
                    'grow' => 'Grow',
                    'shrink' => 'Shrink',
                    'draw' => 'Draw',
                    'corners' => 'Corners',
                    'none' => 'None',
                ],
                'condition' => [
                    'layout!' => 'dropdown',
                    'pointer' => 'framed',
                ],
            ]
        );

        $this->add_control(
            'animation_background',
            [
                'label' => Wp_Helper::__('Animation'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'fade' => 'Fade',
                    'grow' => 'Grow',
                    'shrink' => 'Shrink',
                    'sweep-left' => 'Sweep Left',
                    'sweep-right' => 'Sweep Right',
                    'sweep-up' => 'Sweep Up',
                    'sweep-down' => 'Sweep Down',
                    'shutter-in-vertical' => 'Shutter In Vertical',
                    'shutter-out-vertical' => 'Shutter Out Vertical',
                    'shutter-in-horizontal' => 'Shutter In Horizontal',
                    'shutter-out-horizontal' => 'Shutter Out Horizontal',
                    'none' => 'None',
                ],
                'condition' => [
                    'layout!' => 'dropdown',
                    'pointer' => 'background',
                ],
            ]
        );

        $this->add_control(
            'animation_text',
            [
                'label' => Wp_Helper::__('Animation'),
                'type' => Controls_Manager::SELECT,
                'default' => 'grow',
                'options' => [
                    'grow' => 'Grow',
                    'shrink' => 'Shrink',
                    'sink' => 'Sink',
                    'float' => 'Float',
                    'skew' => 'Skew',
                    'rotate' => 'Rotate',
                    'none' => 'None',
                ],
                'condition' => [
                    'layout!' => 'dropdown',
                    'pointer' => 'text',
                ],
            ]
        ); */

        $submenu_condition = isset($args['submenu_condition']) ? $args['submenu_condition'] : [];

        $this->add_control(
            'submenu_icon',
            [
                'label' => Wp_Helper::__('Submenu Indicator'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'exclude_inline_options' => ['svg'],
                'fa4compatibility' => 'indicator',
                'default' => [
                    'value' => $this->indicator,
                    'library' => 'fa-solid',
                ],
                'frontend_available' => true,
                'condition' => $submenu_condition,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'submenu_icon_size',
            [
                'label' => Wp_Helper::__('Indicator Size'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					]
				],
                'size_units' => ['px', 'em'],
                'default' => [
                    'size' => 13,
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .ava-dropdown-wrapper .icon-toggle' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        if (isset($layout_options['dropdown'])) {
            $submenu_condition['layout!'] = 'dropdown';

            $this->add_control(
                'align_submenu',
                [
                    'label' => Wp_Helper::__('Submenu Align'),
                    'type' => Controls_Manager::CHOOSE,
                    'label_block' => false,
                    'options' => [
                        'left' => [
                            'title' => Wp_Helper::__('Left'),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => Wp_Helper::__('Right'),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'frontend_available' => true,
                    'condition' => $submenu_condition,
                ]
            );
        }

        /* $this->add_control(
            'show_submenu_on',
            [
                'label' => Wp_Helper::__('Show Submenu'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'hover',
                'options' => [
                    'hover' => Wp_Helper::__('On Hover'),
                    'click' => Wp_Helper::__('On Click'),
                ],
                'frontend_available' => true,
                'condition' => $submenu_condition,
            ]
        ); */

        $this->add_control(
			'dropdown_align',
			[
				'label'        => Wp_Helper::__( 'Alignment', 'elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'    => [
						'title' => Wp_Helper::__( 'Left', 'elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'  => [
						'title' => Wp_Helper::__( 'Center', 'elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'   => [
						'title' => Wp_Helper::__( 'Right', 'elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'      => 'left',
				'prefix_class' => 'ava-align-',
			]
		);

        $this->add_control(
			'dropdown_position',
			[
				'label' => Wp_Helper::__('Dropdown Position', 'elementor'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'left' => Wp_Helper::__('Left', 'elementor'),
					'right' => Wp_Helper::__('Right', 'elementor'),
				],
				'separator' => '',
				'default' => 'left',
				'prefix_class' => 'elementor-dropdown-',
			]
		);

        $this->add_control(
			'dropdown_width',
			[
				'label' => Wp_Helper::__( 'Dropdown Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 800,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .ava-dropdown-menu' => 'width: {{SIZE}}{{UNIT}}',
				]
			]
		);

    }

    protected function registerNavStyleSection(array $args = [])
    {
        $devices = isset($args['devices']) ? $args['devices'] : [
            'desktop',
            'laptop',
            'tablet',
            'mobile',
        ];

        $this->start_controls_section(
            'section_style_nav',
            [
                'label' => $this->get_title(),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => isset($args['condition']) ? $args['condition'] : [],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_typography',
                'scheme' => empty($args['scheme']) ? null : Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-nav--main',
            ]
        );

        $this->start_controls_tabs('tabs_menu_item_style');

        $this->start_controls_tab(
            'tab_menu_item_normal',
            [
                'label' => Wp_Helper::__('Normal'),
            ]
        );

        $this->add_control(
            'color_menu_item',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'scheme' => empty($args['scheme']) ? null : [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e)' => 'color: {{VALUE}}',
                ],
            ]
        );


        

        empty($args['show_icon']) || $this->add_control(
            'color_icon',
            [
                'label' => Wp_Helper::__('Icon Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-item > i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-item > svg' => 'fill: {{VALUE}}',
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'toggle_button_background_color',
            [
                'label' => Wp_Helper::__('Background Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main .elementor-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_menu_item_hover',
            [
                'label' => Wp_Helper::__('Hover'),
            ]
        );

        $this->add_control(
            'color_menu_item_hover',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'scheme' => empty($args['scheme']) ? null : [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.elementor-item-active:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.highlighted:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):hover, ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):focus' => 'color: {{VALUE}}',
                ],
                // 'condition' => [
                //     'pointer!' => 'background',
                // ],
            ]
        );

        empty($args['show_icon']) || $this->add_control(
            'color_icon_hover',
            [
                'label' => Wp_Helper::__('Icon Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-item:hover > i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-item:hover >  svg' => 'fill: {{VALUE}}',
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'toggle_button_background_color_hover',
            [
                'label' => Wp_Helper::__('Background Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main .elementor-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'color_menu_item_hover_pointer_bg',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.elementor-item-active:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.highlighted:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):hover, ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):focus' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'pointer' => 'background',
                ],
            ]
        );

        /* $this->add_control(
            'pointer_color_menu_item_hover',
            [
                'label' => Wp_Helper::__('Pointer Color'),
                'type' => Controls_Manager::COLOR,
                'scheme' => empty($args['scheme']) ? null : [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main:not(.e--pointer-framed) .elementor-item:before, ' .
                    '{{WRAPPER}} .elementor-nav--main:not(.e--pointer-framed) .elementor-item:after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .e--pointer-framed .elementor-item:before, ' .
                    '{{WRAPPER}} .e--pointer-framed .elementor-item:after' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'pointer!' => ['none', 'text'],
                ],
            ]
        ); */


        

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_menu_item_active',
            [
                'label' => Wp_Helper::__('Active'),
                'condition' => isset($args['active_condition']) ? $args['active_condition'] : [],
            ]
        );

        $this->add_control(
            'color_menu_item_active',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.elementor-item-active:not(#e)' => 'color: {{VALUE}}',
                ],
            ]
        );

        /* $this->add_control(
            'pointer_color_menu_item_active',
            [
                'label' => Wp_Helper::__('Pointer Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main:not(.e--pointer-framed) .elementor-item.elementor-item-active:before, ' .
                    '{{WRAPPER}} .elementor-nav--main:not(.e--pointer-framed) .elementor-item.elementor-item-active:after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .e--pointer-framed .elementor-item.elementor-item-active:before, ' .
                    '{{WRAPPER}} .e--pointer-framed .elementor-item.elementor-item-active:after' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'pointer!' => ['none', 'text'],
                ],
            ]
        ); */

        $this->end_controls_tab();

        $this->end_controls_tabs();

        /* $this->add_responsive_control(
            'padding_horizontal_menu_item',
            [
                'label' => Wp_Helper::__('Horizontal Padding'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'devices' => $devices,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main .elementor-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'padding_vertical_menu_item',
            [
                'label' => Wp_Helper::__('Vertical Padding'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'devices' => $devices,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main .elementor-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        ); */

        $this->add_responsive_control(
            'toggle_button_padding',
            [
                'label' => Wp_Helper::__('Padding'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main .elementor-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
				'separator' => 'before',
            ]
        );

        /* empty($args['show_icon']) || $this->add_control(
            'icon_size',
            [
                'label' => Wp_Helper::__('Icon Size'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px', 'em'],
                'default' => [
                    'size' => 27,
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .elementor-item > i' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        ); */

        $this->add_responsive_control(
            'menu_space_between',
            [
                'label' => Wp_Helper::__('Space Between'),
                'type' => Controls_Manager::SLIDER,
                'devices' => $devices,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main.elementor-nav--layout-horizontal > .elementor-nav' => 'column-gap: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .elementor-nav--main:not(.elementor-nav--layout-horizontal) > .elementor-nav li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => isset($args['space_between_condition']) ? $args['space_between_condition'] : [],
            ]
        );

        $this->add_responsive_control(
            'border_radius_menu_item',
            [
                'label' => Wp_Helper::__('Border Radius'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'devices' => $devices,
                'selectors' => [
                    '{{WRAPPER}} .elementor-item:before' => 'border-radius: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .e--animation-shutter-in-horizontal .elementor-item:before' => 'border-radius: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0',
                    '{{WRAPPER}} .e--animation-shutter-in-horizontal .elementor-item:after' => 'border-radius: {{SIZE}}{{UNIT}} 0 0 {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .e--animation-shutter-in-vertical .elementor-item:before' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0',
                    '{{WRAPPER}} .e--animation-shutter-in-vertical .elementor-item:after' => 'border-radius: 0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'pointer' => 'background',
                ],
            ]
        );

        /* $this->add_control(
            'pointer_width',
            [
                'label' => Wp_Helper::__('Pointer Width'),
                'type' => Controls_Manager::SLIDER,
                'devices' => $devices,
                'range' => [
                    'px' => [
                        'max' => 30,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .e--pointer-framed .elementor-item:before' => 'border-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .e--pointer-framed.e--animation-draw .elementor-item:before' => 'border-width: 0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .e--pointer-framed.e--animation-draw .elementor-item:after' => 'border-width: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0',
                    '{{WRAPPER}} .e--pointer-framed.e--animation-corners .elementor-item:before' => 'border-width: {{SIZE}}{{UNIT}} 0 0 {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .e--pointer-framed.e--animation-corners .elementor-item:after' => 'border-width: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0',
                    '{{WRAPPER}} .e--pointer-underline .elementor-item:after, ' .
                    '{{WRAPPER}} .e--pointer-overline .elementor-item:before, ' .
                    '{{WRAPPER}} .e--pointer-double-line .elementor-item:before, ' .
                    '{{WRAPPER}} .e--pointer-double-line .elementor-item:after' => 'height: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'pointer' => ['underline', 'overline', 'double-line', 'framed'],
                ],
            ]
        ); */

        $this->end_controls_section();
    }

    protected function registerDropdownStyleSection(array $args = [])
    {
        $this->start_controls_section(
            'section_style_dropdown',
            [
                'label' => Wp_Helper::__('Dropdown'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => isset($args['condition']) ? $args['condition'] : null,
            ]
        );

        empty($args['show_description']) || $this->add_control(
            'dropdown_description',
            [
                'raw' => Wp_Helper::__('On desktop, this will affect the submenu. On mobile, this will affect the entire menu.'),
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor',
                'separator' => 'after',
                'condition' => [
                    'layout!' => 'dropdown',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'dropdown_typography',
                'scheme' => empty($args['scheme']) ? null : Scheme_Typography::TYPOGRAPHY_4,
                'exclude' => ['line_height'],
                'selector' => '{{WRAPPER}} .elementor-nav--dropdown a:not(#e)',
            ]
        );

        $this->start_controls_tabs('tabs_dropdown_item_style');

        $this->start_controls_tab(
            'tab_dropdown_item_normal',
            [
                'label' => Wp_Helper::__('Normal'),
            ]
        );

        $this->add_control(
            'color_dropdown_item',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a:not(#e), {{WRAPPER}} .elementor-menu-toggle' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'background_color_dropdown_item',
            [
                'label' => Wp_Helper::__('Background Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dropdown_item_hover',
            [
                'label' => Wp_Helper::__('Hover'),
            ]
        );

        $this->add_control(
            'color_dropdown_item_hover',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a.highlighted:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a:not(#e):hover, ' .
                    '{{WRAPPER}} .elementor-menu-toggle:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'background_color_dropdown_item_hover',
            [
                'label' => Wp_Helper::__('Background Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a:hover, ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active, ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a.highlighted' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dropdown_item_active',
            [
                'label' => Wp_Helper::__('Active'),
                'condition' => isset($args['active_condition']) ? $args['active_condition'] : null,
            ]
        );

        $this->add_control(
            'color_dropdown_item_active',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active:not(#e)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'background_color_dropdown_item_active',
            [
                'label' => Wp_Helper::__('Background Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_border',
                'selector' => '{{WRAPPER}} .elementor-nav--dropdown',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'dropdown_border_radius',
            [
                'label' => Wp_Helper::__('Border Radius'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-nav--dropdown li:first-child a' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-nav--dropdown li:last-child a' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .elementor-nav--main .elementor-nav--dropdown, {{WRAPPER}} .elementor-nav__container.elementor-nav--dropdown',
            ]
        );

        $this->add_responsive_control(
            'padding_horizontal_dropdown_item',
            [
                'label' => Wp_Helper::__('Horizontal Padding'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-sub-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'padding_vertical_dropdown_item',
            [
                'label' => Wp_Helper::__('Vertical Padding'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-sub-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_dropdown_divider',
            [
                'label' => Wp_Helper::__('Divider'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_divider',
                'selector' => '{{WRAPPER}} .elementor-nav--dropdown a.elementor-sub-item:not(:last-child)',
                'exclude' => ['width'],
            ]
        );

        $this->add_control(
            'dropdown_divider_width',
            [
                'label' => Wp_Helper::__('Border Width'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-sub-item:not(:last-child)' => 'border-width: 0 0 {{SIZE}}{{UNIT}} 0',
                ],
                'condition' => [
                    'dropdown_divider_border!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_top_distance',
            [
                'label' => Wp_Helper::__('Distance'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
						'max' => 100,
                    ],
                ],
                'selectors' => [
                    // '{{WRAPPER}} .elementor-nav--main > .elementor-nav > li > .elementor-nav--dropdown, ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-sub-item' => 'margin-top: {{SIZE}}{{UNIT}} !important',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }
}
