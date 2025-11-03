<?php
/**
 * Debug Functions - Minimal version to test activation
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
 * Test each include individually to find the problematic file
 */
function samacharpatra_debug_includes() {
    $files_to_test = array(
        '/inc/core/setup.php' => 'Theme Setup',
        '/inc/core/enqueue.php' => 'Scripts & Styles',
        '/inc/core/security.php' => 'Security Functions',
        '/inc/hooks/smart-date.php' => 'Smart Date System',
        '/inc/helpers.php' => 'Helper Functions',
        '/inc/customizer/controls.php' => 'Customizer Controls',
        '/inc/customizer/register.php' => 'Customizer Register'
    );
    
    $debug_output = array();
    
    foreach ($files_to_test as $file => $description) {
        $full_path = SAMACHARPATRA_THEME_DIR . $file;
        
        if (!file_exists($full_path)) {
            $debug_output[] = "❌ MISSING: {$description} ({$file})";
            continue;
        }
        
        // Test if file can be included without errors
        ob_start();
        $error_occurred = false;
        
        try {
            include_once $full_path;
            $debug_output[] = "✅ SUCCESS: {$description}";
        } catch (ParseError $e) {
            $error_occurred = true;
            $debug_output[] = "❌ PARSE ERROR in {$description}: " . $e->getMessage();
        } catch (FatalError $e) {
            $error_occurred = true;
            $debug_output[] = "❌ FATAL ERROR in {$description}: " . $e->getMessage();
        } catch (Error $e) {
            $error_occurred = true;
            $debug_output[] = "❌ ERROR in {$description}: " . $e->getMessage();
        } catch (Exception $e) {
            $error_occurred = true;
            $debug_output[] = "❌ EXCEPTION in {$description}: " . $e->getMessage();
        }
        
        ob_end_clean();
        
        if ($error_occurred) {
            break; // Stop at first error
        }
    }
    
    // Store debug info for display
    update_option('samacharpatra_debug_info', $debug_output);
}

// Run debug on theme activation
add_action('after_setup_theme', 'samacharpatra_debug_includes', 1);

/**
 * Display debug info for admin
 */
function samacharpatra_show_debug() {
    if (!current_user_can('administrator')) return;
    
    $debug_info = get_option('samacharpatra_debug_info', array());
    
    if (!empty($debug_info)) {
        echo '<div style="background: #fff; border: 1px solid #ccc; padding: 20px; margin: 20px; font-family: monospace;">';
        echo '<h3>🔍 Theme Debug Information</h3>';
        foreach ($debug_info as $info) {
            echo '<p>' . esc_html($info) . '</p>';
        }
        echo '<p><em>Add ?clear_debug=1 to URL to clear this message</em></p>';
        echo '</div>';
    }
    
    if (isset($_GET['clear_debug'])) {
        delete_option('samacharpatra_debug_info');
    }
}
add_action('wp_head', 'samacharpatra_show_debug');
add_action('admin_notices', 'samacharpatra_show_debug');

/**
 * Minimal theme setup (as fallback)
 */
function samacharpatra_minimal_setup() {
    // Essential theme supports
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', array('search-form', 'gallery', 'caption'));
    
    // Register basic navigation
    register_nav_menus(array(
        'primary' => 'Primary Menu',
        'footer' => 'Footer Menu',
    ));
}
add_action('after_setup_theme', 'samacharpatra_minimal_setup', 20);

/**
 * Minimal styles
 */
function samacharpatra_minimal_styles() {
    wp_enqueue_style('samacharpatra-style', get_stylesheet_uri(), array(), '2.0.0');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
}
add_action('wp_enqueue_scripts', 'samacharpatra_minimal_styles');