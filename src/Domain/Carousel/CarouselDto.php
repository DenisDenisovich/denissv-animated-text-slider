<?php

namespace Denissv\AnimatedTextSlider\Domain\Carousel;

if (!defined('ABSPATH')) {
    exit;
}

class CarouselDto {
    public function __construct(
        public int $id,
        public int $autoplay,
        public int $speed,
        public int $loop,
        public int $showArrows,
        public int $showDots,
        public string $height,
        public string $effect,
        public array $slides
    ) {}
}