<?php
/**
 * Tourism Template
 * Displays 6 tourism posts: 2 vertical cards in first row, 4 horizontal cards in next 2 rows
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
        

        <div class="three-one-layout">
            <!-- Main Tourism Content - 3/4 width -->
            <div class="three-one-content">
                <?php
                // Query for tourism posts
                $tourism_args = array(
                    'posts_per_page' => 6,
                    'post_status' => 'publish',
                    'category_name' => 'tourism',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'ignore_sticky_posts' => 1
                );
                
                $tourism_query = new WP_Query($tourism_args);
                
                if ($tourism_query->have_posts()) :
                    // Collect all posts first
                    $all_posts = array();
                    while ($tourism_query->have_posts()) : 
                        $tourism_query->the_post();
                        $all_posts[] = get_post();
                    endwhile;
                    wp_reset_postdata();

                    // Split posts: first 2 for vertical, last 4 for horizontal
                    $vertical_posts = array_slice($all_posts, 0, 2);
                    $horizontal_posts = array_slice($all_posts, 2, 4);
                ?>
                
                <div class="sixty-grid-col">
                    <div class="section-header">
            <h2 class="section-title">
                पर्यटन
            </h2>
            <a href="<?php echo esc_url(home_url('/tourism')); ?>" class="view-all-link">
                सबै हेर्नुहोस्
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
                    <!-- First Row: 2 Vertical Posts -->
                    <div class="post-row">
                        <?php foreach ($vertical_posts as $post) : setup_postdata($post); ?>
                            <article class="post-card-vertical">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="post-image-wrapper-vertical">
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
                                                 alt="<?php the_title_attribute(); ?>" 
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

                    <!-- Next 2 Rows: 4 Horizontal Posts -->
                    <div class="post-section-horizontal">
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
                                                     alt="<?php the_title_attribute(); ?>" 
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
                </div>
                
                <?php else : ?>
                    <p class="no-post">कुनै पर्यटन समाचार भेटिएन।</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>