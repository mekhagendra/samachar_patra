<?php
/**
 * Enqueue Scripts and Styles
 * 
 * Handles loading of CSS and JavaScript files for the theme.
 *
 * @package Samachar_Patra
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Enqueue scripts and styles
 */
function samacharpatra_scripts() {
    // Main consolidated stylesheet with cache-busting version
    wp_enqueue_style(
        'samacharpatra-style', 
        get_template_directory_uri() . '/assets/css/style.css', 
        array(), 
        filemtime(get_template_directory() . '/assets/css/style.css')
    );
    
    // Font Awesome with fallback
    wp_enqueue_style(
        'font-awesome', 
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', 
        array(), 
        '6.0.0'
    );
    
    // Fallback Font Awesome from different CDN
    wp_enqueue_style(
        'font-awesome-fallback', 
        'https://maxcdn.bootstrapcdn.com/font-awesome/6.0.0/css/font-awesome.min.css', 
        array(), 
        '6.0.0'
    );
    
    // Google Fonts - Remove this since it's now included in the consolidated CSS via @import
    // wp_enqueue_style(
    //     'google-fonts', 
    //     'https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Mukti:wght@400;500;600;700&display=swap', 
    //     array(), 
    //     null
    // );
    
    // Note: Widget styles, admin styles, and ads admin styles are now included in the main stylesheet
    // They are conditionally applied via CSS rules or body classes
    
    // Consolidated theme script (contains all frontend functionality)
    wp_enqueue_script(
        'samacharpatra-theme', 
        get_template_directory_uri() . '/assets/js/theme.js', 
        array(), 
        filemtime(get_template_directory() . '/assets/js/theme.js'), 
        true
    );
    
    // Font Awesome fallback detection script
    wp_add_inline_script('samacharpatra-theme', '
        // Check if Font Awesome loaded properly
        document.addEventListener("DOMContentLoaded", function() {
            var testIcon = document.createElement("i");
            testIcon.className = "fas fa-home";
            testIcon.style.position = "absolute";
            testIcon.style.visibility = "hidden";
            document.body.appendChild(testIcon);
            
            // Check computed styles
            var computedStyle = window.getComputedStyle(testIcon, ":before");
            var fontFamily = computedStyle.getPropertyValue("font-family");
            
            // If Font Awesome didn\'t load, add fallback class
            if (!fontFamily || fontFamily.indexOf("Font Awesome") === -1) {
                document.documentElement.classList.add("no-fontawesome");
            }
            
            // Clean up test element
            document.body.removeChild(testIcon);
        });
    ');
    
    // Comment reply
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Localize scripts for AJAX
    wp_localize_script('samacharpatra-theme', 'samacharpatra_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('samacharpatra_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'samacharpatra_scripts');

/**
 * Enqueue admin scripts and styles
 */
function samacharpatra_enqueue_admin_scripts($hook) {
    // Load on widgets page and customizer
    if ('widgets.php' == $hook || 'customize.php' == $hook || 'post.php' == $hook || 'post-new.php' == $hook) {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-sortable');

        // Ads admin script
        wp_enqueue_script(
            'ads-admin',
            get_template_directory_uri() . '/assets/js/ads-admin.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // TinyMCE ads plugin
        wp_enqueue_script(
            'ads-tinymce',
            get_template_directory_uri() . '/assets/js/ads-tinymce.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Add custom admin script for widgets
        wp_add_inline_script('jquery', '
            jQuery(document).ready(function($) {
                console.log("WordPress Admin jQuery loaded for widgets");
                
                // Reinitialize widget scripts when widgets are saved or updated
                $(document).on("widget-added widget-updated", function(event, widget) {
                    console.log("Widget event triggered:", event.type);
                    // Trigger custom initialization for Interview4X widgets
                    if (widget && widget.find(".interview4x-widget-admin").length > 0) {
                        setTimeout(function() {
                            $(widget).find(".interview4x-widget-admin").each(function() {
                                console.log("Reinitializing Interview4X widget");
                                // Trigger a custom event that our widget can listen to
                                $(this).trigger("interview4x-reinit");
                            });
                        }, 100);
                    }
                });
            });
        ');
    }

    // Admin styles
    wp_enqueue_style(
        'samacharpatra-admin', 
        get_template_directory_uri() . '/assets/css/admin-style.css'
    );
}
add_action('admin_enqueue_scripts', 'samacharpatra_enqueue_admin_scripts');

/**
 * Add preload for critical resources
 */
function samacharpatra_preload_resources() {
    // Preload critical CSS
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/css/style.css" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
    
    // Preload Google Fonts
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Mukti:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
    
    // Fallback for no-JS
    echo '<noscript><link rel="stylesheet" href="' . get_template_directory_uri() . '/assets/css/style.css"></noscript>' . "\n";
    echo '<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Mukti:wght@400;500;600;700&display=swap"></noscript>' . "\n";
}
add_action('wp_head', 'samacharpatra_preload_resources', 1);

/**
 * CSS Debugging for Server Issues
 */
function samacharpatra_css_debug() {
    if (current_user_can('administrator') && isset($_GET['css_debug'])) {
        echo '<div style="position: fixed; top: 0; left: 0; background: #000; color: #fff; padding: 10px; z-index: 9999; font-size: 12px;">';
        echo '<strong>CSS Debug Info:</strong><br>';
        echo 'Theme Directory: ' . get_template_directory_uri() . '<br>';
        echo 'Stylesheet URI: ' . get_stylesheet_uri() . '<br>';
        echo 'CSS File Exists: ' . (file_exists(get_template_directory() . '/assets/css/style.css') ? 'Yes' : 'No') . '<br>';
        echo 'CSS File Size: ' . (file_exists(get_template_directory() . '/assets/css/style.css') ? filesize(get_template_directory() . '/assets/css/style.css') . ' bytes' : 'N/A') . '<br>';
        echo 'CSS Modified: ' . (file_exists(get_template_directory() . '/assets/css/style.css') ? date('Y-m-d H:i:s', filemtime(get_template_directory() . '/assets/css/style.css')) : 'N/A') . '<br>';
        echo '</div>';
    }
}
add_action('wp_head', 'samacharpatra_css_debug');

// Note: Version removal function is handled in security.php