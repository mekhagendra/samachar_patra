<?php
/**
 * Quicklist Sidebar Widget
 * 
 * @package Samachar_Patra
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class SP_Quicklist_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'sp_quicklist_widget',
            __('SP: Quicklist Sidebar', 'samacharpatra'),
            array(
                'description' => __('Display quicklist with tabs (Latest, Popular, Recommended)', 'samacharpatra'),
                'classname' => 'sp-quicklist-widget'
            )
        );
    }

    public function widget($args, $instance) {
        $posts_per_tab = !empty($instance['posts_per_tab']) ? absint($instance['posts_per_tab']) : 7;
        $show_latest = isset($instance['show_latest']) ? (bool) $instance['show_latest'] : true;
        $show_popular = isset($instance['show_popular']) ? (bool) $instance['show_popular'] : true;
        $show_recommended = isset($instance['show_recommended']) ? (bool) $instance['show_recommended'] : true;
        $popular_days = !empty($instance['popular_days']) ? absint($instance['popular_days']) : 7;

        // Set query vars for template
        set_query_var('quicklist_posts_per_tab', $posts_per_tab);
        set_query_var('quicklist_show_latest', $show_latest);
        set_query_var('quicklist_show_popular', $show_popular);
        set_query_var('quicklist_show_recommended', $show_recommended);
        set_query_var('quicklist_popular_days', $popular_days);

        echo $args['before_widget'];
        
        // Load template
        get_template_part('templates/components/quicklist');
        
        echo $args['after_widget'];
    }

    public function form($instance) {
        $posts_per_tab = !empty($instance['posts_per_tab']) ? absint($instance['posts_per_tab']) : 7;
        $show_latest = isset($instance['show_latest']) ? (bool) $instance['show_latest'] : true;
        $show_popular = isset($instance['show_popular']) ? (bool) $instance['show_popular'] : true;
        $show_recommended = isset($instance['show_recommended']) ? (bool) $instance['show_recommended'] : true;
        $popular_days = !empty($instance['popular_days']) ? absint($instance['popular_days']) : 7;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_tab'); ?>">
                <?php _e('Posts Per Tab (1-15):', 'samacharpatra'); ?>
            </label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('posts_per_tab'); ?>" 
                   name="<?php echo $this->get_field_name('posts_per_tab'); ?>" type="number" 
                   step="1" min="1" max="15" value="<?php echo esc_attr($posts_per_tab); ?>" size="3">
        </p>
        <p>
            <input class="checkbox" type="checkbox" 
                   <?php checked($show_latest); ?>
                   id="<?php echo $this->get_field_id('show_latest'); ?>" 
                   name="<?php echo $this->get_field_name('show_latest'); ?>" />
            <label for="<?php echo $this->get_field_id('show_latest'); ?>">
                <?php _e('Show Latest Tab', 'samacharpatra'); ?>
            </label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" 
                   <?php checked($show_popular); ?>
                   id="<?php echo $this->get_field_id('show_popular'); ?>" 
                   name="<?php echo $this->get_field_name('show_popular'); ?>" />
            <label for="<?php echo $this->get_field_id('show_popular'); ?>">
                <?php _e('Show Popular Tab', 'samacharpatra'); ?>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('popular_days'); ?>">
                <?php _e('Popular Posts Days (1-30):', 'samacharpatra'); ?>
            </label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('popular_days'); ?>" 
                   name="<?php echo $this->get_field_name('popular_days'); ?>" type="number" 
                   step="1" min="1" max="30" value="<?php echo esc_attr($popular_days); ?>" size="3">
        </p>
        <p>
            <input class="checkbox" type="checkbox" 
                   <?php checked($show_recommended); ?>
                   id="<?php echo $this->get_field_id('show_recommended'); ?>" 
                   name="<?php echo $this->get_field_name('show_recommended'); ?>" />
            <label for="<?php echo $this->get_field_id('show_recommended'); ?>">
                <?php _e('Show Recommended Tab', 'samacharpatra'); ?>
            </label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['posts_per_tab'] = !empty($new_instance['posts_per_tab']) ? absint($new_instance['posts_per_tab']) : 7;
        $instance['show_latest'] = !empty($new_instance['show_latest']) ? 1 : 0;
        $instance['show_popular'] = !empty($new_instance['show_popular']) ? 1 : 0;
        $instance['show_recommended'] = !empty($new_instance['show_recommended']) ? 1 : 0;
        $instance['popular_days'] = !empty($new_instance['popular_days']) ? absint($new_instance['popular_days']) : 7;
        
        // Validate ranges
        $instance['posts_per_tab'] = max(1, min(15, $instance['posts_per_tab']));
        $instance['popular_days'] = max(1, min(30, $instance['popular_days']));
        
        return $instance;
    }
}
