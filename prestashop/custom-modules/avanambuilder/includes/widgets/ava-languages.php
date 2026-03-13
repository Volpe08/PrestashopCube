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
class Widget_Ava_Languages extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ava-languages';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return Wp_Helper::__( 'Languages', 'elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-global-settings';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'avanam-elements' ];
	}
	
	public function get_keywords() {
		return [ 'ava', 'lang' ];
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
			'section_dropdown',
			[
				'label' => Wp_Helper::__( 'Dropdown', 'elementor' ),
			]
		);
				
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
			'section_dropdown_title',
			[
				'label' => Wp_Helper::__( 'Title', 'elementor' ),
				'type' => Controls_Manager::SECTION,
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
			$this->add_responsive_control(
				'title_icon_size',
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
						'{{WRAPPER}} .ava-dropdown-toggle img' => 'width: {{SIZE}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);
		
			$this->add_responsive_control(
				'title_arrow_spacing',
				[
					'label' => Wp_Helper::__( 'Title Arrow Spacing', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'body:not(.rtl) {{WRAPPER}} .ava-dropdown-toggle .icon-ava' => 'margin-left: {{SIZE}}{{UNIT}}',
						'body.rtl {{WRAPPER}} .ava-dropdown-toggle .icon-ava' => 'margin-right: {{SIZE}}{{UNIT}}',
					]
				]
			);

			$this->add_responsive_control(
				'button_icon_size_margin',
				[
					'label' => Wp_Helper::__( 'Image Margin Right', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'body:not(.rtl) {{WRAPPER}} .ava-dropdown-toggle .ava-dropdown-toggle-text' => 'margin-left: {{SIZE}}{{UNIT}}',
						'body.rtl {{WRAPPER}} .ava-dropdown-toggle .ava-dropdown-toggle-text' => 'margin-right: {{SIZE}}{{UNIT}}',
					]
				]
			);
		
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .ava-dropdown-toggle',
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'title_text_shadow',
					'selector' => '{{WRAPPER}} .ava-dropdown-toggle',
				]
			);

			$this->start_controls_tabs( 'title_tabs_style' );

				$this->start_controls_tab(
					'title_tab_normal',
					[
						'label' => Wp_Helper::__( 'Normal', 'elementor' ),
					]
				);

					$this->add_control(
						'title_text_color',
						[
							'label' => Wp_Helper::__( 'Text Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .ava-dropdown-toggle' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'title_background_color',
						[
							'label' => Wp_Helper::__( 'Background Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ava-dropdown-toggle' => 'background-color: {{VALUE}};',
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'title_tab_hover',
					[
						'label' => Wp_Helper::__( 'Hover & Active', 'elementor' ),
					]
				);

					$this->add_control(
						'title_hover_color',
						[
							'label' => Wp_Helper::__( 'Text Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ava-dropdown-wrapper:hover .ava-dropdown-toggle, {{WRAPPER}} .ava-dropdown-wrapper.open .ava-dropdown-toggle' => 'fill: {{VALUE}}; color: {{VALUE}};'
							],
						]
					);

					$this->add_control(
						'title_background_hover_color',
						[
							'label' => Wp_Helper::__( 'Background Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ava-dropdown-wrapper:hover .ava-dropdown-toggle, {{WRAPPER}} .ava-dropdown-wrapper.open .ava-dropdown-toggle' => 'background-color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'title_hover_border_color',
						[
							'label' => Wp_Helper::__( 'Border Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ava-dropdown-wrapper:hover .ava-dropdown-toggle, {{WRAPPER}} .ava-dropdown-wrapper.open .ava-dropdown-toggle' => 'border-color: {{VALUE}};',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'title_border',
					'selector' => '{{WRAPPER}} .ava-dropdown-toggle',
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'title_border_radius',
				[
					'label' => Wp_Helper::__( 'Border Radius', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .ava-dropdown-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					]
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'title_box_shadow',
					'selector' => '{{WRAPPER}} .ava-dropdown-toggle',
				]
			);

			$this->add_responsive_control(
				'title_text_padding',
				[
					'label' => Wp_Helper::__( 'Padding', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .ava-dropdown-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_main_dropdown',
			[
				'label'     => Wp_Helper::__( 'Main Items', 'elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);
		
			$this->add_control(
				'item_icon_size',
				[
					'label' => Wp_Helper::__( 'Image Size', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .ava-dropdown-menu img' => 'width: {{SIZE}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);
		
			$this->add_control(
				'item_icon_size_margin',
				[
					'label' => Wp_Helper::__( 'Image Margin Right', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .ava-dropdown-toggle img' => 'margin-inline-end: {{SIZE}}{{UNIT}}',
						// 'body:not(.rtl) {{WRAPPER}} .ava-dropdown-menu span' => 'margin-inline-right: {{SIZE}}{{UNIT}}',
						// 'body.rtl {{WRAPPER}} .ava-dropdown-menu span' => 'margin-left: {{SIZE}}{{UNIT}}',
					]
				]
			);
		
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'typography',
					'selector' => '{{WRAPPER}} .ava-dropdown-menu > a',
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'text_shadow',
					'selector' => '{{WRAPPER}} .ava-dropdown-menu > a',
				]
			);

			$this->start_controls_tabs( 'tabs_style' );

				$this->start_controls_tab(
					'tab_normal',
					[
						'label' => Wp_Helper::__( 'Normal', 'elementor' ),
					]
				);

					$this->add_control(
						'text_color',
						[
							'label' => Wp_Helper::__( 'Text Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .ava-dropdown-menu > a' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'background_color',
						[
							'label' => Wp_Helper::__( 'Background Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ava-dropdown-menu' => 'background-color: {{VALUE}};',
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_hover',
					[
						'label' => Wp_Helper::__( 'Hover & Active', 'elementor' ),
					]
				);

					$this->add_control(
						'hover_color',
						[
							'label' => Wp_Helper::__( 'Text Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ava-dropdown-menu > a:hover, {{WRAPPER}} .ava-dropdown-menu > a.selected' => 'color: {{VALUE}};'
							],
						]
					);

					$this->add_control(
						'background_hover_color',
						[
							'label' => Wp_Helper::__( 'Background Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ava-dropdown-menu > a:hover, {{WRAPPER}} .ava-dropdown-menu > a.selected' => 'background-color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'hover_border_color',
						[
							'label' => Wp_Helper::__( 'Border Color', 'elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ava-dropdown-menu > a:hover, {{WRAPPER}} .ava-dropdown-menu > a.selected' => 'border-color: {{VALUE}};',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'border',
					'selector' => '{{WRAPPER}} .ava-dropdown-menu > a',
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'box_shadow',
					'selector' => '{{WRAPPER}} .ava-dropdown-menu',
				]
			);

			$this->add_responsive_control(
				'text_padding',
				[
					'label' => Wp_Helper::__( 'Padding', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .ava-dropdown-menu > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		if ( Wp_Helper::is_admin() ) {
			return;
		}
				
		$module = \Module::getInstanceByName('avanambuilder');
				
		$params = $module->getListLanguages();
		
		if( !$params ){
			return;
		}
		
		$context = \Context::getContext();

		$context->smarty->assign($params);

		echo $context->smarty->fetch('module:avanambuilder/views/templates/widgets/languages.tpl');
	}
}