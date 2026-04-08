<?php
/**
 * Plugin Name: Denissv Animated Text Slider
 * Description: Плагин для создания карусели с анимированным текстом.
 * Version: 2.0.0
 * License: GPL2
 * Text Domain: denissv-animated-text-slider
 * Domain Path: /languages
 * Author: Denis Sviridovskiy
 */

// Запрет прямого доступа к файлу
if (!defined('ABSPATH')) {
    exit;
}

define('DENISSV_ANIMATED_TEXT_SLIDER_VERSION', '2.0.0');
define('DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_NAME', dirname(DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_BASENAME));
define('DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY', '_' . DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_NAME);

define('DENISSV_ANIMATED_TEXT_SLIDER_MENU_SLUG', 'dsvats_carousel');
define('DENISSV_ANIMATED_TEXT_SLIDER_SHORTCODE', 'dsvats_carousel');

/**
 * Composer autoload
 */
if (file_exists(DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_DIR . 'vendor/autoload.php';
}

/**
 * Bootstrap
 */
function dsvats_plugin_run(): void {
    $plugin = new \Denissv\AnimatedTextSlider\Core\Plugin();
    $plugin->run();
}

dsvats_plugin_run();