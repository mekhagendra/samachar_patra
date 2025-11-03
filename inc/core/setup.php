<?php
/**
 * Theme Setup
 * 
 * Core theme setup including theme supports, menus, image sizes, etc.
 *
 * @package Samachar_Patra
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Theme setup function
 * 
 * Sets up theme defaults and registers support for various WordPress features.
 */
function samacharpatra_setup() {
    // Make theme available for translation
    load_theme_textdomain('samacharpatra', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Custom logo support
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array('site-title', 'site-description'),
        'unlink-homepage-logo' => false,
    ));

    // Custom background support
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ));

    // Custom header support
    add_theme_support('custom-header', array(
        'default-image'      => '',
        'default-text-color' => '000',
        'width'              => 1200,
        'height'             => 300,
        'flex-height'        => true,
        'flex-width'         => true,
    ));

    // Switch default core markup for search form, comment form, and comments
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for post formats
    add_theme_support('post-formats', array(
        'aside',
        'gallery',
        'link',
        'image',
        'quote',
        'status',
        'video',
        'audio',
        'chat',
    ));

    // Add support for block styles
    add_theme_support('wp-block-styles');

    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Enqueue editor styles
    add_editor_style('assets/css/style.css');

    // Add custom image sizes (reduced to prevent memory issues)
    add_image_size('samacharpatra-featured', 800, 400, true);
    add_image_size('samacharpatra-medium', 400, 250, true);
    add_image_size('samacharpatra-thumbnail', 300, 200, true);
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'samacharpatra'),
        'top'     => __('Top Menu', 'samacharpatra'),
        'footer'  => __('Footer Menu', 'samacharpatra'),
        'social'  => __('Social Links Menu', 'samacharpatra'),
    ));

    // Set content width
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'samacharpatra_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function samacharpatra_content_width() {
    $GLOBALS['content_width'] = apply_filters('samacharpatra_content_width', 1200);
}
add_action('after_setup_theme', 'samacharpatra_content_width', 0);

/**
 * Register widget area.
 */
function samacharpatra_widgets_init() {
    register_sidebar(array(
        'name'          => __('Main Sidebar', 'samacharpatra'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'samacharpatra'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Homepage Widget Area', 'samacharpatra'),
        'id'            => 'homepage-widgets',
        'description'   => __('Add widgets here to appear on your homepage.', 'samacharpatra'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 1', 'samacharpatra'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in your footer.', 'samacharpatra'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 2', 'samacharpatra'),
        'id'            => 'footer-2',
        'description'   => __('Add widgets here to appear in your footer.', 'samacharpatra'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 3', 'samacharpatra'),
        'id'            => 'footer-3',
        'description'   => __('Add widgets here to appear in your footer.', 'samacharpatra'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 4', 'samacharpatra'),
        'id'            => 'footer-4',
        'description'   => __('Add widgets here to appear in your footer.', 'samacharpatra'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'samacharpatra_widgets_init');

/**
 * Custom navigation walker for Bootstrap-style dropdowns
 */
class Samacharpatra_Nav_Walker extends Walker_Nav_Menu {
    
    public function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }
    
    public function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Check if item has children
        $has_children = in_array('menu-item-has-children', $classes);
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        $item_output = $args->before ?? '';
        $item_output .= ($args->link_before ?? '') . apply_filters('the_title', $item->title, $item->ID) . ($args->link_after ?? '');
        $item_output .= $args->after ?? '';
        if ($has_children && $depth === 0) {
            $item_output .= ' <i class="fas fa-chevron-down"></i>';
        }
        $item_output .= '</a>';
        $item_output .= $args->after ?? '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    public function end_el(&$output, $item, $depth = 0, $args = array()) {
        $output .= "</li>\n";
    }
}

/**
 * Create default categories for news site
 */
function samacharpatra_create_default_categories() {
    // Prevent running multiple times
    if (get_option('samacharpatra_default_categories_created')) {
        return;
    }

    $default_categories = array(
        'राजनीति' => 'politics',
        'अर्थतन्त्र' => 'economy',
        'खेलकुद' => 'sports',
        'मनोरञ्जन' => 'entertainment',
        'प्रविधि' => 'technology',
        'स्वास्थ्य' => 'health',
        'शिक्षा' => 'education',
        'अन्तर्राष्ट्रिय' => 'international',
        'समाज' => 'society'
    );
    
    foreach ($default_categories as $name => $slug) {
        // Check if category already exists
        if (!term_exists($slug, 'category')) {
            // Use wp_insert_term() NOT wp_insert_category() (deprecated)
            $result = wp_insert_term(
                $name,
                'category',
                array(
                    'slug' => $slug,
                    'description' => sprintf(__('%s category', 'samacharpatra'), $name)
                )
            );
            
            // Log any errors
            if (is_wp_error($result)) {
                error_log('Category creation failed: ' . $result->get_error_message());
            }
        }
    }

    // Mark as completed
    update_option('samacharpatra_default_categories_created', true);
}
// CRITICAL: Run on theme activation, NOT every page load
add_action('after_switch_theme', 'samacharpatra_create_default_categories');

/**
 * Create interview category
 */
function samacharpatra_create_interview_category() {
    $interview_category = get_category_by_slug('interview');
    
    if (!$interview_category) {
        // Use wp_insert_term() NOT wp_insert_category()
        $result = wp_insert_term(
            'Interview',
            'category',
            array(
                'description' => 'Interview posts and Q&A sessions',
                'slug' => 'interview'
            )
        );
        
        if (!is_wp_error($result)) {
            error_log('Interview category created with ID: ' . $result['term_id']);
        } else {
            error_log('Interview category creation failed: ' . $result->get_error_message());
        }
    }
}
add_action('after_switch_theme', 'samacharpatra_create_interview_category');