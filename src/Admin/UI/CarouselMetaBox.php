<?php
namespace Denissv\AnimatedTextSlider\Admin\UI;

if (!defined('ABSPATH')) {
    exit;
}

use Denissv\AnimatedTextSlider\Core\Hookable;
use Denissv\AnimatedTextSlider\Core\Loader;
use Denissv\AnimatedTextSlider\Domain\Carousel\CarouselMeta;
use Denissv\AnimatedTextSlider\Admin\UI\Helpers\AdminUrl;
use Denissv\AnimatedTextSlider\Domain\Carousel\CarouselRepository;
use Denissv\AnimatedTextSlider\Domain\Carousel\CarouselService;
use Denissv\AnimatedTextSlider\Domain\Slide\SlideMeta;
use Denissv\AnimatedTextSlider\Domain\Slide\SlideRepository;

class CarouselMetaBox implements Hookable {

    public function register(Loader $loader): void {
        $loader->addAction('add_meta_boxes', $this, 'addMetaBox');
        $loader->addAction('save_post_dsv_carousel', $this, 'save');
    }

    public function addMetaBox(): void {
        add_meta_box(
            'dsvats_slides_list',
            __('Carousel slides', 'denissv_animated_text_slider'),
            array($this, 'render_slides_list'),
            'dsv_carousel',
            'normal',
            'high'
        );

        add_meta_box(
            'dsvats_slides_settings',
            __('Настройки карусели', 'denissv-animated-text-slider'),
            array($this, 'render_slides_settings'),
            'dsv_carousel',
            'side',
            'default'
        );
    }

    public function render_slides_list($post): void {

        $dsvats_data = (new CarouselRepository())->getById($post->ID);

        $newSlideUrl = AdminUrl::newSlide($post->ID);

        include DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_DIR . 'src/Admin/UI/Views/carousel-table.php';
    }

    public function render_slides_settings($post): void {
        // Здесь будет HTML для отображения полей настроек карусели
        echo '<p>' . __('Здесь будут настройки карусели', 'denissv_animated_text_slider') . '</p>';
    }

    public function save($postId): void {
        // Здесь будет логика сохранения данных из мета-бокса
    }

    private function isValid(int $postId): bool {
        if (!isset($_POST['dsv_nonce']) || !wp_verify_nonce($_POST['dsv_nonce'], 'save_carousel')) {
            return false;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return false;
        }

        if (!current_user_can('edit_post', $postId)) {
            return false;
        }

        return true;
    }
}