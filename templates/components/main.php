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
        <div class="category-header">
    <h2 class="category-title">
        मुख्य समाचार
    </h2>
    <?php
    $category_link = samacharpatra_get_category_link('main');
    if ($category_link) : ?>
        <a href="<?php echo esc_url($category_link); ?>" class="view-all-link">
            सबै हेर्नुहोस्
            <i class="fas fa-arrow-right"></i>
        </a>
    <?php endif; ?>
</div>
<div class="main-news-grid">
        <?php
            $latest_posts = get_posts(array(
            'numberposts' => 6,
            'category_name' => 'main',
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
            </div>
        <?php endif; ?>
                            
        <div class="main-news-content">
            <h3 class="main-news-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
                                
            <div class="post-meta meta-item">
                <?php
                    get_template_part('templates/parts/utils/post-meta', null, array('variant' => 'full_date'));
                ?>
            </div>
        </div>
    </article>
    <?php endforeach; wp_reset_postdata(); endif; ?>
    </div>
    </div>
    
    
                
