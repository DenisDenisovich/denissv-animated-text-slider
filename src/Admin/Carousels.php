<?php

namespace Denissv\AnimatedTextSlider\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class Carousels {
    private const MENU_CAROUSEL_SLUG = 'dsv_carousel';
    private const POST_TYPE_SLIDE = self::MENU_CAROUSEL_SLUG . '_slides';
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
        add_action('init', array($this, 'create_menu_carousel'));
        $this->create_menu_carousel();

        add_action('wp_ajax_denissv_animated_text_slider_update_order', array($this, 'update_slide_order'));

        // Добавление мета-боксов
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));

        // Сохранение данных
        add_action('save_post', array($this, 'save_carousel_data'), 10, 2);
    }
    
    public function create_menu_carousel() {
        $this->menu_carousel();
    }

        // Таблица слайдов внутри карусели
    public function render_carousel_slides($post) {
        
        $slides = get_children([
            'post_type'   => self::POST_TYPE_SLIDE,
            'post_parent' => $post->ID,
            'orderby'     => 'menu_order',
            'order'       => 'ASC'
        ]);

        echo '<table class="widefat striped">';
        echo '<thead><tr><th>Заголовок</th><th>Порядок</th><th>Действия</th><th></th></tr></thead>';
        echo '<tbody id="carousel3-sortable">';

        if ($slides) {
            foreach ($slides as $slide) {
                echo '<tr data-id="' . intval($slide->ID) . '">';
                echo '<td>' . esc_html($slide->post_title) . '</td>';
                echo '<td>' . intval($slide->menu_order) . '</td>';
                echo '<td>';
                echo '<a href="' . esc_url( get_edit_post_link( $slide->ID ) ) . '">' . esc_html__( 'Редактировать', 'denissv-animated-text-slider' ) . '</a>';
                echo '</td>';
                echo '<td class="drag-handle" style="cursor:move;">☰</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="3">Слайдов нет</td></tr>';
        }

        echo '</tbody>';
        echo '</table>';

        // Кнопка добавить
        $add_link = admin_url('post-new.php?post_type=' . self::POST_TYPE_SLIDE . '&parent=' . $post->ID);
        echo '<p><a class="button button-primary" href="' . esc_url($add_link) . '">Добавить слайд</a></p>';
    }

    // Меняем позиции слайдов
    public function update_slide_order() {
        check_ajax_referer('denissv_animated_text_slider_sort_slides_nonce', 'nonce');
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Недостаточно прав');
            return;
        }

        if (isset($_POST['order']) && is_array($_POST['order'])) {
            $raw_order = isset($_POST['order']) && is_array($_POST['order'])
                ? wp_unslash($_POST['order']) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                : [];

            foreach ($raw_order as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $id = isset($item['id']) ? absint($item['id']) : 0;
                $menu_order = isset($item['menu_order']) ? absint($item['menu_order']) : 0;

                if ($id <= 0) {
                    continue;
                }

                wp_update_post([
                    'ID' => $id,
                    'menu_order' => $menu_order,
                ]);
            }
        }
        wp_send_json_success('Порядок обновлен');
    }

    public function menu_carousel() {
        register_post_type(self::MENU_CAROUSEL_SLUG, [
            'labels' => [
                'name' => 'DSV Карусели',
                'singular_name' => 'DSV Карусель',
                'add_new' => 'Добавить новую',
                'add_new_item' => 'Создание новой карусели',
                'edit_item' => 'Редактирование карусели',
            ],
            'public' => false,
            'show_ui' => true,
            'menu_icon' => 'dashicons-images-alt2',
            'supports' => ['title'],
            'capability_type' => 'post',
        ]);
    }

    /**
     * Добавление мета-боксов
     */
    public function add_meta_boxes() { 
        add_meta_box(
            'carousel_slides_meta_box',
            'Слайды карусели',
            array($this, 'render_carousel_slides'),
            self::MENU_CAROUSEL_SLUG,
            'normal',
            'high'
        );

        add_meta_box(
            DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_settings',
            __('Настройки карусели', 'denissv-animated-text-slider'),
            array($this, 'render_settings_metabox'),
            self::MENU_CAROUSEL_SLUG,
            'side',
            'default'
        );
    }

    public function render_settings_metabox($post) {
        wp_nonce_field('denissv_animated_text_slider_save_data', 'denissv_animated_text_slider_nonce');
        $shortcode = '';
        if (isset($post) && isset($post->ID) && $post->ID) {
            $shortcode = sprintf('[%s id="%d"]', DENISSV_ANIMATED_TEXT_SLIDER_SHORTCODE, (int) $post->ID);
        }

        $carousel_id = $post->ID;
        $show_arrows = get_post_meta($post->ID, DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_show_arrows', true);
        $show_dots = get_post_meta($post->ID, DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_show_dots', true);
        $height = get_post_meta($post->ID, DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_height', true);
        $effect = get_post_meta($post->ID, DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_effect', true);

        // Значения по умолчанию
        $show_arrows = $show_arrows !== '' ? $show_arrows : '1';
        $show_dots = $show_dots !== '' ? $show_dots : '1';
        $height = $height ? $height : 'none';
        $effect = $effect ? $effect : 'slide';

        include DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_DIR . 'src/Admin/Views/metaboxes/carousel-metabox-settings.php';
    }

    public function save_carousel_data($post_id, $post) {
        if ($post->post_type !== self::MENU_CAROUSEL_SLUG) { // Используйте ваш слаг
            return;
        }
        // Проверка nonce
        if (
            !isset($_POST['denissv_animated_text_slider_nonce']) ||
            !wp_verify_nonce(
                sanitize_text_field(
                    wp_unslash($_POST['denissv_animated_text_slider_nonce'])
                ),
                'denissv_animated_text_slider_save_data'
            )
        ) {
            return;
        }

        // Проверка автосохранения
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Проверка прав
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST[DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_show_arrows'])) {
            update_post_meta($post_id, DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_show_arrows', '1');
        } else {
            update_post_meta($post_id, DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_show_arrows', '0');
        }

        if (isset($_POST[DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_show_dots'])) {
            update_post_meta($post_id, DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_show_dots', '1');
        } else {
            update_post_meta($post_id, DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_show_dots', '0');
        }

        if (isset($_POST[DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_height'])) {
            $height = sanitize_text_field(
                wp_unslash($_POST[DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_height'])
            );
            update_post_meta($post_id, DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_height', $height);
        }

        if (isset($_POST[DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_effect'])) {
            $effect = sanitize_text_field(
                wp_unslash($_POST[DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_effect'])
            );
            update_post_meta($post_id, DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_effect', $effect);
        }
    }
}