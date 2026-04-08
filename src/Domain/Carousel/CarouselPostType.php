<?php
namespace Denissv\AnimatedTextSlider\Domain\Carousel;

if (!defined('ABSPATH')) {
    exit;
}

use Denissv\AnimatedTextSlider\Core\Hookable;
use Denissv\AnimatedTextSlider\Core\Loader;

class CarouselPostType implements Hookable {
    public const POST_TYPE = 'dsv_carousel';

    public function register(Loader $loader): void {
        $loader->addAction('init', $this, 'registerPostType');
    }

    public function registerPostType(): void {
        register_post_type(self::POST_TYPE, $this->getArgs());
    }

    private function getArgs(): array {
        return [
            'labels' => $this->getLabels(),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-images-alt2',
            'supports' => ['title'],
            'capability_type' => 'post',
        ];
    }

    private function getLabels(): array {
        return [
            'name'               => __('DSV Карусели', 'denissv_animated_text_slider'),
            'singular_name'      => __('DSV Карусель', 'denissv_animated_text_slider'),
            'add_new'            => __('Добавить новую карусель', 'denissv_animated_text_slider'),
            'add_new_item'       => __('Создание новой карусели', 'denissv_animated_text_slider'),
            'edit_item'          => __('Редактирование карусели', 'denissv_animated_text_slider'),
            'new_item'           => __('Новая карусель', 'denissv_animated_text_slider'),
            'view_item'          => __('Просмотр карусели', 'denissv_animated_text_slider'),
            'search_items'       => __('Поиск каруселей', 'denissv_animated_text_slider'),
            'not_found'          => __('Не найдено', 'denissv_animated_text_slider'),
            'not_found_in_trash' => __('В корзине не найдено', 'denissv_animated_text_slider'),
            'menu_name'          => __('DSV Карусели', 'denissv_animated_text_slider'),
        ];
    }
}