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
class Widget_Ava_Wishlist extends Widget_Base {

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
		return 'ava-wishlist';
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
		return Wp_Helper::__( 'Wishlist', 'elementor' );
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
		return 'eicon-heart-o';
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
		return [ 'wishlist', 'ava', 'avanam' ];
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
                    'value' => 'lar la-heart',
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
                'prefix_class' => 'elementor-wish--align-icon-',
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
					'{{WRAPPER}} .elementor-wish .btn-canvas' => 'gap: {{SIZE}}{{UNIT}}',
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
                'prefix_class' => 'elementor-wish--items-indicator-',
                'default' => 'bubble',
            ]
        );

        $this->add_control(
            'hide_empty_indicator',
            [
                'label' => Wp_Helper::__('Hide Empty'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'hide',
                'prefix_class' => 'elementor-wish--empty-indicator-',
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
		
			
		
		$this->start_controls_tabs('toggle_button_colors');

        $this->start_controls_tab('toggle_button_normal_colors', ['label' => Wp_Helper::__('Normal')]);

        $this->add_control(
            'toggle_button_text_color',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-wish a.elementor-button:not(#e)' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .elementor-wish .elementor-wish-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-wish .elementor-wish-icon svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'toggle_button_background_color',
            [
                'label' => Wp_Helper::__('Background Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-wish .elementor-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'toggle_button_border',
				'selector' => '{{WRAPPER}} .elementor-wish .elementor-button',
				'separator' => 'before',
			]
		);

        /* $this->add_control(
            'toggle_button_border_color',
            [
                'label' => Wp_Helper::__('Border Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-wish .elementor-button' => 'border-color: {{VALUE}}',
                ],
            ]
        ); */

        $this->end_controls_tab();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'form_typography',
                'selector' => '{{WRAPPER}} .btn-canvas-wishlist .btn-canvas-text',
            ]
        );

        $this->start_controls_tab('toggle_button_hover_colors', ['label' => Wp_Helper::__('Hover')]);

        $this->add_control(
            'toggle_button_hover_text_color',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-wish a.elementor-button:not(#e):hover' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .elementor-wish .elementor-button:hover .elementor-wish-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-wish .elementor-button:hover .elementor-wish-icon svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'toggle_button_hover_background_color',
            [
                'label' => Wp_Helper::__('Background Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-wish .elementor-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        /* $this->add_control(
            'toggle_button_hover_border_color',
            [
                'label' => Wp_Helper::__('Border Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-wish .elementor-button:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        ); */

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'toggle_button_border_hover',
				'selector' => '{{WRAPPER}} .elementor-wish .elementor-button:hover',
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
                    '{{WRAPPER}} .elementor-wish .elementor-button' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        ); */

        $this->add_control(
            'toggle_button_border_radius',
            [
                'label' => Wp_Helper::__('Border Radius'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-wish .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .elementor-wish .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
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
                'selector' => '{{WRAPPER}} .elementor-wish .elementor-wish-icon[data-counter]:before',
            ]
        );

        $this->add_control(
            'items_indicator_text_color',
            [
                'label' => Wp_Helper::__('Text Color'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-wish .elementor-wish-icon[data-counter]:before' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .elementor-wish .elementor-wish-icon[data-counter]:before' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .elementor-wish .elementor-wish-icon[data-counter]:before' => 'top: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementor-wish .elementor-wish-icon[data-counter]:before' => 'right: calc(0em - {{SIZE}}{{UNIT}})',
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

		if( \Module::isEnabled('blockwishlist') ) {
			$settings = $this->get_settings_for_display();

			if ( ! empty( $settings['selected_icon']['value'] ) ) {
				ob_start();
					Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
				$icon = ob_get_clean();
			}else{
				$icon = '';
			}

			$context = \Context::getContext();

			$current_user = (int)$context->cookie->id_customer;

			$id_wishlist = \Db::getInstance()->getValue("SELECT id_wishlist FROM `"._DB_PREFIX_."wishlist` WHERE id_customer = '$current_user'");
			$count_products = \Db::getInstance()->getValue("SELECT COUNT(id_wishlist_product) FROM `"._DB_PREFIX_."wishlist_product` WHERE id_wishlist = '$id_wishlist'");

			$params = array (
				'url' => $context->link->getModuleLink('blockwishlist', 'lists', [], true),
				'ajax_url' => $context->link->getModuleLink('avanambuilder', 'ajax', [], true),
				'count' => $count_products,
				'icon' => $icon,
			);

            $context->smarty->assign($params);
            
			echo '<div class="elementor-wish">';
            echo $context->smarty->fetch('module:avanambuilder/views/templates/hook/wishlist.tpl');
			echo '</div>';
		}
		
	}
}