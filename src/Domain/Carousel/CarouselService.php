<?php

namespace Denissv\AnimatedTextSlider\Domain\Carousel;

if (!defined('ABSPATH')) {
    exit;
}

use Denissv\AnimatedTextSlider\Infrastructure\Sanitizer;
use Denissv\AnimatedTextSlider\Domain\Carousel\CarouselPostType;
use Denissv\AnimatedTextSlider\Domain\Carousel\CarouselMeta;
use Denissv\AnimatedTextSlider\Domain\Carousel\CarouselRepository;
use Denissv\AnimatedTextSlider\Domain\Slide\SlideRepository;
use Denissv\AnimatedTextSlider\Domain\Slide\SlideMeta;

class CarouselService {
    public function __construct(
        private CarouselRepository $carouselRepo,
        private SlideRepository $slideRepo,
        private Sanitizer $sanitizer
    ) {}

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

    public function create(array $input): int {
        $data = [
            'title' => $this->sanitizer->text($input['title'] ?? 'Без названия'),
            'autoplay' => $this->sanitizer->bool($input['autoplay'], 0),
            'speed' => $this->sanitizer->int($input['speed'] ?? 3000),
            'loop' => $this->sanitizer->bool($input['loop'], 0),
            'show_arrows' => $this->sanitizer->bool($input['show_arrows'], 0),
            'show_dots' => $this->sanitizer->bool($input['show_dots'], 0),
            'height' => $this->sanitizer->cssSize($input['height'], '300px'),
            'effect' => $this->sanitizer->enum($input['effect'] ?? 'slide', ['slide', 'fade'], 'slide'),
        ];

        return $this->carouselRepo->create($data);
    }

    public function update(int $id, array $data): void {
        $carousel = $this->carouselRepo->getById($id);
        if (!$carousel) {
            throw new \InvalidArgumentException("Карусель с ID $id не найдена");
        }
        $updateData = [
            'title' => $this->sanitizer->text($data['title'] ?? ''),
            'autoplay' => $this->sanitizer->bool($data['autoplay'], 0),
            'speed' => $this->sanitizer->int($data['speed'] ?? 3000),
            'loop' => $this->sanitizer->bool($data['loop'], 0),
            'show_arrows' => $this->sanitizer->bool($data['show_arrows'], 0),
            'show_dots' => $this->sanitizer->bool($data['show_dots'], 0),
            'height' => $this->sanitizer->cssSize($data['height'], '300px'),
            'effect' => $this->sanitizer->enum($data['effect'] ?? 'slide', ['slide', 'fade'], 'slide'),
        ];

        $this->carouselRepo->update($id, $updateData);
    }

    public function delete(int $id): void {
        if (!$this->carouselRepo->getById($id)) {
            throw new \InvalidArgumentException("Карусель с ID $id не найдена");
        }

        $slides = $this->getSlidesByCarouselId($id);

        foreach ($slides as $slide) { // TODO: ПЕРЕНЕСТИ ЛОГИКУ УДАЛЕНИЯ СЛАЙДОВ В СЛАЙДРЕПОЗИТОРИЙ!!!!!
            if (!wp_delete_post($slide->ID, true)) {
                throw new \RuntimeException("Ошибка при удалении слайда с ID {$slide->ID}");
            }
        }

        if (!wp_delete_post($id, true)) { 
            throw new \RuntimeException("Ошибка при удалении карусели с ID $id");
        }
        
    }

    public function getSlidesByCarouselId(int $carouselId): array {
        $slides = get_children([
            'post_parent' => $carouselId,
            'post_type' => SlideMeta::MENU_SLIDE_SLUG,
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ]);

        return $slides ? array_values($slides) : [];
    }

    public function createWithSlides(array $data): int {
        $id = $this->carouselRepo->create($data);

        foreach ($data['slides'] ?? [] as $slide) {
            $this->slideRepo->create($id, $slide);
        }

        return $id;
    }
}