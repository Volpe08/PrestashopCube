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
	exit; // Exit if accessed directly.
}

/**
 * Elementor image carousel widget.
 *
 * Elementor widget that displays a set of images in a rotating carousel or
 * slider.
 *
 * @since 1.0.0
 */
class Widget_Ava_Menu extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image carousel widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ava-menu';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image carousel widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return Wp_Helper::__( 'Menu', 'elementor' );
	}
	
	public function get_categories() {
		return [ 'avanam-elements' ];
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image carousel widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-nav-menu';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'menu', 'navigation', 'avanam', 'ava' ];
	}

	/**
	 * Register Site Logo controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->register_content_controls();
	}

	/**
	 * Register Site Logo General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_content_controls() {


		
		$this->start_controls_section(
			'content_section',
			[
				'label' => Wp_Helper::__( 'Content' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'layout',
			[ 
	        	'label' => Wp_Helper::__('Menu type'),
	            'type' => Controls_Manager::SELECT,
	            'default' => 'hmenu',
	            'options' => [
	            	'hmenu' => Wp_Helper::__( 'Horizontal menu' ),
	            	'vmenu' => Wp_Helper::__( 'Vertical menu' ),
					'mobilemenu' => Wp_Helper::__( 'Mobile menu' ),
	            ],
        	]
        );
		$this->add_control(
			'm_vertical',
	        array(
				'label' => Wp_Helper::__('Show vertical menu in mobile menu'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => array(
					'layout' => 'mobilemenu'
				),
				'label_on'     => 'Yes',
				'label_off'    => 'No',
			)
		);
		if (Wp_Helper::is_admin() && \Module::getInstanceByName('avanamegamenu')) {
            /* $this->add_control(
                'hmenu_description',
                [
                    'raw' => sprintf(
                       Wp_Helper::__("Go to the <a href='%s' target='_blank'>%s module</a> to manage your menu items."),
                        \Context::getContext()->link->getAdminLink('AdminModules') . '&configure=avanamegamenu',
                       Wp_Helper::__('Avanam Megamenu')
                    ),
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-descriptor',
                    'condition' => [
                        'layout' => 'hmenu',
                    ],
                ]
            ); */


			$this->add_control(
				'hmenu_description',
				[
					'raw' => '<a href="#dsf">' . Wp_Helper::__( 'Please note!', 'elementor' ) . '</a> ' . Wp_Helper::__( 'Custom positioning is not considered best practice for responsive web design and should not be used too frequently.', 'elementor' ),
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
					'render_type' => 'ui',
					'condition' => [
						'layout' => 'hmenu',
					],
				]
			);

        } else {
            $this->add_control(
                'hmenu_description',
                [
                    'raw' => sprintf(Wp_Helper::__('%s module (%s) must be installed!'),Wp_Helper::__('Avanam Megamenu'), 'avanamegamenu'),
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                    'condition' => [
                        'layout' => 'hmenu',
                    ],
                ]
            );
        }
        if (Wp_Helper::is_admin() && \Module::getInstanceByName('avanamverticalmenu')) {
            $this->add_control(
                'vmenu_description',
                [
                    'raw' => sprintf(
                       Wp_Helper::__("Go to the <a href='%s' target='_blank'>%s module</a> to manage your menu items."),
                        \Context::getContext()->link->getAdminLink('AdminModules') . '&configure=avanamverticalmenu',
                       Wp_Helper::__('Vertical Megamenu')
                    ),
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-descriptor',
                    'condition' => [
                        'layout' => 'vmenu',
                    ],
                ]
            );
        } else {
            $this->add_control(
                'vmenu_description',
                [
                    'raw' => sprintf(Wp_Helper::__('%s module (%s) must be installed!'),Wp_Helper::__('Vertical Megamenu'), 'avanamverticalmenu'),
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                    'condition' => [
                        'layout' => 'vmenu',
                    ],
                ]
            );
        }
		$this->end_controls_section();
		// Start for style
        $this->start_controls_section(
            'section_general',
            [
                'label' => Wp_Helper::__('General'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout!' => 'mobilemenu'
				),
            ]
        );
            $this->add_control(
            'search_width',
                [
                    'label' => Wp_Helper::__('Width'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'inline',
                    'options' => [ 
                        'fullwidth' => Wp_Helper::__('Full width 100%'),
                        'inline' => Wp_Helper::__('Inline (auto)')
                    ],
                    'prefix_class' => 'pewidth-',
                    'render_type' => 'template',
                    'frontend_available' => true
                ]
            );
        $this->end_controls_section();
		$this->start_controls_section(
            'section_menu_icon',
            [
                'label' => Wp_Helper::__('Menu icon'),
                'tab' => Controls_Manager::TAB_STYLE,
				 'condition' => [
                    'layout' => 'mobilemenu',
                ],
            ]
        );
            $this->add_control(
				'icon_size',
				[
					'label' => Wp_Helper::__('Icon Size'),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'size' => 28,
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} #mobile-menu-icon i' => 'font-size: {{SIZE}}{{UNIT}}',
					],
				]
			);
			$this->add_control(
				'icon_color',
				[
					'label' => Wp_Helper::__( 'Icon Color' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} #mobile-menu-icon i' => 'fill: {{VALUE}}; color: {{VALUE}};',
					],
				]
			);
			/*$this->add_control(
				'icon_hover_color',
				[
					'label' => Wp_Helper::__( 'Icon Hover Color' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} #mobile-menu-icon i:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
					],
					'scheme' => array(
	                    'type' => Scheme_Color::get_type(),
	                    'value' => Scheme_Color::COLOR_1,
	                ),	
				]
			);*/
        $this->end_controls_section();
		$this->start_controls_section(
			'style_section',
			[
				'label' => Wp_Helper::__( 'Style' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		); 
			$this->add_group_control(
				Group_Control_Typography::get_type(), 
				[
					'name' => 'typography',
					'selector' => '{{WRAPPER}} .avaorg-menu-horizontal .menu-item > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item > a',
				]
			);


			$this->start_controls_tabs( 'tabs_style' );

				$this->start_controls_tab(
					'tab_normal',
					[
						'label' => Wp_Helper::__( 'Normal' ),
					]
				);

					$this->add_control(
						'text_color',
						[
							'label' => Wp_Helper::__( 'Text Color' ),
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .avaorg-menu-horizontal .menu-item > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item > a' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'background_color',
						[
							'label' => Wp_Helper::__( 'Background Color' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .avaorg-menu-horizontal .menu-item > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item > a' => 'background-color: {{VALUE}};',
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_hover',
					[
						'label' => Wp_Helper::__( 'Hover & Active' ),
					]
				);

					$this->add_control(
						'hover_color',
						[
							'label' => Wp_Helper::__( 'Text Color' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .avaorg-menu-horizontal .menu-item:hover > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item:hover > a,{{WRAPPER}} .avaorg-menu-horizontal .menu-item.home > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item.home > a, {{WRAPPER}} .avaorg-menu-horizontal .menu-item.active > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item.active > a' => 'color: {{VALUE}};'
							],
						]
					);

					$this->add_control(
						'background_hover_color',
						[
							'label' => Wp_Helper::__( 'Background Color' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .avaorg-menu-horizontal .menu-item:hover > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item:hover > a,{{WRAPPER}} .avaorg-menu-horizontal .menu-item.home > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item.home > a, {{WRAPPER}} .avaorg-menu-horizontal .menu-item.active > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item.active > a' => 'background-color: {{VALUE}};',
							],
						] 
					);

					$this->add_control(
						'hover_border_color',
						[
							'label' => Wp_Helper::__( 'Border Color' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .avaorg-menu-horizontal .menu-item:hover > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item:hover > a,{{WRAPPER}} .avaorg-menu-horizontal .menu-item.home > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item.home > a, {{WRAPPER}} .avaorg-menu-horizontal .menu-item.active > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item.active > a' => 'border-color: {{VALUE}};',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'border',
					'selector' => '{{WRAPPER}} .avaorg-menu-horizontal .menu-item > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item > a',
					'separator' => 'before',
				]
			);

			$this->add_control(
				'border_radius',
				[
					'label' => Wp_Helper::__( 'Border Radius' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .avaorg-menu-horizontal .menu-item > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],					
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'box_shadow',
					'selector' => '{{WRAPPER}} .avaorg-menu-horizontal .menu-item > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item > a',
				]
			);

			$this->add_responsive_control(
				'text_padding',
				[
					'label' => Wp_Helper::__( 'Padding' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .avaorg-menu-horizontal .menu-item > a, {{WRAPPER}} .avaorg-menu-vertical .menu-item > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'margin',
				[
					'label' => Wp_Helper::__( 'Margin' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .avaorg-menu-horizontal .menu-item > a , {{WRAPPER}} .avaorg-menu-vertical .menu-item > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_vertical_title',
			[
				'label' => Wp_Helper::__( 'Vertical Title' ),
				'type' => Controls_Manager::SECTION,
				'tab' => Controls_Manager::TAB_STYLE,
				'condition'    => [
					'layout' => [ 'vmenu' ],
				],
			]
		);
		
			$this->add_responsive_control(
				'title_icon_size',
				[
					'label' => Wp_Helper::__( 'Icon Size' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .avaorg-menu-vertical .title_vertical i' => 'font-size: {{SIZE}}{{UNIT}}', 
					],
					'separator' => 'before',
				]
			);		
		
			$this->add_responsive_control(
				'title_icon_size_margin',
				[
					'label' => Wp_Helper::__( 'Icon Margin Right' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .avaorg-menu-vertical .title_vertical i' => 'margin-right: {{SIZE}}{{UNIT}}',  
					]
				]
			);
			
			$this->add_responsive_control(
				'title_space_right_icon',
				[
					'label' => Wp_Helper::__( 'Icon(:before) Margin Right' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .avaorg-menu-vertical .title_vertical:before' => 'margin-right: {{SIZE}}{{UNIT}}',  
					]
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .avaorg-menu-vertical .title_vertical',
				]
			);

			$this->start_controls_tabs( 'title_tabs_style' );

				$this->start_controls_tab(
					'title_tab_normal',
					[
						'label' => Wp_Helper::__( 'Normal' ),
					]
				);

					$this->add_control(
						'title_text_color',
						[
							'label' => Wp_Helper::__( 'Text Color' ),
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .avaorg-menu-vertical .title_vertical' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'title_background_color',
						[
							'label' => Wp_Helper::__( 'Background Color' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .avaorg-menu-vertical .title_vertical' => 'background-color: {{VALUE}};',
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'title_tab_hover',
					[
						'label' => Wp_Helper::__( 'Hover & Active' ),
					]
				);

					$this->add_control(
						'title_hover_color',
						[
							'label' => Wp_Helper::__( 'Text Color' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .avaorg-menu-vertical:hover .title_vertical' => 'fill: {{VALUE}}; color: {{VALUE}};'
							],
						]
					);

					$this->add_control(
						'title_background_hover_color',
						[
							'label' => Wp_Helper::__( 'Background Color' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .avaorg-menu-vertical:hover .title_vertical' => 'background-color: {{VALUE}};', 
							],
						]
					);

					$this->add_control(
						'title_hover_border_color',
						[
							'label' => Wp_Helper::__( 'Border Color' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .avaorg-menu-vertical:hover .title_vertical' => 'border-color: {{VALUE}};',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'title_border',
					'selector' => '{{WRAPPER}} .avaorg-menu-vertical .title_vertical',
					'separator' => 'before',
				]
			);

			$this->add_control(
				'title_border_radius',
				[
					'label' => Wp_Helper::__( 'Border Radius' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .avaorg-menu-vertical .title_vertical' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					]
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'title_box_shadow',
					'selector' => '{{WRAPPER}} .avaorg-menu-vertical .title_vertical',
				]
			);

			$this->add_responsive_control(
				'title_text_padding',
				[
					'label' => Wp_Helper::__( 'Padding' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .avaorg-menu-vertical .title_vertical' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

		$this->end_controls_section();
	}
	
	/**
	 * Render Site Image output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		//$content = empty($settings['module']) ? '' : $this->_renderModule('displayIncludePageBuilder', [], $settings['module']);
		
		//echo $content;

		if (Wp_Helper::is_admin()){
			return print '<div class="ce-remote-render"></div>';
		}
		$context = \Context::getContext();
		$settings = $this->get_settings_for_display();
		if($settings['layout'] == 'hmenu'){
			if( \Module::isEnabled('avanamegamenu') ) {
				$module = \Module::getInstanceByName( 'avanamegamenu' );
				echo $module->hookDisplayMegamenu();
			}else{
				echo Wp_Helper::__('Megamenu is not active.');
			}
		}else if($settings['layout'] == 'vmenu'){
			if( \Module::isEnabled('avanamverticalmenu') ) {
				$module = \Module::getInstanceByName( 'avanamverticalmenu' );
				echo $module->hookDisplayVerticalMenu();
			}else{
				echo Wp_Helper::__('Vertical menu is not active.');
			}
		}else{
			$hmenu = $vmenu = '';
			if( \Module::isEnabled('avanamegamenu') ) {
				$module = \Module::getInstanceByName( 'avanamegamenu' );
				$hmenu = $module->hookDisplayMegamenuMobile();
			}
			if( \Module::isEnabled('avanamverticalmenu') && $settings['m_vertical'] ) {
				$module = \Module::getInstanceByName( 'avanamverticalmenu' );
				$vmenu = $module->hookDisplayVerticalMenuMobile();
			}
			$context->smarty->assign(
				array(
					'hmenu'      => $hmenu,
					'vmenu'      => $vmenu,
				)
			);
			$output = $context->smarty->fetch( 'module:avanamegamenu/views/templates/hook/menu-mobile.tpl' );			
			echo $output;
		}
	}
	
}