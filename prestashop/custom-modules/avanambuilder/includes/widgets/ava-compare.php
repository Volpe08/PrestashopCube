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
class Widget_Ava_Compare extends Widget_Base {

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
		return 'ava-compare';
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
		return Wp_Helper::__( 'Compare', 'elementor' );
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
		return 'eicon-sync';
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
		return [ 'compare', 'ava' ];
	}

	/**
	 * Register Site Logo controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->register_content_controls();
		$this->register_styling_controls();
	}

	/**
	 * Register Site Logo General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_content_controls() {
		
		$this->start_controls_section(
			'section_general_fields',
			[
				'label' => Wp_Helper::__('Layout', 'elementor'),
			]
		);
		
		$this->add_control(
			'button_layout',
			[
				'label' => Wp_Helper::__( 'Button Layout', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon' => Wp_Helper::__( 'Icon', 'elementor' ),
					'text' => Wp_Helper::__( 'Text', 'elementor' ),
					'icon_text' => Wp_Helper::__( 'Icon & Text', 'elementor' ),
				],
				'prefix_class' => 'button-layout-',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => Wp_Helper::__('Alignment', 'elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => Wp_Helper::__('Left', 'elementor'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => Wp_Helper::__('Center', 'elementor'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => Wp_Helper::__('Right', 'elementor'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			]
		);

		
		$this->add_control(
			'selected_icon',
			[
				'label' => Wp_Helper::__('Button Icon', 'elementor'),
				'label_block' => false,
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'fa4compatibility' => 'icon',
				'default' => [
                    'value' => 'la la-exchange-alt',
                    'library' => 'line-awesome',
                ],
				'condition' => [
					'button_layout!' => 'text',
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
                'prefix_class' => 'elementor-compare--align-icon-',
                'condition' => [
                    'button_layout' => 'icon_text',
                ],
            ]
        );


		$this->add_responsive_control(
			'button_icon_spacing',
			[
				'label' => Wp_Helper::__( 'Icon Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-compare .btn-canvas' => 'gap: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .btn-canvas .btn-canvas-text' => 'margin: 0',                    
				],
				
			]
		);

		$this->add_responsive_control(
			'button_icon_size',
			[
				'label' => Wp_Helper::__( 'Icon Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					]
				],
				'default' => [
                    'size' => 24,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .btn-canvas i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .btn-canvas svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'button_layout!' => 'text',
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
                'prefix_class' => 'elementor-compare--items-indicator-',
                'default' => 'bubble',
            ]
        );

        $this->add_control(
            'hide_empty_indicator',
            [
                'label' => Wp_Helper::__('Hide Empty'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'hide',
                'prefix_class' => 'elementor-compare--empty-indicator-',
                'condition' => [
                    'items_indicator!' => 'none',
                ],
            ]
        );

		$this->add_control(
			'view',
			[
				'label' => Wp_Helper::__('View', 'elementor'),
				'type' => Controls_Manager::HIDDEN,
			]
		);

		$this->end_controls_section();
	}
	/**
	 * Register Site Image Style Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_styling_controls() {
		
		$this->start_controls_section(
			'section_button',
			[
				'label' => Wp_Helper::__( 'Style', 'elementor' ),
				'type' => Controls_Manager::SECTION,
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
			/* $this->add_control(
				'button_icon_size',
				[
					'label' => Wp_Helper::__( 'Icon Size', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .btn-canvas i' => 'font-size: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .btn-canvas svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					],
					'separator' => 'before',
					'condition' => [
						'button_layout!' => 'text',
					],
				]
			);	
		
			$this->add_control(
				'button_icon_size_margin',
				[
					'label' => Wp_Helper::__( 'Icon Margin Right', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'body:not(.rtl) {{WRAPPER}} .btn-canvas .btn-canvas-text' => 'margin-left: {{SIZE}}{{UNIT}}',
						'body.rtl {{WRAPPER}} .btn-canvas .btn-canvas-text' => 'margin-left: {{SIZE}}{{UNIT}}',
					]
				]
			); */
		
			/* 
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'button_typography',
					'selector' => '{{WRAPPER}} .btn-canvas',
					'condition' => [
						'button_layout!' => 'icon',
					],
				]
			);
				
			$this->add_control(
				'count_top',
				[
					'label' => Wp_Helper::__( 'Count Possition Top', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => -100,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .btn-canvas .compare-count' => 'top: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'before',
				]
			);	
		
			$this->add_control(
				'count_right',
				[
					'label' => Wp_Helper::__( 'Count Possition Right', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => -100,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .btn-canvas .compare-count' => 'right: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'before',
				]
			);	
		
			$this->add_control(
				'count_size',
				[
					'label' => Wp_Helper::__( 'Count Size', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .btn-canvas .compare-count' => 'min-width: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'before',
				]
			);	
		
			$this->add_control(
				'count_font_size',
				[
					'label' => Wp_Helper::__( 'Count Font Size', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .btn-canvas .compare-count' => 'font-size: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'before',
				]
			);	
		
			$this->add_control(
				'count_text_color',
				[
					'label' => Wp_Helper::__( 'Count Text Color', 'elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .btn-canvas .compare-count' => 'fill: {{VALUE}}; color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'count_background_color',
				[
					'label' => Wp_Helper::__( 'Count Background Color', 'elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .btn-canvas .compare-count' => 'background-color: {{VALUE}};',
					],
				]
			);
 */
			/* $this->start_controls_tabs( 'title_tabs_style' );

				$this->start_controls_tab(
					'button_tab_normal',
					[
						'label' => Wp_Helper::__( 'Normal', 'elementor' ),
					]
				);

					$this->add_control(
						'button_text_color',
						[
							'label' => Wp_Helper::__( 'Text Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .btn-canvas' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'button_background_color',
						[
							'label' => Wp_Helper::__( 'Background Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-canvas' => 'background-color: {{VALUE}};',
							],
						]
					);
		
				$this->end_controls_tab();

				$this->start_controls_tab(
					'button_tab_hover',
					[
						'label' => Wp_Helper::__( 'Hover', 'elementor' ),
					]
				);

					$this->add_control(
						'button_hover_color',
						[
							'label' => Wp_Helper::__( 'Text Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-canvas:hover' => 'fill: {{VALUE}}; color: {{VALUE}};'
							],
						]
					);

					$this->add_control(
						'button_background_hover_color',
						[
							'label' => Wp_Helper::__( 'Background Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-canvas:hover' => 'background-color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'button_hover_border_color',
						[
							'label' => Wp_Helper::__( 'Border Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-canvas:hover' => 'border-color: {{VALUE}};',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs(); */

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'form_typography',
					'selector' => '{{WRAPPER}} .btn-canvas-compare .btn-canvas-text',
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
						'{{WRAPPER}} .elementor-compare a.elementor-button:not(#e)' => 'color: {{VALUE}}',
					],
					'condition' => [
						'button_layout!' => 'icon',
					],
				]
			);
	
			$this->add_control(
				'toggle_button_icon_color',
				[
					'label' => Wp_Helper::__('Icon Color'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-compare .elementor-compare-icon i' => 'color: {{VALUE}}',
						'{{WRAPPER}} .elementor-compare .elementor-compare-icon svg' => 'fill: {{VALUE}}',
					],
				]
			);
	
			$this->add_control(
				'toggle_button_background_color',
				[
					'label' => Wp_Helper::__('Background Color'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-compare .elementor-button' => 'background-color: {{VALUE}}',
					],
				]
			);
	
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'toggle_button_border',
					'selector' => '{{WRAPPER}} .elementor-compare .elementor-button',
					'separator' => 'before',
				]
			);
	
			/* $this->add_control(
				'toggle_button_border_color',
				[
					'label' => Wp_Helper::__('Border Color'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-compare .elementor-button' => 'border-color: {{VALUE}}',
					],
				]
			); */
	
			$this->end_controls_tab();


			$this->start_controls_tab('toggle_button_hover_colors', ['label' => Wp_Helper::__('Hover')]);

			$this->add_control(
				'toggle_button_hover_text_color',
				[
					'label' => Wp_Helper::__('Text Color'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-compare a.elementor-button:not(#e):hover' => 'color: {{VALUE}}',
					],
					'condition' => [
						'button_layout!' => 'icon',
					],
				]
			);
	
			$this->add_control(
				'toggle_button_hover_icon_color',
				[
					'label' => Wp_Helper::__('Icon Color'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-compare .elementor-button:hover .elementor-compare-icon i' => 'color: {{VALUE}}',
						'{{WRAPPER}} .elementor-compare .elementor-button:hover .elementor-compare-icon svg' => 'fill: {{VALUE}}',
					],
				]
			);
	
			$this->add_control(
				'toggle_button_hover_background_color',
				[
					'label' => Wp_Helper::__('Background Color'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-compare .elementor-button:hover' => 'background-color: {{VALUE}}',
					],
				]
			);
	
			/* $this->add_control(
				'toggle_button_hover_border_color',
				[
					'label' => Wp_Helper::__('Border Color'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-compare .elementor-button:hover' => 'border-color: {{VALUE}}',
					],
				]
			); */
	
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'toggle_button_border_hover',
					'selector' => '{{WRAPPER}} .elementor-compare .elementor-button:hover',
					'separator' => 'before',
				]
			);
	
			$this->end_controls_tab();
	
			$this->end_controls_tabs();

			/* $this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'button_border',
					'selector' => '{{WRAPPER}} .btn-canvas',
					'separator' => 'before',
				]
			);

			$this->add_control(
				'button_border_radius',
				[
					'label' => Wp_Helper::__( 'Border Radius', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .btn-canvas' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'button_box_shadow',
					'selector' => '{{WRAPPER}} .btn-canvas',
				]
			);

			$this->add_control(
				'button_text_padding',
				[
					'label' => Wp_Helper::__( 'Padding', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .btn-canvas' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			); */

			$this->add_control(
				'toggle_button_border_radius',
				[
					'label' => Wp_Helper::__('Border Radius'),
					'type' => Controls_Manager::SLIDER,
					'size_units' => ['px', 'em', '%'],
					'selectors' => [
						'{{WRAPPER}} .elementor-compare .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}}',
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
						'{{WRAPPER}} .elementor-compare .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
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
					'selector' => '{{WRAPPER}} .elementor-compare .elementor-compare-icon[data-counter]:before',
				]
			);

			$this->add_control(
				'items_indicator_text_color',
				[
					'label' => Wp_Helper::__('Text Color'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-compare .elementor-compare-icon[data-counter]:before' => 'color: {{VALUE}}',
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
						'{{WRAPPER}} .elementor-compare .elementor-compare-icon[data-counter]:before' => 'background-color: {{VALUE}}',
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
						'{{WRAPPER}} .elementor-compare .elementor-compare-icon[data-counter]:before' => 'top: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .elementor-compare .elementor-compare-icon[data-counter]:before' => 'right: calc(0em - {{SIZE}}{{UNIT}})',
					],
					'condition' => [
						'items_indicator' => 'bubble',
					],
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
		
		if ( Wp_Helper::is_admin() ) {
			return;
		}

		if( \Module::isEnabled('avanamcompare') ) {
			$settings = $this->get_settings_for_display();

			if ( ! empty( $settings['selected_icon']['value'] ) ) {
				ob_start();
					Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
				$icon = ob_get_clean();
			}else{
				$icon = '';
			}

			$context = \Context::getContext();

			$productsIds = $context->cookie->AvaCompareItemsCount;
			if($productsIds) {
				$productsIds = json_decode($productsIds, true);
			}else{
				$productsIds = array();
			}

			$params = array (
				'count' => count($productsIds),
				'icon' => $icon,
			);

			$context->smarty->assign($params);

			echo '<div class="elementor-compare">';
			echo $context->smarty->fetch('module:avanamcompare/views/templates/hook/counter.tpl');
			echo '</div>';

		}
		
	}
}