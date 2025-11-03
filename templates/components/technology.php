<?php
/**
 * Interview Template
 * Displays interview content in 4-column grid layout
 * 
 * @package Samachar_Patra
 * @since 1.0
 */

// Don't load directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="container">
 <div class="category-header">
        <h2 class="category-title">
            प्रविधि
        </h2>
        <a href="<?php echo esc_url(get_category_link(get_cat_ID('video'))); ?>" class="view-all-link">
            सबै हेर्नुहोस्
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>
<div class="interview-news-grid">
    <?php
        // Query for interview category posts
        $technology_posts = get_posts(array(
            'numberposts' => 8, // 8 posts for 4 columns x 2 rows
            'category_name' => 'technology', // Change this to your interview category slug
            'orderby' => 'date',
            'order' => 'DESC'
        ));
                
        if (!empty($technology_posts)) :
            foreach ($technology_posts as $post) : setup_postdata($post);
    ?>
    <article class="news-card enhanced-card">
        <!-- Featured Image -->
        <?php if (has_post_thumbnail()) : ?>
            <div class="news-image-wrapper">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('medium', array('class' => 'news-image', 'loading' => 'lazy')); ?>
                </a>
                
                <!-- Enhanced Image Overlay -->
                <div class="image-overlay-enhanced">
                    <div class="overlay-content">
                        <!-- Reading Time -->
                        <span class="reading-time">
                            <i class="fas fa-clock"></i>
                            <?php 
                            // Simple reading time calculation (approx 200 words per minute)
                            $word_count = str_word_count(strip_tags(get_the_content()));
                            $reading_time = ceil($word_count / 200);
                            echo $reading_time . ' मिनेट';
                            ?>
                        </span>
                        
                        <!-- Engagement Stats -->
                        <span class="engagement-stats">
                            <a href="<?php the_permalink(); ?>#comments" class="comment-count">
                                <i class="fas fa-comments"></i>
                                <?php comments_number('0', '1', '%'); ?>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
                            
        <div class="news-content">
            <!-- News Title -->
            <h3 class="main-news-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
                                
            <!-- Author and Date Info -->
            <div class="news-meta-enhanced">
                <div class="main-news-single-row-meta">
                    <div class="author-info-block">
                        <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="author-link">
                            <div class="author-avatar">
                                <?php echo get_avatar(get_the_author_meta('ID'), 28, '', get_the_author()); ?>
                            </div>
                            <span class="author-name">
                                <?php the_author(); ?>
                            </span>
                        </a>
                    </div>
                                        
                    <time datetime="<?php echo get_the_date('c'); ?>" class="publish-date">
                        <i class="fas fa-calendar-alt"></i>
                            <?php 
                            // Use Smart Date System for short date formatting
                            if (function_exists('smart_date_short')) {
                                echo smart_date_short(get_the_time('U'));
                            } else {
                                echo get_the_date('M j');
                            }
                            ?>
                    </time>
                </div>
            </div>
        </div>
    </article>
    <?php endforeach; wp_reset_postdata(); endif; ?>
</div>
</div>
