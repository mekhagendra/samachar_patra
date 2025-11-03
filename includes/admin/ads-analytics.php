<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get ads manager instance
global $ads_manager;

// Get current tab
$current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'overview';

// Date range for filtering
$date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : date('Y-m-d', strtotime('-30 days'));
$date_to = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : date('Y-m-d');

// Get analytics data
$overview_stats = get_analytics_overview($date_from, $date_to);
$top_performing_ads = get_top_performing_ads($date_from, $date_to);
$location_performance = get_location_performance($date_from, $date_to);
$daily_stats = get_daily_stats($date_from, $date_to);

?>

<div class="wrap">
    <h1>Ads Analytics</h1>
    
    <!-- Date Filter -->
    <div class="ads-date-filter">
        <form method="get" style="margin-bottom: 20px;">
            <input type="hidden" name="page" value="<?php echo esc_attr($_GET['page']); ?>">
            <input type="hidden" name="tab" value="<?php echo esc_attr($current_tab); ?>">
            
            <label>From: </label>
            <input type="date" name="date_from" value="<?php echo esc_attr($date_from); ?>">
            
            <label>To: </label>
            <input type="date" name="date_to" value="<?php echo esc_attr($date_to); ?>">
            
            <button type="submit" class="button">Filter</button>
            <a href="<?php echo admin_url('admin.php?page=ads-analytics'); ?>" class="button">Reset</a>
        </form>
    </div>

    <!-- Tab Navigation -->
    <nav class="nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=ads-analytics&tab=overview&date_from=' . $date_from . '&date_to=' . $date_to); ?>" 
           class="nav-tab <?php echo $current_tab === 'overview' ? 'nav-tab-active' : ''; ?>">
            Overview
        </a>
        <a href="<?php echo admin_url('admin.php?page=ads-analytics&tab=ads-performance&date_from=' . $date_from . '&date_to=' . $date_to); ?>" 
           class="nav-tab <?php echo $current_tab === 'ads-performance' ? 'nav-tab-active' : ''; ?>">
            Ads Performance
        </a>
        <a href="<?php echo admin_url('admin.php?page=ads-analytics&tab=locations&date_from=' . $date_from . '&date_to=' . $date_to); ?>" 
           class="nav-tab <?php echo $current_tab === 'locations' ? 'nav-tab-active' : ''; ?>">
            Locations
        </a>
        <a href="<?php echo admin_url('admin.php?page=ads-analytics&tab=trends&date_from=' . $date_from . '&date_to=' . $date_to); ?>" 
           class="nav-tab <?php echo $current_tab === 'trends' ? 'nav-tab-active' : ''; ?>">
            Trends
        </a>
    </nav>

    <!-- Tab Content -->
    <div class="analytics-content">
        <?php if ($current_tab === 'overview'): ?>
            <!-- Overview Tab -->
            <div class="analytics-overview">
                <h2>Overview (<?php echo date('M j, Y', strtotime($date_from)); ?> - <?php echo date('M j, Y', strtotime($date_to)); ?>)</h2>
                
                <!-- Summary Cards -->
                <div class="analytics-cards">
                    <div class="analytics-card">
                        <div class="card-icon">👁️</div>
                        <div class="card-content">
                            <h3><?php echo number_format($overview_stats['total_impressions']); ?></h3>
                            <p>Total Impressions</p>
                        </div>
                    </div>
                    
                    <div class="analytics-card">
                        <div class="card-icon">👆</div>
                        <div class="card-content">
                            <h3><?php echo number_format($overview_stats['total_clicks']); ?></h3>
                            <p>Total Clicks</p>
                        </div>
                    </div>
                    
                    <div class="analytics-card">
                        <div class="card-icon">📊</div>
                        <div class="card-content">
                            <h3><?php echo $overview_stats['ctr']; ?>%</h3>
                            <p>Click-through Rate</p>
                        </div>
                    </div>
                    
                    <div class="analytics-card">
                        <div class="card-icon">📈</div>
                        <div class="card-content">
                            <h3><?php echo $overview_stats['active_ads']; ?></h3>
                            <p>Active Ads</p>
                        </div>
                    </div>
                </div>

                <!-- Top Performing Ads -->
                <div class="analytics-section">
                    <h3>Top Performing Ads</h3>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Ad Title</th>
                                <th>Location</th>
                                <th>Impressions</th>
                                <th>Clicks</th>
                                <th>CTR</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($top_performing_ads)): ?>
                                <?php foreach ($top_performing_ads as $ad): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo esc_html($ad['title']); ?></strong>
                                            <div class="ad-type-badge"><?php echo esc_html(ucfirst($ad['type'])); ?></div>
                                        </td>
                                        <td><?php echo esc_html($ad['location_name'] ?: 'Multiple'); ?></td>
                                        <td><?php echo number_format($ad['impressions']); ?></td>
                                        <td><?php echo number_format($ad['clicks']); ?></td>
                                        <td><?php echo $ad['ctr']; ?>%</td>
                                        <td>
                                            <a href="<?php echo admin_url('admin.php?page=ads-manage&action=edit&id=' . $ad['id']); ?>" class="button button-small">Edit</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 20px;">No data available for the selected period</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php elseif ($current_tab === 'ads-performance'): ?>
            <!-- Ads Performance Tab -->
            <div class="analytics-ads-performance">
                <h2>Individual Ads Performance</h2>
                
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Ad Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Locations</th>
                            <th>Impressions</th>
                            <th>Clicks</th>
                            <th>CTR</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $all_ads_performance = get_all_ads_performance($date_from, $date_to);
                        if (!empty($all_ads_performance)): 
                        ?>
                            <?php foreach ($all_ads_performance as $ad): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo esc_html($ad['title']); ?></strong>
                                        <?php if ($ad['description']): ?>
                                            <div class="ad-description"><?php echo esc_html(wp_trim_words($ad['description'], 10)); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="ad-type-badge <?php echo esc_attr($ad['type']); ?>">
                                            <?php echo esc_html(ucfirst($ad['type'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo esc_attr($ad['status']); ?>">
                                            <?php echo esc_html(ucfirst($ad['status'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo esc_html($ad['location_count']); ?> location(s)</td>
                                    <td><?php echo number_format($ad['impressions']); ?></td>
                                    <td><?php echo number_format($ad['clicks']); ?></td>
                                    <td><?php echo $ad['ctr']; ?>%</td>
                                    <td><?php echo date('M j, Y', strtotime($ad['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 20px;">No ads found for the selected period</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($current_tab === 'locations'): ?>
            <!-- Locations Performance Tab -->
            <div class="analytics-locations">
                <h2>Location Performance</h2>
                
                <div class="location-performance-grid">
                    <?php if (!empty($location_performance)): ?>
                        <?php foreach ($location_performance as $location): ?>
                            <div class="location-performance-card">
                                <h4><?php echo esc_html($location['name']); ?></h4>
                                <div class="location-stats">
                                    <div class="stat">
                                        <label>Active Ads:</label>
                                        <span><?php echo $location['active_ads']; ?></span>
                                    </div>
                                    <div class="stat">
                                        <label>Impressions:</label>
                                        <span><?php echo number_format($location['impressions']); ?></span>
                                    </div>
                                    <div class="stat">
                                        <label>Clicks:</label>
                                        <span><?php echo number_format($location['clicks']); ?></span>
                                    </div>
                                    <div class="stat">
                                        <label>CTR:</label>
                                        <span><?php echo $location['ctr']; ?>%</span>
                                    </div>
                                </div>
                                <div class="location-actions">
                                    <a href="<?php echo admin_url('admin.php?page=ads-locations&location=' . $location['id']); ?>" class="button button-small">Manage</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No location data available for the selected period.</p>
                    <?php endif; ?>
                </div>
            </div>

        <?php elseif ($current_tab === 'trends'): ?>
            <!-- Trends Tab -->
            <div class="analytics-trends">
                <h2>Performance Trends</h2>
                
                <!-- Chart Container -->
                <div class="chart-container">
                    <canvas id="trendsChart" width="400" height="200"></canvas>
                </div>
                
                <!-- Daily Stats Table -->
                <div class="daily-stats-section">
                    <h3>Daily Performance</h3>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Impressions</th>
                                <th>Clicks</th>
                                <th>CTR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($daily_stats)): ?>
                                <?php foreach ($daily_stats as $day): ?>
                                    <tr>
                                        <td><?php echo date('M j, Y', strtotime($day['date'])); ?></td>
                                        <td><?php echo number_format($day['impressions']); ?></td>
                                        <td><?php echo number_format($day['clicks']); ?></td>
                                        <td><?php echo $day['ctr']; ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 20px;">No daily data available</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Chart.js for trends -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if ($current_tab === 'trends' && !empty($daily_stats)): ?>
    // Prepare chart data
    const chartData = {
        labels: [<?php echo "'" . implode("','", array_map(function($day) { return date('M j', strtotime($day['date'])); }, $daily_stats)) . "'"; ?>],
        datasets: [{
            label: 'Impressions',
            data: [<?php echo implode(',', array_column($daily_stats, 'impressions')); ?>],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'Clicks',
            data: [<?php echo implode(',', array_column($daily_stats, 'clicks')); ?>],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1
        }]
    };

    // Create chart
    const ctx = document.getElementById('trendsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Impressions vs Clicks Trend'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    <?php endif; ?>
});
</script>

<style>
.analytics-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.analytics-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.analytics-card .card-icon {
    font-size: 2em;
    margin-right: 15px;
}

.analytics-card .card-content h3 {
    margin: 0;
    font-size: 2em;
    color: #0073aa;
}

.analytics-card .card-content p {
    margin: 5px 0 0 0;
    color: #666;
}

.analytics-section {
    margin: 30px 0;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
}

.location-performance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.location-performance-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.location-performance-card h4 {
    margin: 0 0 15px 0;
    color: #0073aa;
}

.location-stats .stat {
    display: flex;
    justify-content: space-between;
    margin: 8px 0;
    padding: 5px 0;
    border-bottom: 1px solid #f0f0f0;
}

.location-stats .stat:last-child {
    border-bottom: none;
}

.location-stats label {
    font-weight: 600;
    color: #333;
}

.location-actions {
    margin-top: 15px;
    text-align: center;
}

.chart-container {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
}

.daily-stats-section {
    margin: 30px 0;
}

.ad-type-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    color: #fff;
    background: #666;
}

.ad-type-badge.image { background: #2196F3; }
.ad-type-badge.html { background: #4CAF50; }
.ad-type-badge.script { background: #FF9800; }

.status-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    color: #fff;
}

.status-badge.active { background: #4CAF50; }
.status-badge.inactive { background: #757575; }
.status-badge.scheduled { background: #2196F3; }

.ad-description {
    font-size: 12px;
    color: #666;
    margin-top: 4px;
}

.ads-date-filter {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
}

.ads-date-filter label {
    margin: 0 10px 0 15px;
    font-weight: 600;
}

.ads-date-filter input[type="date"] {
    margin-right: 10px;
}
</style>

<?php
// Analytics helper functions
function get_analytics_overview($date_from, $date_to) {
    global $wpdb;
    
    $stats_table = $wpdb->prefix . 'sp_ad_stats';
    $ads_table = $wpdb->prefix . 'sp_ads';
    
    // Get total impressions and clicks
    $totals = $wpdb->get_row($wpdb->prepare("
        SELECT 
            COALESCE(SUM(impressions), 0) as total_impressions,
            COALESCE(SUM(clicks), 0) as total_clicks
        FROM {$stats_table} 
        WHERE DATE(created_at) BETWEEN %s AND %s
    ", $date_from, $date_to));
    
    // Calculate CTR
    $ctr = ($totals->total_impressions > 0) ? round(($totals->total_clicks / $totals->total_impressions) * 100, 2) : 0;
    
    // Get active ads count
    $active_ads = $wpdb->get_var("SELECT COUNT(*) FROM {$ads_table} WHERE status = 'active'");
    
    return [
        'total_impressions' => $totals->total_impressions,
        'total_clicks' => $totals->total_clicks,
        'ctr' => $ctr,
        'active_ads' => $active_ads
    ];
}

function get_top_performing_ads($date_from, $date_to, $limit = 10) {
    global $wpdb;
    
    $stats_table = $wpdb->prefix . 'sp_ad_stats';
    $ads_table = $wpdb->prefix . 'sp_ads';
    $locations_table = $wpdb->prefix . 'sp_ad_locations';
    
    return $wpdb->get_results($wpdb->prepare("
        SELECT 
            a.id,
            a.title,
            a.type,
            l.name as location_name,
            COALESCE(SUM(s.impressions), 0) as impressions,
            COALESCE(SUM(s.clicks), 0) as clicks,
            CASE 
                WHEN SUM(s.impressions) > 0 
                THEN ROUND((SUM(s.clicks) / SUM(s.impressions)) * 100, 2)
                ELSE 0 
            END as ctr
        FROM {$ads_table} a
        LEFT JOIN {$stats_table} s ON a.id = s.ad_id 
            AND DATE(s.created_at) BETWEEN %s AND %s
        LEFT JOIN {$locations_table} l ON JSON_CONTAINS(a.locations, CAST(l.id as JSON), '$')
        WHERE a.status = 'active'
        GROUP BY a.id, a.title, a.type, l.name
        ORDER BY impressions DESC, clicks DESC
        LIMIT %d
    ", $date_from, $date_to, $limit), ARRAY_A);
}

function get_location_performance($date_from, $date_to) {
    global $wpdb;
    
    $stats_table = $wpdb->prefix . 'sp_ad_stats';
    $ads_table = $wpdb->prefix . 'sp_ads';
    $locations_table = $wpdb->prefix . 'sp_ad_locations';
    
    return $wpdb->get_results($wpdb->prepare("
        SELECT 
            l.id,
            l.name,
            l.description,
            COUNT(DISTINCT CASE WHEN a.status = 'active' THEN a.id END) as active_ads,
            COALESCE(SUM(s.impressions), 0) as impressions,
            COALESCE(SUM(s.clicks), 0) as clicks,
            CASE 
                WHEN SUM(s.impressions) > 0 
                THEN ROUND((SUM(s.clicks) / SUM(s.impressions)) * 100, 2)
                ELSE 0 
            END as ctr
        FROM {$locations_table} l
        LEFT JOIN {$ads_table} a ON JSON_CONTAINS(a.locations, CAST(l.id as JSON), '$')
        LEFT JOIN {$stats_table} s ON a.id = s.ad_id 
            AND DATE(s.created_at) BETWEEN %s AND %s
        GROUP BY l.id, l.name, l.description
        ORDER BY impressions DESC
    ", $date_from, $date_to), ARRAY_A);
}

function get_daily_stats($date_from, $date_to) {
    global $wpdb;
    
    $stats_table = $wpdb->prefix . 'sp_ad_stats';
    
    return $wpdb->get_results($wpdb->prepare("
        SELECT 
            DATE(created_at) as date,
            SUM(impressions) as impressions,
            SUM(clicks) as clicks,
            CASE 
                WHEN SUM(impressions) > 0 
                THEN ROUND((SUM(clicks) / SUM(impressions)) * 100, 2)
                ELSE 0 
            END as ctr
        FROM {$stats_table}
        WHERE DATE(created_at) BETWEEN %s AND %s
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ", $date_from, $date_to), ARRAY_A);
}

function get_all_ads_performance($date_from, $date_to) {
    global $wpdb;
    
    $stats_table = $wpdb->prefix . 'sp_ad_stats';
    $ads_table = $wpdb->prefix . 'sp_ads';
    
    return $wpdb->get_results($wpdb->prepare("
        SELECT 
            a.id,
            a.title,
            a.description,
            a.type,
            a.status,
            a.created_at,
            JSON_LENGTH(a.locations) as location_count,
            COALESCE(SUM(s.impressions), 0) as impressions,
            COALESCE(SUM(s.clicks), 0) as clicks,
            CASE 
                WHEN SUM(s.impressions) > 0 
                THEN ROUND((SUM(s.clicks) / SUM(s.impressions)) * 100, 2)
                ELSE 0 
            END as ctr
        FROM {$ads_table} a
        LEFT JOIN {$stats_table} s ON a.id = s.ad_id 
            AND DATE(s.created_at) BETWEEN %s AND %s
        GROUP BY a.id, a.title, a.description, a.type, a.status, a.created_at
        ORDER BY impressions DESC, a.created_at DESC
    ", $date_from, $date_to), ARRAY_A);
}
?>