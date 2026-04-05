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
        <?php foreach ($animations as $value => $label) : ?>
            <option value="<?php echo esc_attr($value); ?>" <?php selected($animation_type, $value); ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>