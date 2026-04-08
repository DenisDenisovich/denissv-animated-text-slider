<?php

namespace Denissv\AnimatedTextSlider\Core;

if (!defined('ABSPATH')) {
    exit;
}

interface Hookable {
    public function register(Loader $loader): void;
}