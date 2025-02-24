<?php
/**
 * Plugin Name: Custom Product Quality Selector For WooCommerce by MSD
 * Plugin URI: https://github.com/msdigitalsolution25/custom-product-quality-selector-for-woocommerce-by-msd
 * Description: Displays a dynamic dot selector on WooCommerce single product pages to show product quality. Integrates with Elementor as a widget with customizable colors and font styles.
 * Version: 1.0.0
 * Author: MSdigitalsolution
 * Author URI: https://msdigitalsolution.com
 * Text Domain: custom-product-quality-selector-for-woocommerce-by-msd
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;   
}

if ( ! class_exists( 'Custom_Product_Quality_Selector_For_WooCommerce_By_MSD' ) ) :

class Custom_Product_Quality_Selector_For_WooCommerce_By_MSD {

    /**
     * Constructor to initialize the plugin
     */
    public function __construct() {
        // Load plugin textdomain
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

        // Register Custom Taxonomy: Condition
        add_action( 'init', array( __CLASS__, 'register_condition_taxonomy' ), 0 );

        // Add quality fields to product edit page
        add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_quality_fields' ) );

        // Save the quality fields
        add_action( 'woocommerce_process_product_meta', array( $this, 'save_quality_fields' ) );

        // Register shortcode
        add_shortcode( 'quality_dot_selector', array( $this, 'quality_dot_selector_shortcode' ) );

        // Enqueue frontend styles and scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );

        // Automatically display the selector on single product pages
        add_action( 'woocommerce_single_product_summary', array( $this, 'display_quality_dot_selector' ), 25 );

        // Initialize Elementor widget
        add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widget' ) );

        // Enqueue styles in Elementor's preview mode
        add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_elementor_preview_styles' ) );
    }

    /**
     * Load plugin textdomain for translations
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'custom-product-quality-selector-for-woocommerce-by-msd', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Register Custom Taxonomy: Condition
     */
    public static function register_condition_taxonomy() {
        $labels = array(
            'name'                       => _x( 'Conditions', 'Taxonomy General Name', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'singular_name'              => _x( 'Condition', 'Taxonomy Singular Name', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'menu_name'                  => __( 'Conditions', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'all_items'                  => __( 'All Conditions', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'new_item_name'              => __( 'New Condition Name', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'add_new_item'               => __( 'Add New Condition', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'edit_item'                  => __( 'Edit Condition', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'update_item'                => __( 'Update Condition', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'view_item'                  => __( 'View Condition', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'separate_items_with_commas' => __( 'Separate conditions with commas', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'add_or_remove_items'        => __( 'Add or remove conditions', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'popular_items'              => __( 'Popular Conditions', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'search_items'               => __( 'Search Conditions', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'not_found'                  => __( 'Not Found', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => false,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => false,
            'show_tagcloud'              => false,
        );
        register_taxonomy( 'condition', array( 'product' ), $args );
    }

    /**
     * Plugin Activation Hook
     */
    public static function activate() {
        // Ensure the taxonomy is registered before adding terms
        self::register_condition_taxonomy();

        // Flush rewrite rules to register the taxonomy
        flush_rewrite_rules();

        // Define default conditions
        $default_conditions = array( 'Good', 'Very Good', 'Excellent', 'Pristine', 'New' );

        foreach( $default_conditions as $condition ) {
            if( ! term_exists( $condition, 'condition' ) ) {
                wp_insert_term( $condition, 'condition' );
            }
        }
    }

    /**
     * Add Quality and Enable Fields to Product General Tab
     */
    public function add_quality_fields() {
        echo '<div class="options_group">';

        // Enable Quality Selector Checkbox
        woocommerce_wp_checkbox( array(
            'id'            => '_enable_quality_selector',
            'label'         => __( 'Enable Quality Selector', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'description'   => __( 'Check to display the quality selector on the frontend.', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
        ) );

        // Product Quality Taxonomy Select Field
        $terms = get_terms( array(
            'taxonomy'   => 'condition',
            'hide_empty' => false,
        ) );

        $options = array( '' => __( 'Select Condition', 'custom-product-quality-selector-for-woocommerce-by-msd' ) );
        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            foreach ( $terms as $term ) {
                $options[ $term->term_id ] = $term->name;
            }
        } else {
            $options = array( '' => __( 'No Conditions Found', 'custom-product-quality-selector-for-woocommerce-by-msd' ) );
        }

        $current_condition = get_post_meta( get_the_ID(), '_product_quality', true );

        woocommerce_wp_select( array(
            'id'          => '_product_quality',
            'label'       => __( 'Product Condition', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'options'     => $options,
            'desc_tip'    => true,
            'description' => __( 'Select the condition of the product.', 'custom-product-quality-selector-for-woocommerce-by-msd' ),
            'value'       => $current_condition,
        ));

        echo '</div>';
    }

    /**
     * Save Quality and Enable Fields
     */
    public function save_quality_fields( $post_id ) {
        if ( ! isset( $_POST['woocommerce_meta_nonce'] ) ) {
            return;
        }
        $nonce = sanitize_text_field( wp_unslash( $_POST['woocommerce_meta_nonce'] ) );
        if ( ! wp_verify_nonce( $nonce, 'woocommerce_save_data' ) ) {
            return;
        }
        $enable_quality = isset( $_POST['_enable_quality_selector'] ) ? 'yes' : 'no';
        update_post_meta( $post_id, '_enable_quality_selector', sanitize_text_field( $enable_quality ) );

        if ( isset( $_POST['_product_quality'] ) && ! empty( $_POST['_product_quality'] ) ) {
            $term_id = intval( $_POST['_product_quality'] );
            $term = get_term( $term_id, 'condition' );
            if ( ! is_wp_error( $term ) && $term ) {
                wp_set_object_terms( $post_id, (int) $term_id, 'condition', false );
                update_post_meta( $post_id, '_product_quality', $term_id );
            }
        } else {
            wp_set_object_terms( $post_id, null, 'condition', false );
            delete_post_meta( $post_id, '_product_quality' );
        }
    }

    /**
     * Shortcode to Display Dot Selector
     */
    public function quality_dot_selector_shortcode() {
        if ( ! is_product() ) {
            return '';
        }

        global $post;
        $enable_quality = get_post_meta( $post->ID, '_enable_quality_selector', true );
        if ( $enable_quality !== 'yes' ) {
            return '';
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
            return '<p>' . esc_html__( 'No Conditions Available.', 'custom-product-quality-selector-for-woocommerce-by-msd' ) . '</p>';
        }

        $primary_label = __( 'Condition', 'custom-product-quality-selector-for-woocommerce-by-msd' );
        ob_start();
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
        return ob_get_clean();
    }

    /**
     * Enqueue Styles for Elementor Preview Mode
     */
    public function enqueue_elementor_preview_styles() {
        wp_enqueue_style(
            'custom-product-quality-selector-for-woocommerce-by-msd-css',
            plugin_dir_url( __FILE__ ) . 'assets/css/custom-product-quality-selector-for-woocommerce-by-msd.css',
            array(),
            '1.0.0'
         );
    }

    /**
     * Enqueue Frontend Styles and Scripts
     */
    public function enqueue_styles_scripts() {
        if ( is_product() ) {
            wp_enqueue_style( 'custom-product-quality-selector-for-woocommerce-by-msd-css', plugin_dir_url( __FILE__ ) . 'assets/css/custom-product-quality-selector-for-woocommerce-by-msd.css', array(), '1.0.0' );
            wp_enqueue_script( 'custom-product-quality-selector-for-woocommerce-by-msd-js', plugin_dir_url( __FILE__ ) . 'assets/js/custom-product-quality-selector-for-woocommerce-by-msd.js', array( 'jquery' ), '1.0.0', true );
            wp_localize_script( 'custom-product-quality-selector-for-woocommerce-by-msd-js', 'pq_selector_params', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'update_quality_nonce' ),
            ) );
        }
    }
    /**
     * Automatically Display Dot Selector on Single Product Pages
     */
    public function display_quality_dot_selector() {
        echo do_shortcode( '[quality_dot_selector]' );
    }

    /**
     * Register Elementor Widget
     */
    public function register_elementor_widget() {
        if ( did_action( 'elementor/loaded' ) ) {
            require_once( __DIR__ . '/includes/class-quality-dot-selector-widget.php' );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Quality_Dot_Selector_Widget() );
        }
    }

}

endif;

// Register activation hook
register_activation_hook( __FILE__, array( 'Custom_Product_Quality_Selector_For_WooCommerce_By_MSD', 'activate' ) );

// Initialize the plugin
new Custom_Product_Quality_Selector_For_WooCommerce_By_MSD();
