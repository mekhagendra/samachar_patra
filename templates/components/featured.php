<?php
/**
 * Featured News Template
 * Displays 3 featured category news from the last 24 hours
 * Layout: Title, Image, Byline, Author & Date (one news per row)
 * 
 * @package Samachar_Patra
 * @since 1.0
 */

// Don't load directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="featured-news-section">
    <div class="container">
        
        <div class="featured-news">
            <?php
            // Get featured posts from last 24 hours
            $featured_posts = get_posts(array(
                'numberposts' => 3,
                'meta_query' => array(
                    array(
                        'key' => '_featured_post',
                        'value' => 'yes',
                        'compare' => '='
                    )
                ),
                'date_query' => array(
                    array(
                        'after' => '1 day ago'
                    ),
                ),
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            
            // If no featured posts in last 24 hours, get latest 3 posts with featured images
            if (empty($featured_posts)) {
                $featured_posts = get_posts(array(
                    'numberposts' => 3,
                    'meta_query' => array(
                        array(
                            'key' => '_thumbnail_id',
                            'compare' => 'EXISTS'
                        )
                    ),
                    'date_query' => array(
                        array(
                            'after' => '1 day ago'
                        ),
                    ),
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
            }
            
            // If still no posts from last 24 hours, get latest 3 posts with images
            if (empty($featured_posts)) {
                $featured_posts = get_posts(array(
                    'numberposts' => 3,
                    'meta_query' => array(
                        array(
                            'key' => '_thumbnail_id',
                            'compare' => 'EXISTS'
                        )
                    ),
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
            }
            
            if (!empty($featured_posts)) :
                $post_count = 0;
                foreach ($featured_posts as $post) : 
                    setup_postdata($post);
                    $post_count++;
            ?>
                <article class="featured-news-item featured-news-<?php echo $post_count; ?>">
                    <!-- Row 1: Title -->
                    <div class="featured-title-row">
                        <h1 class="featured-news-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h1>
                    </div>

                     <!-- Row 3: Author, Date, Time, and Reading Time in Single Row -->
                    <div class="featured-meta-row">
                        <div class="featured-single-row-meta">
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
                                // Use Smart Date System for location-aware date formatting
                                if (function_exists('smart_date_short')) {
                                    echo short_date(get_the_time('U'));
                                } else {
                                    echo get_the_date('M j');
                                }
                                ?>
                            </time>
                        </div>
                    </div>
                    
                    <!-- Row 2: Image with Full Width -->
                    <div class="featured-image">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php 
                                the_post_thumbnail('large', array(
                                    'style' => 'width: 100%; height: auto; display: block; object-fit: cover;',
                                    'loading' => 'lazy',
                                    'alt' => get_the_title()
                                ));
                                ?>
                            </a>
                        <?php endif; ?>
                    </div>

                   
                </article>
                
                <?php if ($post_count < 3 && $post_count < count($featured_posts)) : ?>

                <?php endif; ?>
                
            <?php 
                endforeach; 
                wp_reset_postdata();
            else:
            ?>
                <div class="no-featured-news">
                    <i class="fas fa-info-circle"></i>
                    <p>हाल कुनै फिचर्ड समाचार उपलब्ध छैन।</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
/* Featured Image Styling - Full Width Coverage */
.featured-image {
    width: 100%;
    margin: 10px 0;
    overflow: hidden;
}

.featured-image a {
    display: block;
    width: 100%;
    line-height: 0;
}

.featured-image img {
    width: 100% !important;
    height: auto !important;
    display: block !important;
    border-radius: 4px;
    transition: transform 0.3s ease;
    object-fit: cover !important;
    max-width: none !important;
}

.featured-image img:hover {
    transform: scale(1.02);
}

/* Ensure featured news container allows full width */
.featured-news-container {
    width: 100%;
}

.featured-news-item {
    width: 100%;
    margin-bottom: 20px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .featured-image {
        margin: 8px 0;
    }
    
    .featured-image img {
        border-radius: 2px;
    }
}
</style>