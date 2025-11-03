<?php
/**
 * Samachar Patra Theme - Emergency Minimal Version
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Bare minimum theme setup
function samacharpatra_theme_setup() {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'samacharpatra_theme_setup');

// Bare minimum styles
function samacharpatra_theme_styles() {
    wp_enqueue_style('samacharpatra-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'samacharpatra_theme_styles');