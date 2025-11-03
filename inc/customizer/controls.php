<?php
/**
 * Custom Customizer Controls
 * 
 * Custom controls for the WordPress Customizer.
 *
 * @package Samachar_Patra
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Custom Range Control
 */
if (class_exists('WP_Customize_Control')) {
    class Samacharpatra_Range_Control extends WP_Customize_Control {
        public $type = 'range';

        public function enqueue() {
            wp_enqueue_script(
                'samacharpatra-range-control',
                get_template_directory_uri() . '/assets/js/range-control.js',
                array('jquery'),
                '1.0.0',
                true
            );
        }

        public function render_content() {
            ?>
            <label>
                <?php if (!empty($this->label)): ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif; ?>
                <?php if (!empty($this->description)): ?>
                    <span class="description customize-control-description"><?php echo $this->description; ?></span>
                <?php endif; ?>
                <input 
                    type="range" 
                    <?php $this->input_attrs(); ?> 
                    value="<?php echo esc_attr($this->value()); ?>" 
                    <?php $this->link(); ?> 
                    data-reset_value="<?php echo esc_attr($this->setting->default); ?>" 
                />
                <span class="range-value"><?php echo esc_attr($this->value()); ?></span>
            </label>
            <?php
        }
    }
}

/**
 * Custom Toggle Control
 */
if (class_exists('WP_Customize_Control')) {
    class Samacharpatra_Toggle_Control extends WP_Customize_Control {
        public $type = 'toggle';

        public function enqueue() {
            wp_enqueue_script(
                'samacharpatra-toggle-control',
                get_template_directory_uri() . '/assets/js/toggle-control.js',
                array('jquery'),
                '1.0.0',
                true
            );
            wp_enqueue_style(
                'samacharpatra-toggle-control',
                get_template_directory_uri() . '/assets/css/customizer/toggle-control.css',
                array(),
                '1.0.0'
            );
        }

        public function render_content() {
            ?>
            <label class="samacharpatra-toggle-control">
                <?php if (!empty($this->label)): ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif; ?>
                <?php if (!empty($this->description)): ?>
                    <span class="description customize-control-description"><?php echo $this->description; ?></span>
                <?php endif; ?>
                <div class="toggle-wrapper">
                    <input 
                        type="checkbox" 
                        id="<?php echo esc_attr($this->id); ?>" 
                        <?php $this->input_attrs(); ?> 
                        value="<?php echo esc_attr($this->value()); ?>" 
                        <?php $this->link(); ?> 
                        <?php checked($this->value()); ?> 
                    />
                    <label for="<?php echo esc_attr($this->id); ?>" class="toggle-label">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </label>
            <?php
        }
    }
}

/**
 * Custom Multi-Select Control
 */
if (class_exists('WP_Customize_Control')) {
    class Samacharpatra_Multi_Select_Control extends WP_Customize_Control {
        public $type = 'multi-select';

        public function enqueue() {
            wp_enqueue_script(
                'samacharpatra-multi-select-control',
                get_template_directory_uri() . '/assets/js/multi-select-control.js',
                array('jquery'),
                '1.0.0',
                true
            );
            wp_enqueue_style(
                'samacharpatra-multi-select-control',
                get_template_directory_uri() . '/assets/css/customizer/multi-select-control.css',
                array(),
                '1.0.0'
            );
        }

        public function render_content() {
            if (empty($this->choices)) {
                return;
            }
            ?>
            <label>
                <?php if (!empty($this->label)): ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif; ?>
                <?php if (!empty($this->description)): ?>
                    <span class="description customize-control-description"><?php echo $this->description; ?></span>
                <?php endif; ?>
                <select multiple="multiple" <?php $this->link(); ?>>
                    <?php foreach ($this->choices as $value => $label): ?>
                        <option value="<?php echo esc_attr($value); ?>" <?php selected(in_array($value, (array) $this->value())); ?>>
                            <?php echo esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <?php
        }
    }
}

/**
 * Custom Image Radio Control
 */
if (class_exists('WP_Customize_Control')) {
    class Samacharpatra_Image_Radio_Control extends WP_Customize_Control {
        public $type = 'image-radio';

        public function enqueue() {
            wp_enqueue_style(
                'samacharpatra-image-radio-control',
                get_template_directory_uri() . '/assets/css/customizer/image-radio-control.css',
                array(),
                '1.0.0'
            );
        }

        public function render_content() {
            if (empty($this->choices)) {
                return;
            }
            
            $name = '_customize-radio-' . $this->id;
            ?>
            <label>
                <?php if (!empty($this->label)): ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif; ?>
                <?php if (!empty($this->description)): ?>
                    <span class="description customize-control-description"><?php echo $this->description; ?></span>
                <?php endif; ?>
            </label>
            
            <div class="image-radio-control">
                <?php foreach ($this->choices as $value => $choice): ?>
                    <label class="radio-label">
                        <input 
                            type="radio" 
                            value="<?php echo esc_attr($value); ?>" 
                            name="<?php echo esc_attr($name); ?>" 
                            <?php $this->link(); ?> 
                            <?php checked($this->value(), $value); ?> 
                        />
                        <img src="<?php echo esc_url($choice['image']); ?>" alt="<?php echo esc_attr($choice['name']); ?>" title="<?php echo esc_attr($choice['name']); ?>" />
                        <span class="image-radio-label"><?php echo esc_html($choice['name']); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
            <?php
        }
    }
}

/**
 * Custom Typography Control
 */
if (class_exists('WP_Customize_Control')) {
    class Samacharpatra_Typography_Control extends WP_Customize_Control {
        public $type = 'typography';

        public function enqueue() {
            wp_enqueue_script(
                'samacharpatra-typography-control',
                get_template_directory_uri() . '/assets/js/typography-control.js',
                array('jquery'),
                '1.0.0',
                true
            );
            wp_enqueue_style(
                'samacharpatra-typography-control',
                get_template_directory_uri() . '/assets/css/customizer/typography-control.css',
                array(),
                '1.0.0'
            );
        }

        public function render_content() {
            $fonts = array(
                'Roboto' => 'Roboto',
                'Open Sans' => 'Open Sans',
                'Lato' => 'Lato',
                'Mukti' => 'Mukti',
                'Noto Sans' => 'Noto Sans',
                'Playfair Display' => 'Playfair Display',
                'Montserrat' => 'Montserrat',
            );

            $font_weights = array(
                '300' => 'Light',
                '400' => 'Normal',
                '500' => 'Medium',
                '600' => 'Semi Bold',
                '700' => 'Bold',
            );
            
            $value = $this->value();
            if (is_string($value)) {
                $value = json_decode($value, true);
            }
            ?>
            <label>
                <?php if (!empty($this->label)): ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif; ?>
                <?php if (!empty($this->description)): ?>
                    <span class="description customize-control-description"><?php echo $this->description; ?></span>
                <?php endif; ?>
            </label>

            <div class="typography-control">
                <div class="typography-field">
                    <label>Font Family</label>
                    <select class="typography-font-family">
                        <?php foreach ($fonts as $font_value => $font_label): ?>
                            <option value="<?php echo esc_attr($font_value); ?>" <?php selected(isset($value['font-family']) ? $value['font-family'] : '', $font_value); ?>>
                                <?php echo esc_html($font_label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="typography-field">
                    <label>Font Weight</label>
                    <select class="typography-font-weight">
                        <?php foreach ($font_weights as $weight_value => $weight_label): ?>
                            <option value="<?php echo esc_attr($weight_value); ?>" <?php selected(isset($value['font-weight']) ? $value['font-weight'] : '', $weight_value); ?>>
                                <?php echo esc_html($weight_label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="typography-field">
                    <label>Font Size (px)</label>
                    <input type="number" class="typography-font-size" min="8" max="72" value="<?php echo esc_attr(isset($value['font-size']) ? $value['font-size'] : '14'); ?>" />
                </div>

                <div class="typography-field">
                    <label>Line Height</label>
                    <input type="number" class="typography-line-height" min="1" max="3" step="0.1" value="<?php echo esc_attr(isset($value['line-height']) ? $value['line-height'] : '1.4'); ?>" />
                </div>

                <input type="hidden" <?php $this->link(); ?> class="typography-hidden-value" value="<?php echo esc_attr(json_encode($value)); ?>" />
            </div>
            <?php
        }
    }
}