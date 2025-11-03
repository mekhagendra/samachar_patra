<?php
/**
 * Samachar Patra Theme - Minimal Functions
 * Critical activation fix for server deployment
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
 * Essential theme setup only
 */
function samacharpatra_setup() {
    // Essential theme supports
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', array('search-form', 'gallery', 'caption'));
    
    // Navigation menus
    register_nav_menus(array(
        'primary' => 'Primary Menu',
        'footer' => 'Footer Menu',
    ));
    
    // Image sizes
    add_image_size('samacharpatra-large', 800, 450, true);
    add_image_size('samacharpatra-medium', 400, 225, true);
    add_image_size('samacharpatra-small', 200, 112, true);
}
add_action('after_setup_theme', 'samacharpatra_setup');

/**
 * Essential styles and scripts
 */
function samacharpatra_scripts() {
    // Main stylesheet
    wp_enqueue_style('samacharpatra-style', get_stylesheet_uri(), array(), SAMACHARPATRA_VERSION);
    
    // Additional CSS if exists
    $main_css = get_template_directory() . '/assets/css/style.css';
    if (file_exists($main_css)) {
        wp_enqueue_style('samacharpatra-main', get_template_directory_uri() . '/assets/css/style.css', array(), SAMACHARPATRA_VERSION);
    }
    
    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
    
    // Theme JS if exists
    $main_js = get_template_directory() . '/assets/js/theme.js';
    if (file_exists($main_js)) {
        wp_enqueue_script('samacharpatra-theme', get_template_directory_uri() . '/assets/js/theme.js', array('jquery'), SAMACHARPATRA_VERSION, true);
    }
}
add_action('wp_enqueue_scripts', 'samacharpatra_scripts');

/**
 * Essential Smart Date fallback functions
 */
if (!function_exists('full_date')) {
    function full_date($timestamp = null) {
        if (!$timestamp) $timestamp = time();
        return date('F j, Y', $timestamp);
    }
}

if (!function_exists('short_date')) {
    function short_date($timestamp = null) {
        if (!$timestamp) $timestamp = time();
        return date('M j', $timestamp);
    }
}

if (!function_exists('to_nepali_digits')) {
    function to_nepali_digits($number) {
        $english = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $nepali = array('०', '१', '२', '३', '४', '५', '६', '७', '८', '९');
        return str_replace($english, $nepali, $number);
    }
}

/**
 * Safe file loading with error handling
 */
function samacharpatra_safe_include($file_path) {
    $full_path = SAMACHARPATRA_THEME_DIR . $file_path;
    
    if (file_exists($full_path)) {
        // Use include instead of require to prevent fatal errors
        $result = include_once $full_path;
        return $result !== false;
    }
    
    return false;
}

/**
 * Load additional files only after successful activation
 */
function samacharpatra_load_additional_features() {
    // Core files (load first)
    samacharpatra_safe_include('/inc/core/setup.php');
    samacharpatra_safe_include('/inc/core/enqueue.php');
    samacharpatra_safe_include('/inc/core/security.php');
    
    // Smart Date (essential for theme functionality)
    samacharpatra_safe_include('/inc/hooks/smart-date.php');
    
    // Helpers (essential for widgets)
    samacharpatra_safe_include('/inc/helpers.php');
    
    // Optional features (safe to fail)
    samacharpatra_safe_include('/inc/customizer/controls.php');
    samacharpatra_safe_include('/inc/customizer/register.php');
}
add_action('after_setup_theme', 'samacharpatra_load_additional_features', 15);

/**
 * Minimal security measures
 */
// Remove WordPress version
remove_action('wp_head', 'wp_generator');

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Basic optimization for server compatibility
 */
function samacharpatra_server_optimization() {
    // Increase memory limit if possible
    if (function_exists('ini_get') && (int)ini_get('memory_limit') < 128) {
        @ini_set('memory_limit', '128M');
    }
    
    // Set reasonable execution time
    @ini_set('max_execution_time', 60);
}
add_action('init', 'samacharpatra_server_optimization');