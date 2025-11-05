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

<div class="tourism-section">
    <div class="container">
        

        <div class="tourism-layout">
            <!-- Main Tourism Content - 3/4 width -->
            <div class="tourism-content">
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
                
                <div class="tourism-grid">
                    <div class="tourism-header">
            <h2 class="section-title">
                <i class="fas fa-plane"></i>
                पर्यटन
            </h2>
            <a href="<?php echo esc_url(home_url('/tourism')); ?>" class="view-all-link">
                सबै हेर्नुहोस्
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
                    <!-- First Row: 2 Vertical Posts -->
                    <div class="tourism-vertical-row">
                        <?php foreach ($vertical_posts as $post) : setup_postdata($post); ?>
                            <article class="tourism-vertical-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="tourism-vertical-image-wrapper">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', array(
                                                'class' => 'tourism-vertical-image',
                                                'loading' => 'lazy'
                                            )); ?>
                                        </a>
                                    </div>
                                <?php else : ?>
                                    <div class="tourism-vertical-image-wrapper">
                                        <a href="<?php the_permalink(); ?>">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder.jpg" 
                                                 alt="<?php the_title_attribute(); ?>" 
                                                 class="tourism-vertical-image">
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="tourism-vertical-content">
                                    <h3 class="tourism-vertical-title">
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
                    <div class="tourism-horizontal-section">
                        <?php foreach ($horizontal_posts as $post) : setup_postdata($post); ?>
                            <article class="tourism-horizontal-card">
                                <div class="tourism-card-content">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="tourism-image-wrapper">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('thumbnail', array(
                                                    'class' => 'tourism-image',
                                                    'loading' => 'lazy'
                                                )); ?>
                                            </a>
                                        </div>
                                    <?php else : ?>
                                        <div class="tourism-image-wrapper">
                                            <a href="<?php the_permalink(); ?>">
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder.jpg" 
                                                     alt="<?php the_title_attribute(); ?>" 
                                                     class="tourism-image">
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="tourism-text-content">
                                        <h3 class="tourism-title">
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
                    <p class="no-tourism">कुनै पर्यटन समाचार भेटिएन।</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>