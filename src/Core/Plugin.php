<?php

namespace Denissv\AnimatedTextSlider\Core;

if (!defined('ABSPATH')) {
    exit;
}

use Denissv\AnimatedTextSlider\Admin\UI\CarouselMetaBox;
use Denissv\AnimatedTextSlider\Domain\Carousel\CarouselPostType;
use Denissv\AnimatedTextSlider\Domain\Carousel\CarouselMeta;
use Denissv\AnimatedTextSlider\Infrastructure\Assets;

class Plugin {
    public function run(): void {
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