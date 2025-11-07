<?php
/**
 * Featured News Template
 * 
 * @package Samachar_Patra
 * @since 1.0
 */

// Don't load directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="featured-news-block">
    <div class="container">
            <?php
        // Query for interview category posts
        $featured_posts = get_posts(array(
            'numberposts' => 3, 
            'category_name' => 'featured',
            'orderby' => 'date',
            'order' => 'DESC'
        ));
                
        if (!empty($featured_posts)) :
            foreach ($featured_posts as $post) : setup_postdata($post);
    ?>
                <article class="featured-news-item featured-news<?php echo $post_count; ?>">
                    <div class="featured-title-row">
                        <h1 class="featured-news-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h1>
                    </div>

                    <div class="post-meta meta-item">
                            <?php
                                get_template_part('templates/parts/utils/post-meta', null, array('variant' => 'author_relative_time'));
                            ?>
                    </div>

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