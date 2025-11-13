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

// Get widget settings with defaults
$technology_title = sanitize_text_field(get_query_var('technology_title', 'प्रविधि'));
$technology_posts_count = absint(get_query_var('technology_posts_count', 4));
$technology_category = sanitize_text_field(get_query_var('technology_category', 'technology'));
?>
<div class="container">
    <div class="section-header">
            <h2 class="section-title">
                <?php echo esc_html($technology_title); ?>
            </h2>
            <a href="<?php echo esc_url(home_url('/technology')); ?>" class="view-all-link">
                सबै हेर्नुहोस्
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
<div class="full-width-grid">
    <?php
        // Query for technology category posts
        $technology_posts = get_posts(array(
            'numberposts' => $technology_posts_count,
            'category_name' => $technology_category,
            'orderby' => 'date',
            'order' => 'DESC'
        ));
                
        if (!empty($technology_posts)) :
            foreach ($technology_posts as $post) : setup_postdata($post);
    ?>
    <article class="post-card enhanced">
        <!-- Featured Image -->
        <?php if (has_post_thumbnail()) : ?>
            <div >
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('medium', array('class' => 'post-image', 'loading' => 'lazy')); ?>
                </a>
            </div>
        <?php endif; ?>
                            
        <div class="post-content">
            <!-- Post Title -->
            <h3 class="post-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
        </div>
    </article>
    <?php endforeach; wp_reset_postdata(); endif; ?>
</div>
</div>
