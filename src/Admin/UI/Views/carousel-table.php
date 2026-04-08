<?php

if (!defined('ABSPATH')) {
    exit;
}
?>
<table class="widefat striped">
    <thead><tr><th>Заголовок</th><th>Порядок</th><th>Действия</th><th></th></tr></thead>
        <tbody id="carousel3-sortable">

        <?php if ($dsvats_data['slides']): ?>
            <?php foreach ($dsvats_data['slides'] as $slide): ?>
                <tr data-id="<?php echo intval($slide->ID); ?>">
                    <td><?php echo esc_html($slide->post_title); ?></td>
                    <td><?php echo intval($slide->menu_order); ?></td>
                    <td>
                <?php echo '<a href="' . esc_url( get_edit_post_link( $slide->ID ) ) . '">' . esc_html__( 'Редактировать', 'denissv-animated-text-slider' ) . '</a>'; ?>
                </td>
                <td class="drag-handle" style="cursor:move;">☰</td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">Слайдов нет</td></tr>
        <?php endif; ?>

        </tbody>
        </table>

        <p>
            <a class="button button-primary" href="
            <?php echo esc_url($dsvats_data['new_slide_url']); ?>
            ">Добавить слайд</a>
        </p>;