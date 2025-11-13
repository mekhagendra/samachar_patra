<?php
/**
 * Interview Widget
 * 
 * @package Samachar_Patra
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class SP_Interview_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'sp_interview_widget',
            __('SP: Interview', 'samacharpatra'),
            array(
                'description' => __('Display interview section', 'samacharpatra'),
                'classname' => 'sp-interview-widget'
            )
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'अन्तर्वार्ता';
        $posts_count = !empty($instance['posts_count']) ? absint($instance['posts_count']) : 8;
        $category = !empty($instance['category']) ? sanitize_text_field($instance['category']) : 'interview';
        $grid_columns = !empty($instance['grid_columns']) ? absint($instance['grid_columns']) : 4;

        // Set query vars for template
        set_query_var('interview_title', $title);
        set_query_var('interview_posts_count', $posts_count);
        set_query_var('interview_category', $category);
        set_query_var('interview_grid_columns', $grid_columns);

        echo $args['before_widget'];
        
        // Load template
        get_template_part('templates/components/interview');
        
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'अन्तर्वार्ता';
        $posts_count = !empty($instance['posts_count']) ? absint($instance['posts_count']) : 8;
        $category = !empty($instance['category']) ? $instance['category'] : 'interview';
        $grid_columns = !empty($instance['grid_columns']) ? absint($instance['grid_columns']) : 4;
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
        <p>
            <label for="<?php echo $this->get_field_id('grid_columns'); ?>">
                <?php _e('Grid Columns:', 'samacharpatra'); ?>
            </label>
            <select class="widefat" id="<?php echo $this->get_field_id('grid_columns'); ?>" 
                    name="<?php echo $this->get_field_name('grid_columns'); ?>">
                <option value="2" <?php selected($grid_columns, 2); ?>>2 Columns</option>
                <option value="3" <?php selected($grid_columns, 3); ?>>3 Columns</option>
                <option value="4" <?php selected($grid_columns, 4); ?>>4 Columns</option>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['posts_count'] = !empty($new_instance['posts_count']) ? absint($new_instance['posts_count']) : 8;
        $instance['category'] = !empty($new_instance['category']) ? sanitize_text_field($new_instance['category']) : 'interview';
        $instance['grid_columns'] = !empty($new_instance['grid_columns']) ? absint($new_instance['grid_columns']) : 4;
        
        // Validate ranges
        $instance['posts_count'] = max(1, min(20, $instance['posts_count']));
        $instance['grid_columns'] = in_array($instance['grid_columns'], array(2, 3, 4)) ? $instance['grid_columns'] : 4;
        
        return $instance;
    }
}
