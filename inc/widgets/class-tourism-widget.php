<?php
/**
 * Tourism Widget
 * 
 * @package Samachar_Patra
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class SP_Tourism_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'sp_tourism_widget',
            __('SP: Tourism', 'samacharpatra'),
            array(
                'description' => __('Display tourism news section', 'samacharpatra'),
                'classname' => 'sp-tourism-widget'
            )
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'पर्यटन';
        $posts_count = !empty($instance['posts_count']) ? absint($instance['posts_count']) : 6;
        $category = !empty($instance['category']) ? sanitize_text_field($instance['category']) : 'tourism';

        // Set query vars for template
        set_query_var('tourism_title', $title);
        set_query_var('tourism_posts_count', $posts_count);
        set_query_var('tourism_category', $category);

        echo $args['before_widget'];
        
        // Load template
        get_template_part('templates/components/tourism');
        
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'पर्यटन';
        $posts_count = !empty($instance['posts_count']) ? absint($instance['posts_count']) : 6;
        $category = !empty($instance['category']) ? $instance['category'] : 'tourism';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                <?php _e('Title:', 'samacharpatra'); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_count'); ?>">
                <?php _e('Number of Posts (1-20):', 'samacharpatra'); ?>
            </label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('posts_count'); ?>" 
                   name="<?php echo $this->get_field_name('posts_count'); ?>" type="number" 
                   step="1" min="1" max="20" value="<?php echo esc_attr($posts_count); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('category'); ?>">
                <?php _e('Category Slug:', 'samacharpatra'); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id('category'); ?>" 
                   name="<?php echo $this->get_field_name('category'); ?>" type="text" 
                   value="<?php echo esc_attr($category); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['posts_count'] = !empty($new_instance['posts_count']) ? absint($new_instance['posts_count']) : 6;
        $instance['category'] = !empty($new_instance['category']) ? sanitize_text_field($new_instance['category']) : 'tourism';
        
        // Validate range
        $instance['posts_count'] = max(1, min(20, $instance['posts_count']));
        
        return $instance;
    }
}
