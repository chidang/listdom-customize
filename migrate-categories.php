<?php
/**
 * Migration script to clean up _listdom_categories meta and ensure proper taxonomy relationships
 * 
 * Run this script once to migrate existing data from meta fields to taxonomy relationships.
 * After running, you can delete this file.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Migration function to clean up meta data and ensure taxonomy relationships
 */
function migrate_listdom_categories() {
    global $wpdb;
    
    // Get all listdom-listing posts that have _listdom_categories meta
    $posts_with_meta = $wpdb->get_results("
        SELECT p.ID, pm.meta_value 
        FROM {$wpdb->posts} p 
        INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id 
        WHERE p.post_type = 'listdom-listing' 
        AND pm.meta_key = '_listdom_categories'
    ");
    
    $migrated_count = 0;
    $errors = array();
    
    foreach ($posts_with_meta as $post) {
        $category_ids = maybe_unserialize($post->meta_value);
        
        if (is_array($category_ids)) {
            // Set the taxonomy terms
            $result = wp_set_object_terms($post->ID, $category_ids, 'listdom-category');
            
            if (is_wp_error($result)) {
                $errors[] = "Error migrating post ID {$post->ID}: " . $result->get_error_message();
            } else {
                $migrated_count++;
            }
        }
    }
    
    // Delete all _listdom_categories meta
    $deleted_meta = $wpdb->delete(
        $wpdb->postmeta,
        array('meta_key' => '_listdom_categories'),
        array('%s')
    );
    
    return array(
        'migrated_posts' => $migrated_count,
        'deleted_meta' => $deleted_meta,
        'errors' => $errors
    );
}

/**
 * Admin page to run migration
 */
function add_migration_admin_page() {
    add_submenu_page(
        'edit.php?post_type=listdom-listing',
        'Migrate Categories',
        'Migrate Categories',
        'manage_options',
        'migrate-listdom-categories',
        'render_migration_page'
    );
}

/**
 * Render migration admin page
 */
function render_migration_page() {
    if (isset($_POST['run_migration']) && wp_verify_nonce($_POST['migration_nonce'], 'run_migration')) {
        $result = migrate_listdom_categories();
        
        echo '<div class="wrap">';
        echo '<h1>Category Migration Results</h1>';
        echo '<div class="notice notice-success">';
        echo '<p><strong>Migration completed!</strong></p>';
        echo '<ul>';
        echo '<li>Migrated posts: ' . $result['migrated_posts'] . '</li>';
        echo '<li>Deleted meta entries: ' . $result['deleted_meta'] . '</li>';
        echo '</ul>';
        
        if (!empty($result['errors'])) {
            echo '<div class="notice notice-error">';
            echo '<h3>Errors encountered:</h3>';
            echo '<ul>';
            foreach ($result['errors'] as $error) {
                echo '<li>' . esc_html($error) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="wrap">';
        echo '<h1>Migrate Listdom Categories</h1>';
        echo '<p>This tool will migrate existing category data from meta fields to proper WordPress taxonomy relationships.</p>';
        echo '<p><strong>Warning:</strong> This will permanently delete the <code>_listdom_categories</code> meta field after migration.</p>';
        
        echo '<form method="post">';
        wp_nonce_field('run_migration', 'migration_nonce');
        echo '<p><input type="submit" name="run_migration" class="button button-primary" value="Run Migration" /></p>';
        echo '</form>';
        echo '</div>';
    }
}

// Add admin page
add_action('admin_menu', 'add_migration_admin_page');
