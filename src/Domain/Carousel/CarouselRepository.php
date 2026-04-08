<?php
namespace Denissv\AnimatedTextSlider\Domain\Carousel;

if (!defined('ABSPATH')) {
    exit;
}

use Denissv\AnimatedTextSlider\Domain\Carousel\CarouselService;
use Denissv\AnimatedTextSlider\Domain\Slide\SlideRepository;
use Denissv\AnimatedTextSlider\Infrastructure\Sanitizer;

class CarouselRepository {
    private $carouselService;

    /**
     * Get one carousel by ID
     */
    public function getById(int $id): ?CarouselDto {
        $post = get_post($id);

        if (!$post || $post->post_type !== CarouselPostType::POST_TYPE) {
            return null;
        }

        return $this->map($post);
    }

  

    /**
     * Create new carousel
     */
    public function create(array $data): int {
        $postId = wp_insert_post([
            'post_type'   => CarouselPostType::POST_TYPE,
            'post_title'  => $data['title'] ?? 'Без названия',
            'post_status' => 'publish',
        ]);

        if (is_wp_error($postId)) {
            throw new \RuntimeException($postId->get_error_message());
        }

        $this->updateMeta($postId, $data);

        return $postId;
    }

    /**
     * Update existing carousel
     */
    public function update(int $id, array $data): void {
        wp_update_post([
            'ID'         => $id,
            'post_title' => $data['title'] ?? '',
        ]);

        $this->updateMeta($id, $data);
    }

    /**
     * Delete carousel
     */
    public function delete(int $id): void {
        wp_delete_post($id, true);
    }

    /**
     * Update carousel meta
     */
    private function updateMeta(int $postId, array $data): void {
        update_post_meta($postId, CarouselMeta::FIELD_AUTOPLAY, !empty($data['autoplay']) ? 1 : 0);
        update_post_meta($postId, CarouselMeta::FIELD_SPEED, absint($data['speed'] ?? 3000));
        update_post_meta($postId, CarouselMeta::FIELD_LOOP, !empty($data['loop']) ? 1 : 0);
        update_post_meta($postId, CarouselMeta::SHOW_ARROWS, !empty($data['show_arrows']) ? 1 : 0);
        update_post_meta($postId, CarouselMeta::SHOW_DOTS, !empty($data['show_dots']) ? 1 : 0);
        update_post_meta($postId, CarouselMeta::HEIGHT, sanitize_text_field($data['height'] ?? '300px'));
        update_post_meta($postId, CarouselMeta::EFFECT, sanitize_text_field($data['effect'] ?? 'slide'));
    }

    /**
     * Map WP_Post to array (DTO)
     */
    private function map(\WP_Post $post): CarouselDto {
        $defaults = CarouselMeta::getDefaults();

        $slides = $this->carouselService->getSlidesByCarouselId($post->ID);

        return new CarouselDto(
            id: $post->ID,
            autoplay: (int) get_post_meta($post->ID, CarouselMeta::FIELD_AUTOPLAY, true) ?: $defaults[CarouselMeta::FIELD_AUTOPLAY],
            speed: (int) get_post_meta($post->ID, CarouselMeta::FIELD_SPEED, true) ?: $defaults[CarouselMeta::FIELD_SPEED],
            loop: (int) get_post_meta($post->ID, CarouselMeta::FIELD_LOOP, true) ?: $defaults[CarouselMeta::FIELD_LOOP],
            showArrows: (int) get_post_meta($post->ID, CarouselMeta::SHOW_ARROWS, true) ?: $defaults[CarouselMeta::SHOW_ARROWS],
            showDots: (int) get_post_meta($post->ID, CarouselMeta::SHOW_DOTS, true) ?: $defaults[CarouselMeta::SHOW_DOTS],
            height: (string) get_post_meta($post->ID, CarouselMeta::HEIGHT, true) ?: $defaults[CarouselMeta::HEIGHT],
            effect: (string) get_post_meta($post->ID, CarouselMeta::EFFECT, true) ?: $defaults[CarouselMeta::EFFECT],
            slides: $slides ? array_map(function($slide) {
                return [
                    'id' => $slide->ID,
                    'title' => $slide->post_title,
                    'content' => $slide->post_content,
                    'menu_order' => $slide->menu_order,
                ];
            }, $slides) : [],
        );
    }
}