<?php
/**
 * Template Part: Post Meta 
 * Usage Examples:
 * get_template_part('templates/parts/post-meta', null, array('variant' => 'author_only'));
 * @package Samachar_Patra
 */

// Import smart date functionality
$smart_date_file = get_template_directory() . '/inc/hooks/smart-date.php';
if (file_exists($smart_date_file)) {
    require_once $smart_date_file;
}
// Get variant from args, default to 'author_short_date'
$variant = $args['variant'] ?? 'author_short_date';
$author_id = get_the_author_meta('ID');
$post_id = get_the_ID();

// Check if post_id exists
if (!$post_id) {
    return;
}

// Get the Unix timestamp from post
$post_timestamp = get_post_time('U', false, $post_id);
if (function_exists('samacharpatra_smart_short_date')) {
    $short_date = samacharpatra_smart_short_date($post_timestamp);
    $full_date = samacharpatra_smart_full_date($post_timestamp);
    $full_date_time = samacharpatra_smart_full_date_time($post_timestamp);
    $relative_time = samacharpatra_smart_relative_time($post_timestamp);
} else {
    // Fallback
    $short_date = get_the_date('M j, Y', $post_id);
    $full_date = get_the_date('F j, Y', $post_id);
    $full_date_time = get_the_date('F j, Y g:i A', $post_id);
    $relative_time = human_time_diff(get_post_time('U', false, $post_id), current_time('timestamp')) . ' ago';
}

// Get comments count
$comments_count = get_comments_number($post_id);
?>

<div class="post-meta post-meta--<?php echo esc_attr($variant); ?>">
    
    <?php
    if ($variant === 'author_only') : ?>
        <div class="meta-item meta-author">
            <div class="author-avatar">
                <?php echo get_avatar($author_id, 32, '', get_the_author()); ?>
            </div>
            <span class="author-name">
                <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>">
                    <?php echo esc_html(get_the_author()); ?>
                </a>
            </span>
        </div>
    
    <?php
    elseif ($variant === 'author_short_date') : ?>
        <div class="meta-item meta-author">
            <div class="author-avatar">
                <?php echo get_avatar($author_id, 32, '', get_the_author()); ?>
            </div>
            <span class="author-name">
                <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>">
                    <?php echo esc_html(get_the_author()); ?>
                </a>
            </span>
        </div>
        <div class="meta-item meta-date">
            <i class="far fa-calendar-alt"></i>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html($short_date); ?>
            </time>
        </div>
    
    <?php
    elseif ($variant === 'author_full_date') : ?>
        <div class="meta-item meta-author">
            <div class="author-avatar">
                <?php echo get_avatar($author_id, 32, '', get_the_author()); ?>
            </div>
            <span class="author-name">
                <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>">
                    <?php echo esc_html(get_the_author()); ?>
                </a>
            </span>
        </div>
        <div class="meta-item meta-date">
            <i class="far fa-calendar-alt"></i>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html($full_date); ?>
            </time>
        </div>
    
    <?php
    elseif ($variant === 'author_full_date_time') : ?>
        <div class="meta-item meta-author">
            <div class="author-avatar">
                <?php echo get_avatar($author_id, 32, '', get_the_author()); ?>
            </div>
            <span class="author-name">
                <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>">
                    <?php echo esc_html(get_the_author()); ?>
                </a>
            </span>
        </div>
        <div class="meta-item meta-datetime">
            <i class="far fa-clock"></i>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html($full_date_time); ?>
            </time>
        </div>
    
    <?php
    elseif ($variant === 'author_relative_time') : ?>
        <div class="meta-item meta-author">
            <div class="author-avatar">
                <?php echo get_avatar($author_id, 32, '', get_the_author()); ?>
            </div>
            <span class="author-name">
                <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>">
                    <?php echo esc_html(get_the_author()); ?>
                </a>
            </span>
        </div>
        <div class="meta-item meta-relative">
            <i class="far fa-clock"></i>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html($relative_time); ?>
            </time>
        </div>
    
    <?php
    elseif ($variant === 'relative_time') : ?>
        <div class="meta-item meta-relative">
            <i class="far fa-clock"></i>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html($relative_time); ?>
            </time>
        </div>
    
    <?php
    elseif ($variant === 'short_date') : ?>
        <div class="meta-item meta-date">
            <i class="far fa-calendar-alt"></i>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html($short_date); ?>
            </time>
        </div>
    
    <?php
    elseif ($variant === 'full_date') : ?>
        <div class="meta-item meta-date">
            <i class="far fa-calendar-alt"></i>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html($full_date); ?>
            </time>
        </div>
    
    <?php
    elseif ($variant === 'full_date_time') : ?>
        <div class="meta-item meta-datetime">
            <i class="far fa-clock"></i>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html($full_date_time); ?>
            </time>
        </div>
    
    <?php
    elseif ($variant === 'author_relative_time_comments') : ?>
        <div class="meta-item meta-author">
            <div class="author-avatar">
                <?php echo get_avatar($author_id, 32, '', get_the_author()); ?>
            </div>
            <span class="author-name">
                <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>">
                    <?php echo esc_html(get_the_author()); ?>
                </a>
            </span>
        </div>
        <div class="meta-item meta-relative">
            <i class="far fa-clock"></i>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html($relative_time); ?>
            </time>
        </div>
        <div class="meta-item meta-comments">
            <i class="far fa-comments"></i>
            <a href="<?php echo esc_url(get_comments_link($post_id)); ?>" class="comments-link">
                <span class="comments-count"><?php echo esc_html($comments_count); ?></span>
            </a>
        </div>
    
    <?php
    elseif ($variant === 'author_full_date_comments') : ?>
        <div class="meta-item meta-author">
            <div class="author-avatar">
                <?php echo get_avatar($author_id, 32, '', get_the_author()); ?>
            </div>
            <span class="author-name">
                <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>">
                    <?php echo esc_html(get_the_author()); ?>
                </a>
            </span>
        </div>
        <div class="meta-item meta-date">
            <i class="far fa-calendar-alt"></i>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html($full_date); ?>
            </time>
        </div>
        <div class="meta-item meta-comments">
            <i class="far fa-comments"></i>
            <a href="<?php echo esc_url(get_comments_link($post_id)); ?>" class="comments-link">
                <span class="comments-count"><?php echo esc_html($comments_count); ?></span>
            </a>
        </div>
    
    <?php endif; ?>
    
</div>

