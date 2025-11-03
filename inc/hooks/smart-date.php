<?php
/**
 * Smart Date System - Fixed Version
 * @package Samachar_Patra
 * @since 2.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Detect user location based on timezone
 * 
 * @return string 'nepal' or 'international'
 */
function get_user_location() {
    // Check if timezone is set via cookie (from JavaScript detection)
    if (isset($_COOKIE['smart_date_timezone'])) {
        $timezone = sanitize_text_field($_COOKIE['smart_date_timezone']);
        if ($timezone === 'Asia/Kathmandu') {
            return 'nepal';
        }
    }
    
    // Check if user IP suggests Nepal (optional, requires GeoIP)
    if (function_exists('geoip_country_code_by_name')) {
        $country = geoip_country_code_by_name($_SERVER['REMOTE_ADDR']);
        if ($country === 'NP') {
            return 'nepal';
        }
    }
    
    // Fallback to server timezone
    $server_timezone = wp_timezone_string();
    if ($server_timezone === 'Asia/Kathmandu') {
        return 'nepal';
    }
    
    // Default to international (NOT nepal)
    return 'international';
}

/**
 * Get BS calendar data
 * 
 * @return array BS calendar data
 */
function get_bs_data() {
    static $bs_data = null;
    
    if ($bs_data === null) {
        // Accurate BS calendar data for recent years
        $bs_data = array(
            2082 => array(31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30), // 2025-2026
            2083 => array(31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30), // 2026-2027
        );
    }
    
    return $bs_data;
}

/**
 * Convert Gregorian date to Bikram Sambat (accurate conversion)
 * 
 * @param int $timestamp Unix timestamp
 * @return array BS date components
 */
function gregorian_to_bs($timestamp) {
    // More accurate reference point: 2082-01-01 BS = 2025-04-14 AD
    $ref_ad_timestamp = mktime(0, 0, 0, 4, 14, 2025);
    $ref_bs_year = 2082;
    $ref_bs_month = 1;
    $ref_bs_day = 1;
    
    $days_diff = floor(($timestamp - $ref_ad_timestamp) / 86400);
    
    $bs_year = $ref_bs_year;
    $bs_month = $ref_bs_month;
    $bs_day = $ref_bs_day + $days_diff;
    
    $bs_data = get_bs_data();
    
    // Handle positive days
    while ($bs_day > 0) {
        if (!isset($bs_data[$bs_year])) {
            // Fallback for years beyond our data
            $month_days = 30; // Average month
        } else {
            $month_days = $bs_data[$bs_year][$bs_month - 1];
        }
        
        if ($bs_day <= $month_days) {
            break;
        }
        
        $bs_day -= $month_days;
        $bs_month++;
        
        if ($bs_month > 12) {
            $bs_month = 1;
            $bs_year++;
        }
    }
    
    // Handle negative days (past dates)
    while ($bs_day <= 0) {
        $bs_month--;
        if ($bs_month <= 0) {
            $bs_month = 12;
            $bs_year--;
        }
        
        if (!isset($bs_data[$bs_year])) {
            $month_days = 30; // Average month
        } else {
            $month_days = $bs_data[$bs_year][$bs_month - 1];
        }
        
        $bs_day += $month_days;
    }
    
    $nepali_months = array(
        1 => 'बैशाख', 2 => 'जेठ', 3 => 'असार', 4 => 'साउन',
        5 => 'भदौ', 6 => 'असोज', 7 => 'कार्तिक', 8 => 'मंसिर',
        9 => 'पुष', 10 => 'माघ', 11 => 'फागुन', 12 => 'चैत'
    );
    
    return array(
        'year' => $bs_year,
        'month' => $bs_month,
        'month_name' => isset($nepali_months[$bs_month]) ? $nepali_months[$bs_month] : 'बैशाख',
        'day' => $bs_day
    );
}

/**
 * Convert numbers to Nepali digits
 * 
 * @param mixed $number
 * @return string
 */
function to_nepali_digits($number) {
    $nepali_digits = array(
        '0' => '०', '1' => '१', '2' => '२', '3' => '३', '4' => '४',
        '5' => '५', '6' => '६', '7' => '७', '8' => '८', '9' => '९'
    );
    
    return strtr((string)$number, $nepali_digits);
}

/**
 * 1. Short Date: Month name and day (DD)
 * 
 * @param int $timestamp Unix timestamp
 * @return string Formatted date
 */
function short_date($timestamp = null) {
    if ($timestamp === null) {
        $timestamp = current_time('timestamp');
    }
    
    $location = get_user_location();
    
    if ($location === 'nepal') {
        $bs_date = gregorian_to_bs($timestamp);
        $day_nepali = to_nepali_digits($bs_date['day']);
        return $bs_date['month_name'] . ' ' . $day_nepali;
    } else {
        return date('M j', $timestamp);
    }
}

/**
 * 2. Short Date Time: Month name, day (dd), Hour, Minute, AM/PM
 * 
 * @param int $timestamp Unix timestamp
 * @return string Formatted date with time
 */
function short_date_time($timestamp = null) {
    if ($timestamp === null) {
        $timestamp = current_time('timestamp');
    }
    
    $location = get_user_location();
    
    if ($location === 'nepal') {
        $bs_date = gregorian_to_bs($timestamp);
        $day_nepali = to_nepali_digits($bs_date['day']);
        
        // Convert time to Nepali
        $hour = date('g', $timestamp);
        $minute = date('i', $timestamp);
        $ampm = date('A', $timestamp);
        $hour_nepali = to_nepali_digits($hour);
        $minute_nepali = to_nepali_digits($minute);
        $ampm_nepali = ($ampm === 'AM') ? 'बिहान' : 'बेलुका';
        
        return $bs_date['month_name'] . ' ' . $day_nepali . ', ' . $hour_nepali . ':' . $minute_nepali . ' ' . $ampm_nepali;
    } else {
        return date('M j, g:i A', $timestamp);
    }
}

/**
 * 3. Full Date: Year, Month name and Day
 * 
 * @param int $timestamp Unix timestamp
 * @return string Formatted full date
 */
function full_date($timestamp = null) {
    if ($timestamp === null) {
        $timestamp = current_time('timestamp');
    }
    
    $location = get_user_location();
    
    if ($location === 'nepal') {
        $bs_date = gregorian_to_bs($timestamp);
        $year_nepali = to_nepali_digits($bs_date['year']);
        $day_nepali = to_nepali_digits($bs_date['day']);
        return $bs_date['month_name'] . ' ' . $day_nepali . ', ' . $year_nepali;
    } else {
        return date('F j, Y', $timestamp);
    }
}

/**
 * 4. Full Date Time: Year, Month, day, Hour, Minute, AM/PM
 * 
 * @param int $timestamp Unix timestamp
 * @return string Formatted full date with time
 */
function full_date_time($timestamp = null) {
    if ($timestamp === null) {
        $timestamp = current_time('timestamp');
    }
    $location = get_user_location();
    if ($location === 'nepal') {
        $bs_date = gregorian_to_bs($timestamp);
        $year_nepali = to_nepali_digits($bs_date['year']);
        $day_nepali = to_nepali_digits($bs_date['day']);
        
        // Convert time to Nepali
        $hour = date('g', $timestamp);
        $minute = date('i', $timestamp);
        $ampm = date('A', $timestamp);
        
        $hour_nepali = to_nepali_digits($hour);
        $minute_nepali = to_nepali_digits($minute);
        $ampm_nepali = ($ampm === 'AM') ? 'बिहान' : 'बेलुका';
        
        return $bs_date['month_name'] . ' ' . $day_nepali . ', ' . $year_nepali . ' ' . $hour_nepali . ':' . $minute_nepali . ' ' . $ampm_nepali;
    } else {
        return date('F j, Y g:i A', $timestamp);
    }
}

/**
 * 5. Relative Time: Minutes ago, hours ago, days ago
 * 
 * @param int $timestamp Unix timestamp
 * @return string Relative time
 */
function relative_time($timestamp = null) {
    if ($timestamp === null) {
        $timestamp = current_time('timestamp');
    }
    $location = get_user_location();
    $now = current_time('timestamp');
    $diff = $now - $timestamp;
    
    if ($location === 'nepal') {
        if ($diff < 60) {
            return 'भर्खरै';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            $minutes_nepali = to_nepali_digits($minutes);
            return $minutes_nepali . ' मिनेट अघि';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            $hours_nepali = to_nepali_digits($hours);
            return $hours_nepali . ' घण्टा अघि';
        } else {
            $days = floor($diff / 86400);
            $remaining_hours = floor(($diff % 86400) / 3600);
            
            $days_nepali = to_nepali_digits($days);
            
            if ($remaining_hours > 0 && $days < 7) {
                $hours_nepali = to_nepali_digits($remaining_hours);
                return $days_nepali . ' दिन ' . $hours_nepali . ' घण्टा अघि';
            } else {
                return $days_nepali . ' दिन अघि';
            }
        }
    } else {
        if ($diff < 60) {
            return 'just now';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' minute' . ($minutes != 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours != 1 ? 's' : '') . ' ago';
        } else {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days != 1 ? 's' : '') . ' ago';
            }
        }
    }


/**
 * Enhanced timezone detection with fallback
 */
function smart_date_enqueue_scripts() {
    wp_add_inline_script('jquery', '
        jQuery(document).ready(function($) {
            try {
                var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                var isNepal = timezone === "Asia/Kathmandu";
                
                // Set timezone cookie
                document.cookie = "smart_date_timezone=" + timezone + "; path=/; max-age=" + (30 * 24 * 60 * 60);
                
                // Debug info for administrators
                if (window.location.search.indexOf("debug_smart_date=1") !== -1) {
                    console.log("Smart Date Debug:", {
                        timezone: timezone,
                        isNepal: isNepal,
                        userAgent: navigator.userAgent
                    });
                }
            } catch(e) {
                console.log("Timezone detection failed: " + e.message);
            }
        });
    ');
}
add_action('wp_enqueue_scripts', 'smart_date_enqueue_scripts');

/**
 * Template helper functions
 */
function the_short_date($timestamp = null) {
    echo esc_html(short_date($timestamp));
}

function the_short_date_time($timestamp = null) {
    echo esc_html(short_date_time($timestamp));
}

function the_full_date($timestamp = null) {
    echo esc_html(full_date($timestamp));
}

function the_full_date_time($timestamp = null) {
    echo esc_html(full_date_time($timestamp));
}

function the_relative_time($timestamp = null) {
    echo esc_html(relative_time($timestamp));
}
