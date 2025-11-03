<?php
/**
 * Default Layout Wrapper
 * 
 * This layout handles header/footer and provides a content area.
 * Templates should include content in $layout_content variable or use a callback.
 * 
 * @package Samachar_Patra
 * @since 1.0.0
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <?php
            // Check if content is provided via variable or callback
            if (isset($layout_content)) {
                echo $layout_content;
            } elseif (isset($layout_callback) && is_callable($layout_callback)) {
                call_user_func($layout_callback);
            } else {
                // Default WordPress loop for simple cases
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        the_content();
                    endwhile;
                endif;
            }
            ?>
        </main>
    </div>

<?php get_footer(); ?>
