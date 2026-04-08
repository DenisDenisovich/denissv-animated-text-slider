<?php
namespace Denissv\AnimatedTextSlider\Admin\UI\Helpers;

if (!defined('ABSPATH')) {
    exit;
}

use Denissv\AnimatedTextSlider\Domain\Slide\SlideMeta;

class AdminUrl {
    public static function newSlide(int $carouselId): string
    {
        return add_query_arg(
            [
                'post_type' => SlideMeta::MENU_SLIDE_SLUG,
                'parent'    => $carouselId,
            ],
            admin_url('post-new.php')
        );
    }
}