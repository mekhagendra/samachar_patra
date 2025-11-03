<?php
/**
 * Main News Template
 * Displays the latest news grid with enhanced styling
 * 
 * @package Samachar_Patra
 * @since 1.0
 */

// Don't load directly
if (!defined('ABSPATH')) {
    exit;
}
?>
    <div>
        <div class="category-header">
        <h2 class="category-title">
            मुख्य समाचार
        </h2>
        <a href="<?php echo esc_url(get_category_link(get_cat_ID('video'))); ?>" class="view-all-link">
            सबै हेर्नुहोस्
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>
<div class="main-news-grid">
        <?php
            $latest_posts = get_posts(array(
            'numberposts' => 6,
            'orderby' => 'date',
            'order' => 'DESC'
            ));
                    
            if (!empty($latest_posts)) :
                foreach ($latest_posts as $post) : setup_postdata($post);
        ?>

    <article class="main-news-card enhanced">
        <?php if (has_post_thumbnail()) : ?>
            <div class="news-image">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('samacharpatra-medium', array('alt' => get_the_title())); ?>
                </a>
                                    
                <!-- Additional Meta - Image Footer Overlay -->
                <div class="image-footer-meta">
                    <div class="reading-time">
                        <i class="fas fa-book-open"></i>
                        <?php echo samacharpatra_reading_time(get_the_content()); ?> मिनेट पढाइ
                    </div>
                                        
                    <div class="engagement-stats">
                        <span class="comments-count">
                            <i class="fas fa-comment"></i>
                                <a href="<?php comments_link(); ?>">
                                    <?php comments_number('0', '1', '%'); ?>
                                </a>
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
                            
        <div class="main-news-content">
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
    
    
                
