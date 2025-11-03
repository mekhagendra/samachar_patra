<?php
/**
 * Ads List Admin Page
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

// Handle bulk actions
if (isset($_POST['action']) && $_POST['action'] === 'bulk_delete' && !empty($_POST['ads'])) {
    $ad_ids = array_map('intval', $_POST['ads']);
    $placeholders = implode(',', array_fill(0, count($ad_ids), '%d'));
    $wpdb->query($wpdb->prepare("DELETE FROM {$table_ads} WHERE id IN ($placeholders)", ...$ad_ids));
    echo '<div class="notice notice-success"><p>' . __('Selected ads have been deleted.', 'samacharpatra') . '</p></div>';
}

// Get all ads with pagination
$page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

$total_ads = $wpdb->get_var("SELECT COUNT(*) FROM {$table_ads}");
$ads = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM {$table_ads} ORDER BY created_at DESC LIMIT %d OFFSET %d",
    $per_page, $offset
));

$total_pages = ceil($total_ads / $per_page);
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Ads Manager', 'samacharpatra'); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=sp-ads-add-new'); ?>" class="page-title-action">
        <?php _e('Add New Ad', 'samacharpatra'); ?>
    </a>
    
    <hr class="wp-header-end">
    
    <div class="sp-ads-stats-summary">
        <div class="sp-stats-card">
            <h3><?php echo $total_ads; ?></h3>
            <p><?php _e('Total Ads', 'samacharpatra'); ?></p>
        </div>
        <div class="sp-stats-card">
            <h3><?php echo $wpdb->get_var("SELECT COUNT(*) FROM {$table_ads} WHERE status = 'active'"); ?></h3>
            <p><?php _e('Active Ads', 'samacharpatra'); ?></p>
        </div>
        <div class="sp-stats-card">
            <h3><?php echo $wpdb->get_var("SELECT COUNT(DISTINCT location_id) FROM {$table_ads}"); ?></h3>
            <p><?php _e('Used Locations', 'samacharpatra'); ?></p>
        </div>
        <div class="sp-stats-card">
            <h3><?php echo $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}sp_ad_stats WHERE event_type = 'click' AND DATE(created_at) = CURDATE()"); ?></h3>
            <p><?php _e('Today\'s Clicks', 'samacharpatra'); ?></p>
        </div>
    </div>
    
    <?php if (!empty($ads)) : ?>
    <form method="post" id="ads-filter">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <select name="action" id="bulk-action-selector-top">
                    <option value="-1"><?php _e('Bulk Actions', 'samacharpatra'); ?></option>
                    <option value="bulk_delete"><?php _e('Delete', 'samacharpatra'); ?></option>
                </select>
                <input type="submit" class="button action" value="<?php _e('Apply', 'samacharpatra'); ?>">
            </div>
            
            <?php if ($total_pages > 1) : ?>
            <div class="tablenav-pages">
                <span class="displaying-num"><?php printf(__('%s items', 'samacharpatra'), $total_ads); ?></span>
                <?php
                $page_links = paginate_links(array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'prev_text' => __('&laquo;'),
                    'next_text' => __('&raquo;'),
                    'total' => $total_pages,
                    'current' => $page
                ));
                if ($page_links) {
                    echo '<span class="pagination-links">' . $page_links . '</span>';
                }
                ?>
            </div>
            <?php endif; ?>
        </div>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <td class="manage-column column-cb check-column">
                        <input type="checkbox" id="cb-select-all-1">
                    </td>
                    <th class="manage-column column-title"><?php _e('Title', 'samacharpatra'); ?></th>
                    <th class="manage-column column-location"><?php _e('Location', 'samacharpatra'); ?></th>
                    <th class="manage-column column-type"><?php _e('Type', 'samacharpatra'); ?></th>
                    <th class="manage-column column-status"><?php _e('Status', 'samacharpatra'); ?></th>
                    <th class="manage-column column-stats"><?php _e('Stats', 'samacharpatra'); ?></th>
                    <th class="manage-column column-date"><?php _e('Created', 'samacharpatra'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ads as $ad) : 
                    $stats = $wpdb->get_row($wpdb->prepare(
                        "SELECT 
                            SUM(CASE WHEN event_type = 'impression' THEN 1 ELSE 0 END) as impressions,
                            SUM(CASE WHEN event_type = 'click' THEN 1 ELSE 0 END) as clicks
                        FROM {$wpdb->prefix}sp_ad_stats WHERE ad_id = %d",
                        $ad->id
                    ));
                    $location = $wpdb->get_var($wpdb->prepare(
                        "SELECT name FROM {$wpdb->prefix}sp_ad_locations WHERE id = %s",
                        $ad->location_id
                    ));
                ?>
                <tr>
                    <th class="check-column">
                        <input type="checkbox" name="ads[]" value="<?php echo $ad->id; ?>">
                    </th>
                    <td class="column-title">
                        <strong>
                            <a href="<?php echo admin_url('admin.php?page=sp-ads-add-new&edit=' . $ad->id); ?>">
                                <?php echo esc_html($ad->title); ?>
                            </a>
                        </strong>
                        <div class="row-actions">
                            <span class="edit">
                                <a href="<?php echo admin_url('admin.php?page=sp-ads-add-new&edit=' . $ad->id); ?>">
                                    <?php _e('Edit', 'samacharpatra'); ?>
                                </a> |
                            </span>
                            <span class="delete">
                                <a href="#" class="sp-delete-ad" data-ad-id="<?php echo $ad->id; ?>">
                                    <?php _e('Delete', 'samacharpatra'); ?>
                                </a> |
                            </span>
                            <span class="status">
                                <a href="#" class="sp-toggle-status" data-ad-id="<?php echo $ad->id; ?>" data-status="<?php echo $ad->status; ?>">
                                    <?php echo $ad->status === 'active' ? __('Deactivate', 'samacharpatra') : __('Activate', 'samacharpatra'); ?>
                                </a>
                            </span>
                        </div>
                    </td>
                    <td class="column-location">
                        <span class="sp-location-badge">
                            <?php echo esc_html($location ?: $ad->location_id); ?>
                        </span>
                    </td>
                    <td class="column-type">
                        <span class="sp-type-badge sp-type-<?php echo $ad->ad_type; ?>">
                            <?php echo ucfirst($ad->ad_type); ?>
                        </span>
                    </td>
                    <td class="column-status">
                        <span class="sp-status-badge sp-status-<?php echo $ad->status; ?>">
                            <?php echo ucfirst($ad->status); ?>
                        </span>
                    </td>
                    <td class="column-stats">
                        <div class="sp-ad-stats">
                            <span class="impressions"><?php echo intval($stats->impressions); ?> <?php _e('impressions', 'samacharpatra'); ?></span><br>
                            <span class="clicks"><?php echo intval($stats->clicks); ?> <?php _e('clicks', 'samacharpatra'); ?></span>
                        </div>
                    </td>
                    <td class="column-date">
                        <?php echo date_i18n(get_option('date_format'), strtotime($ad->created_at)); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="tablenav bottom">
            <?php if ($total_pages > 1) : ?>
            <div class="tablenav-pages">
                <?php if ($page_links) echo '<span class="pagination-links">' . $page_links . '</span>'; ?>
            </div>
            <?php endif; ?>
        </div>
    </form>
    
    <?php else : ?>
    <div class="sp-no-ads">
        <div class="sp-no-ads-content">
            <h2><?php _e('No ads found', 'samacharpatra'); ?></h2>
            <p><?php _e('Get started by creating your first advertisement.', 'samacharpatra'); ?></p>
            <a href="<?php echo admin_url('admin.php?page=sp-ads-add-new'); ?>" class="button button-primary button-large">
                <?php _e('Create Your First Ad', 'samacharpatra'); ?>
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function($) {
    // Handle select all checkbox
    $('#cb-select-all-1').on('change', function() {
        $('input[name="ads[]"]').prop('checked', this.checked);
    });
    
    // Handle individual checkbox changes
    $('input[name="ads[]"]').on('change', function() {
        var allChecked = $('input[name="ads[]"]:checked').length === $('input[name="ads[]"]').length;
        $('#cb-select-all-1').prop('checked', allChecked);
    });
    
    // Handle delete ad
    $('.sp-delete-ad').on('click', function(e) {
        e.preventDefault();
        if (confirm(spAdsAjax.confirmDelete)) {
            var adId = $(this).data('ad-id');
            $.post(spAdsAjax.ajaxUrl, {
                action: 'sp_delete_ad',
                ad_id: adId,
                nonce: spAdsAjax.nonce
            }, function(response) {
                if (response.success) {
                    location.reload();
                }
            });
        }
    });
    
    // Handle toggle status
    $('.sp-toggle-status').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var adId = $this.data('ad-id');
        var currentStatus = $this.data('status');
        
        $.post(spAdsAjax.ajaxUrl, {
            action: 'sp_toggle_ad_status',
            ad_id: adId,
            nonce: spAdsAjax.nonce
        }, function(response) {
            if (response.success) {
                location.reload();
            }
        });
    });
});
</script>