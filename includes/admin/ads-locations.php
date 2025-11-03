<?php
/**
 * Ads Locations Admin Page
 * 
 * @package SamacharPatra
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$table_locations = $wpdb->prefix . 'sp_ad_locations';
$table_ads = $wpdb->prefix . 'sp_ads';

// Handle location updates
if (isset($_POST['update_location'])) {
    $location_id = sanitize_text_field($_POST['location_id']);
    $name = sanitize_text_field($_POST['name']);
    $description = sanitize_textarea_field($_POST['description']);
    $dimensions = sanitize_text_field($_POST['dimensions']);
    $position = sanitize_text_field($_POST['position']);
    $template_hook = sanitize_text_field($_POST['template_hook']);
    
    $updated = $wpdb->update(
        $table_locations,
        array(
            'name' => $name,
            'description' => $description,
            'dimensions' => $dimensions,
            'position' => $position,
            'template_hook' => $template_hook
        ),
        array('id' => $location_id)
    );
    
    if ($updated !== false) {
        echo '<div class="notice notice-success"><p>' . __('Location updated successfully.', 'samacharpatra') . '</p></div>';
    }
}

// Get all locations with ad counts
$locations = $wpdb->get_results("
    SELECT l.*, 
           COUNT(a.id) as ad_count,
           SUM(CASE WHEN a.status = 'active' THEN 1 ELSE 0 END) as active_ads
    FROM {$table_locations} l
    LEFT JOIN {$table_ads} a ON l.id = a.location_id
    GROUP BY l.id
    ORDER BY l.name
");
?>

<div class="wrap">
    <h1><?php _e('Ad Locations', 'samacharpatra'); ?></h1>
    <p class="description">
        <?php _e('Manage ad locations throughout your website. These are predefined positions where ads can be displayed.', 'samacharpatra'); ?>
    </p>
    
    <div class="sp-locations-grid">
        <?php foreach ($locations as $location) : ?>
        <div class="sp-location-card" id="location-<?php echo esc_attr($location->id); ?>">
            <h3><?php echo esc_html($location->name); ?></h3>
            
            <div class="sp-location-meta">
                <p><strong><?php _e('ID:', 'samacharpatra'); ?></strong> <code><?php echo esc_html($location->id); ?></code></p>
                <p><strong><?php _e('Position:', 'samacharpatra'); ?></strong> <?php echo esc_html($location->position); ?></p>
                <?php if ($location->dimensions) : ?>
                <p><strong><?php _e('Dimensions:', 'samacharpatra'); ?></strong> <span class="dimensions"><?php echo esc_html($location->dimensions); ?></span></p>
                <?php endif; ?>
                <p><strong><?php _e('Template Hook:', 'samacharpatra'); ?></strong> <code><?php echo esc_html($location->template_hook); ?></code></p>
            </div>
            
            <div class="sp-location-description">
                <p><?php echo esc_html($location->description); ?></p>
            </div>
            
            <div class="sp-location-stats">
                <div class="sp-stat-item">
                    <span class="sp-stat-number"><?php echo intval($location->ad_count); ?></span>
                    <span class="sp-stat-label"><?php _e('Total Ads', 'samacharpatra'); ?></span>
                </div>
                <div class="sp-stat-item">
                    <span class="sp-stat-number sp-active"><?php echo intval($location->active_ads); ?></span>
                    <span class="sp-stat-label"><?php _e('Active Ads', 'samacharpatra'); ?></span>
                </div>
            </div>
            
            <div class="sp-location-actions">
                <a href="<?php echo admin_url('admin.php?page=sp-ads-add-new&location=' . $location->id); ?>" class="button button-primary">
                    <?php _e('Add Ad Here', 'samacharpatra'); ?>
                </a>
                <button class="button sp-edit-location" data-location-id="<?php echo esc_attr($location->id); ?>">
                    <?php _e('Edit Location', 'samacharpatra'); ?>
                </button>
                <a href="<?php echo admin_url('admin.php?page=sp-ads-manager&location=' . $location->id); ?>" class="button">
                    <?php _e('View Ads', 'samacharpatra'); ?>
                </a>
            </div>
            
            <!-- Usage Code -->
            <div class="sp-location-usage">
                <h4><?php _e('Usage Code:', 'samacharpatra'); ?></h4>
                <div class="sp-code-example">
                    <strong><?php _e('Template Function:', 'samacharpatra'); ?></strong>
                    <code>&lt;?php sp_display_ads('<?php echo esc_html($location->id); ?>'); ?&gt;</code>
                </div>
                <div class="sp-code-example">
                    <strong><?php _e('Shortcode:', 'samacharpatra'); ?></strong>
                    <code>[sp_ads location="<?php echo esc_html($location->id); ?>"]</code>
                </div>
            </div>
            
            <!-- Edit Form (Hidden by default) -->
            <div class="sp-edit-form" id="edit-form-<?php echo esc_attr($location->id); ?>" style="display: none;">
                <form method="post" class="sp-location-edit-form">
                    <input type="hidden" name="location_id" value="<?php echo esc_attr($location->id); ?>">
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="name-<?php echo esc_attr($location->id); ?>"><?php _e('Name', 'samacharpatra'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="name-<?php echo esc_attr($location->id); ?>" name="name" value="<?php echo esc_attr($location->name); ?>" class="regular-text" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="description-<?php echo esc_attr($location->id); ?>"><?php _e('Description', 'samacharpatra'); ?></label>
                            </th>
                            <td>
                                <textarea id="description-<?php echo esc_attr($location->id); ?>" name="description" class="large-text" rows="3"><?php echo esc_textarea($location->description); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="dimensions-<?php echo esc_attr($location->id); ?>"><?php _e('Dimensions', 'samacharpatra'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="dimensions-<?php echo esc_attr($location->id); ?>" name="dimensions" value="<?php echo esc_attr($location->dimensions); ?>" class="regular-text" placeholder="e.g., 728x90">
                                <p class="description"><?php _e('Recommended ad dimensions (e.g., 728x90, 300x250)', 'samacharpatra'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="position-<?php echo esc_attr($location->id); ?>"><?php _e('Position', 'samacharpatra'); ?></label>
                            </th>
                            <td>
                                <select id="position-<?php echo esc_attr($location->id); ?>" name="position">
                                    <option value="header" <?php selected($location->position, 'header'); ?>><?php _e('Header', 'samacharpatra'); ?></option>
                                    <option value="sidebar" <?php selected($location->position, 'sidebar'); ?>><?php _e('Sidebar', 'samacharpatra'); ?></option>
                                    <option value="content" <?php selected($location->position, 'content'); ?>><?php _e('Content', 'samacharpatra'); ?></option>
                                    <option value="footer" <?php selected($location->position, 'footer'); ?>><?php _e('Footer', 'samacharpatra'); ?></option>
                                    <option value="mobile" <?php selected($location->position, 'mobile'); ?>><?php _e('Mobile', 'samacharpatra'); ?></option>
                                    <option value="popup" <?php selected($location->position, 'popup'); ?>><?php _e('Popup', 'samacharpatra'); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="template_hook-<?php echo esc_attr($location->id); ?>"><?php _e('Template Hook', 'samacharpatra'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="template_hook-<?php echo esc_attr($location->id); ?>" name="template_hook" value="<?php echo esc_attr($location->template_hook); ?>" class="regular-text">
                                <p class="description"><?php _e('WordPress action hook for automatic insertion', 'samacharpatra'); ?></p>
                            </td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <input type="submit" name="update_location" class="button button-primary" value="<?php _e('Update Location', 'samacharpatra'); ?>">
                        <button type="button" class="button sp-cancel-edit"><?php _e('Cancel', 'samacharpatra'); ?></button>
                    </p>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Add New Location Section -->
    <div class="sp-add-location-section">
        <h2><?php _e('Add New Location', 'samacharpatra'); ?></h2>
        <button class="button button-primary" id="sp-show-add-form"><?php _e('Add New Location', 'samacharpatra'); ?></button>
        
        <div id="sp-add-location-form" style="display: none;">
            <form method="post" class="sp-location-add-form">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="new_location_id"><?php _e('Location ID', 'samacharpatra'); ?> <span class="required">*</span></label>
                        </th>
                        <td>
                            <input type="text" id="new_location_id" name="new_location_id" class="regular-text" required>
                            <p class="description"><?php _e('Unique identifier for this location (e.g., custom_sidebar)', 'samacharpatra'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="new_name"><?php _e('Name', 'samacharpatra'); ?> <span class="required">*</span></label>
                        </th>
                        <td>
                            <input type="text" id="new_name" name="new_name" class="regular-text" required>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="new_description"><?php _e('Description', 'samacharpatra'); ?></label>
                        </th>
                        <td>
                            <textarea id="new_description" name="new_description" class="large-text" rows="3"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="new_dimensions"><?php _e('Dimensions', 'samacharpatra'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="new_dimensions" name="new_dimensions" class="regular-text" placeholder="e.g., 728x90">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="new_position"><?php _e('Position', 'samacharpatra'); ?></label>
                        </th>
                        <td>
                            <select id="new_position" name="new_position">
                                <option value="header"><?php _e('Header', 'samacharpatra'); ?></option>
                                <option value="sidebar"><?php _e('Sidebar', 'samacharpatra'); ?></option>
                                <option value="content"><?php _e('Content', 'samacharpatra'); ?></option>
                                <option value="footer"><?php _e('Footer', 'samacharpatra'); ?></option>
                                <option value="mobile"><?php _e('Mobile', 'samacharpatra'); ?></option>
                                <option value="popup"><?php _e('Popup', 'samacharpatra'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="add_location" class="button button-primary" value="<?php _e('Add Location', 'samacharpatra'); ?>">
                    <button type="button" class="button" id="sp-cancel-add"><?php _e('Cancel', 'samacharpatra'); ?></button>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Toggle edit forms
    $('.sp-edit-location').on('click', function() {
        var locationId = $(this).data('location-id');
        var $form = $('#edit-form-' + locationId);
        var $card = $('#location-' + locationId);
        
        if ($form.is(':visible')) {
            $form.slideUp();
            $(this).text('<?php _e('Edit Location', 'samacharpatra'); ?>');
        } else {
            $('.sp-edit-form').slideUp(); // Hide other forms
            $('.sp-edit-location').text('<?php _e('Edit Location', 'samacharpatra'); ?>'); // Reset other buttons
            $form.slideDown();
            $(this).text('<?php _e('Cancel Edit', 'samacharpatra'); ?>');
        }
    });
    
    // Cancel edit
    $('.sp-cancel-edit').on('click', function() {
        var $form = $(this).closest('.sp-edit-form');
        var locationId = $form.attr('id').replace('edit-form-', '');
        $form.slideUp();
        $('#location-' + locationId + ' .sp-edit-location').text('<?php _e('Edit Location', 'samacharpatra'); ?>');
    });
    
    // Show add form
    $('#sp-show-add-form').on('click', function() {
        $('#sp-add-location-form').slideDown();
        $(this).hide();
    });
    
    // Cancel add
    $('#sp-cancel-add').on('click', function() {
        $('#sp-add-location-form').slideUp();
        $('#sp-show-add-form').show();
    });
    
    // Copy code to clipboard
    $('.sp-code-example code').on('click', function() {
        var text = $(this).text();
        navigator.clipboard.writeText(text).then(function() {
            // Show temporary feedback
            var $code = $(this);
            var originalText = $code.text();
            $code.text('<?php _e('Copied!', 'samacharpatra'); ?>').addClass('copied');
            setTimeout(function() {
                $code.text(originalText).removeClass('copied');
            }, 2000);
        }.bind(this));
    });
});
</script>

<style>
.sp-code-example {
    margin: 10px 0;
    padding: 10px;
    background: #f1f1f1;
    border-radius: 4px;
}

.sp-code-example code {
    background: #fff;
    padding: 5px 8px;
    border-radius: 3px;
    font-family: Consolas, Monaco, monospace;
    cursor: pointer;
    transition: background-color 0.2s;
}

.sp-code-example code:hover {
    background: #e8f4fd;
}

.sp-code-example code.copied {
    background: #d1e7dd;
    color: #0f5132;
}

.sp-location-stats {
    display: flex;
    gap: 20px;
    margin: 15px 0;
}

.sp-stat-item {
    text-align: center;
}

.sp-stat-number {
    display: block;
    font-size: 24px;
    font-weight: bold;
    color: #2271b1;
}

.sp-stat-number.sp-active {
    color: #00a32a;
}

.sp-stat-label {
    font-size: 12px;
    color: #646970;
}

.sp-location-usage {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #c3c4c7;
}

.sp-location-usage h4 {
    margin-top: 0;
    margin-bottom: 10px;
}

.sp-add-location-section {
    margin-top: 40px;
    padding-top: 20px;
    border-top: 2px solid #c3c4c7;
}
</style>