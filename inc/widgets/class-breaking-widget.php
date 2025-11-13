<?php
/**
 * Breaking News Widget
 * 
 * @package Samachar_Patra
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class SP_Breaking_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'sp_breaking_widget',
            __('SP: Breaking News', 'samacharpatra'),
            array(
                'description' => __('Display breaking news ticker with configurable settings', 'samacharpatra'),
                'classname' => 'sp-breaking-widget'
            )
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'ब्रेकिङ न्युज';
        $posts_count = !empty($instance['posts_count']) ? absint($instance['posts_count']) : 5;
        $time_filter = !empty($instance['time_filter']) ? absint($instance['time_filter']) : 2;

        // Set query vars for template
        set_query_var('breaking_title', $title);
        set_query_var('breaking_posts_count', $posts_count);
        set_query_var('breaking_time_filter', $time_filter);

        echo $args['before_widget'];
        
        // Load template
        get_template_part('templates/components/breaking');
        
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'ब्रेकिङ न्युज';
        $posts_count = !empty($instance['posts_count']) ? absint($instance['posts_count']) : 5;
        $time_filter = !empty($instance['time_filter']) ? absint($instance['time_filter']) : 2;
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
                <?php _e('Number of Posts (1-10):', 'samacharpatra'); ?>
            </label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('posts_count'); ?>" 
                   name="<?php echo $this->get_field_name('posts_count'); ?>" type="number" 
                   step="1" min="1" max="10" value="<?php echo esc_attr($posts_count); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('time_filter'); ?>">
                <?php _e('Time Filter (hours, 1-24):', 'samacharpatra'); ?>
            </label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('time_filter'); ?>" 
                   name="<?php echo $this->get_field_name('time_filter'); ?>" type="number" 
                   step="1" min="1" max="24" value="<?php echo esc_attr($time_filter); ?>" size="3">
            <br><small><?php _e('Fallback: Show posts from last X hours if no breaking news', 'samacharpatra'); ?></small>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['posts_count'] = !empty($new_instance['posts_count']) ? absint($new_instance['posts_count']) : 5;
        $instance['time_filter'] = !empty($new_instance['time_filter']) ? absint($new_instance['time_filter']) : 2;
        
        // Validate ranges
        $instance['posts_count'] = max(1, min(10, $instance['posts_count']));
        $instance['time_filter'] = max(1, min(24, $instance['time_filter']));
        
        return $instance;
    }
}
