<?php
/**
 * Header Template Part
 * 
 * Contains the HTML document head and opening body elements.
 * Displays the site header including top bar, logo, navigation.
 * 
 * @package Samachar_Patra
 * @version 2.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'samacharpatra'); ?></a>
    <!-- Header -->
    <header id="masthead" class="site-header">
        <div class="header-wrapper">
            <div class="header-content container">
                <!-- Logo Section -->
                <div class="site-branding">
                    <?php if (has_custom_logo()) : ?>
                        <div class="site-logo">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php else : ?>
                        <!-- Fallback: Show default logo and site title when no custom logo is set -->
                        <?php 
                        $fallback_logo = get_template_directory_uri() . '/assets/images/logo.png';
                        $logo_exists = file_exists(get_template_directory() . '/assets/images/logo.png');
                        ?>
                        
                        <?php if ($logo_exists) : ?>
                            <div class="site-logo fallback-logo">
                                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                    <img src="<?php echo esc_url($fallback_logo); ?>" 
                                         alt="<?php bloginfo('name'); ?>">
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="site-title-group">
                            <?php 
                            $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()) : 
                            ?>
                                <p class="site-description"><?php echo $description; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Header Banner/Ad Space -->
                <div class="header-banner">
                    <?php sp_display_ads('header_banner'); ?>
                </div>
            </div>

            <!-- Main Navigation -->
            <nav id="site-navigation" class="main-navigation " role="navigation" aria-label="Primary Navigation">
                <div class="nav-container ">
                    
                    <!-- Mobile Menu Row (Mobile Only) -->
                    <div class="mobile-menu-row container">
                        <?php
                            $logo_path = get_template_directory() . '/assets/images/logo.png';
                            $logo_url = get_template_directory_uri() . '/assets/images/logo.png';
                        ?>
                        
                        <!-- Logo on Left -->
                        <?php if (file_exists($logo_path)) : ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="mobile-logo-link">
                                <img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>" class="mobile-logo-img">
                            </a>
                        <?php else : ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="mobile-logo-link">
                                <span class="mobile-brand-text"><?php bloginfo('name'); ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <!-- Toggle Button on Right -->
                        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                    </div>
                    
                    <!-- Desktop Navigation Menu -->
                    <div class="desktop-nav-container container">
                        <!-- Sticky Navigation Icon (Hidden by default, shown when sticky) -->
                        <div class="sticky-nav-icon">
                            <?php
                                $sticky_logo_path = get_template_directory() . '/assets/images/samacharpatraicon.png';
                                $sticky_logo_url = get_template_directory_uri() . '/assets/images/samacharpatraicon.png';
                            ?>
                            <?php if (file_exists($sticky_logo_path)) : ?>
                               <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($sticky_logo_url); ?>" alt="<?php bloginfo('name'); ?>" class="sticky-logo-img"></a> 
                            <?php else : ?>
                                <span class="sticky-logo-fallback">
                                    <i class="fas fa-home"></i>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (has_nav_menu('primary')) : ?>
                            <?php
                            wp_nav_menu(array(
                                'theme_location'  => 'primary',
                                'menu_id'        => 'primary-menu',
                                'menu_class'     => 'nav-menu',
                                'container'      => 'div',
                                'container_class' => 'nav-menu-container',
                                'depth'          => 3,
                            ));
                            ?>
                        <?php else : ?>
                            <!-- Fallback menu when no primary menu is assigned -->
                            <div class="nav-menu-container">
                                <ul id="primary-menu" class="nav-menu">
                                    <li class="menu-item">
                                        <a href="<?php echo esc_url(home_url('/')); ?>">मुख्य पृष्ठ</a>
                                    </li>
                                    <?php if (current_user_can('manage_options')) : ?>
                                        <li class="menu-item">
                                            <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>">मेनु थप्नुहोस्</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Mobile Navigation Menu (Hidden by default) -->
                    <div class="mobile-nav-container" id="mobile-navigation">
                        <?php if (has_nav_menu('primary')) : ?>
                            <?php
                            wp_nav_menu(array(
                                'theme_location'  => 'primary',
                                'menu_id'        => 'mobile-menu',
                                'menu_class'     => 'mobile-nav-menu',
                                'container'      => false,
                                'depth'          => 2,
                            ));
                            ?>
                        <?php else : ?>
                            <ul id="mobile-menu" class="mobile-nav-menu">
                                <li class="mobile-menu-item">
                                    <a href="<?php echo esc_url(home_url('/')); ?>">मुख्य पृष्ठ</a>
                                </li>
                                <?php if (current_user_can('manage_options')) : ?>
                                    <li class="mobile-menu-item">
                                        <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>">मेनु थप्नुहोस्</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    
                </div>
            </nav>
        </div>
    </header>

    <div id="content" class="site-content">