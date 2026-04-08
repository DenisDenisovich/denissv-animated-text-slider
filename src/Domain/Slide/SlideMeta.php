<?php
namespace Denissv\AnimatedTextSlider\Domain\Slide;

if (!defined('ABSPATH')) {
    exit;
}

class SlideMeta {

    public const MENU_SLIDE_SLUG = 'dsv_carousel';

    public const FIELD_IMAGE = '_dsvats_slide_image';
    public const FIELD_TITLE = '_dsvats_slide_title';
    public const FIELD_DESCRIPTION = '_dsvats_slide_description';

    public static function getDefaults(): array {
        return [
            self::FIELD_IMAGE => '',
            self::FIELD_TITLE => '',
            self::FIELD_DESCRIPTION => '',
        ];
    }

    public static function getAnimationsList(): array {
        return [
            ''                              => __('No animation', 'denissv-animated-text-slider'),
            'animate__fadeIn'               => __('Fade In', 'denissv-animated-text-slider'),
            'animate__fadeInUp'             => __('Fade In Up', 'denissv-animated-text-slider'),
            'animate__fadeInDown'           => __('Fade In Down', 'denissv-animated-text-slider'),
            'animate__bounceIn'             => __('Bounce In', 'denissv-animated-text-slider'),
            'animate__zoomIn'               => __('Zoom In', 'denissv-animated-text-slider'),
            'animate__slideInLeft'          => __('Slide In Left', 'denissv-animated-text-slider'),
            'animate__slideInRight'         => __('Slide In Right', 'denissv-animated-text-slider'),
            'animate__backInDown'           => __('Back In Down', 'denissv-animated-text-slider'),
            'animate__flipInX'              => __('Flip In X', 'denissv-animated-text-slider'),
            'animate__lightSpeedInRight'    => __('Light Speed In', 'denissv-animated-text-slider'),
            'animate__rollIn'               => __('Roll In', 'denissv-animated-text-slider'),
        ];
    }

}