<?php
/**
 * Main News Template
 * @package Samachar_Patra
 * @since 1.0
 */

// Don't load directly
if (!defined('ABSPATH')) {
    exit;
}
?>
    <div>
        <div class="section-header">
            <h2 class="section-title">
                मुख्य समाचार
            </h2>
            <a href="<?php echo esc_url(home_url('/main')); ?>" class="view-all-link">
                सबै हेर्नुहोस्
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
<div class="three-quarter-width-grid">
        <?php
            $latest_posts = get_posts(array(
            'numberposts' => 4,
            'category_name' => 'main',
            'orderby' => 'date',
            'order' => 'DESC'
            ));
                    
            if (!empty($latest_posts)) :
                foreach ($latest_posts as $post) : setup_postdata($post);
        ?>

    <article class="post-card enhanced">
        <?php if (has_post_thumbnail()) : ?>
            <div class="post-image">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('samacharpatra-medium', array('alt' => get_the_title())); ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="post-content">
            <h3 class="post-title post-title-md">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
        </div>
    </article>
    <?php endforeach; wp_reset_postdata(); endif; ?>
    </div>
    </div>
    
    
                
