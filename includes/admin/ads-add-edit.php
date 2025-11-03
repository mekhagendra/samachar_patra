<?php
/**
 * Add/Edit Ad Admin Page
 * 
 * @package SamacharPatra
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$ads_manager = sp_ads_manager();
$table_ads = $wpdb->prefix . 'sp_ads';
$table_locations = $wpdb->prefix . 'sp_ad_locations';

$editing = isset($_GET['edit']) && !empty($_GET['edit']);
$ad_id = $editing ? intval($_GET['edit']) : 0;
$ad_data = null;

if ($editing) {
    $ad_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_ads} WHERE id = %d", $ad_id));
    if (!$ad_data) {
        wp_die(__('Ad not found.', 'samacharpatra'));
    }
}

// Handle form submission
if (isset($_POST['save_ad'])) {
    $title = sanitize_text_field($_POST['title']);
    $description = sanitize_textarea_field($_POST['description']);
    $ad_type = sanitize_text_field($_POST['ad_type']);
    $ad_content = wp_kses_post($_POST['ad_content']);
    $ad_url = sanitize_url($_POST['ad_url']);
    $location_id = sanitize_text_field($_POST['location_id']);
    $target_pages = !empty($_POST['target_pages']) ? implode(',', array_map('sanitize_text_field', $_POST['target_pages'])) : '';
    $start_date = !empty($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : null;
    $end_date = !empty($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : null;
    $status = sanitize_text_field($_POST['status']);
    $priority = intval($_POST['priority']);
    $max_impressions = intval($_POST['max_impressions']);
    $max_clicks = intval($_POST['max_clicks']);
    
    $data = array(
        'title' => $title,
        'description' => $description,
        'ad_type' => $ad_type,
        'ad_content' => $ad_content,
        'ad_url' => $ad_url,
        'location_id' => $location_id,
        'target_pages' => $target_pages,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'status' => $status,
        'priority' => $priority,
        'max_impressions' => $max_impressions,
        'max_clicks' => $max_clicks
    );
    
    if ($editing) {
        $wpdb->update($table_ads, $data, array('id' => $ad_id));
        $message = __('Ad updated successfully.', 'samacharpatra');
    } else {
        $wpdb->insert($table_ads, $data);
        $message = __('Ad created successfully.', 'samacharpatra');
    }
    
    echo '<div class="notice notice-success"><p>' . $message . '</p></div>';
    
    if (!$editing) {
        $ad_id = $wpdb->insert_id;
        wp_redirect(admin_url('admin.php?page=sp-ads-add-new&edit=' . $ad_id));
        exit;
    }
}

// Get locations
$locations = $wpdb->get_results("SELECT * FROM {$table_locations} ORDER BY name");

// Default values
$defaults = array(
    'title' => '',
    'description' => '',
    'ad_type' => 'image',
    'ad_content' => '',
    'ad_url' => '',
    'location_id' => '',
    'target_pages' => '',
    'start_date' => '',
    'end_date' => '',
    'status' => 'active',
    'priority' => 1,
    'max_impressions' => 0,
    'max_clicks' => 0
);

if ($ad_data) {
    foreach ($defaults as $key => $value) {
        $defaults[$key] = $ad_data->$key;
    }
    $target_pages_array = !empty($defaults['target_pages']) ? explode(',', $defaults['target_pages']) : array();
} else {
    $target_pages_array = array();
}
?>

<div class="wrap">
    <h1><?php echo $editing ? __('Edit Ad', 'samacharpatra') : __('Add New Ad', 'samacharpatra'); ?></h1>
    
    <form method="post" class="sp-ad-form">
        <div class="sp-ad-form-container">
            <div class="sp-ad-main-content">
                <div class="postbox">
                    <h3 class="hndle"><?php _e('Ad Details', 'samacharpatra'); ?></h3>
                    <div class="inside">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="title"><?php _e('Ad Title', 'samacharpatra'); ?> <span class="required">*</span></label>
                                </th>
                                <td>
                                    <input type="text" id="title" name="title" class="regular-text" value="<?php echo esc_attr($defaults['title']); ?>" required>
                                    <p class="description"><?php _e('Enter a descriptive title for this ad.', 'samacharpatra'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="description"><?php _e('Description', 'samacharpatra'); ?></label>
                                </th>
                                <td>
                                    <textarea id="description" name="description" class="regular-text" rows="3"><?php echo esc_textarea($defaults['description']); ?></textarea>
                                    <p class="description"><?php _e('Optional description for internal reference.', 'samacharpatra'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="ad_type"><?php _e('Ad Type', 'samacharpatra'); ?> <span class="required">*</span></label>
                                </th>
                                <td>
                                    <select id="ad_type" name="ad_type" required>
                                        <option value="image" <?php selected($defaults['ad_type'], 'image'); ?>><?php _e('Image Ad', 'samacharpatra'); ?></option>
                                        <option value="html" <?php selected($defaults['ad_type'], 'html'); ?>><?php _e('HTML Ad', 'samacharpatra'); ?></option>
                                        <option value="script" <?php selected($defaults['ad_type'], 'script'); ?>><?php _e('Script Ad (AdSense, etc.)', 'samacharpatra'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="ad_content"><?php _e('Ad Content', 'samacharpatra'); ?> <span class="required">*</span></label>
                                </th>
                                <td>
                                    <div class="sp-ad-content-help">
                                        <div class="ad-type-help image-help" style="display: <?php echo $defaults['ad_type'] === 'image' ? 'block' : 'none'; ?>;">
                                            <p><strong><?php _e('Image Ad Instructions:', 'samacharpatra'); ?></strong></p>
                                            <p><?php _e('Use the media uploader below or paste HTML img tag. Example:', 'samacharpatra'); ?></p>
                                            <code>&lt;img src="your-image-url.jpg" alt="Ad Description" style="max-width: 100%; height: auto;"&gt;</code>
                                        </div>
                                        <div class="ad-type-help html-help" style="display: <?php echo $defaults['ad_type'] === 'html' ? 'block' : 'none'; ?>;">
                                            <p><strong><?php _e('HTML Ad Instructions:', 'samacharpatra'); ?></strong></p>
                                            <p><?php _e('Enter your custom HTML code. You can include images, text, styling, etc.', 'samacharpatra'); ?></p>
                                        </div>
                                        <div class="ad-type-help script-help" style="display: <?php echo $defaults['ad_type'] === 'script' ? 'block' : 'none'; ?>;">
                                            <p><strong><?php _e('Script Ad Instructions:', 'samacharpatra'); ?></strong></p>
                                            <p><?php _e('Paste your ad script code (Google AdSense, etc.). Include the complete script tag.', 'samacharpatra'); ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="sp-media-upload" style="display: <?php echo $defaults['ad_type'] === 'image' ? 'block' : 'none'; ?>;">
                                        <button type="button" class="button sp-upload-image"><?php _e('Upload Image', 'samacharpatra'); ?></button>
                                    </div>
                                    
                                    <textarea id="ad_content" name="ad_content" class="large-text code" rows="8" required><?php echo esc_textarea($defaults['ad_content']); ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="ad_url"><?php _e('Target URL', 'samacharpatra'); ?></label>
                                </th>
                                <td>
                                    <input type="url" id="ad_url" name="ad_url" class="regular-text" value="<?php echo esc_attr($defaults['ad_url']); ?>">
                                    <p class="description"><?php _e('Where should this ad link to when clicked? (Leave empty for non-clickable ads)', 'samacharpatra'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="sp-ad-sidebar">
                <div class="postbox">
                    <h3 class="hndle"><?php _e('Ad Settings', 'samacharpatra'); ?></h3>
                    <div class="inside">
                        <div class="sp-form-field">
                            <label for="location_id"><strong><?php _e('Location', 'samacharpatra'); ?> <span class="required">*</span></strong></label>
                            <select id="location_id" name="location_id" required>
                                <option value=""><?php _e('Select Location', 'samacharpatra'); ?></option>
                                <?php foreach ($locations as $location) : ?>
                                <option value="<?php echo esc_attr($location->id); ?>" <?php selected($defaults['location_id'], $location->id); ?>>
                                    <?php echo esc_html($location->name); ?>
                                    <?php if ($location->dimensions) : ?>
                                        (<?php echo esc_html($location->dimensions); ?>)
                                    <?php endif; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="sp-form-field">
                            <label for="status"><strong><?php _e('Status', 'samacharpatra'); ?></strong></label>
                            <select id="status" name="status">
                                <option value="active" <?php selected($defaults['status'], 'active'); ?>><?php _e('Active', 'samacharpatra'); ?></option>
                                <option value="inactive" <?php selected($defaults['status'], 'inactive'); ?>><?php _e('Inactive', 'samacharpatra'); ?></option>
                            </select>
                        </div>
                        
                        <div class="sp-form-field">
                            <label for="priority"><strong><?php _e('Priority', 'samacharpatra'); ?></strong></label>
                            <input type="number" id="priority" name="priority" min="1" max="10" value="<?php echo esc_attr($defaults['priority']); ?>">
                            <p class="description"><?php _e('Higher priority ads are shown first (1-10).', 'samacharpatra'); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="postbox">
                    <h3 class="hndle"><?php _e('Targeting & Scheduling', 'samacharpatra'); ?></h3>
                    <div class="inside">
                        <div class="sp-form-field">
                            <label><strong><?php _e('Target Pages', 'samacharpatra'); ?></strong></label>
                            <div class="sp-checkbox-group">
                                <label><input type="checkbox" name="target_pages[]" value="home" <?php checked(in_array('home', $target_pages_array)); ?>> <?php _e('Homepage', 'samacharpatra'); ?></label>
                                <label><input type="checkbox" name="target_pages[]" value="single" <?php checked(in_array('single', $target_pages_array)); ?>> <?php _e('Single Posts', 'samacharpatra'); ?></label>
                                <label><input type="checkbox" name="target_pages[]" value="page" <?php checked(in_array('page', $target_pages_array)); ?>> <?php _e('Pages', 'samacharpatra'); ?></label>
                                <label><input type="checkbox" name="target_pages[]" value="category" <?php checked(in_array('category', $target_pages_array)); ?>> <?php _e('Category Pages', 'samacharpatra'); ?></label>
                                <label><input type="checkbox" name="target_pages[]" value="archive" <?php checked(in_array('archive', $target_pages_array)); ?>> <?php _e('Archive Pages', 'samacharpatra'); ?></label>
                                <label><input type="checkbox" name="target_pages[]" value="search" <?php checked(in_array('search', $target_pages_array)); ?>> <?php _e('Search Results', 'samacharpatra'); ?></label>
                            </div>
                            <p class="description"><?php _e('Leave unchecked to show on all pages.', 'samacharpatra'); ?></p>
                        </div>
                        
                        <div class="sp-form-field">
                            <label for="start_date"><strong><?php _e('Start Date', 'samacharpatra'); ?></strong></label>
                            <input type="datetime-local" id="start_date" name="start_date" value="<?php echo esc_attr($defaults['start_date']); ?>">
                        </div>
                        
                        <div class="sp-form-field">
                            <label for="end_date"><strong><?php _e('End Date', 'samacharpatra'); ?></strong></label>
                            <input type="datetime-local" id="end_date" name="end_date" value="<?php echo esc_attr($defaults['end_date']); ?>">
                        </div>
                    </div>
                </div>
                
                <div class="postbox">
                    <h3 class="hndle"><?php _e('Limits', 'samacharpatra'); ?></h3>
                    <div class="inside">
                        <div class="sp-form-field">
                            <label for="max_impressions"><strong><?php _e('Max Impressions', 'samacharpatra'); ?></strong></label>
                            <input type="number" id="max_impressions" name="max_impressions" min="0" value="<?php echo esc_attr($defaults['max_impressions']); ?>">
                            <p class="description"><?php _e('0 = unlimited', 'samacharpatra'); ?></p>
                        </div>
                        
                        <div class="sp-form-field">
                            <label for="max_clicks"><strong><?php _e('Max Clicks', 'samacharpatra'); ?></strong></label>
                            <input type="number" id="max_clicks" name="max_clicks" min="0" value="<?php echo esc_attr($defaults['max_clicks']); ?>">
                            <p class="description"><?php _e('0 = unlimited', 'samacharpatra'); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="postbox">
                    <div class="inside">
                        <input type="submit" name="save_ad" class="button button-primary button-large" value="<?php echo $editing ? __('Update Ad', 'samacharpatra') : __('Create Ad', 'samacharpatra'); ?>">
                        <a href="<?php echo admin_url('admin.php?page=sp-ads-manager'); ?>" class="button button-secondary"><?php _e('Cancel', 'samacharpatra'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Handle ad type change
    $('#ad_type').on('change', function() {
        var selectedType = $(this).val();
        $('.ad-type-help').hide();
        $('.' + selectedType + '-help').show();
        
        // Show/hide media upload button
        if (selectedType === 'image') {
            $('.sp-media-upload').show();
        } else {
            $('.sp-media-upload').hide();
        }
    });
    
    // Media uploader
    var mediaUploader;
    $('.sp-upload-image').on('click', function(e) {
        e.preventDefault();
        
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        
        mediaUploader = wp.media({
            title: '<?php _e('Choose Ad Image', 'samacharpatra'); ?>',
            button: {
                text: '<?php _e('Use this image', 'samacharpatra'); ?>'
            },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            var imgTag = '<img src="' + attachment.url + '" alt="' + attachment.alt + '" style="max-width: 100%; height: auto;">';
            $('#ad_content').val(imgTag);
        });
        
        mediaUploader.open();
    });
});
</script>