<?php
/**
 * The main template file - uses layout system
 * 
 * @package Samachar_Patra
 * @since 1.0
 */

// Store all the index content logic in a variable to pass to layout
ob_start();

// Check if this is a custom category route (e.g., /wordpress/news, /wordpress/economy, etc.)
$request_uri = trim($_SERVER['REQUEST_URI'], '/');
$url_parts = explode('/', $request_uri);

// Get the WordPress subdirectory from home URL
$home_url = home_url('/');
$parsed_home = parse_url($home_url);
$wp_path = isset($parsed_home['path']) ? trim($parsed_home['path'], '/') : '';

// Find the category segment after WordPress path
$category_segment = '';
if (!empty($wp_path)) {
    // WordPress is in subdirectory
    $wp_parts = explode('/', $wp_path);
    $wp_parts_count = count($wp_parts);
    if (count($url_parts) > $wp_parts_count) {
        $category_segment = $url_parts[$wp_parts_count];
    }
} else {
    // WordPress is in root
    $category_segment = isset($url_parts[0]) ? $url_parts[0] : '';
}

// Define custom category routes
$category_routes = array(
    'news' => 'news',
    'sports' => 'sports', 
    'politics' => 'politics',
    'economy' => 'economy',
    'entertainment' => 'entertainment',
    'technology' => 'technology',
    'health' => 'health',
    'education' => 'education',
    'international' => 'international',
    'society' => 'society',
    'views' => 'views',
    'agriculture' => 'agriculture',
    'world' => 'world',
    'environment' => 'environment',
    'main' => 'main',
);

// Check if current URL matches a category route
$is_category_route = false;
$category_slug = '';
$category_obj = null;

if (!empty($category_segment) && array_key_exists($category_segment, $category_routes)) {
    $is_category_route = true;
    $category_slug = $category_routes[$category_segment];
    
    // Get category object
    $category_obj = get_category_by_slug($category_slug);
    if (!$category_obj) {
        // Try to get category by name
        $category_obj = get_term_by('name', ucfirst($category_slug), 'category');
    }
    if (!$category_obj) {
        // Try to find category by partial name match
        $categories = get_categories(array('hide_empty' => false));
        foreach ($categories as $cat) {
            if (stripos($cat->slug, $category_slug) !== false || stripos($cat->name, $category_slug) !== false) {
                $category_obj = $cat;
                break;
            }
        }
    }
}

if ($is_category_route && $category_obj): ?>
    <!-- Category Listing Page -->
    <section class="category-listing-page">
        <div class="container">
            <!-- Category Content -->
                <div class="category-content">
                    <?php
                    // Get page number for pagination
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        if (isset($_GET['page'])) {
                            $paged = intval($_GET['page']);
                        }

                        // Query posts from this category
                        $category_query = new WP_Query(array(
                            'cat' => $category_obj->term_id,
                            'posts_per_page' => 12,
                            'paged' => $paged,
                            'post_status' => 'publish',
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));

                        if ($category_query->have_posts()) : ?>
                            <div class="category-posts-grid">
                                <?php while ($category_query->have_posts()) : $category_query->the_post(); ?>
                                    <article class="category-news-item">
                                        <div class="news-card">
                                            <!-- Featured Image -->
                                            <div class="news-image">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_post_thumbnail('medium', array(
                                                            'class' => 'img-fluid',
                                                            'alt' => get_the_title()
                                                        )); ?>
                                                    </a>
                                                <?php else : ?>
                                                    <a href="<?php the_permalink(); ?>">
                                                        <div class="no-image-placeholder">
                                                            <i class="fas fa-image"></i>
                                                            <span>कुनै छवि छैन</span>
                                                        </div>
                                                    </a>
                                                <?php endif; ?>
                                                 
                                            </div>

                                            <!-- News Content -->
                                            <div class="news-content">
                                                <h3 class="main-news-title">
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_title(); ?>
                                                    </a>
                                                </h3>

                                                <div class="news-meta">
                                                    <div class="publish-info">
                                                        <span class="date">
                                                            <i class="fas fa-calendar-alt"></i>
                                                            <?php 
                                                                // Smart Date functions
                                                                if (function_exists('full_date')) {
                                                                    echo full_date(get_the_time('U'));
                                                                } else {
                                                                // Fallback if Smart Date functions not loaded
                                                                    echo get_the_date('F j, Y');
                                                                }
                                                            ?>      
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                <?php endwhile; ?>
                            </div>

                            
                        <?php else : ?>
                            <!-- No Posts Found -->
                            <div class="no-posts-found">
                                <div class="no-posts-content">
                                    <p>माफ गर्नुहोला यस र्शिषकमा कुनै समाचार फेला परेन।</p>
                                </div>
                            </div>
                        <?php endif; 
                        wp_reset_postdata(); ?>
                    </div>
                </div>
            </section>

        <?php elseif ($is_category_route && !$category_obj): ?>
            <!-- Category Not Found -->
            <section class="category-not-found">
                <div class="container">
                    <div class="not-found-content">

                        <p>माफ गर्नुहोस्, तपाईंले खोज्नुभएको श्रेणी अवस्थित छैन।</p>

                    </div>
                </div>
            </section>

        <?php else: ?>
    
            <!-- Featured News Section -->
            <?php get_template_part('templates/components/featured'); ?>
            
            <!-- Main News Section with Sidebar -->
            <section class="latest-news">
                <div class="container">
                    <!-- FIXED: Proper two-column layout -->
                    <div class="content-wrapper responsive-layout">
                        <!-- RIGHT MAIN CONTENT: Main News -->
                        <div class="main-content article-content-area">
                            <?php get_template_part('templates/components/main'); ?>
                        </div>
                        <!-- LEFT SIDEBAR: Quicklist -->
                        <aside class="sidebar quicklist-sidebar">
                            <?php get_template_part('templates/components/quicklist'); ?>
                        </aside>
                        
                        
                    </div>
                </div>
            </section>
            <!-- technology -->
            <?php get_template_part('templates/components/technology'); ?>
        <?php endif; ?>

<?php 
// Capture the content and pass it to layout
$layout_content = ob_get_clean();

// Now include the layout with our content
include get_template_directory() . '/templates/layouts/default.php';
