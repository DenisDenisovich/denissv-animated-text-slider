<?php

namespace Denissv\AnimatedTextSlider\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class Admin {
    private static $instance = null;

    public static $carousel3_pages = [
        'edit-' . DENISSV_ANIMATED_TEXT_SLIDER_MENU_SLUG,
        DENISSV_ANIMATED_TEXT_SLIDER_MENU_SLUG,
        DENISSV_ANIMATED_TEXT_SLIDER_MENU_SLUG . '_slides',
    ];

    public static $allowed_hooks = [
        'post.php', 
        'edit.php'
    ];

    private function __construct() {
        $this->init_hooks();
    }

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function init_hooks() {
        Carousels::get_instance();
        Sliders::get_instance();

        // Подключение Js и CSS для админки только в плагине
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    public function enqueue_admin_assets($hook) {
        error_log('Admin enqueue scripts hook: ' . $hook); // Debug log
        $screen = get_current_screen();
        error_log('Current admin screen: ' . ($screen ? $screen->id : 'unknown')); // Debug log
        if (empty($hook) || !in_array($hook, self::$allowed_hooks) || empty($screen) || !in_array($screen->id, self::$carousel3_pages)) {
            return;
        }
        error_log(DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_URL . 'assets/admin/css/admin.css'); // Debug log

        wp_enqueue_style(DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_NAME . '-css-admin', DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_URL . 'assets/admin/css/admin.css', array(), DENISSV_ANIMATED_TEXT_SLIDER_VERSION);

        wp_enqueue_script(DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_NAME . '-js-admin', DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_URL . 'assets/admin/js/admin.js', array('jquery'), DENISSV_ANIMATED_TEXT_SLIDER_VERSION, true);
        wp_localize_script(DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_NAME . '-js-admin', 'carousel3TableSort', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('denissv_animated_text_slider_sort_slides_nonce'),
        ]);
    }
}