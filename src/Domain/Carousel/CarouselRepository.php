<?php
namespace Denissv\AnimatedTextSlider\Domain\Carousel;

if (!defined('ABSPATH')) {
    exit;
}

class CarouselRepository {

    public function __construct() {
        $this->meta = $meta;
    }

    /**
     * Get one carousel by ID
     */
    public function getById(int $id): ?array {
        $post = get_post($id);

        if (!$post || $post->post_type !== CarouselPostType::POST_TYPE) {
            return null;
        }

        return $this->map($post);
    }

    /**
     * Get all carousels
     */
    public function getAll(): array {
        $query = new \WP_Query([
            'post_type'      => CarouselPostType::POST_TYPE,
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);

        $result = [];

        foreach ($query->posts as $post) {
            $result[] = $this->map($post);
        }

        return $result;
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
    private function map(\WP_Post $post): array {
        $defaults = CarouselMeta::getDefaults();

        $new_slide_url = 
            add_query_arg(
                array(
                    'post_type' => 'dsv_carousel_slide',
                    'parent'    => $post->ID,
                ),
                admin_url( 'post-new.php' )
            );

        $slides = get_children([
            'post_parent' => $post->ID,
            'post_type' => 'dsv_carousel_slide',
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ]);

        return [
            'autoplay' => (int) get_post_meta($post->ID, self::FIELD_AUTOPLAY, true) ?: $defaults[self::FIELD_AUTOPLAY],
            'speed'    => (int) get_post_meta($post->ID, self::FIELD_SPEED, true) ?: $defaults[self::FIELD_SPEED],
            'loop'     => (int) get_post_meta($post->ID, self::FIELD_LOOP, true) ?: $defaults[self::FIELD_LOOP],
            'show_arrows' => (int) get_post_meta($post->ID, self::SHOW_ARROWS, true) ?: $defaults[self::SHOW_ARROWS],
            'show_dots' => (int) get_post_meta($post->ID, self::SHOW_DOTS, true) ?: $defaults[self::SHOW_DOTS],
            'height' => (int) get_post_meta($post->ID, self::HEIGHT, true) ?: $defaults[self::HEIGHT],
            'effect' => get_post_meta($post->ID, self::EFFECT, true) ?: $defaults[self::EFFECT],
            'new_slide_url' => $new_slide_url,
            'slides' => $slides,
        ];
    }
}