<?php

if (!defined('ABSPATH')) {
    exit;
}

?>
<p>
    <label for="<?php echo esc_attr(DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY); ?>_animation_type">
        <?php esc_html_e('Анимация', 'denissv-animated-text-slider'); ?>
    </label>
    <select id="<?php echo esc_attr(DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY); ?>_animation_type" name="<?php echo esc_attr(DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY); ?>_animation_type">
        <?php foreach ($animations as $denissv_value => $denissv_label) : ?>
            <option value="<?php echo esc_attr($denissv_value); ?>" <?php selected($animation_type, $denissv_value); ?>>
                <?php echo esc_html($denissv_label); ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>