<?php
/**
 * Samachar Patra Ads Manager
 * 
 * A comprehensive ads management system for handling multiple ads
 * in various locations throughout the website
 * 
 * @package SamacharPatra
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class SamacharPatra_Ads_Manager {
    
    private static $instance = null;
    private $table_ads;
    private $table_locations;
    private $table_stats;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        global $wpdb;
        
        $this->table_ads = $wpdb->prefix . 'sp_ads';
        $this->table_locations = $wpdb->prefix . 'sp_ad_locations';
        $this->table_stats = $wpdb->prefix . 'sp_ad_stats';
        
        $this->init_hooks();
    }
    
    private function init_hooks() {
        // Admin hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        
        // AJAX hooks
        add_action('wp_ajax_sp_save_ad', array($this, 'ajax_save_ad'));
        add_action('wp_ajax_sp_delete_ad', array($this, 'ajax_delete_ad'));
        add_action('wp_ajax_sp_toggle_ad_status', array($this, 'ajax_toggle_ad_status'));
        add_action('wp_ajax_sp_track_ad_click', array($this, 'ajax_track_click'));
        add_action('wp_ajax_nopriv_sp_track_ad_click', array($this, 'ajax_track_click'));
        
        // Frontend hooks
        add_action('wp_enqueue_scripts', array($this, 'frontend_enqueue_scripts'));
        add_action('wp_head', array($this, 'add_tracking_script'));
        
        // Database setup
        register_activation_hook(__FILE__, array($this, 'create_tables'));
    }
    
    /**
     * Create database tables for ads management
     */
    public function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Ads table
        $sql_ads = "CREATE TABLE {$this->table_ads} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            description text,
            ad_type varchar(50) NOT NULL DEFAULT 'image',
            ad_content longtext NOT NULL,
            ad_url varchar(500),
            location_id varchar(100) NOT NULL,
            target_pages text,
            start_date datetime DEFAULT NULL,
            end_date datetime DEFAULT NULL,
            status varchar(20) NOT NULL DEFAULT 'active',
            priority int(11) DEFAULT 1,
            max_impressions int(11) DEFAULT 0,
            max_clicks int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY location_id (location_id),
            KEY status (status),
            KEY priority (priority)
        ) $charset_collate;";
        
        // Ad locations table
        $sql_locations = "CREATE TABLE {$this->table_locations} (
            id varchar(100) NOT NULL,
            name varchar(255) NOT NULL,
            description text,
            dimensions varchar(50),
            position varchar(100),
            template_hook varchar(100),
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Ad statistics table
        $sql_stats = "CREATE TABLE {$this->table_stats} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            ad_id mediumint(9) NOT NULL,
            event_type varchar(20) NOT NULL,
            ip_address varchar(45),
            user_agent text,
            referrer text,
            page_url text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY ad_id (ad_id),
            KEY event_type (event_type),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_ads);
        dbDelta($sql_locations);
        dbDelta($sql_stats);
        
        // Insert default ad locations
        $this->insert_default_locations();
    }
    
    /**
     * Insert default ad locations
     */
    private function insert_default_locations() {
        global $wpdb;
        
        $default_locations = array(
            array(
                'id' => 'header_banner',
                'name' => 'Header Banner',
                'description' => 'Banner ad in the header area',
                'dimensions' => '728x90',
                'position' => 'header',
                'template_hook' => 'sp_header_banner'
            ),
            array(
                'id' => 'sidebar_top',
                'name' => 'Sidebar Top',
                'description' => 'Ad at the top of sidebar',
                'dimensions' => '300x250',
                'position' => 'sidebar',
                'template_hook' => 'sp_sidebar_top'
            ),
            array(
                'id' => 'sidebar_middle',
                'name' => 'Sidebar Middle',
                'description' => 'Ad in the middle of sidebar',
                'dimensions' => '300x250',
                'position' => 'sidebar',
                'template_hook' => 'sp_sidebar_middle'
            ),
            array(
                'id' => 'sidebar_bottom',
                'name' => 'Sidebar Bottom',
                'description' => 'Ad at the bottom of sidebar',
                'dimensions' => '300x250',
                'position' => 'sidebar',
                'template_hook' => 'sp_sidebar_bottom'
            ),
            array(
                'id' => 'content_top',
                'name' => 'Content Top',
                'description' => 'Ad above main content',
                'dimensions' => '728x90',
                'position' => 'content',
                'template_hook' => 'sp_content_top'
            ),
            array(
                'id' => 'content_middle',
                'name' => 'Content Middle',
                'description' => 'Ad in the middle of content',
                'dimensions' => '728x90',
                'position' => 'content',
                'template_hook' => 'sp_content_middle'
            ),
            array(
                'id' => 'content_bottom',
                'name' => 'Content Bottom',
                'description' => 'Ad below main content',
                'dimensions' => '728x90',
                'position' => 'content',
                'template_hook' => 'sp_content_bottom'
            ),
            array(
                'id' => 'footer_banner',
                'name' => 'Footer Banner',
                'description' => 'Banner ad in footer area',
                'dimensions' => '728x90',
                'position' => 'footer',
                'template_hook' => 'sp_footer_banner'
            ),
            array(
                'id' => 'mobile_banner',
                'name' => 'Mobile Banner',
                'description' => 'Mobile-specific banner ad',
                'dimensions' => '320x50',
                'position' => 'mobile',
                'template_hook' => 'sp_mobile_banner'
            ),
            array(
                'id' => 'popup_ad',
                'name' => 'Popup Advertisement',
                'description' => 'Popup or overlay advertisement',
                'dimensions' => '600x400',
                'position' => 'popup',
                'template_hook' => 'sp_popup_ad'
            )
        );
        
        foreach ($default_locations as $location) {
            $existing = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->table_locations} WHERE id = %s",
                $location['id']
            ));
            
            if (!$existing) {
                $wpdb->insert($this->table_locations, $location);
            }
        }
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Ads Manager', 'samacharpatra'),
            __('Ads Manager', 'samacharpatra'),
            'manage_options',
            'sp-ads-manager',
            array($this, 'admin_page'),
            'dashicons-megaphone',
            30
        );
        
        add_submenu_page(
            'sp-ads-manager',
            __('All Ads', 'samacharpatra'),
            __('All Ads', 'samacharpatra'),
            'manage_options',
            'sp-ads-manager',
            array($this, 'admin_page')
        );
        
        add_submenu_page(
            'sp-ads-manager',
            __('Add New Ad', 'samacharpatra'),
            __('Add New Ad', 'samacharpatra'),
            'manage_options',
            'sp-ads-add-new',
            array($this, 'add_new_ad_page')
        );
        
        add_submenu_page(
            'sp-ads-manager',
            __('Ad Locations', 'samacharpatra'),
            __('Locations', 'samacharpatra'),
            'manage_options',
            'sp-ads-locations',
            array($this, 'locations_page')
        );
        
        add_submenu_page(
            'sp-ads-manager',
            __('Analytics', 'samacharpatra'),
            __('Analytics', 'samacharpatra'),
            'manage_options',
            'sp-ads-analytics',
            array($this, 'analytics_page')
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts($hook) {
        if (strpos($hook, 'sp-ads') === false) {
            return;
        }
        
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_style('jquery-ui-datepicker');
        
        wp_enqueue_script(
            'sp-ads-admin',
            get_template_directory_uri() . '/assets/js/ads-admin.js',
            array('jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable'),
            '1.0.0',
            true
        );
        
        wp_enqueue_style(
            'sp-ads-admin',
            get_template_directory_uri() . '/assets/css/ads-admin.css',
            array(),
            '1.0.0'
        );
        
        wp_localize_script('sp-ads-admin', 'spAdsAjax', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sp_ads_nonce'),
            'confirmDelete' => __('Are you sure you want to delete this ad?', 'samacharpatra')
        ));
    }
    
    /**
     * Enqueue frontend scripts
     */
    public function frontend_enqueue_scripts() {
        wp_enqueue_script(
            'sp-ads-admin',
            get_template_directory_uri() . '/assets/js/ads-admin.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        wp_localize_script('sp-ads-frontend', 'spAdsTracker', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sp_ads_track_nonce')
        ));
    }
    
    /**
     * Get all ads for a specific location
     */
    public function get_ads_by_location($location_id, $limit = 1) {
        global $wpdb;
        
        $current_page = $this->get_current_page_type();
        $current_time = current_time('mysql');
        
        $sql = "SELECT * FROM {$this->table_ads} 
                WHERE location_id = %s 
                AND status = 'active' 
                AND (start_date IS NULL OR start_date <= %s)
                AND (end_date IS NULL OR end_date >= %s)
                AND (target_pages IS NULL OR target_pages = '' OR FIND_IN_SET(%s, target_pages))
                ORDER BY priority DESC, RAND()";
        
        if ($limit > 0) {
            $sql .= " LIMIT %d";
            $results = $wpdb->get_results($wpdb->prepare($sql, $location_id, $current_time, $current_time, $current_page, $limit));
        } else {
            $results = $wpdb->get_results($wpdb->prepare($sql, $location_id, $current_time, $current_time, $current_page));
        }
        
        return $results;
    }
    
    /**
     * Display ads for a specific location
     */
    public function display_ads($location_id, $echo = true) {
        $ads = $this->get_ads_by_location($location_id);
        $output = '';
        
        if (!empty($ads)) {
            foreach ($ads as $ad) {
                $output .= $this->render_ad($ad);
                $this->track_impression($ad->id);
            }
        }
        
        if ($echo) {
            echo $output;
        } else {
            return $output;
        }
    }
    
    /**
     * Render individual ad
     */
    private function render_ad($ad) {
        $wrapper_class = 'sp-ad sp-ad-' . $ad->location_id . ' sp-ad-' . $ad->id;
        $onclick = '';
        
        if (!empty($ad->ad_url)) {
            $onclick = "onclick=\"spTrackAdClick({$ad->id}, '{$ad->ad_url}')\"";
        }
        
        $output = '<div class="' . esc_attr($wrapper_class) . '" data-ad-id="' . $ad->id . '">';
        
        switch ($ad->ad_type) {
            case 'image':
                $output .= '<div class="sp-ad-content" ' . $onclick . '>';
                $output .= $ad->ad_content;
                $output .= '</div>';
                break;
                
            case 'html':
                $output .= '<div class="sp-ad-content">';
                $output .= $ad->ad_content;
                $output .= '</div>';
                break;
                
            case 'script':
                $output .= $ad->ad_content;
                break;
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Get current page type for targeting
     */
    private function get_current_page_type() {
        if (is_home() || is_front_page()) {
            return 'home';
        } elseif (is_single()) {
            return 'single';
        } elseif (is_page()) {
            return 'page';
        } elseif (is_category()) {
            return 'category';
        } elseif (is_archive()) {
            return 'archive';
        } elseif (is_search()) {
            return 'search';
        }
        return 'other';
    }
    
    /**
     * Track ad impression
     */
    public function track_impression($ad_id) {
        global $wpdb;
        
        $wpdb->insert(
            $this->table_stats,
            array(
                'ad_id' => $ad_id,
                'event_type' => 'impression',
                'ip_address' => $this->get_client_ip(),
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
                'referrer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
                'page_url' => $this->get_current_url()
            )
        );
    }
    
    /**
     * Track ad click
     */
    public function track_click($ad_id) {
        global $wpdb;
        
        $wpdb->insert(
            $this->table_stats,
            array(
                'ad_id' => $ad_id,
                'event_type' => 'click',
                'ip_address' => $this->get_client_ip(),
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
                'referrer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
                'page_url' => $this->get_current_url()
            )
        );
    }
    
    /**
     * Get client IP address
     */
    private function get_client_ip() {
        $ip_keys = array('HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                return trim($ip);
            }
        }
        
        return '';
    }
    
    /**
     * Get current URL
     */
    private function get_current_url() {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . 
               "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    
    /**
     * AJAX: Track ad click
     */
    public function ajax_track_click() {
        check_ajax_referer('sp_ads_track_nonce', 'nonce');
        
        $ad_id = intval($_POST['ad_id']);
        $url = sanitize_url($_POST['url']);
        
        global $wpdb;
        $wpdb->insert(
            $this->table_stats,
            array(
                'ad_id' => $ad_id,
                'event_type' => 'click',
                'ip_address' => $this->get_client_ip(),
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
                'referrer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
                'page_url' => $this->get_current_url()
            )
        );
        
        wp_send_json_success(array('redirect_url' => $url));
    }
    
    /**
     * Add tracking script to head
     */
    public function add_tracking_script() {
        ?>
        <script>
        function spTrackAdClick(adId, url) {
            if (typeof spAdsTracker !== 'undefined') {
                jQuery.post(spAdsTracker.ajaxUrl, {
                    action: 'sp_track_ad_click',
                    ad_id: adId,
                    url: url,
                    nonce: spAdsTracker.nonce
                }, function(response) {
                    if (response.success && response.data.redirect_url) {
                        window.open(response.data.redirect_url, '_blank');
                    }
                });
            } else {
                window.open(url, '_blank');
            }
        }
        </script>
        <?php
    }
    
    /**
     * Admin page placeholder - will be implemented in separate files
     */
    public function admin_page() {
        include get_template_directory() . '/admin/ads-list.php';
    }
    
    public function add_new_ad_page() {
        include get_template_directory() . '/admin/ads-add-edit.php';
    }
    
    public function locations_page() {
        include get_template_directory() . '/admin/ads-locations.php';
    }
    
    public function analytics_page() {
        include get_template_directory() . '/admin/ads-analytics.php';
    }
    
    /**
     * Shortcode handler for displaying ads
     */
    public function ads_shortcode($atts) {
        $atts = shortcode_atts(array(
            'location' => '',
            'id' => '',
            'limit' => 1,
            'class' => '',
        ), $atts, 'sp_ads');
        
        if (!empty($atts['id'])) {
            // Display specific ad by ID
            return $this->display_ad_by_id($atts['id'], $atts['class']);
        } elseif (!empty($atts['location'])) {
            // Display ads by location
            return $this->display_ads_shortcode($atts['location'], $atts['limit'], $atts['class']);
        }
        
        return '';
    }
    
    /**
     * Display specific ad by ID for shortcode
     */
    public function display_ad_by_id($ad_id, $class = '') {
        global $wpdb;
        
        $ad = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table_ads} WHERE id = %d AND status = 'active'",
            $ad_id
        ));
        
        if (!$ad) {
            return '';
        }
        
        // Check if ad is scheduled and active
        $current_time = current_time('mysql');
        if (!empty($ad->start_date) && $ad->start_date > $current_time) {
            return '';
        }
        if (!empty($ad->end_date) && $ad->end_date < $current_time) {
            return '';
        }
        
        $wrapper_class = 'sp-ad sp-ad-shortcode sp-ad-' . $ad->id;
        if (!empty($class)) {
            $wrapper_class .= ' ' . sanitize_html_class($class);
        }
        
        $onclick = '';
        if (!empty($ad->ad_url)) {
            $onclick = "onclick=\"spTrackAdClick({$ad->id}, '{$ad->ad_url}')\"";
        }
        
        $output = '<div class="' . esc_attr($wrapper_class) . '" data-ad-id="' . $ad->id . '">';
        
        switch ($ad->ad_type) {
            case 'image':
                $output .= '<div class="sp-ad-content" ' . $onclick . '>';
                $output .= $ad->ad_content;
                $output .= '</div>';
                break;
                
            case 'html':
                $output .= '<div class="sp-ad-content">';
                $output .= $ad->ad_content;
                $output .= '</div>';
                break;
                
            case 'script':
                $output .= $ad->ad_content;
                break;
        }
        
        $output .= '</div>';
        
        // Track impression
        $this->track_impression($ad->id);
        
        return $output;
    }
    
    /**
     * Display ads by location for shortcode
     */
    private function display_ads_shortcode($location_id, $limit = 1, $class = '') {
        $ads = $this->get_ads_by_location($location_id, $limit);
        $output = '';
        
        $wrapper_class = 'sp-ads-shortcode sp-ads-location-' . $location_id;
        if (!empty($class)) {
            $wrapper_class .= ' ' . sanitize_html_class($class);
        }
        
        if (!empty($ads)) {
            $output .= '<div class="' . esc_attr($wrapper_class) . '">';
            foreach ($ads as $ad) {
                $output .= $this->render_ad($ad);
                $this->track_impression($ad->id);
            }
            $output .= '</div>';
        }
        
        return $output;
    }
}

// Initialize the ads manager
function sp_ads_manager() {
    return SamacharPatra_Ads_Manager::get_instance();
}

// Template function to display ads
function sp_display_ads($location_id, $echo = true) {
    return sp_ads_manager()->display_ads($location_id, $echo);
}

// Template function to display single ad by ID
function sp_display_ad($ad_id, $echo = true) {
    $output = sp_ads_manager()->display_ad_by_id($ad_id);
    if ($echo) {
        echo $output;
    } else {
        return $output;
    }
}

// Register shortcode
add_shortcode('sp_ads', array(sp_ads_manager(), 'ads_shortcode'));

// Add shortcode button to TinyMCE editor
add_action('init', 'sp_add_ads_shortcode_button');

function sp_add_ads_shortcode_button() {
    if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
        add_filter('mce_buttons', 'sp_register_ads_button');
        add_filter('mce_external_plugins', 'sp_add_ads_plugin');
    }
}

function sp_register_ads_button($buttons) {
    array_push($buttons, 'sp_ads_button');
    return $buttons;
}

function sp_add_ads_plugin($plugin_array) {
    $plugin_array['sp_ads_button'] = get_template_directory_uri() . '/assets/js/ads-tinymce.js';
    return $plugin_array;
}

// AJAX handlers for stats
add_action('wp_ajax_sp_track_impression', 'sp_handle_track_impression');
add_action('wp_ajax_nopriv_sp_track_impression', 'sp_handle_track_impression');
add_action('wp_ajax_sp_track_click', 'sp_handle_track_click');
add_action('wp_ajax_nopriv_sp_track_click', 'sp_handle_track_click');

function sp_handle_track_impression() {
    if (!isset($_POST['ad_id']) || !wp_verify_nonce($_POST['nonce'], 'sp_ads_nonce')) {
        wp_die('Security check failed');
    }
    
    sp_ads_manager()->track_impression($_POST['ad_id']);
    wp_die();
}

function sp_handle_track_click() {
    if (!isset($_POST['ad_id']) || !wp_verify_nonce($_POST['nonce'], 'sp_ads_nonce')) {
        wp_die('Security check failed');
    }
    
    sp_ads_manager()->track_click($_POST['ad_id']);
    wp_die();
}

// Initialize
sp_ads_manager();