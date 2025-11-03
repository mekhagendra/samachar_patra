<?php
/**
 * Simple Index Template
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <?php if (have_posts()) : ?>
            <header class="page-header">
                <h1 class="page-title">समाचारपत्र</h1>
            </header>
            
            <div class="posts-container">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="entry-meta">
                                <span class="posted-on">
                                    <?php echo get_the_date(); ?>
                                </span>
                            </div>
                        </header>
                        
                        <div class="entry-content">
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <div class="pagination">
                <?php the_posts_pagination(); ?>
            </div>
            
        <?php else : ?>
            <div class="no-content">
                <h1>No posts found</h1>
                <p>कुनै पोस्ट फेला परेन।</p>
            </div>
        <?php endif; ?>
        
    </main>
</div>

<?php get_footer(); ?>