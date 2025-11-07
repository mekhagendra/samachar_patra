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
    <div class="section-header">
            <h2 class="section-title">
                प्रविधि
            </h2>
            <a href="<?php echo esc_url(home_url('/technology')); ?>" class="view-all-link">
                सबै हेर्नुहोस्
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
<div class="full-width-grid">
    <?php
        // Query for interview category posts
        $technology_posts = get_posts(array(
            'numberposts' => 4, // 4 posts for 4 columns x 1 row
            'category_name' => 'technology', // Change this to your interview category slug
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
