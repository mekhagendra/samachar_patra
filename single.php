<?php
/**
 * The template for displaying all single posts
 * 
 * @package Samachar_Patra
 * @since 1.0
 */

// Capture content for layout
ob_start();
?>

<?php while (have_posts()) : the_post(); ?>

            <div class="container"> 
             
                <div class="single-news-wrapper responsive-single-layout">

                   <div class="single-article-content">

                    <article id="post-<?php the_ID(); ?>" <?php post_class('single-news-article'); ?>>
                        
                        <!-- Article Header -->
                        <header class="article-header">
                            <!-- Article Title -->
                            <h1 class="article-title"><?php the_title(); ?></h1>
                            
                            <!-- Featured Image - Display just below title -->
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="article-featured-image">
                                    <?php the_post_thumbnail('large', array('class' => 'article-image')); ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Publication Info -->
                            <div class="publication-info">
                                <div class="publication-meta">
                                    <span class="publication-source">Samacharpatra</span>
                                    <span class="publication-date">
                                        <?php 
                                        // Use Smart Date System for full date and time
                                        if (function_exists('full_date_time')) {
                                            echo full_date_time(get_the_time('U'));
                                        } elseif (function_exists('samacharpatra_smart_full_date')) {
                                            echo samacharpatra_smart_full_date(get_the_time('U')) . ' ' . get_the_time('g:i A');
                                        } else {
                                            // Fallback to regular date
                                            echo get_the_date('j M, Y') . ' ' . get_the_time('g:i A');
                                        }
                                        ?>
                                    </span>
                                </div>
                                
                                <!-- Social Share Buttons -->
                                <div class="social-share-buttons">
                                    <span class="share-count"><?php echo get_comments_number(); ?> &nbsp; Shares</span>
                                    <div class="share-buttons">
                                        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" 
                                           target="_blank" 
                                           class="share-btn whatsapp"
                                           title="WhatsApp मा साझा गर्नुहोस्">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.485 3.515"/>
                                            </svg>
                                        </a>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                                           target="_blank" 
                                           class="share-btn messenger"
                                           title="Messenger मा साझा गर्नुहोस्">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 0C5.376 0 0 4.568 0 10.199c0 3.208 1.6 6.065 4.098 7.933V24l5.358-2.942C10.954 21.358 11.46 21.4 12 21.4c6.624 0 12-4.568 12-10.201C24 4.568 18.624 0 12 0zm1.2 13.8L10.8 12l-4.8 1.8L12 6l2.4 1.8L18 6l-6 7.8z"/>
                                            </svg>
                                        </a>
                                        <a href="javascript:void(0)" 
                                           class="share-btn sharethis"
                                           onclick="shareArticle()"
                                           title="अन्य तरिकाले साझा गर्नुहोस्">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92-1.31-2.92-2.92-2.92z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </header>

                            <!-- Post Content -->
                        <!-- Article Content -->
                        <div class="article-content">
                            <?php
                            the_content();
                            
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'samacharpatra'),
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>

                    </article>
                </div>

                <!-- Quicklist Sidebar -->
                <aside class="single-quicklist-sidebar">
                    <?php get_template_part('templates/components/quicklist'); ?>
                </aside>

                <!-- sidebar2 -->
                <section>
            <aside class="sidebar-2">
                <?php if (is_active_sidebar('sidebar-2')) : ?>
                    <?php dynamic_sidebar('sidebar-2'); ?>
                <?php else : ?>
                <?php endif; ?>
        </section>

                    <!-- Related News Section - थप समाचार -->
                    <section class="related-news-full-width">
                    <?php
                    $related_posts = get_posts(array(
                        'numberposts' => 8,
                        'post__not_in' => array(get_the_ID()),
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));
                    if (!empty($related_posts)) :
                    ?>
                        <div class="related-news-section">
                            <h3 class="related-news-title">थप सम्बन्धित समाचार</h3>
                            <div class="full-width-grid">
                                <?php foreach ($related_posts as $post) : setup_postdata($post); ?>
                                    <article class="related-news-item">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="related-news-image">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail('samacharpatra-medium'); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <div class="related-news-content">
                                            <h4 class="post-title post-title-sm">
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h4>
                                        </div>
                                    </article>
                                <?php endforeach; wp_reset_postdata(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    </section>

                </div>
            </div>

        <?php endwhile; ?>

    </main>
</div>

<!-- JavaScript for social sharing -->
<script>
function shareArticle() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo esc_js(get_the_title()); ?>',
            url: '<?php echo esc_js(get_permalink()); ?>'
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        const url = '<?php echo esc_js(get_permalink()); ?>';
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(function() {
                alert('लिंक कपी भयो!');
            });
        }
    }
}
</script>

<?php 
// Capture the content and pass it to layout
$layout_content = ob_get_clean();

// Now include the layout with our content
include get_template_directory() . '/templates/layouts/default.php';
