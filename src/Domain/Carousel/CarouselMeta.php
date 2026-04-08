<?php
namespace Denissv\AnimatedTextSlider\Domain\Carousel;

if (!defined('ABSPATH')) {
    exit;
}

class CarouselMeta {

    public const MENU_CAROUSEL_SLUG = 'dsv_carousel';

    public const FIELD_AUTOPLAY = '_dsvats_autoplay';
    public const FIELD_SPEED    = '_dsvats_speed';
    public const FIELD_LOOP     = '_dsvats_loop';

    public const SHOW_ARROWS = '_dsvats_show_arrows';
    public const SHOW_DOTS = '_dsvats_show_dots';
    public const HEIGHT = '_dsvats_height';
    public const EFFECT = '_dsvats_effect';

    public static function getDefaults(): array {
        return [
            self::FIELD_AUTOPLAY => 1,
            self::FIELD_SPEED    => 3000,
            self::FIELD_LOOP     => 1,
            self::SHOW_ARROWS => 1,
            self::SHOW_DOTS => 1,
            self::HEIGHT => '300px',
            self::EFFECT => 'slide',
        ];
    }
}