<?php
/**
 * Trending News Template
 * Displays 7 trending posts: 5 horizontal cards in left column, 2 vertical cards in right column
 * 
 * @package Samachar_Patra
 * @since 1.0
 */

// Don't load directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="post-section">
    <div class="container">
        <div class="section-header-hot">
            <h2 class="section-title">
                ट्रेन्डिङ
            </h2>
        </div>

        <div class="three-one-layout">
            <div>
                <?php
                // Query for trending posts
                $trending_args = array(
                    'posts_per_page' => 6,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'ignore_sticky_posts' => 1
                );
                
                $trending_query = new WP_Query($trending_args);
                
                if ($trending_query->have_posts()) :
                    // Collect all posts first
                    $all_posts = array();
                    while ($trending_query->have_posts()) : 
                        $trending_query->the_post();
                        $all_posts[] = get_post();
                    endwhile;
                    wp_reset_postdata();

                    // Split posts: first 4 for horizontal, last 2 for vertical
                    $horizontal_posts = array_slice($all_posts, 0, 4);
                    $vertical_posts = array_slice($all_posts, 4, 2);
                ?>
                <div class="sixty-grid">

                
                    <!-- Left Column: 4 Horizontal Posts -->
                    <div class="post-col">
                        <?php foreach ($horizontal_posts as $post) : setup_postdata($post); ?>
                            <article class="post-card-horizontal">
                                <div class="post-card-content">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="post-image-wrapper-horizontal">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('thumbnail', array(
                                                    'class' => 'post-image-sm',
                                                    'loading' => 'lazy'
                                                )); ?>
                                            </a>
                                        </div>
                                    <?php else : ?>
                                        <div class="post-image-wrapper-horizontal">
                                            <a href="<?php the_permalink(); ?>">
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder.jpg" 
                                                     alt="<?php the_title(); ?>" 
                                                     class="post-image-sm">
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="post-text-content">
                                        <h3 class="post-title post-title-sm">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </h3>
                                        
                                        <?php
                                        // Display post meta
                                        get_template_part('templates/parts/post-meta', null, array(
                                            'variant' => 'relative_time'
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </div>

                    <!-- Right Column: 2 Vertical Posts -->
                    <div class="post-col">
                        <?php foreach ($vertical_posts as $post) : setup_postdata($post); ?>
                            <article class="post-card-vertical">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="post-image-wrapper">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', array(
                                                'class' => 'post-image',
                                                'loading' => 'lazy'
                                            )); ?>
                                        </a>
                                    </div>
                                <?php else : ?>
                                    <div class="post-image-wrapper">
                                        <a href="<?php the_permalink(); ?>">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder.jpg" 
                                                 alt="<?php the_title(); ?>" 
                                                 class="post-image">
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="post-content">
                                    <h3 class="post-title post-title-sm">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    
                                    <?php
                                    // Display post meta
                                    get_template_part('templates/parts/post-meta', null, array(
                                        'variant' => 'relative_time'
                                    ));
                                    ?>
                                </div>
                            </article>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </div>
                </div>
                
                <?php else : ?>
                    <p class="no-posts">कुनै ट्रेन्डिङ समाचार भेटिएन।</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>