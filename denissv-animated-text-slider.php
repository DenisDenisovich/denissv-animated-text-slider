<?php
/**
 * Plugin Name: Denissv Animated Text Slider
 * Description: Плагин для создания карусели с анимированным текстом.
 * Version: 1.0.0
 * License: GPL2
 * Text Domain: denissv-animated-text-slider
 * Domain Path: /languages
 * Author: Denis Sviridovskiy
 */

// Запрет прямого доступа к файлу
if (!defined('ABSPATH')) {
    exit;
}

// Определение констант плагина
define('DENISSV_ANIMATED_TEXT_SLIDER_VERSION', '1.0.0');
define('DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_NAME', dirname(DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_BASENAME));
define('DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY', '_' . DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_NAME);

define('DENISSV_ANIMATED_TEXT_SLIDER_MENU_SLUG', 'dsv_carousel');
define('DENISSV_ANIMATED_TEXT_SLIDER_SHORTCODE', 'dsv_carousel');

// ПОДКЛЮЧАЕМ АВТОЗАГРУЗКУ
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Инициализация плагина
function denissv_animated_text_slider_init() {
    Denissv\AnimatedTextSlider\Init::get_instance();
}
add_action('plugins_loaded', 'denissv_animated_text_slider_init');

