<?php
/**
 * Theme Customizer Registration
 * 
 * Registers all customizer settings, sections, and controls.
 *
 * @package Samachar_Patra
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function samacharpatra_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('blogname', array(
            'selector'        => '.site-title a',
            'render_callback' => 'samacharpatra_customize_partial_blogname',
        ));
        $wp_customize->selective_refresh->add_partial('blogdescription', array(
            'selector'        => '.site-description',
            'render_callback' => 'samacharpatra_customize_partial_blogdescription',
        ));
    }

    /**
     * Site Identity Section
     */
    $wp_customize->add_section('samacharpatra_site_identity', array(
        'title'    => __('Site Identity', 'samacharpatra'),
        'priority' => 30,
    ));

    // Site Description
    $wp_customize->add_setting('site_description', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('site_description', array(
        'label'   => __('Site Description', 'samacharpatra'),
        'section' => 'samacharpatra_site_identity',
        'type'    => 'text',
    ));

    /**
     * Header Section
     */
    $wp_customize->add_section('samacharpatra_header_options', array(
        'title'       => __('Header Options', 'samacharpatra'),
        'priority'    => 35,
        'description' => __('Customize header appearance and functionality.', 'samacharpatra'),
    ));

    // Header Layout
    $wp_customize->add_setting('header_layout', array(
        'default'           => 'layout1',
        'sanitize_callback' => 'samacharpatra_sanitize_select',
    ));

    $wp_customize->add_control('header_layout', array(
        'label'   => __('Header Layout', 'samacharpatra'),
        'section' => 'samacharpatra_header_options',
        'type'    => 'select',
        'choices' => array(
            'layout1' => __('Layout 1 - Logo Left, Menu Right', 'samacharpatra'),
            'layout2' => __('Layout 2 - Centered Logo, Menu Below', 'samacharpatra'),
            'layout3' => __('Layout 3 - Logo Center, Menu Sides', 'samacharpatra'),
        ),
    ));

    // Show Search Button
    $wp_customize->add_setting('show_search_button', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('show_search_button', array(
        'label'   => __('Show Search Button in Header', 'samacharpatra'),
        'section' => 'samacharpatra_header_options',
        'type'    => 'checkbox',
    ));

    // Header Phone Number
    $wp_customize->add_setting('header_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('header_phone', array(
        'label'       => __('Header Phone Number', 'samacharpatra'),
        'section'     => 'samacharpatra_header_options',
        'type'        => 'tel',
        'description' => __('Phone number to display in header.', 'samacharpatra'),
    ));

    // Header Email
    $wp_customize->add_setting('header_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ));

    $wp_customize->add_control('header_email', array(
        'label'       => __('Header Email', 'samacharpatra'),
        'section'     => 'samacharpatra_header_options',
        'type'        => 'email',
        'description' => __('Email address to display in header.', 'samacharpatra'),
    ));

    /**
     * Colors Section
     */
    $wp_customize->add_section('samacharpatra_colors', array(
        'title'    => __('Theme Colors', 'samacharpatra'),
        'priority' => 40,
    ));

    // Primary Color
    $wp_customize->add_setting('primary_color', array(
        'default'           => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
        'label'   => __('Primary Color', 'samacharpatra'),
        'section' => 'samacharpatra_colors',
    )));

    // Secondary Color
    $wp_customize->add_setting('secondary_color', array(
        'default'           => '#005177',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', array(
        'label'   => __('Secondary Color', 'samacharpatra'),
        'section' => 'samacharpatra_colors',
    )));

    // Accent Color
    $wp_customize->add_setting('accent_color', array(
        'default'           => '#ff6b35',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color', array(
        'label'   => __('Accent Color', 'samacharpatra'),
        'section' => 'samacharpatra_colors',
    )));

    /**
     * Typography Section
     */
    $wp_customize->add_section('samacharpatra_typography', array(
        'title'    => __('Typography', 'samacharpatra'),
        'priority' => 45,
    ));

    // Body Font
    $wp_customize->add_setting('body_font', array(
        'default'           => 'Roboto',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('body_font', array(
        'label'   => __('Body Font', 'samacharpatra'),
        'section' => 'samacharpatra_typography',
        'type'    => 'select',
        'choices' => array(
            'Roboto'     => 'Roboto',
            'Open Sans'  => 'Open Sans',
            'Lato'       => 'Lato',
            'Mukti'      => 'Mukti',
            'Noto Sans'  => 'Noto Sans',
        ),
    ));

    // Heading Font
    $wp_customize->add_setting('heading_font', array(
        'default'           => 'Mukti',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('heading_font', array(
        'label'   => __('Heading Font', 'samacharpatra'),
        'section' => 'samacharpatra_typography',
        'type'    => 'select',
        'choices' => array(
            'Roboto'     => 'Roboto',
            'Open Sans'  => 'Open Sans',
            'Lato'       => 'Lato',
            'Mukti'      => 'Mukti',
            'Noto Sans'  => 'Noto Sans',
        ),
    ));

    /**
     * Footer Section
     */
    $wp_customize->add_section('samacharpatra_footer_options', array(
        'title'    => __('Footer Options', 'samacharpatra'),
        'priority' => 50,
    ));

    // Footer Copyright Text
    $wp_customize->add_setting('footer_copyright', array(
        'default'           => sprintf(__('© %d %s. All rights reserved.', 'samacharpatra'), date('Y'), get_bloginfo('name')),
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('footer_copyright', array(
        'label'       => __('Footer Copyright Text', 'samacharpatra'),
        'section'     => 'samacharpatra_footer_options',
        'type'        => 'textarea',
        'description' => __('Copyright text to display in footer.', 'samacharpatra'),
    ));

    // Footer Social Links
    $social_links = array('facebook', 'twitter', 'instagram', 'youtube', 'linkedin');
    
    foreach ($social_links as $social) {
        $wp_customize->add_setting("social_{$social}", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control("social_{$social}", array(
            'label'   => sprintf(__('%s URL', 'samacharpatra'), ucfirst($social)),
            'section' => 'samacharpatra_footer_options',
            'type'    => 'url',
        ));
    }

    /**
     * Blog Options Section
     */
    $wp_customize->add_section('samacharpatra_blog_options', array(
        'title'    => __('Blog Options', 'samacharpatra'),
        'priority' => 55,
    ));

    // Blog Layout
    $wp_customize->add_setting('blog_layout', array(
        'default'           => 'sidebar-right',
        'sanitize_callback' => 'samacharpatra_sanitize_select',
    ));

    $wp_customize->add_control('blog_layout', array(
        'label'   => __('Blog Layout', 'samacharpatra'),
        'section' => 'samacharpatra_blog_options',
        'type'    => 'select',
        'choices' => array(
            'sidebar-right' => __('Sidebar Right', 'samacharpatra'),
            'sidebar-left'  => __('Sidebar Left', 'samacharpatra'),
            'no-sidebar'    => __('No Sidebar', 'samacharpatra'),
        ),
    ));

    // Show Post Excerpts
    $wp_customize->add_setting('show_post_excerpts', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('show_post_excerpts', array(
        'label'   => __('Show Post Excerpts on Archive Pages', 'samacharpatra'),
        'section' => 'samacharpatra_blog_options',
        'type'    => 'checkbox',
    ));

    // Show Post Meta
    $wp_customize->add_setting('show_post_meta', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('show_post_meta', array(
        'label'   => __('Show Post Meta Information', 'samacharpatra'),
        'section' => 'samacharpatra_blog_options',
        'type'    => 'checkbox',
    ));
}
add_action('customize_register', 'samacharpatra_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function samacharpatra_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function samacharpatra_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Sanitize select options
 */
function samacharpatra_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function samacharpatra_customize_preview_js() {
    wp_enqueue_script(
        'samacharpatra-customizer',
        get_template_directory_uri() . '/assets/js/customizer.js',
        array('customize-preview'),
        '1.0.0',
        true
    );
}
add_action('customize_preview_init', 'samacharpatra_customize_preview_js');