<?php
/**
 * Plugin Name: Customize UI - Select2 for Categories
 * Plugin URI: https://example.com/customize-ui
 * Description: Applies Select2 to elements with class "listdom-category" in the frontend
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL v2 or later
 * Text Domain: customize-ui
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CUSTOMIZE_UI_VERSION', '1.0.0');
define('CUSTOMIZE_UI_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CUSTOMIZE_UI_PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * Main plugin class
 */
class CustomizeUI {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'add_script'));
        add_filter('gettext', array($this, 'custom_translations'), 20, 3);
        
        // Admin functionality for multiple categories
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('add_meta_boxes', array($this, 'add_listdom_category_meta_box'));
        add_action('save_post', array($this, 'save_listdom_categories'));
    }
    
    /**
     * Enqueue Select2 CSS and JS
     */
    public function enqueue_scripts() {
        // Enqueue Select2 CSS
        wp_enqueue_style(
            'select2-css',
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            array(),
            '4.1.0'
        );
        
        // Enqueue custom CSS
        wp_enqueue_style(
            'customize-ui-css',
            CUSTOMIZE_UI_PLUGIN_URL . 'assets/css/customize-ui.css',
            array('select2-css'),
            CUSTOMIZE_UI_VERSION
        );
        
        // Enqueue jQuery (if not already loaded)
        wp_enqueue_script('jquery');
        
        // Enqueue Select2 JS
        wp_enqueue_script(
            'select2-js',
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            array('jquery'),
            '4.1.0',
            true
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts($hook) {
        global $post_type;
        
        // Only load on listdom-listing post type
        if ($post_type !== 'listdom-listing') {
            return;
        }
        
        // Enqueue Select2 CSS
        wp_enqueue_style(
            'select2-css',
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            array(),
            '4.1.0'
        );
        
        // Enqueue custom CSS
        wp_enqueue_style(
            'customize-ui-css',
            CUSTOMIZE_UI_PLUGIN_URL . 'assets/css/customize-ui.css',
            array('select2-css'),
            CUSTOMIZE_UI_VERSION
        );
        
        // Enqueue jQuery
        wp_enqueue_script('jquery');
        
        // Enqueue Select2 JS
        wp_enqueue_script(
            'select2-js',
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            array('jquery'),
            '4.1.0',
            true
        );
        
        // Enqueue admin script
        wp_enqueue_script(
            'customize-ui-admin',
            CUSTOMIZE_UI_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery', 'select2-js'),
            CUSTOMIZE_UI_VERSION,
            true
        );
    }
    
    /**
     * Add custom script to initialize Select2
     */
    public function add_script() {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Initialize Select2 on elements with class "listdom-category"
            $('.listdom-category').select2({
                placeholder: 'Select an option...',
                allowClear: true,
                width: '100%',
                theme: 'classic'
            });
            
            // Handle dynamic content (if elements are added after page load)
            $(document).on('DOMNodeInserted', function(e) {
                if ($(e.target).hasClass('listdom-category') || $(e.target).find('.listdom-category').length) {
                    $(e.target).find('.listdom-category').select2({
                        placeholder: 'Select an option...',
                        allowClear: true,
                        width: '100%',
                        theme: 'classic'
                    });
                }
            });
        });
        </script>
        <?php
    }
    
    /**
     * Custom translations for specific strings
     */
    public function custom_translations($translated_text, $text, $domain) {
        // Only apply to 'listdom' domain
        if ($domain === 'listdom') {
            switch ($text) {
                case '« Previous':
                    return '<';
                case 'Next »':
                    return '>';
                case 'Previous':
                    return '<';
                case 'Next':
                    return '>';
                default:
                    return $translated_text;
            }
        }
        
        return $translated_text;
    }
    
    /**
     * Add meta box for listdom categories
     */
    public function add_listdom_category_meta_box() {
        add_meta_box(
            'listdom_categories_meta_box',
            'Listdom Categories',
            array($this, 'render_listdom_category_meta_box'),
            'listdom-listing',
            'side',
            'high'
        );
    }
    
    /**
     * Render meta box content
     */
    public function render_listdom_category_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('save_listdom_categories', 'listdom_categories_nonce');
        
        // Get existing categories
        $existing_categories = get_post_meta($post->ID, '_listdom_categories', true);
        if (!is_array($existing_categories)) {
            $existing_categories = array();
        }
        
        // Get all available categories (you may need to adjust this based on your taxonomy)
        $categories = get_terms(array(
            'taxonomy' => 'listdom-category',
            'hide_empty' => false,
        ));
        
        echo '<div class="listdom-categories-container">';
        echo '<select id="listdom_categories" name="listdom_categories[]" class="listdom-category-admin" multiple style="width: 100%;">';
        
        foreach ($categories as $category) {
            $selected = in_array($category->term_id, $existing_categories) ? 'selected' : '';
            echo '<option value="' . esc_attr($category->term_id) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
        }
        
        echo '</select>';
        echo '<p class="description">Select multiple categories for this listing. Use Ctrl/Cmd + click to select multiple options.</p>';
        echo '</div>';
    }
    
    /**
     * Save listdom categories
     */
    public function save_listdom_categories($post_id) {
        // Check if nonce is valid
        if (!isset($_POST['listdom_categories_nonce']) || !wp_verify_nonce($_POST['listdom_categories_nonce'], 'save_listdom_categories')) {
            return;
        }
        
        // Check if user has permission
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Save categories
        if (isset($_POST['listdom_categories']) && is_array($_POST['listdom_categories'])) {
            $categories = array_map('intval', $_POST['listdom_categories']);
            update_post_meta($post_id, '_listdom_categories', $categories);
            
            // Also set the terms for the taxonomy (if using WordPress taxonomy)
            wp_set_object_terms($post_id, $categories, 'listdom-category');
        } else {
            // If no categories selected, save empty array
            update_post_meta($post_id, '_listdom_categories', array());
            wp_set_object_terms($post_id, array(), 'listdom-category');
        }
            }
    }
    
    /**
     * Helper function to get listdom categories for a post
     */
    function get_listdom_categories($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        $categories = get_post_meta($post_id, '_listdom_categories', true);
        if (!is_array($categories)) {
            $categories = array();
        }
        
        return $categories;
    }
    
    /**
     * Helper function to get listdom category names for a post
     */
    function get_listdom_category_names($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        $category_ids = get_listdom_categories($post_id);
        $category_names = array();
        
        foreach ($category_ids as $category_id) {
            $term = get_term($category_id, 'listdom-category');
            if ($term && !is_wp_error($term)) {
                $category_names[] = $term->name;
            }
        }
        
        return $category_names;
    }
    
    /**
     * Helper function to display listdom categories
     */
    function display_listdom_categories($post_id = null, $separator = ', ') {
        $category_names = get_listdom_category_names($post_id);
        return implode($separator, $category_names);
    }
    
    // Initialize the plugin
    new CustomizeUI();
