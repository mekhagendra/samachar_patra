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
        <?php
    $category_link = samacharpatra_get_category_link('technology');
    if ($category_link) : ?>
        <a href="<?php echo esc_url($category_link); ?>" class="view-all-link">
            सबै हेर्नुहोस्
            <i class="fas fa-arrow-right"></i>
        </a>
    <?php endif; ?>
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
            <div >
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('medium', array('class' => 'news-image', 'loading' => 'lazy')); ?>
                </a>
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
                    <time datetime="<?php echo get_the_date('c'); ?>" class="publish-date">
                        <i class="fas fa-calendar-alt"></i>
                            <?php
                                get_template_part('templates/parts/utils/post-meta', null, array('variant' => 'full_date'));
                            ?>
                    </time>
                </div>
            </div>
        </div>
    </article>
    <?php endforeach; wp_reset_postdata(); endif; ?>
</div>
</div>
