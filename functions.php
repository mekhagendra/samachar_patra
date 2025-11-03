<?php
/**
 * Samachar Patra Theme Functions
 *
 * @package Samachar_Patra
 * @version 2.0.0
 * @author Samachar Patra Team
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('SAMACHARPATRA_VERSION', '2.0.0');
define('SAMACHARPATRA_THEME_DIR', get_template_directory());
define('SAMACHARPATRA_THEME_URI', get_template_directory_uri());


/**
 * Safe file loader - ADD THIS FUNCTION FIRST
 */
function samacharpatra_safe_include($file, $required = false) {
    $path = SAMACHARPATRA_THEME_DIR . $file;
    
    if (file_exists($path)) {
        if ($required) {
            require_once $path;
        } else {
            include_once $path;
        }
        return true;
    }
    
    if ($required) {
        error_log("Samachar Patra: Missing required file - {$file}");
        // Create minimal fallback for critical files
        if (strpos($file, 'setup.php') !== false) {
            add_action('after_setup_theme', function() {
            add_theme_support('post-thumbnails');
            add_theme_support('title-tag');
            });
        }
    }
    
    return false;
}
/**
 * Load core files with safe loader
 */
samacharpatra_safe_include('/inc/core/setup.php', true);
samacharpatra_safe_include('/inc/core/enqueue.php', true);
samacharpatra_safe_include('/inc/core/security.php', true);
samacharpatra_safe_include('/inc/hooks/smart-date.php', true);

/**
 * Optimize upload functionality
 */
function samacharpatra_optimize_uploads() {
    if (function_exists('ini_get') && ini_get('memory_limit') && (int)ini_get('memory_limit') < 256) {
        @ini_set('memory_limit', '256M');
    }
    
    @ini_set('max_execution_time', 300);
    @ini_set('upload_max_filesize', '32M');
    @ini_set('post_max_size', '32M');
}
add_action('init', 'samacharpatra_optimize_uploads');
/**
 * Ensure proper MIME types for uploads
 */
function samacharpatra_upload_mimes($mimes) {
    $mimes['jpg|jpeg|jpe'] = 'image/jpeg';
    $mimes['gif'] = 'image/gif';
    $mimes['png'] = 'image/png';
    $mimes['webp'] = 'image/webp';
    $mimes['svg'] = 'image/svg+xml';
    
    return $mimes;
}
add_filter('upload_mimes', 'samacharpatra_upload_mimes');

/**
 * Load helpers
 */
samacharpatra_safe_include('/inc/helpers.php', true);

/**
 * Load customizer
 */
samacharpatra_safe_include('/inc/customizer/controls.php', false);
samacharpatra_safe_include('/inc/customizer/register.php', false);

/**
 * Legacy includes
 */
$legacy_includes = array(
    '/includes/ads-manager.php',
    '/inc/forex-widget.php',
    '/inc/forex-shortcodes.php', 
    '/inc/nepse-widget.php',
    '/inc/nepse-shortcodes.php'
);

foreach ($legacy_includes as $file) {
    samacharpatra_safe_include($file, false);
}

/**
 * Widget includes
 */
$widget_files = array(
    '/widgets/province-full-widget.php',
    '/widgets/test-widget.php',
    '/widgets/province-widget.php',
    '/widgets/interview4x-widget.php',
    '/widgets/news-8col-widget.php'
);

foreach ($widget_files as $widget_file) {
    samacharpatra_safe_include($widget_file, false);
}

/**
 * Register widgets
 */
function samacharpatra_register_widgets() {
    $widgets = array(
        'Samacharpatra_Province_Full_Widget',
        'Test_Simple_Widget', 
        'Samacharpatra_Interview4X_Widget',
        'News_8Col_Widget'
    );

    foreach ($widgets as $widget_class) {
        if (class_exists($widget_class)) {
            register_widget($widget_class);
        }
    }
}
add_action('widgets_init', 'samacharpatra_register_widgets');




/**
 * Theme initialization
 */
function samacharpatra_init() {
    // Add theme support for post views tracking
    add_action('wp_head', 'samacharpatra_track_post_views');
    
    // Remove featured image from content
    add_filter('the_content', 'samacharpatra_remove_featured_image_from_content', 20);
    
    // Prevent automatic featured image insertion
    if (is_single()) {
        remove_filter('the_content', 'prepend_attachment', 10);
        remove_filter('the_content', 'wp_make_content_images_responsive', 10);
    }
    
    // Ensure categories exist
    if (!get_option('samacharpatra_default_categories_created')) {
        samacharpatra_create_default_categories();
    }
}
add_action('init', 'samacharpatra_init');

/**
 * Load additional functionality based on context
 */

// Admin-only includes
if (is_admin()) {
    // Future: Admin-specific functionality
    $admin_files = array();
    
    foreach ($admin_files as $admin_file) {
        $admin_path = SAMACHARPATRA_THEME_DIR . '/inc/admin/' . $admin_file;
        if (file_exists($admin_path)) {
            require_once $admin_path;
        }
    }
}

// AJAX includes
if (defined('DOING_AJAX') && DOING_AJAX) {
    // Future: AJAX-specific functionality
}

// API includes (if needed)
if (defined('REST_REQUEST') && REST_REQUEST) {
    $api_files = array(
        '/inc/api/rest-endpoints.php'
    );
    
    foreach ($api_files as $api_file) {
        $api_path = SAMACHARPATRA_THEME_DIR . $api_file;
        if (file_exists($api_path)) {
            require_once $api_path;
        }
    }
}

/**
 * Backward compatibility functions
 * These maintain compatibility with existing widgets and functionality
 */

// Legacy component helper (backward compatibility)
if (!function_exists('samacharpatra_component')) {
    /**
     * Load component (backward compatibility version)
     * Now redirects to new template structure
     */
    function samacharpatra_component_legacy($component, $data = array()) {
        // Map old component paths to new structure
        $component_map = array(
            'header' => 'parts/header/header',
            'footer' => 'parts/footer/footer', 
            'interview' => 'components/interview'
        );
        
        $new_component = isset($component_map[$component]) ? $component_map[$component] : $component;
        
        if (function_exists('samacharpatra_component')) {
            samacharpatra_component($new_component, $data);
        } else {
            // Fallback to old method
            $template_path = get_template_directory() . '/components/' . $component . '.php';
            if (file_exists($template_path)) {
                if (!empty($data)) {
                    extract($data);
                }
                include $template_path;
            }
        }
    }
}

/**
 * AJAX handlers for ads management and other features
 */

// AJAX: Delete ad
function sp_ajax_delete_ad() {
    check_ajax_referer('sp_ads_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions');
    }
    
    $ad_id = intval($_POST['ad_id']);
    
    global $wpdb;
    $table_ads = $wpdb->prefix . 'sp_ads';
    $table_stats = $wpdb->prefix . 'sp_ad_stats';
    
    $deleted = $wpdb->delete($table_ads, array('id' => $ad_id));
    $wpdb->delete($table_stats, array('ad_id' => $ad_id));
    
    if ($deleted) {
        wp_send_json_success('Ad deleted successfully');
    } else {
        wp_send_json_error('Failed to delete ad');
    }
}
add_action('wp_ajax_sp_delete_ad', 'sp_ajax_delete_ad');

// AJAX: Toggle ad status
function sp_ajax_toggle_ad_status() {
    check_ajax_referer('sp_ads_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions');
    }
    
    $ad_id = intval($_POST['ad_id']);
    
    global $wpdb;
    $table_ads = $wpdb->prefix . 'sp_ads';
    
    $current_status = $wpdb->get_var($wpdb->prepare(
        "SELECT status FROM {$table_ads} WHERE id = %d",
        $ad_id
    ));
    
    $new_status = $current_status === 'active' ? 'inactive' : 'active';
    
    $updated = $wpdb->update(
        $table_ads,
        array('status' => $new_status),
        array('id' => $ad_id)
    );
    
    if ($updated !== false) {
        wp_send_json_success(array('new_status' => $new_status));
    } else {
        wp_send_json_error('Failed to update status');
    }
}
add_action('wp_ajax_sp_toggle_ad_status', 'sp_ajax_toggle_ad_status');

/**
 * Theme activation/deactivation hooks
 */

// Flush rewrite rules on theme activation
function samacharpatra_flush_rewrite_rules() {
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'samacharpatra_flush_rewrite_rules');

// Cleanup on theme deactivation
function samacharpatra_theme_deactivation() {
    // Future: Cleanup tasks when theme is deactivated
    flush_rewrite_rules();
}
add_action('switch_theme', 'samacharpatra_theme_deactivation');

/**
 * Development and debugging helpers
 */
if (defined('WP_DEBUG') && WP_DEBUG) {
    /**
     * Component documentation dashboard widget
     */
    function samacharpatra_component_docs() {
        if (!current_user_can('administrator')) {
            return;
        }
        
        $components = samacharpatra_get_components();
        
        echo '<div class="component-docs">';
        echo '<h3>Available Components (' . count($components) . ')</h3>';
        echo '<ul>';
        
        foreach ($components as $component) {
            echo '<li><strong>' . esc_html($component) . '</strong>';
            
            // Try to read component description from file comments
            $component_file = SAMACHARPATRA_THEME_DIR . '/templates/' . $component . '.php';
            if (file_exists($component_file)) {
                $content = file_get_contents($component_file);
                if (preg_match('/\/\*\*\s*\n\s*\*\s*(.+?)\s*\n/', $content, $matches)) {
                    echo ' - ' . esc_html(trim($matches[1]));
                }
            }
            
            echo '</li>';
        }
        
        echo '</ul>';
        echo '<p><em>New organized structure: templates/parts/ and templates/components/</em></p>';
        echo '</div>';
    }

    // Add debug dashboard widget
    add_action('wp_dashboard_setup', function() {
        wp_add_dashboard_widget(
            'samacharpatra_components', 
            'Theme Components (Debug)', 
            'samacharpatra_component_docs'
        );
    });
}

/**
 * Template redirect for new component structure
 */
function samacharpatra_template_redirect() {
    // Future: Handle any template redirects or routing if needed
}
add_action('template_redirect', 'samacharpatra_template_redirect');

/**
 * Theme information and credits
 */
function samacharpatra_theme_info() {
    return array(
        'name' => 'Samachar Patra',
        'version' => SAMACHARPATRA_VERSION,
        'author' => 'Samachar Patra Team', 
        'description' => 'Modern Nepali news theme with organized architecture',
        'structure' => '2.0 - Organized with assets/, inc/, templates/ structure'
    );
}