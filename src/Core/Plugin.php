<?php

namespace Denissv\AnimatedTextSlider\Core;

if (!defined('ABSPATH')) {
    exit;
}

use Denissv\AnimatedTextSlider\Admin\UI\CarouselMetaBox;
use Denissv\AnimatedTextSlider\Domain\Carousel\CarouselPostType;
use Denissv\AnimatedTextSlider\Domain\Carousel\CarouselService;
use Denissv\AnimatedTextSlider\Domain\Carousel\CarouselRepository;
use Denissv\AnimatedTextSlider\Domain\Slide\SlideRepository;
use Denissv\AnimatedTextSlider\Infrastructure\Sanitizer;

class Plugin {
    public function run(): void {
        $carouselRepository = new CarouselRepository();
        $slideRepository    = new SlideRepository();
        $sanitizer          = new Sanitizer();

        $carouselService = new CarouselService(
            $carouselRepository,
            $slideRepository,
            $sanitizer
        );

        $loader = new Loader();

        $services = [
            new CarouselPostType(),
            new CarouselMetaBox(),
            //new CarouselMeta(),
            //new Assets(),
        ];

        foreach ($services as $service) {
            $service->register($loader);
        }

        $loader->run();
    }
}