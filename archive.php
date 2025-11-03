<?php
/**
 * The template for displaying archive pages
 * 
 * @package Samachar_Patra
 * @since 1.0
 */

// Capture content for layout
ob_start();
?>

        <!-- Archive Header -->
        <section class="archive-header">
            <div class="container">
                <div class="archive-header-content">
                    <div class="breadcrumbs">
                        <a href="<?php echo home_url(); ?>">मुख्य पृष्ठ</a>
                        <span class="separator">></span>
                        <span class="current">
                            <?php
                            if (is_category()) :
                                echo '<i class="fas fa-folder"></i>';
                                printf('श्रेणी: %s', single_cat_title('', false));
                            elseif (is_day()) :
                                printf('दैनिक अभिलेख: %s', 
                                    function_exists('full_date') 
                                        ? full_date(get_the_time('U')) 
                                        : get_the_date()
                                );
                            elseif (is_month()) :
                                printf('मासिक अभिलेख: %s', 
                                    function_exists('full_date') 
                                        ? full_date(mktime(0, 0, 0, get_the_time('n'), 1, get_the_time('Y'))) 
                                        : get_the_date('F Y')
                                );
                            elseif (is_year()) :
                                printf('वार्षिक अभिलेख: %s', 
                                    function_exists('full_date') 
                                        ? full_date(mktime(0, 0, 0, 1, 1, get_the_time('Y'))) 
                                        : get_the_date('Y')
                                );
                            elseif (is_tag()) :
                                printf('ट्याग: %s', single_tag_title('', false));
                            elseif (is_author()) :
                                printf('लेखक: %s', get_the_author());
                            else :
                                echo 'अभिलेख';
                            endif;
                            ?>
                        </span>
                    </div>
                    
                    <h1 class="archive-title">
                        <?php
                        if (is_category()) :
                            echo '<i class="fas fa-folder"></i>';
                            printf('श्रेणी: %s', single_cat_title('', false));
                        elseif (is_day()) :
                            echo '<i class="fas fa-calendar-day"></i>';
                            printf('दैनिक अभिलेख: %s', 
                                function_exists('full_date') 
                                    ? full_date(get_the_time('U')) 
                                    : get_the_date()
                            );
                        elseif (is_month()) :
                            echo '<i class="fas fa-calendar-alt"></i>';
                            printf('मासिक अभिलेख: %s', 
                                function_exists('full_date') 
                                    ? full_date(mktime(0, 0, 0, get_the_time('n'), 1, get_the_time('Y'))) 
                                    : get_the_date('F Y')
                            );
                        elseif (is_year()) :
                            echo '<i class="fas fa-calendar"></i>';
                            printf('वार्षिक अभिलेख: %s', 
                                function_exists('full_date') 
                                    ? full_date(mktime(0, 0, 0, 1, 1, get_the_time('Y'))) 
                                    : get_the_date('Y')
                            );
                        elseif (is_tag()) :
                            echo '<i class="fas fa-tag"></i>';
                            printf('ट्याग: %s', single_tag_title('', false));
                        elseif (is_author()) :
                            echo '<i class="fas fa-user"></i>';
                            printf('लेखक: %s', get_the_author());
                        elseif (is_post_type_archive()) :
                            echo '<i class="fas fa-archive"></i>';
                            echo post_type_archive_title('', false);
                        else :
                            echo '<i class="fas fa-archive"></i>';
                            echo 'अभिलेख';
                        endif;
                        ?>
                    </h1>
                    
                    <?php 
                    // Category description
                    if (is_category() && category_description()) :
                    ?>
                        <div class="archive-description">
                            <?php echo category_description(); ?>
                        </div>
                    <?php elseif (is_tag() && tag_description()) : ?>
                        <div class="archive-description">
                            <?php echo tag_description(); ?>
                        </div>
                    <?php elseif (is_author() && get_the_author_meta('description')) : ?>
                        <div class="archive-description">
                            <div class="author-info">
                                <div class="author-avatar">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
                                </div>
                                <div class="author-bio">
                                    <p><?php echo get_the_author_meta('description'); ?></p>
                                    <div class="author-stats">
                                        <span class="posts-count">
                                            <i class="fas fa-newspaper"></i>
                                            <?php 
                                            $posts_count = count_user_posts(get_the_author_meta('ID'));
                                            if (function_exists('to_nepali_digits')) {
                                                echo to_nepali_digits($posts_count);
                                            } else {
                                                echo $posts_count;
                                            }
                                            ?> लेखहरू
                                        </span>
                                        <span class="member-since">
                                            <i class="fas fa-calendar"></i>
                                            सदस्य: <?php 
                                            $user_registered = get_the_author_meta('user_registered');
                                            if (function_exists('full_date') && $user_registered) {
                                                echo full_date(strtotime($user_registered));
                                            } else {
                                                echo date('F Y', strtotime($user_registered));
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="archive-meta">
                        <span class="post-count">
                            <i class="fas fa-newspaper"></i>
                            <?php
                            global $wp_query;
                            $found_posts = $wp_query->found_posts;
                            if (function_exists('to_nepali_digits')) {
                                printf('%s समाचारहरू फेला पर्‍यो', to_nepali_digits($found_posts));
                            } else {
                                printf('%d समाचारहरू फेला पर्‍यो', $found_posts);
                            }
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <div class="container">
            <div class="content-wrapper">
                <div class="main-content">
                    
                    <?php if (have_posts()) : ?>
                        
                        <!-- Archive Filters (for certain archive types) -->
                        <?php if (is_author() || is_tag() || is_category()) : ?>
                            <div class="archive-filters">
                                <div class="filter-options">
                                    <span class="filter-label">क्रमबद्ध गर्नुहोस्:</span>
                                    <select id="archive-sort" onchange="sortArchive(this.value)">
                                        <option value="date-desc">नयाँ देखि पुरानो</option>
                                        <option value="date-asc">पुरानो देखि नयाँ</option>
                                        <option value="title-asc">शीर्षक अनुसार (अ-ह)</option>
                                        <option value="title-desc">शीर्षक अनुसार (ह-अ)</option>
                                        <option value="comments">टिप्पणी संख्या अनुसार</option>
                                    </select>
                                </div>
                                
                                <?php if (is_author() || is_category()) : ?>
                                    <div class="view-options">
                                        <span class="view-label">दृश्य:</span>
                                        <button class="view-btn active" data-view="grid" title="ग्रिड दृश्य">
                                            <i class="fas fa-th"></i>
                                        </button>
                                        <button class="view-btn" data-view="list" title="सूची दृश्य">
                                            <i class="fas fa-list"></i>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Posts Grid/List -->
                        <div class="archive-posts-container" id="archive-posts">
                            <?php while (have_posts()) : the_post(); ?>
                                <article id="post-<?php the_ID(); ?>" <?php post_class('archive-post-item'); ?>>
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="post-thumbnail">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('samacharpatra-medium'); ?>
                                            </a>
                                            <?php if (is_sticky()) : ?>
                                                <span class="sticky-label">
                                                    <i class="fas fa-thumbtack"></i>
                                                    चर्चित
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="post-content">
                                        <div class="post-meta">
                                            <span class="post-date">
                                                <i class="fas fa-calendar"></i>
                                                <?php 
                                                // Use EXACT Smart Date System functions from Smart-date.php
                                                if (function_exists('full_date')) {
                                                    echo full_date(get_the_time('U'));
                                                } else {
                                                    // Fallback if Smart Date functions not loaded
                                                    echo get_the_date('F j, Y');
                                                }
                                                ?>
                                            </span>
                                            <?php if (!is_author()) : ?>
                                                <span class="post-author">
                                                    <i class="fas fa-user"></i>
                                                    <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
                                                        <?php the_author(); ?>
                                                    </a>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!is_category()) : ?>
                                                <span class="post-category">
                                                    <i class="fas fa-folder"></i>
                                                    <?php 
                                                    $categories = get_the_category();
                                                    if (!empty($categories)) :
                                                        echo '<a href="' . get_category_link($categories[0]->term_id) . '">' . $categories[0]->name . '</a>';
                                                    endif;
                                                    ?>
                                                </span>
                                            <?php endif; ?>
                                            <span class="reading-time">
                                                <i class="fas fa-clock"></i>
                                                <?php 
                                                $content = get_the_content();
                                                $word_count = str_word_count(strip_tags($content));
                                                $reading_time = ceil($word_count / 200);
                                                
                                                if (function_exists('to_nepali_digits')) {
                                                    echo to_nepali_digits($reading_time) . ' मिनेट';
                                                } else {
                                                    echo $reading_time . ' मिनेट';
                                                }
                                                ?>
                                            </span>
                                        </div>
                                        
                                        <h2 class="post-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        
                                        <div class="post-excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt(), 30, '...'); ?>
                                        </div>
                                        
                                        <div class="post-footer">
                                            <a href="<?php the_permalink(); ?>" class="read-more">
                                                पूरा पढ्नुहोस् <i class="fas fa-arrow-right"></i>
                                            </a>
                                            <div class="post-stats">
                                                <span class="comments-count">
                                                    <i class="fas fa-comment"></i>
                                                    <a href="<?php comments_link(); ?>">
                                                        <?php 
                                                        $comment_count = get_comments_number();
                                                        if (function_exists('to_nepali_digits')) {
                                                            echo to_nepali_digits($comment_count);
                                                        } else {
                                                            echo $comment_count;
                                                        }
                                                        ?>
                                                    </a>
                                                </span>
                                                <span class="views-count">
                                                    <i class="fas fa-eye"></i>
                                                    <?php 
                                                    $views = get_post_meta(get_the_ID(), 'post_views_count', true);
                                                    if (!$views) $views = 0;
                                                    
                                                    // Format large numbers (K, M)
                                                    if ($views >= 1000000) {
                                                        $formatted_views = round($views / 1000000, 1) . 'M';
                                                    } elseif ($views >= 1000) {
                                                        $formatted_views = round($views / 1000, 1) . 'K';
                                                    } else {
                                                        $formatted_views = $views;
                                                    }
                                                    
                                                    if (function_exists('to_nepali_digits')) {
                                                        echo to_nepali_digits($formatted_views);
                                                    } else {
                                                        echo $formatted_views;
                                                    }
                                                    ?>
                                                </span>
                                                <span class="share-count">
                                                    <i class="fas fa-share"></i>
                                                    <span class="share-number">
                                                        <?php 
                                                        if (function_exists('to_nepali_digits')) {
                                                            echo to_nepali_digits('0');
                                                        } else {
                                                            echo '0';
                                                        }
                                                        ?>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Tags (for tag archives) -->
                                        <?php if (is_tag() && has_tag()) : ?>
                                            <div class="post-tags">
                                                <?php the_tags('<span class="tags-label"><i class="fas fa-tags"></i></span>', '', ''); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>

                        <!-- Load More Button -->
                        <div class="load-more-section">
                            <?php
                            global $wp_query;
                            if ($wp_query->max_num_pages > 1) :
                            ?>
                                <button id="load-more-posts" class="load-more-btn" data-page="1" data-max="<?php echo $wp_query->max_num_pages; ?>">
                                    <span class="btn-text">थप समाचार लोड गर्नुहोस्</span>
                                    <span class="btn-loader" style="display: none;">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </span>
                                </button>
                            <?php endif; ?>
                        </div>

                        <!-- Traditional Pagination (fallback) -->
                        <nav class="archive-pagination">
                            <?php
                            $pagination_args = array(
                                'prev_text' => '<i class="fas fa-chevron-left"></i> अघिल्लो',
                                'next_text' => 'अर्को <i class="fas fa-chevron-right"></i>',
                                'type' => 'array'
                            );
                            
                            $pages = paginate_links($pagination_args);
                            
                            if ($pages) :
                            ?>
                                <div class="pagination-wrapper">
                                    <ul class="pagination">
                                        <?php foreach ($pages as $page) : ?>
                                            <li class="page-item"><?php echo $page; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </nav>

                    <?php else : ?>
                        
                        <!-- No Posts Found -->
                        <div class="no-posts-found">
                            <div class="no-posts-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h2>कुनै समाचार फेला परेन</h2>
                            <p>
                                <?php
                                if (is_category()) :
                                    echo 'यस श्रेणीमा कुनै समाचार छैन।';
                                elseif (is_author()) :
                                    echo 'यस लेखकका कुनै प्रकाशित लेखहरू छैनन्।';
                                elseif (is_tag()) :
                                    echo 'यस ट्यागसँग सम्बन्धित कुनै समाचार छैन।';
                                else :
                                    echo 'यस अभिलेखमा कुनै समाचार छैन।';
                                endif;
                                ?>
                            </p>
                            <div class="no-posts-actions">
                                <a href="<?php echo home_url(); ?>" class="btn btn-primary">
                                    <i class="fas fa-home"></i>
                                    मुख्य पृष्ठमा फर्कनुहोस्
                                </a>
                                <a href="<?php echo get_search_link(); ?>" class="btn btn-secondary">
                                    <i class="fas fa-search"></i>
                                    खोज्नुहोस्
                                </a>
                            </div>
                        </div>

                    <?php endif; ?>

                </div>

                <!-- Sidebar -->
                <aside class="sidebar">
                    <!-- Archive-specific widgets -->
                    <?php if (is_author()) : ?>
                        <!-- Author's Popular Posts -->
                        <section class="widget author-popular-posts">
                            <h3 class="widget-title">
                                <i class="fas fa-fire"></i>
                                लेखकका लोकप्रिय लेखहरू
                            </h3>
                            <div class="widget-content">
                                <?php
                                $author_popular = get_posts(array(
                                    'author' => get_the_author_meta('ID'),
                                    'numberposts' => 5,
                                    'orderby' => 'comment_count',
                                    'order' => 'DESC'
                                ));
                                
                                if (!empty($author_popular)) :
                                    foreach ($author_popular as $post) : setup_postdata($post);
                                ?>
                                    <article class="author-popular-post">
                                        <h4 class="post-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h4>
                                        <div class="post-meta">
                                            <span class="post-date">
                                                <?php 
                                                if (function_exists('short_date')) {
                                                    echo short_date(get_the_time('U'));
                                                } else {
                                                    echo get_the_date('M j');
                                                }
                                                ?>
                                            </span>
                                            <span class="post-comments">
                                                <i class="fas fa-comment"></i>
                                                <?php 
                                                $comment_count = get_comments_number();
                                                if (function_exists('to_nepali_digits')) {
                                                    echo to_nepali_digits($comment_count);
                                                } else {
                                                    echo $comment_count;
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    </article>
                                <?php 
                                    endforeach; 
                                    wp_reset_postdata();
                                endif; 
                                ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php if (is_tag()) : ?>
                        <!-- Related Tags -->
                        <section class="widget related-tags">
                            <h3 class="widget-title">
                                <i class="fas fa-tags"></i>
                                सम्बन्धित ट्यागहरू
                            </h3>
                            <div class="widget-content">
                                <div class="tag-cloud">
                                    <?php
                                    $current_tag = get_queried_object();
                                    $related_tags = get_tags(array(
                                        'exclude' => $current_tag->term_id,
                                        'orderby' => 'count',
                                        'order' => 'DESC',
                                        'number' => 15
                                    ));
                                    
                                    foreach ($related_tags as $tag) :
                                        $tag_size = ($tag->count > 10) ? 'large' : (($tag->count > 5) ? 'medium' : 'small');
                                    ?>
                                        <a href="<?php echo get_tag_link($tag->term_id); ?>" 
                                           class="tag-item <?php echo $tag_size; ?>">
                                            <?php echo esc_html($tag->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php if (is_category()) : ?>
                        <!-- Category Info Widget -->
                        <section class="widget category-info">
                            <h3 class="widget-title">
                                <i class="fas fa-info-circle"></i>
                                श्रेणी जानकारी
                            </h3>
                            <div class="widget-content">
                                <div class="category-stats">
                                    <div class="stat-item">
                                        <span class="stat-label">कुल समाचार:</span>
                                        <span class="stat-value">
                                            <?php 
                                            $category = get_queried_object();
                                            if (function_exists('to_nepali_digits')) {
                                                echo to_nepali_digits($category->count);
                                            } else {
                                                echo $category->count;
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">श्रेणी नाम:</span>
                                        <span class="stat-value"><?php single_cat_title(); ?></span>
                                    </div>
                                    <?php if (category_description()) : ?>
                                        <div class="stat-item">
                                            <span class="stat-label">विवरण:</span>
                                            <div class="category-description">
                                                <?php echo category_description(); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php get_sidebar(); ?>
                </aside>
            </div>
        </div>

    </main>
</div>

<script>
// Archive page functionality
document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality
    const viewButtons = document.querySelectorAll('.view-btn');
    const postsContainer = document.getElementById('archive-posts');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Update active button
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Update container class
            postsContainer.className = 'archive-posts-container ' + view + '-view';
        });
    });
    
    // Load more functionality
    const loadMoreBtn = document.getElementById('load-more-posts');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const button = this;
            const currentPage = parseInt(button.dataset.page);
            const maxPages = parseInt(button.dataset.max);
            const nextPage = currentPage + 1;
            
            // Show loading state
            button.classList.add('loading');
            button.querySelector('.btn-text').style.display = 'none';
            button.querySelector('.btn-loader').style.display = 'inline-block';
            
            // AJAX request would go here
            // For now, just hide the button if we've reached max pages
            setTimeout(() => {
                if (nextPage >= maxPages) {
                    button.style.display = 'none';
                } else {
                    button.dataset.page = nextPage;
                    button.classList.remove('loading');
                    button.querySelector('.btn-text').style.display = 'inline-block';
                    button.querySelector('.btn-loader').style.display = 'none';
                }
            }, 1000);
        });
    }
});

// Sort functionality
function sortArchive(sortBy) {
    // This would typically involve AJAX to reload the posts
    // For now, just show a message
    console.log('Sorting by: ' + sortBy);
    
    // You can implement AJAX sorting here
    // The sorting would reload the posts based on the selected criteria
}
</script>

<?php 
// Capture the content and pass it to layout
$layout_content = ob_get_clean();

// Now include the layout with our content
include get_template_directory() . '/templates/layouts/default.php';