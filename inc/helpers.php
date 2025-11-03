<?php
/**
 * Helper Functions
 * 
 * Optimized utility functions for the theme.
 * Uses Smart Date system functions from inc/hooks/smart-date.php
 *
 * @package Samachar_Patra
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Date and Time Helper Functions - Smart Date System Integration
 * These functions utilize the Smart Date system from inc/hooks/smart-date.php
 */

/**
 * Get Smart Date short format
 * Uses the Smart Date system's short_date() function
 * 
 * @param int|null $timestamp Optional timestamp, uses current time if null
 * @return string Formatted short date based on user location
 */
function samacharpatra_smart_short_date($timestamp = null) {
    if (function_exists('short_date')) {
        return short_date($timestamp);
    }
    // Fallback if Smart Date not available
    return date('M j, Y', $timestamp ?: time());
}

/**
 * Get Smart Date short date time
 * Uses the Smart Date system's short_date_time() function
 * 
 * @param int|null $timestamp Optional timestamp, uses current time if null
 * @return string Formatted short date time based on user location
 */
function samacharpatra_smart_short_date_time($timestamp = null) {
    if (function_exists('short_date_time')) {
        return short_date_time($timestamp);
    }
    // Fallback if Smart Date not available
    return date('M j, Y g:i A', $timestamp ?: time());
}

/**
 * Get Smart Date full format
 * Uses the Smart Date system's full_date() function
 * 
 * @param int|null $timestamp Optional timestamp, uses current time if null
 * @return string Formatted full date based on user location
 */
function samacharpatra_smart_full_date($timestamp = null) {
    if (function_exists('full_date')) {
        return full_date($timestamp);
    }
    // Fallback if Smart Date not available
    return date('F j, Y', $timestamp ?: time());
}

/**
 * Get Smart Date relative time
 * Uses the Smart Date system's relative_time() function
 * 
 * @param int|null $timestamp Optional timestamp, uses current time if null
 * @return string Relative time based on user location
 */
function samacharpatra_smart_relative_time($timestamp = null) {
    if (function_exists('relative_time')) {
        return relative_time($timestamp);
    }
    // Fallback if Smart Date not available
    $diff = time() - ($timestamp ?: time());
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    return floor($diff / 86400) . ' days ago';
}

/**
 * Convert to Nepali numerals
 * Uses the Smart Date system's to_nepali_digits() function
 * 
 * @param mixed $number Number to convert
 * @return string Nepali numerals
 */
function samacharpatra_convert_to_nepali_numerals($number) {
    if (function_exists('to_nepali_digits')) {
        return to_nepali_digits($number);
    }
    // Fallback conversion
    $nepali_numerals = array('०', '१', '२', '३', '४', '५', '६', '७', '८', '९');
    $english = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    return str_replace($english, $nepali_numerals, strval($number));
}

/**
 * Get user location information
 * Uses the Smart Date system's get_user_location() function
 * 
 * @return array User location data
 */
function samacharpatra_get_user_location() {
    if (function_exists('get_user_location')) {
        return get_user_location();
    }
    // Fallback - assume Nepal
    return array(
        'country' => 'Nepal',
        'timezone' => 'Asia/Kathmandu'
    );
}

/**
 * Get post date in Smart Date format
 * 
 * @param string $format 'short', 'full', 'relative', 'short_time', 'full_time'
 * @param int|null $post_id Optional post ID
 * @return string Formatted date
 */
function samacharpatra_get_post_smart_date($format = 'short', $post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    $timestamp = strtotime(get_the_date('Y-m-d H:i:s', $post_id));
    
    switch ($format) {
        case 'short':
            return samacharpatra_smart_short_date($timestamp);
        case 'full':
            return samacharpatra_smart_full_date($timestamp);
        case 'relative':
            return samacharpatra_smart_relative_time($timestamp);
        case 'short_time':
            return samacharpatra_smart_short_date_time($timestamp);
        default:
            return samacharpatra_smart_short_date($timestamp);
    }
}

/**
 * Echo post date in Smart Date format
 * 
 * @param string $format 'short', 'full', 'relative', 'short_time', 'full_time'
 * @param int|null $post_id Optional post ID
 */
function samacharpatra_the_post_smart_date($format = 'short', $post_id = null) {
    echo samacharpatra_get_post_smart_date($format, $post_id);
}

/**
 * Alias functions for backward compatibility with existing template files
 */

/**
 * Alias for samacharpatra_smart_short_date
 */
function smart_date_short($timestamp = null) {
    return samacharpatra_smart_short_date($timestamp);
}

/**
 * Alias for samacharpatra_smart_relative_time
 */
function smart_date_relative($timestamp = null) {
    return samacharpatra_smart_relative_time($timestamp);
}

/**
 * Alias for samacharpatra_smart_full_date
 */
function smart_date_full($timestamp = null) {
    return samacharpatra_smart_full_date($timestamp);
}

/**
 * Component Helper Functions
 */

/**
 * Load a component from the templates directory
 * 
 * @param string $component Component path
 * @param array $data Optional data to pass to the component
 */
function samacharpatra_component($component, $data = array()) {
    if (!empty($data)) {
        extract($data);
    }
    
    $component_path = 'templates/' . $component;
    $full_path = get_template_directory() . '/' . $component_path . '.php';
    
    if (file_exists($full_path)) {
        get_template_part($component_path);
    } else {
        error_log("Component not found: " . $full_path);
        if (defined('WP_DEBUG') && WP_DEBUG) {
            echo '<div class="component-error">Component "' . esc_html($component) . '" not found</div>';
        }
    }
}

/**
 * Check if a component exists
 * 
 * @param string $component Component path
 * @return bool
 */
function samacharpatra_component_exists($component) {
    $full_path = get_template_directory() . '/templates/' . $component . '.php';
    return file_exists($full_path);
}

/**
 * List all available components
 * 
 * @return array List of component paths
 */
function samacharpatra_get_components() {
    $components = array();
    $directories = array('templates/parts', 'templates/components');
    
    foreach ($directories as $dir) {
        $full_dir = get_template_directory() . '/' . $dir;
        if (is_dir($full_dir)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($full_dir));
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $relative_path = str_replace($full_dir . '/', '', $file->getPathname());
                    $relative_path = str_replace('.php', '', $relative_path);
                    $relative_path = str_replace('\\', '/', $relative_path); // Windows compatibility
                    $components[] = str_replace('templates/', '', $dir) . '/' . $relative_path;
                }
            }
        }
    }
    
    return $components;
}

/**
 * Include component with parameters and output buffering
 * 
 * @param string $component Component path
 * @param array $data Data to pass to component
 * @return string Component output
 */
function samacharpatra_get_component($component, $data = array()) {
    ob_start();
    samacharpatra_component($component, $data);
    return ob_get_clean();
}

/**
 * Legacy date conversion functions for backward compatibility
 */

/**
 * Convert AD date to Nepali BS date using Smart Date system
 * 
 * @param string $ad_date AD date in Y-m-d format
 * @return array Nepali date array (for backward compatibility)
 */
function samacharpatra_ad_to_bs($ad_date) {
    $timestamp = strtotime($ad_date);
    if (function_exists('gregorian_to_bs')) {
        return gregorian_to_bs($timestamp);
    }
    
    // Fallback - simple conversion
    $nepali_months = array(
        1 => 'बैशाख', 2 => 'जेठ', 3 => 'आषाढ', 4 => 'साउन',
        5 => 'भदौ', 6 => 'आश्विन', 7 => 'कार्तिक', 8 => 'मंसिर',
        9 => 'पौष', 10 => 'माघ', 11 => 'फागुन', 12 => 'चैत्र'
    );
    
    $ad_year = (int)date('Y', $timestamp);
    $bs_year = $ad_year + 57; // Approximate conversion
    
    return array(
        'year' => $bs_year,
        'month' => 1,
        'day' => 1,
        'month_name' => $nepali_months[1]
    );
}

/**
 * Format Nepali date using Smart Date system
 * 
 * @param string $ad_date AD date
 * @param string $format Format: 'full', 'short', 'month_day'
 * @return string Formatted Nepali date
 */
function samacharpatra_format_nepali_date($ad_date, $format = 'full') {
    $timestamp = strtotime($ad_date);
    
    switch ($format) {
        case 'full':
            return samacharpatra_smart_full_date($timestamp);
        case 'short':
            return samacharpatra_smart_short_date($timestamp);
        default:
            return samacharpatra_smart_full_date($timestamp);
    }
}

/**
 * Utility Functions
 */

/**
 * Get category posts function
 */
function samacharpatra_get_category_posts($category_slug, $limit = 5) {
    $args = array(
        'category_name'  => $category_slug,
        'posts_per_page' => $limit,
        'post_status'    => 'publish'
    );
    
    return get_posts($args);
}

/**
 * Reading time function
 */
function samacharpatra_reading_time($content) {
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed
    return $reading_time;
}

/**
 * Format numbers for display
 */
function samacharpatra_format_number($number) {
    if ($number >= 1000000) {
        return round($number / 1000000, 1) . 'M';
    } elseif ($number >= 1000) {
        return round($number / 1000, 1) . 'K';
    }
    return $number;
}

/**
 * Post views tracking function
 */
function samacharpatra_track_post_views($post_id) {
    if (!is_single()) return;
    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    
    $count_key = 'post_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
    } else {
        $count++;
        update_post_meta($post_id, $count_key, $count);
    }
}

/**
 * Get post views count
 */
function samacharpatra_get_post_views($post_id) {
    $count_key = 'post_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    if ($count == '') {
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
        return '0';
    }
    return $count;
}

/**
 * Theme Options Functions
 */

/**
 * Get theme option with default value
 */
function samacharpatra_get_option($option_name, $default = '') {
    return get_theme_mod($option_name, $default);
}

/**
 * Check if theme supports a feature
 */
function samacharpatra_supports($feature) {
    return current_theme_supports($feature);
}

/**
 * Remove featured image from post content on single posts
 */
function samacharpatra_remove_featured_image_from_content($content) {
    if (is_single() && has_post_thumbnail()) {
        $featured_image_id = get_post_thumbnail_id();
        $featured_image_src = wp_get_attachment_image_src($featured_image_id, 'full');
        
        if ($featured_image_src) {
            $image_url = $featured_image_src[0];
            $content = preg_replace('/<img[^>]*src=["\']?' . preg_quote($image_url, '/') . '["\']?[^>]*>/i', '', $content);
            $content = preg_replace('/<(figure|div)[^>]*class="[^"]*wp-block-image[^"]*"[^>]*>\s*<\/\1>/i', '', $content);
            $content = preg_replace('/<(figure|div)[^>]*>\s*<\/\1>/i', '', $content);
        }
    }
    return $content;
}

/**
 * Set post views count
 */
function samacharpatra_set_post_views($post_id) {
    $count_key = 'post_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '1');
    } else {
        $count++;
        update_post_meta($post_id, $count_key, $count);
    }
}

/**
 * Custom pagination for category pages
 */
function samacharpatra_custom_pagination($query = null) {
    global $wp_query;
    
    if (!$query) {
        $query = $wp_query;
    }
    
    $total_pages = $query->max_num_pages;
    $current_page = max(1, get_query_var('paged'));
    
    if ($total_pages <= 1) {
        return '';
    }
    
    $pagination_args = array(
        'base' => str_replace('999999999', '%#%', esc_url(get_pagenum_link(999999999))),
        'format' => '?paged=%#%',
        'current' => $current_page,
        'total' => $total_pages,
        'prev_text' => '<i class="fas fa-chevron-left"></i> अघिल्लो',
        'next_text' => 'अर्को <i class="fas fa-chevron-right"></i>',
        'type' => 'array',
        'show_all' => false,
        'end_size' => 3,
        'mid_size' => 1,
    );
    
    return paginate_links($pagination_args);
}

/**
 * Breadcrumb function
 */
function samacharpatra_breadcrumbs() {
    if (!is_home()) {
        echo '<nav class="breadcrumbs">';
        echo '<a href="' . home_url() . '">मुख्य पृष्ठ</a> <span class="separator">></span> ';
        
        if (is_category() || is_single()) {
            $category = get_the_category();
            if ($category) {
                echo '<a href="' . get_category_link($category[0]->term_id) . '">' . $category[0]->name . '</a>';
                if (is_single()) {
                    echo ' <span class="separator">></span> ';
                    the_title();
                }
            }
        } elseif (is_page()) {
            echo get_the_title();
        } elseif (is_search()) {
            echo 'खोज परिणामहरू';
        } elseif (is_archive()) {
            echo 'अभिलेख';
        }
        
        echo '</nav>';
    }
}