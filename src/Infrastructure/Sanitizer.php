<?php

namespace Denissv\AnimatedTextSlider\Infrastructure;

if (!defined('ABSPATH')) {
    exit;
}

class Sanitizer
{
    /**
     * Sanitizing a text field (single-line)
     */
    public function text(?string $value): string
    {
        return sanitize_text_field($value ?? '');
    }

    /**
     * Sanitizing a textarea field (multi-line text)
     */
    public function textarea(?string $value): string
    {
        return sanitize_textarea_field($value ?? '');
    }

    /**
     * Sanitizing HTML (allowed WP HTML)
     */
    public function html(?string $value): string
    {
        return wp_kses_post($value ?? '');
    }

    /**
     * Sanitizing URL
     */
    public function url(?string $value): string
    {
        return esc_url_raw($value ?? '');
    }

    /**
     * Sanitizing integer
     */
    public function int($value, int $default = 0): int
    {
        return isset($value) ? absint($value) : $default;
    }

    /**
     * Boolean (checkbox, flags)
     */
    public function bool($value): int
    {
        return !empty($value) ? 1 : 0;
    }

    /**
     * Ограничение значения по whitelist (например effect)
     */
    public function enum($value, array $allowed, $default)
    {
        return in_array($value, $allowed, true) ? $value : $default;
    }

    /**
     * Sanitizing CSS size (e.g., 300px, 50%, auto)
     */
    public function cssSize(?string $value, string $default = 'auto'): string
    {
        $value = trim($value ?? '');

        if ($value === '') {
            return $default;
        }

        // Resolution: 100px, 50%, auto
        if (preg_match('/^\d+(px|%)$/', $value)) {
            return $value;
        }

        if ($value === 'auto') {
            return $value;
        }

        return $default;
    }

    /**
     * Array (recursive sanitization of strings in arrays)
     */
    public function array(array $data): array
    {
        return array_map(function ($item) {
            if (is_array($item)) {
                return $this->array($item);
            }

            return is_string($item)
                ? sanitize_text_field($item)
                : $item;
        }, $data);
    }
}