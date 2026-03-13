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
class Widget_Ava_Search extends Widget_Base {

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
		return 'ava-search';
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
		return Wp_Helper::__( 'Search', 'elementor' );
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
		return 'eicon-search';
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
		return [ 'seach', 'ava' ];
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
				'label' => Wp_Helper::__('Search', 'elementor'),
			]
		);
		
		$this->add_control(
			'layout',
			[
				'label'        => Wp_Helper::__( 'Layout', 'elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'icon',
				'options'      => [
					'icon'    => Wp_Helper::__( 'Icon', 'elementor' ),
					'form'      => Wp_Helper::__( 'Form', 'elementor' ),
				],
			]
		);
		
		$this->add_responsive_control(
			'show_cat',
			[
				'label' => Wp_Helper::__('Show categories', 'elementor'),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => true,
				'label_on'     => Wp_Helper::__( 'Yes', 'elementor' ),
				'label_off'    => Wp_Helper::__( 'No', 'elementor' ),
				'default' => 'yes',
				'selectors_dictionary' => [
					'yes' => 'block',
					'' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .ava-search-bar .search-category-field' => 'display: {{VALUE}}',
				],
				
				/* 'condition' => [
					'layout' => 'form',
				], */
			]
		);
		/* $this->add_control(
			'search_placeholder',
			[
				'label' => Wp_Helper::__( 'Search placeholder', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Search product here...',
				'placeholder' => Wp_Helper::__( 'Place holder for search input', 'elementor' ),
			]
		); */

		/* $this->add_control(
			'show_focus',
			[
				'label' => Wp_Helper::__('Show focus?', 'elementor'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => Wp_Helper::__( 'Yes', 'elementor' ),
				'label_off'    => Wp_Helper::__( 'No', 'elementor' ),
				'default' => 'yes',
				'condition'    => [
					'layout' => 'form',
				],
			]
		); */

		/* $this->add_control(
			'search_result_padding',
			[
				'label' => Wp_Helper::__( 'Search Result Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ava-search-bar .search-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_result_margin',
			[
				'label' => Wp_Helper::__( 'Search Result Margin', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ava-search-bar .search-dropdown' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		); */
				
		/* $this->add_control(
			'search_result_padding',
			[
				'label' => Wp_Helper::__( 'Search Result Padding top', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					]
				],
				'default' => [
                    'size' => 16,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ava-search-bar .search-dropdown' => 'padding-top: {{SIZE}}{{UNIT}}',
				],
			]
		); */

		

		/* $this->add_responsive_control(
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
				'condition'    => [
					'layout' => [ 'button' ],
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			]
		); */

		$this->add_control(
			'show_popular_terms',
			[
				'label' => Wp_Helper::__('Show Popular Terms?', 'elementor'),
				'type'         => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'label_on'     => Wp_Helper::__( 'Yes', 'elementor' ),
				'label_off'    => Wp_Helper::__( 'No', 'elementor' ),
				'default' => 'yes',
			]
		);

		/* $this->add_control(
			'popular_terms_title',
			[
				'label' => Wp_Helper::__( 'Popular Terms Title', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Popular search terms',
				'placeholder' => Wp_Helper::__( 'Title to show before popular terms list', 'elementor' ),
				'condition'    => [
					'show_popular_terms' => 'yes',
				],
			]
		); */
		
		$this->add_control(
			'popular_search_terms',
			[
				'label' => Wp_Helper::__( 'Popular Terms', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'electronics,clothes,furniture',
				'placeholder' => Wp_Helper::__( 'Comma-separated popular search terms. i.e electronics,clothes,furniture', 'elementor' ),
				'condition'    => [
					'show_popular_terms' => 'yes',
				],
			]
		);


		$this->add_control(
			'search_icon',
			[
				'label' => Wp_Helper::__('Icon', 'elementor'),
				'label_block' => false,
				'type' => Controls_Manager::ICONS,
				'separator' => 'before',
				//'skin' => 'inline',
				//'fa4compatibility' => 'icon',
				'recommended' => [
                    'fa-brands' => [
                        'sistrix',
                    ],
                    'fa-solid' => [
                        'search',
                    ],
                ],
				'default' => [
                    'value' => 'fab fa-sistrix',
                    'library' => 'fa-brands',
                ],
				'condition' => [
					//'layout' => 'button',
					'layout' => 'icon',
				],
			]
		);

		$this->add_responsive_control(
			'search_icon_size',
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
					'{{WRAPPER}} .elementor-button.search-button i::before' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-button.search-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => 'icon',
				],
			]
		);
		
		$this->add_control(
			'search_icon_color',
			[
				'label' => Wp_Helper::__('Icon Color'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button.search-button i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .elementor-button.search-button svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'layout' => 'icon',
				],
			]
		);

		$this->add_control(
			'search_icon_hover_color',
			[
				'label' => Wp_Helper::__('Icon Hover Color'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button.search-button:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .elementor-button.search-button:hover svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'layout' => 'icon',
				],
			]
		);


		$this->add_control(
			'button_layout',
			[
				'label'        => Wp_Helper::__( 'Button Layout', 'elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'text',
				'separator' => 'before',
				'options'      => [
					'icon'    => Wp_Helper::__( 'Icon', 'elementor' ),
					'text'      => Wp_Helper::__( 'Text', 'elementor' ),
					'icon_text'      => Wp_Helper::__( 'Icon & Text', 'elementor' ),
				],
				/* 'condition'    => [
					'layout' => [ 'button' ],
				], */
				'prefix_class' => 'button-layout-',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => Wp_Helper::__( 'Button Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Search',
				'placeholder' => Wp_Helper::__( 'Search button text', 'elementor' ),
				'condition' => [
					//'layout' => 'button',
					'button_layout!' => 'icon',
				],
			]
		);


		$this->add_control(
			'icon',
			[
				'label' => Wp_Helper::__('Button Icon', 'elementor'),
				'label_block' => false,
				'type' => Controls_Manager::ICONS,
				//'skin' => 'inline',
				//'fa4compatibility' => 'icon',
				'recommended' => [
                    'fa-brands' => [
                        'sistrix',
                    ],
                    'fa-solid' => [
                        'search',
                    ],
                ],
				'default' => [
                    'value' => 'fab fa-sistrix',
                    'library' => 'fa-brands',
                ],
				'condition' => [
					//'layout' => 'button',
					'button_layout!' => 'text',
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
					//'{{WRAPPER}} .button-layout-icon .ava-search-bar .search-button i:before, .button-layout-icon_text .ava-search-bar .search-button i:before' => 'padding-inline-end: {{SIZE}}{{UNIT}}',
'{{WRAPPER}} .ava-search-bar .search-button' => 'gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .ava-search-bar .search-button' => 'gap: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'button_layout!' =>  [ 'text', 'icon' ],
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
					// '{{WRAPPER}} .btn-canvas-search.search-button i:before, .button-layout-icon_text .ava-search-bar .search-button:before' => 'font-size: {{SIZE}}{{UNIT}}',
					// '{{WRAPPER}} .search-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ava-search-bar .search-button i::before' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .ava-search-bar .search-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'button_layout!' => 'text',
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
			'section_form',
			[
				'label' => Wp_Helper::__( 'Form Style', 'elementor' ),
				'type' => Controls_Manager::SECTION,
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_responsive_control(
			'search_form_width',
			[
				'label' => Wp_Helper::__( 'Search Form Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1920,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					]
				],
				'default' => [
					'size' => 550,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} #avasearch_block.ava-search-bar #searchbox' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} #avasearch_block.ava-search-bar:not(.box-search) .search-dropdown' => 'width: {{SIZE}}{{UNIT}}',
					
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'search_form_height',
			[
				'label' => Wp_Helper::__( 'Search Form Height', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 45,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} #avasearch_block.ava-search-bar #searchbox' => 'height: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'form_typography',
				'selector' => '{{WRAPPER}} .ava-search-bar #searchbox .form-control, {{WRAPPER}} .ava-search-bar .search-button',
			]
		);

		$this->add_control(
			'form_text_color',
			[
				'label' => Wp_Helper::__( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ava-search-bar #search_category' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

	
		$this->add_control(
			'form_background_color',
			[
				'label' => Wp_Helper::__( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ava-search-bar #searchbox' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'form_border',
				'selector' => '{{WRAPPER}} .ava-search-bar #searchbox',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'form_border_radius',
			[
				'label' => Wp_Helper::__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ava-search-bar #searchbox' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'form_box_shadow',
				'selector' => '{{WRAPPER}} .ava-search-bar #searchbox',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_search_input',
			[
				'label' => Wp_Helper::__( 'Search Input Style', 'elementor' ),
				'type' => Controls_Manager::SECTION,
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
			$this->add_responsive_control(
				'search_input_width',
				[
					'label' => Wp_Helper::__( 'Search Input Width', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 100,
							'max' => 1920,
						],
						'%' => [
							'min' => 1,
							'max' => 100,
						]
					],
					/* 'default' => [
						'size' => 250,
						'unit' => 'px',
					], */
					'selectors' => [
						'{{WRAPPER}} .ava-search-bar #searchbox .form-control.query' => 'width: {{SIZE}}{{UNIT}}',
					],
					'separator' => 'before'
				]
			);

			$this->add_responsive_control(
				'search_input_height',
				[
					'label' => Wp_Helper::__( 'Search Input Height', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 30,
							'max' => 100,
						],
					],
					'default' => [
						'size' => 45,
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .ava-search-bar #searchbox .form-control.query, {{WRAPPER}} .ava-search-bar #searchbox .form-control' => 'height: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}};'
					],
					'separator' => 'before'
				]
			);
		
			
			
		
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'search_input_typography',
					'selector' => '{{WRAPPER}} .ava-search-bar #searchbox .form-control.query, {{WRAPPER}} .ava-search-bar #searchbox .form-control.query::placeholder',
				]
			);

			$this->add_control(
				'search_input_text_color',
				[
					'label' => Wp_Helper::__( 'Text Color', 'elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .ava-search-bar #searchbox .form-control.query' => 'fill: {{VALUE}}; color: {{VALUE}};',
						'{{WRAPPER}} .ava-search-bar #searchbox .form-control.query::placeholder, {{WRAPPER}} .ava-search-bar #searchbox .form-control.query::-ms-input-placeholder' => 'fill: {{VALUE}}; color: {{VALUE}}; opacity: 1;',
					],
				]
			);

			

			$this->add_responsive_control(
				'search_input_text_padding',
				[
					'label' => Wp_Helper::__( 'Padding', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .ava-search-bar #searchbox .form-control.query' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'search_input_text_margin',
				[
					'label' => Wp_Helper::__( 'Margin', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .ava-search-bar #searchbox .form-control.query' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);





		$this->end_controls_section();


		$this->start_controls_section(
			'section_search_button',
			[
				'label' => Wp_Helper::__( 'Search Button Style', 'elementor' ),
				'type' => Controls_Manager::SECTION,
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
			$this->add_responsive_control(
				'search_button_width',
				[
					'label' => Wp_Helper::__( 'Search Button Width', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 40,
							'max' => 300,
						],
						'%' => [
							'min' => 1,
							'max' => 100,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .ava-search-bar #searchbox .search-button' => 'width: {{SIZE}}{{UNIT}}',
					],
					'separator' => 'before'
				]
			);

			$this->add_responsive_control(
				'search_button_height',
				[
					'label' => Wp_Helper::__( 'Search Button Height', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 300,
						],
						'%' => [
							'min' => 1,
							'max' => 100,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .ava-search-bar #searchbox .search-button' => 'height: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}};',
					],
					'separator' => 'before'
				]
			);

			
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'search_button_typography',
					'selector' => '{{WRAPPER}} .ava-search-bar #searchbox .search-button',
				]
			);


			$this->add_responsive_control(
				'search_button_text_padding',
				[
					'label' => Wp_Helper::__( 'Padding', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .ava-search-bar #searchbox .search-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					//'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'search_button_text_margin',
				[
					'label' => Wp_Helper::__( 'Margin', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .ava-search-bar #searchbox .search-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					//'separator' => 'before',
				]
			);

			$this->start_controls_tabs('search_button_colors');

			$this->start_controls_tab('search_button_normal_colors', ['label' => Wp_Helper::__('Normal')]);
	
			$this->add_control(
				'search_button_text_color',
				[
					'label' => Wp_Helper::__('Text Color'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ava-search-bar .search-button' => 'color: {{VALUE}}',
					],
					'condition' => [
						'button_layout!' => 'icon',
					],
				]
			);
	
			$this->add_control(
				'search_button_icon_color',
				[
					'label' => Wp_Helper::__('Icon Color'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						//'{{WRAPPER}} .ava-search-bar .search-button i:before' => 'color: {{VALUE}}',
'{{WRAPPER}} .ava-search-bar .search-button i' => 'color: {{VALUE}}',
						'{{WRAPPER}} .ava-search-bar .search-button svg' => 'fill: {{VALUE}}',
					],
				]
			);
	
			$this->add_control(
				'search_button_background_color',
				[
					'label' => Wp_Helper::__('Background Color'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ava-search-bar .search-button' => 'background-color: {{VALUE}}',
					],
				]
			);
	
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'search_button_border',
					'selector' => '{{WRAPPER}} .ava-search-bar .search-button',
					//'separator' => 'before',
				]
			);
	
	
			$this->end_controls_tab();
	
			$this->start_controls_tab('search_button_hover_colors', ['label' => Wp_Helper::__('Hover')]);
	
			$this->add_control(
				'search_button_hover_text_color',
				[
					'label' => Wp_Helper::__('Text Color'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ava-search-bar .search-button:hover' => 'color: {{VALUE}}',
					],
					'condition' => [
						'button_layout!' => 'icon',
					],
				]
			);
	
			$this->add_control(
				'search_button_hover_icon_color',
				[
					'label' => Wp_Helper::__('Icon Color'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						//'{{WRAPPER}} .ava-search-bar .search-button:hover i:before' => 'color: {{VALUE}}',
'{{WRAPPER}} .ava-search-bar .search-button:hover i' => 'color: {{VALUE}}',
						'{{WRAPPER}} .ava-search-bar .search-button:hover svg' => 'fill: {{VALUE}}',
					],
				]
			);
	
			$this->add_control(
				'search_button_hover_background_color',
				[
					'label' => Wp_Helper::__('Background Color'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ava-search-bar .search-button:hover' => 'background-color: {{VALUE}}',
					],
				]
			);
	
			/* $this->add_control(
				'search_button_hover_border_color',
				[
					'label' => Wp_Helper::__('Border Color'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ava-search-bar .search-button:hover' => 'border-color: {{VALUE}}',
					],
				]
			); */
	
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'search_button_border_hover',
					'selector' => '{{WRAPPER}} .ava-search-bar .search-button:hover',
					//'separator' => 'before',
				]
			);
	
			$this->end_controls_tab();
	
			$this->end_controls_tabs();


			$this->add_control(
				'button_border_radius',
				[
					'label' => Wp_Helper::__( 'Border Radius', 'elementor' ),
					'separator' => 'before',
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .ava-search-bar .search-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'button_box_shadow',
					'selector' => '{{WRAPPER}} .ava-search-bar .search-button',
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
						
		if(\Module::isEnabled('avanamsearchbar'))
		{
			$settings = $this->get_settings_for_display();
			if ( ! empty( $settings['icon'] ) ) {
				ob_start();
					Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
				$icon = ob_get_clean();
			}else{
				$icon = '';
			} 

			if ( ! empty( $settings['search_icon'] ) ) {
				ob_start();
					Icons_Manager::render_icon( $settings['search_icon'], [ 'aria-hidden' => 'true' ] );
				$search_icon = ob_get_clean();
			}else{
				$search_icon = '';
			} 

			$module = \Module::getInstanceByName('avanamsearchbar');


			$popularTerms = array();
			$popularTerms = trim($settings['popular_search_terms']);
			$popularTerms = explode(',', trim($popularTerms,','));
		
			$params = array (
				'result_limit' => '4',
				'icon' => $icon,
				'search_icon' => $search_icon,
				'button_text' => $settings['button_text'],
				'layout' => $settings['layout'],
				//'search_placeholder' => $settings['search_placeholder'],
				//'show_focus' => $settings['show_focus'],
				'show_popular_terms' => $settings['show_popular_terms'],
				//'popular_terms_title' => $settings['popular_terms_title'],
				'popular_search_terms' => $popularTerms,
				'show_cat' => $settings['show_cat']
			);

			echo $module->renderWidget( null, $params );

			/* if( $settings['layout'] == 'form' ) {
				echo $module->renderWidget( 'displaySearch', [ 'show_cat' => $settings['show_cat'] ] );
			}else{
				echo $module->renderWidget( null, [ 'icon' => $icon ] );
			} */
		}
	}
}