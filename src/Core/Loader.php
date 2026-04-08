<?php

namespace Denissv\AnimatedTextSlider\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Loader {
    private array $actions = [];
    private array $filters = [];

    public function addAction(string $hook, object $component, string $callback): void {
        $this->actions[] = compact('hook', 'component', 'callback');
    }

    public function addFilter(string $hook, object $component, string $callback): void {
        $this->filters[] = compact('hook', 'component', 'callback');
    }

    public function run(): void {
        foreach ($this->actions as $action) {
            add_action(
                $action['hook'],
                [$action['component'], $action['callback']]
            );
        }

        foreach ($this->filters as $filter) {
            add_filter(
                $filter['hook'],
                [$filter['component'], $filter['callback']]
            );
        }
    }
}