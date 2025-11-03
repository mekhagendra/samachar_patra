<?php
/**
 * Security Functions
 * 
 * Handles security headers, sanitization, and other security measures.
 *
 * @package Samachar_Patra
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add security headers (conditional for upload functionality)
 */
function samacharpatra_security_headers() {
    // Skip some headers on upload pages to prevent blocking
    $is_upload_page = is_admin() && (
        strpos($_SERVER['REQUEST_URI'], 'upload.php') !== false || 
        strpos($_SERVER['REQUEST_URI'], 'media-new.php') !== false ||
        strpos($_SERVER['REQUEST_URI'], 'admin-ajax.php') !== false
    );
    
    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Prevent framing (clickjacking protection) - but allow for upload modals
    if (!$is_upload_page) {
        header('X-Frame-Options: SAMEORIGIN');
    }
    
    // Enable XSS filtering - but be less restrictive on upload pages
    if (!$is_upload_page) {
        header('X-XSS-Protection: 1; mode=block');
    }
    
    // Referrer policy - more permissive for uploads
    if ($is_upload_page) {
        header('Referrer-Policy: same-origin');
    } else {
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
    
    // Content Security Policy (basic) - Only apply on frontend, not admin
    if (!is_admin() && !is_customize_preview() && !wp_doing_ajax()) {
        $csp = "default-src 'self'; ";
        $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' *.googleapis.com *.gstatic.com *.cdnjs.cloudflare.com *.maxcdn.bootstrapcdn.com; ";
        $csp .= "style-src 'self' 'unsafe-inline' *.googleapis.com *.gstatic.com *.cdnjs.cloudflare.com *.maxcdn.bootstrapcdn.com; ";
        $csp .= "font-src 'self' *.googleapis.com *.gstatic.com *.cdnjs.cloudflare.com; ";
        $csp .= "img-src 'self' data: blob: *.gravatar.com *.wordpress.com; ";
        $csp .= "connect-src 'self' *.wordpress.com; ";
        $csp .= "frame-ancestors 'self'; ";
        
        header("Content-Security-Policy: $csp");
    }
}
add_action('send_headers', 'samacharpatra_security_headers');

/**
 * Remove WordPress version from RSS feeds
 */
function samacharpatra_remove_version() {
    return '';
}
add_filter('the_generator', 'samacharpatra_remove_version');

/**
 * Disable XML-RPC
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Remove XML-RPC pingback ping
 */
function samacharpatra_remove_xmlrpc_ping($methods) {
    unset($methods['pingback.ping']);
    return $methods;
}
add_filter('xmlrpc_methods', 'samacharpatra_remove_xmlrpc_ping');

/**
 * Disable XML-RPC methods
 */
function samacharpatra_disable_xmlrpc($action) {
    if ($action === 'xmlrpc') {
        wp_die('XML-RPC is disabled on this site.', 'XML-RPC Disabled', array('response' => 403));
    }
}
add_action('init', 'samacharpatra_disable_xmlrpc');

/**
 * Remove WordPress version from head
 */
remove_action('wp_head', 'wp_generator');

/**
 * Hide login errors
 */
function samacharpatra_login_errors() {
    return 'Login failed. Please check your credentials.';
}
add_filter('login_errors', 'samacharpatra_login_errors');

/**
 * Remove version from scripts and styles
 */
function samacharpatra_remove_version_scripts_styles($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'samacharpatra_remove_version_scripts_styles', 9999);
add_filter('script_loader_src', 'samacharpatra_remove_version_scripts_styles', 9999);

/**
 * Disable file editing from admin
 */
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

/**
 * Limit login attempts (basic implementation)
 */
function samacharpatra_check_attempted_login($user, $username, $password) {
    if (get_transient('attempted_login')) {
        $datas = get_transient('attempted_login');
        
        if ($datas['tried'] >= 3) {
            $until = get_option('_transient_timeout_attempted_login');
            $time = samacharpatra_time_to_go($until);
            
            return new WP_Error('too_many_tries', sprintf(__('You have reached authentication limit, you will be able to try again in %1$s.'), $time));
        }
    }
    
    return $user;
}
add_filter('authenticate', 'samacharpatra_check_attempted_login', 30, 3);

/**
 * Login failed function
 */
function samacharpatra_login_failed($username) {
    if (get_transient('attempted_login')) {
        $datas = get_transient('attempted_login');
        $datas['tried']++;
        
        if ($datas['tried'] <= 3) {
            set_transient('attempted_login', $datas, 300); // 5 minutes
        }
    } else {
        $datas = array(
            'tried' => 1
        );
        set_transient('attempted_login', $datas, 300); // 5 minutes
    }
}
add_action('wp_login_failed', 'samacharpatra_login_failed', 10, 1);

/**
 * Calculate time to wait
 */
function samacharpatra_time_to_go($timestamp) {
    $periods = array('week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute' => 60, 'second' => 1);
    $seconds = $timestamp - time();
    
    foreach ($periods as $name => $seconds_in_period) {
        if ($seconds >= $seconds_in_period) {
            $period_value = floor($seconds / $seconds_in_period);
            if ($period_value == 1) {
                return "1 $name";
            } else {
                return "$period_value ${name}s";
            }
        }
    }
    
    return '1 second';
}

/**
 * Remove unnecessary header information
 */
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * Disable REST API for non-authenticated users (optional)
 */
function samacharpatra_disable_rest_api($access) {
    if (!is_user_logged_in()) {
        return new WP_Error('rest_disabled', __('REST API is disabled for non-authenticated users.'), array('status' => 401));
    }
    return $access;
}
// Uncomment the line below if you want to disable REST API for non-authenticated users
// add_filter('rest_authentication_errors', 'samacharpatra_disable_rest_api');

/**
 * Sanitize file names on upload (less aggressive)
 */
function samacharpatra_sanitize_file_name($filename) {
    // Skip sanitization in admin upload area to prevent blocking
    if (is_admin() && (strpos($_SERVER['REQUEST_URI'], 'upload.php') !== false || 
                       strpos($_SERVER['REQUEST_URI'], 'media-new.php') !== false || 
                       wp_doing_ajax())) {
        return $filename;
    }
    
    $filename = sanitize_file_name($filename);
    $filename = str_replace(' ', '-', $filename);
    // Less aggressive - allow Unicode characters for international file names
    $filename = preg_replace('/[<>:"/\\|?*]/', '', $filename);
    return $filename;
}
add_filter('sanitize_file_name', 'samacharpatra_sanitize_file_name', 10, 1);

/**
 * Prevent direct access to PHP files
 */
function samacharpatra_prevent_direct_access() {
    if (!defined('ABSPATH')) {
        exit('Direct access not permitted.');
    }
}

/**
 * Block suspicious user agents
 */
function samacharpatra_block_bad_queries() {
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $bad_agents = array('emailcollector', 'emailsiphon', 'emailwolf', 'extractorpro', 'copyrightcheck');
    
    foreach ($bad_agents as $agent) {
        if (stripos($user_agent, $agent) !== false) {
            status_header(403);
            exit('Forbidden');
        }
    }
}
add_action('init', 'samacharpatra_block_bad_queries');

/**
 * Secure cookie settings
 */
function samacharpatra_secure_cookies() {
    if (is_ssl()) {
        ini_set('session.cookie_secure', 1);
        ini_set('session.cookie_httponly', 1);
    }
}
add_action('init', 'samacharpatra_secure_cookies');