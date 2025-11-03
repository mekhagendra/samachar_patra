<?php
/**
 * Video Component Template
 * Displays video content in 3-column grid with YouTube/media player integration
 * 
 * @package Samachar_Patra
 * @since 1.0
 */

// Don't load directly
if (!defined('ABSPATH')) {
    exit;
}

// Get posts from 'video' category only, with fallback to recent posts
$video_posts = get_posts(array(
    'numberposts' => 3,
    'category_name' => 'video',
    'orderby' => 'date',
    'order' => 'DESC'
));

if (!empty($video_posts)) : ?>

<section class="video-section container">
    <!-- Video Section Header -->
    <div class="category-header">
        <h2 class="category-title">
            भिडियो
        </h2>
        <a href="<?php echo esc_url(get_category_link(get_cat_ID('video'))); ?>" class="view-all-link">
            सबै हेर्नुहोस्
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <!-- Video Grid -->
    <div class="video-grid">
        <?php 
        foreach ($video_posts as $post) : 
            setup_postdata($post);
            
            // Get video URL from custom field
            $video_url = get_post_meta(get_the_ID(), 'video_url', true);
            
            // Use post featured image or placeholder
            $thumbnail_url = '';
            if (has_post_thumbnail()) {
                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            }
        ?>
        
        <article class="video-item">
            <div class="video-thumbnail-wrapper">
                <?php if ($thumbnail_url) : ?>
                    <img src="<?php echo esc_url($thumbnail_url); ?>" 
                         alt="<?php echo esc_attr(get_the_title()); ?>" 
                         class="video-thumbnail"
                         loading="lazy">
                <?php endif; ?>
                
                <!-- Title Overlay -->
                <div class="video-title-overlay">
                    <h3 class="video-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                </div>
                
                <!-- Play Button Overlay -->
                <div class="video-play-overlay">
                    <button class="play-button" aria-label="Play Video">
                        <i class="fas fa-play"></i>
                    </button>
                </div>
            </div>
        </article>
        
        <?php endforeach; wp_reset_postdata(); ?>
    </div>
</section>

<style>

</style>

<?php 
else : 
    if (current_user_can('administrator')) {
        echo '<div style="padding: 20px; background: #f0f0f0; margin: 20px 0; border-left: 4px solid #dc3232;">';
        echo '<strong>Video Section:</strong> No video posts found.<br>';
        echo 'Create a "video" category and add posts to display videos.';
        echo '</div>';
    }
endif; ?>