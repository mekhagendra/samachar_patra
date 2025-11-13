<?php
/**
 * Quicklist Widget for Samachar Patra theme
 * 
 * @package Samachar_Patra
 * @since 1.0
 */
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="quicklist-widget">
    <?php
    // Get widget settings if available
    $posts_per_tab = get_query_var('quicklist_posts_per_tab', 7);
    $show_latest = get_query_var('quicklist_show_latest', true);
    $show_popular = get_query_var('quicklist_show_popular', true);
    $show_recommended = get_query_var('quicklist_show_recommended', true);
    $popular_days = get_query_var('quicklist_popular_days', 7);
    
    // Determine active tab
    $active_tab = $show_latest ? 'latest' : ($show_popular ? 'popular' : 'recommended');
    ?>
    <div class="quicklist-tabs">
        <div class="tab-navigation">
            <?php if ($show_latest) : ?>
            <button class="tab-btn <?php echo $active_tab === 'latest' ? 'active' : ''; ?>" data-tab="latest-tab">
                <i class="fas fa-bolt"></i>
                ताजा
            </button>
            <?php endif; ?>
            <?php if ($show_popular) : ?>
            <button class="tab-btn <?php echo $active_tab === 'popular' ? 'active' : ''; ?>" data-tab="popular-tab">
                <i class="fas fa-fire"></i>
                लोकप्रिय
            </button>
            <?php endif; ?>
            <?php if ($show_recommended) : ?>
            <button class="tab-btn <?php echo $active_tab === 'recommended' ? 'active' : ''; ?>" data-tab="recommended-tab">
                <i class="fas fa-star"></i>
                सिफारिस
            </button>
            <?php endif; ?>
        </div>
        
        <div class="tab-content">
            <!-- Latest Tab -->
            <?php if ($show_latest) : ?>
            <div class="tab-pane <?php echo $active_tab === 'latest' ? 'active' : ''; ?>" id="latest-tab">
                <div class="quicklist-items">
                    <?php
                    $latest_posts = get_posts(array(
                        'numberposts' => absint($posts_per_tab),
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'post_status' => 'publish'
                    ));
                    
                    if (!empty($latest_posts)) :
                        foreach ($latest_posts as $post) : 
                            setup_postdata($post);
                    ?>
                        <article class="quicklist-item">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="quicklist-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('thumbnail'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="quicklist-content">
                                <h3 class="post-title post-title-xs">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <div class="post-meta-left meta-item">
                                    <?php
                                        get_template_part('templates/parts/utils/post-meta', null, array('variant' => 'relative_time'));
                                    ?>
                                </div>
                            </div>
                        </article>
                    <?php 
                        endforeach; 
                        wp_reset_postdata();
                    else:
                    ?>
                        <p class="no-posts">कुनै समाचार फेला परेन।</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Popular Tab -->
            <?php if ($show_popular) : ?>
            <div class="tab-pane <?php echo $active_tab === 'popular' ? 'active' : ''; ?>" id="popular-tab">
                <div class="quicklist-items">
                    <?php
                    // Get posts from last X days with views
                    $popular_posts = get_posts(array(
                        'numberposts' => absint($posts_per_tab),
                        'meta_key' => 'post_views_count',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC',
                        'post_status' => 'publish',
                        'date_query' => array(
                            array(
                                'after' => absint($popular_days) . ' days ago'
                            ),
                        ),
                        'meta_query' => array(
                            array(
                                'key' => 'post_views_count',
                                'value' => '0',
                                'compare' => '>'
                            )
                        )
                    ));
                    
                    // Fallback if no popular posts
                    if (empty($popular_posts)) {
                        $popular_posts = get_posts(array(
                            'numberposts' => absint($posts_per_tab),
                            'orderby' => 'comment_count',
                            'order' => 'DESC',
                            'post_status' => 'publish'
                        ));
                    }
                    
                    if (!empty($popular_posts)) :
                        foreach ($popular_posts as $post) : 
                            setup_postdata($post);
                    ?>
                        <article class="quicklist-item">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="quicklist-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('thumbnail'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="quicklist-content">
                                <h4 class="post-title post-title-xs">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>
                            </div>
                        </article>
                    <?php 
                        endforeach; 
                        wp_reset_postdata();
                    else:
                    ?>
                        <p class="no-posts">कुनै लोकप्रिय समाचार फेला परेन।</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Recommended Tab -->
            <?php if ($show_recommended) : ?>
            <div class="tab-pane <?php echo $active_tab === 'recommended' ? 'active' : ''; ?>" id="recommended-tab">
                <div class="quicklist-items">
                    <?php
                    // Try to find recommended category
                    $recommended_posts = array();
                    $category_slugs = array('recommended', 'sifaris');
                    
                    foreach ($category_slugs as $slug) {
                        $category = get_category_by_slug($slug);
                        if ($category) {
                            $recommended_posts = get_posts(array(
                                'numberposts' => absint($posts_per_tab),
                                'category' => $category->term_id,
                                'orderby' => 'date',
                                'order' => 'DESC',
                                'post_status' => 'publish'
                            ));
                            break;
                        }
                    }
                    
                    // Fallback to highly viewed posts
                    if (empty($recommended_posts)) {
                        $recommended_posts = get_posts(array(
                            'numberposts' => absint($posts_per_tab),
                            'meta_key' => 'post_views_count',
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC',
                            'post_status' => 'publish',
                            'meta_query' => array(
                                array(
                                    'key' => 'post_views_count',
                                    'value' => '50',
                                    'compare' => '>='
                                )
                            )
                        ));
                    }
                    
                    if (!empty($recommended_posts)) :
                        foreach ($recommended_posts as $post) : 
                            setup_postdata($post);
                    ?>
                        <article class="quicklist-item">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="quicklist-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('thumbnail'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="quicklist-content">
                                <h4 class="post-title post-title-xs">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4> 
                            </div>
                        </article>
                    <?php 
                        endforeach; 
                        wp_reset_postdata();
                    else:
                    ?>
                        <p class="no-posts">कुनै सिफारिस समाचार फेला परेन।</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Enhanced quicklist tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.quicklist-widget .tab-btn');
    const tabPanes = document.querySelectorAll('.quicklist-widget .tab-pane');
    
    if (tabButtons.length === 0 || tabPanes.length === 0) return;
    
    // Add click event to each tab button
    tabButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetTabId = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panes
            tabButtons.forEach(function(btn) {
                btn.classList.remove('active');
            });
            
            tabPanes.forEach(function(pane) {
                pane.classList.remove('active');
            });
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Show target tab pane
            const targetPane = document.getElementById(targetTabId);
            if (targetPane) {
                targetPane.classList.add('active');
            }
            
            // Visual feedback
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
});
</script>
