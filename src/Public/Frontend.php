<?php

namespace Denissv\AnimatedTextSlider\Public;

if (!defined('ABSPATH')) {
    exit;
}

class Frontend {
    private const MENU_CAROUSEL_SLUG = 'dsv_carousel';
    private const MENU_SLIDER_SLUG = self::MENU_CAROUSEL_SLUG . '_slides';

    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() {
        // Регистрация шорткода
        add_shortcode(DENISSV_ANIMATED_TEXT_SLIDER_SHORTCODE, array($this, 'carousel_shortcode'));
    }

    /**
     * Шорткод для вывода карусели
     *
     * @param array $atts Атрибуты шорткода
     * @return string HTML код карусели
     */
    public function carousel_shortcode($atts) {
        
        $atts = shortcode_atts(array(
            'id' => 0,
        ), $atts, DENISSV_ANIMATED_TEXT_SLIDER_SHORTCODE);
        
        
        $carousel_id = absint($atts['id']);

        if (!$carousel_id) {
            return '<p>' . __('Укажите ID карусели', 'denissv-animated-text-slider') . '</p>';
        }

        $carousel = get_post($carousel_id);

        if (!$carousel || $carousel->post_type !== self::MENU_CAROUSEL_SLUG) {
            return '<p>' . __('Карусель не найдена', 'denissv-animated-text-slider') . '</p>';
        }

        $query = new \WP_Query([
            'post_type'      => self::MENU_SLIDER_SLUG,
            'post_parent'    => $carousel_id,
            'posts_per_page' => -1,
            'orderby'     => 'menu_order',
            'order'   => 'ASC',
            'post_status'    => 'any',
            'no_found_rows'  => true,      // оптимизация
            'cache_results'          => true,
        ]);

        // Проверка на ошибку запроса
        if ( is_wp_error( $query ) ) {
            return;
        }

        // Проверка есть ли записи
        if ( ! $query->have_posts() ) {
            return;
        }

        $slides = $query->posts;
        
        // Получение настроек карусели
        $show_arrows = get_post_meta($carousel->ID, DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_show_arrows', true);
        $show_dots = get_post_meta($carousel->ID, DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_show_dots', true);
        $height = get_post_meta($carousel->ID, DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_height', true);
        $effect = get_post_meta($carousel->ID, DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_effect', true);

        // Значения по умолчанию
        $show_arrows = $show_arrows !== '' ? $show_arrows : '1';
        $show_dots = $show_dots !== '' ? $show_dots : '1';
        $height = $height ? $height : 'none';
        $effect = $effect ? $effect : 'slide';

        // Подключаем библиотеку только там, где реально выводится шорткод
        // Стили карусели плагина
        wp_enqueue_style(
            'carousel3-swiper-css',
            DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_URL . 'src/Public/assets/styles/swiper-bundle.min.css',
            array(),
            DENISSV_ANIMATED_TEXT_SLIDER_VERSION
        );
        wp_enqueue_style(
            'carousel3-swiper-custom-css',
            DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_URL . 'src/Public/assets/styles/swiper-custom.css',
            array('carousel3-swiper-css'),
            DENISSV_ANIMATED_TEXT_SLIDER_VERSION
        );
        wp_enqueue_style(
            'carousel3-animate-css',
            DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_URL . 'src/Public/assets/styles/animate.css',
            array('carousel3-swiper-custom-css'),
            DENISSV_ANIMATED_TEXT_SLIDER_VERSION
        );
        wp_enqueue_script(
            'swcarousel-swiper-js',
            DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_URL . 'src/Public/assets/js/swiper-bundle.min.js',
            array(),
            '9.4.1',
            true
        );
        wp_enqueue_script(
            'swcarousel-slider-config',
            DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_URL . 'src/Public/assets/js/swiper-config.js',
            array('swcarousel-swiper-js'),
            DENISSV_ANIMATED_TEXT_SLIDER_VERSION,
            true
        );

        /* Render carousel */

        $c3_conf = [
            'autoplay'         => '1',
            'autoplay_speed'   => '3000',
            'animation_speed'  => '600',
            'show_arrows'      => '1',
            'show_dots'        => '1',
            'show_scrollbar'   => '0',
            'spaces_between'   => '0',
            'slides_per_view'  => '1',
        ];

        $c3_slides_count = max(1, (int)$c3_conf['slides_per_view']);
        $c3_vw           = round(100 / $c3_slides_count, 4);
        $c3_sizes        = "(max-width: 768px) 100vw, {$c3_vw}vw";
        
        ob_start();
        include DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_DIR . 'src/Public/views/render_carousel.php';
        return ob_get_clean();
    }
}