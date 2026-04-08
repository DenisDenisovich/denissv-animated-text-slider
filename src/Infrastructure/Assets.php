<?php
namespace Denissv\AnimatedTextSlider\Infrastructure;

if (!defined('ABSPATH')) {
    exit;
}

use Denissv\AnimatedTextSlider\Core\Hookable;

class Assets implements Hookable {
    public function register(): void {
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminAssets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontendAssets']);
    }

    public function enqueueAdminAssets() {
        wp_enqueue_style('animated-text-slider-admin', plugin_dir_url(__FILE__) . 'assets/css/admin.css');
        wp_enqueue_script('animated-text-slider-admin', plugin_dir_url(__FILE__) . 'assets/js/admin.js', ['jquery'], null, true);
    }

    public function enqueueFrontendAssets() {
        wp_enqueue_style('animated-text-slider-frontend', plugin_dir_url(__FILE__) . 'assets/css/frontend.css');
        wp_enqueue_script('animated-text-slider-frontend', plugin_dir_url(__FILE__) . 'assets/js/frontend.js', ['jquery'], null, true);
    }
}