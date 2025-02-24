<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Quality_Dot_Selector_Widget extends Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'quality_dot_selector';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return __( 'Quality Dot Selector', 'custom-product-quality-selector-for-woocommerce-by-msd' );
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-rating';
    }

    /**
     * Get widget categories.
     */
    public function get_categories() {
        return [ 'woocommerce-elements' ];
    }

    /**
     * Register widget controls.
     */
    protected function _register_controls() {

        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Style', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Primary Label Control
        $this->add_control(
            'primary_label',
            [
                'label'       => __( 'Primary Label', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'Condition', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
                'placeholder' => __( 'Enter label', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            ]
        );

        $this->add_control(
            'primary_label_alignment',
            [
                'label' => __( 'Primary Label Alignment', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .primary-label' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        // Primary Label Margin (Padding) Control
        $this->add_responsive_control(
            'primary_label_margin',
            [
                'label'      => __( 'Primary Label Margin', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .primary-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default'    => [
                    'top'    => '',
                    'right'  => '',
                    'bottom' => '10',
                    'left'   => '',
                    'unit'   => 'px',
                ],
            ]
        );

        // Primary Label Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'primary_label_typography',
                'label'    => __( 'Primary Label Typography', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
                'selector' => '{{WRAPPER}} .primary-label',
            ]
        );

        // Inactive Dot Color
        $this->add_control(
            'inactive_dot_color',
            [
                'label'     => __( 'Inactive Dot Color', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#FFFF00',
                'selectors' => [
                    '{{WRAPPER}} .dot-selector .dot' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Active Dot Color
        $this->add_control(
            'active_dot_color',
            [
                'label'     => __( 'Active Dot Color', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#FF0000',
                'selectors' => [
                    '{{WRAPPER}} .dot-selector .dot.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Dot Size
        $this->add_responsive_control(
            'dot_size',
            [
                'label'      => __( 'Dot Size', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min'  => 10,
                        'max'  => 50,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .dot-selector .dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Labels Typography Control
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'labels_typography',
                'label'    => __( 'Labels Typography', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
                'selector' => '{{WRAPPER}} .dot-selector .labels span',
            ]
        );

        // Label Spacing (Space Control)
        $this->add_responsive_control(
            'label_spacing',
            [
                'label'      => __( 'Label Spacing', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .dot-selector .labels span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default'    => [
                    'top'    => '',
                    'right'  => '',
                    'bottom' => '',
                    'left'   => '',
                    'unit'   => 'px',
                ],
            ]
        );

        // Dot Transition Duration
        $this->add_control(
            'dot_transition_duration',
            [
                'label'     => __( 'Transition Duration (s)', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
                'type'      => \Elementor\Controls_Manager::NUMBER,
                'default'   => 0.3,
                'min'       => 0,
                'max'       => 5,
                'step'      => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .dot-selector .dot' => 'transition-duration: {{VALUE}}s;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     */
    protected function render() {
        if ( ! is_product() ) {
            return;
        }

        global $post;
        $enable_quality = get_post_meta( $post->ID, '_enable_quality_selector', true );
        if ( $enable_quality !== 'yes' ) {
            return;
        }

        $terms = wp_get_post_terms( $post->ID, 'condition' );

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            $current_conditions = array();
        } else {
            $current_conditions = wp_list_pluck( $terms, 'term_id' );
        }

        $all_terms = get_terms( array(
            'taxonomy'   => 'condition',
            'hide_empty' => false,
        ) );

        if ( is_wp_error( $all_terms ) || empty( $all_terms ) ) {
            echo '<p>' . esc_html__( 'No Conditions Available.', 'custom-product-quality-selector-for-woocommerce-by-msd' ) . '</p>';
            return;
        }

        $primary_label = $this->get_settings_for_display( 'primary_label' );
        ?>
        <div class="dot-selector-wrapper">
            <?php if ( ! empty( $primary_label ) ) : ?>
                <div class="primary-label"><?php echo esc_html( $primary_label ); ?></div>
            <?php endif; ?>
            <div class="dot-selector">
                <!-- Dots -->
                <div class="dots">
                    <?php foreach ( $all_terms as $term ) : ?>
                        <?php
                            $active_class = ( in_array( $term->term_id, $current_conditions ) ) ? 'active' : '';
                            $index = array_search( $term, $all_terms );
                            $total = count( $all_terms );
                            $left_position = ($total > 1) ? ($index / ( $total - 1 )) * 100 : 50;
                        ?>
                        <div class="dot <?php echo esc_attr( $active_class ); ?>" data-value="<?php echo esc_attr( $term->term_id ); ?>" style="left: <?php echo esc_attr( $left_position ); ?>%;"></div>
                    <?php endforeach; ?>
                </div>

                <!-- Labels -->
                <div class="labels">
                    <?php foreach ( $all_terms as $term ) : ?>
                        <?php
                            $index = array_search( $term, $all_terms );
                            $total = count( $all_terms );
                            $left_position = ($total > 1) ? ($index / ( $total - 1 )) * 100 : 50;
                        ?>
                        <span data-value="<?php echo esc_attr( $term->term_id ); ?>" style="left: <?php echo esc_attr( $left_position ); ?>%;"><?php echo esc_html( $term->name ); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render the widget output in the editor.
     */
    protected function _content_template() {
        ?>
        <#
        var enable_quality = '<?php echo esc_js( get_post_meta( get_the_ID(), '_enable_quality_selector', true ) ); ?>';
        if ( enable_quality !== 'yes' ) {
            return;
        }

        var primary_label = settings.primary_label ? settings.primary_label : 'Condition';
        var product_quality = '<?php echo esc_js( get_post_meta( get_the_ID(), '_product_quality', true ) ); ?>' || '1';

        // Mock data for Elementor Editor Preview
        var terms = {
            '1': 'Good',
            '2': 'Very Good',
            '3': 'Excellent',
            '4': 'Pristine',
            '5': 'New'
        };
        #>
        <div class="dot-selector-wrapper">
            <# if ( primary_label ) { #>
                <div class="primary-label" style="
                    margin: {{ settings.primary_label_margin.top }}{{ settings.primary_label_margin.unit }} {{ settings.primary_label_margin.right }}{{ settings.primary_label_margin.unit }} {{ settings.primary_label_margin.bottom }}{{ settings.primary_label_margin.unit }} {{ settings.primary_label_margin.left }}{{ settings.primary_label_margin.unit }};
                    font-family: {{ settings.primary_label_typography.font_family }};
                    font-size: {{ settings.primary_label_typography.font_size.size }}{{ settings.primary_label_typography.font_size.unit }};
                    font-weight: {{ settings.primary_label_typography.font_weight }};
                    color: {{ settings.primary_label_typography.color }};
                ">
                    {{{ primary_label }}}
                </div>
            <# } #>
            <div class="dot-selector">
                <!-- Dots -->
                <div class="dots">
                    <# 
                    var total = Object.keys(terms).length;
                    for ( var key in terms ) {
                        var active_class = ( parseInt(product_quality) === parseInt(key) ) ? 'active' : '';
                        var index = Object.keys(terms).indexOf(key);
                        var left_position = ( ( index ) / ( total - 1 )) * 100;
                    #>
                        <div class="dot {{ active_class }}" data-value="{{ key }}" style="left: {{ left_position }}%;"></div>
                    <# } #>
                </div>

                <!-- Labels -->
                <div class="labels">
                    <# 
                    for ( var key in terms ) {
                        var index = Object.keys(terms).indexOf(key);
                        var left_position = ( ( index ) / ( total - 1 )) * 100;
                    #>
                        <span data-value="{{ key }}" style="left: {{ left_position }}%;">{{ terms[key] }}</span>
                    <# } #>
                </div>
            </div>
        </div>
        <?php
    }

}
