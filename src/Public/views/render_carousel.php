<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
    <div class="carouselwp-carousel slider-container">
        <div class="swiper carousel3" 
            <?php echo ($height !== 'none') ? 'style="height:' . esc_attr($height) . ';"' : ''; ?>
            data-carousel-id="<?php echo esc_attr($carousel_id); ?>"
            data-autoplay="<?php echo esc_attr($c3_conf['autoplay']); ?>"
            data-autoplay-speed="<?php echo esc_attr($c3_conf['autoplay_speed']); ?>"
            data-animation-speed="<?php echo esc_attr($c3_conf['animation_speed']); ?>"
            data-show-arrows="<?php echo esc_attr($c3_conf['show_arrows']); ?>"
            data-show-dots="<?php echo esc_attr($c3_conf['show_dots']); ?>"
            data-show-scrollbar="<?php echo esc_attr($c3_conf['show_scrollbar']); ?>"
            data-height="<?php echo esc_attr($height); ?>"
            data-slides-per-view="<?php echo esc_attr($c3_conf['slides_per_view']); ?>"
            data-spaces-between="<?php echo esc_attr($c3_conf['spaces_between']); ?>"
            data-effect="<?php echo esc_attr($effect); ?>"
        >
            <div class="swiper-wrapper">
                <?php foreach ($query->posts as $denissv_slide) : 
                    $denissv_animation_type = get_post_meta($denissv_slide->ID, DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY . '_animation_type', true);
                    $denissv_animation_type = $denissv_animation_type ? $denissv_animation_type : 'animate__fadeInUp';

                    $denissv_slide_id = (int) $denissv_slide->ID;
                    $denissv_thumb_id = get_post_thumbnail_id($denissv_slide_id);

                    if ($denissv_thumb_id) :
                        $denissv_attachment_image = wp_get_attachment_image(
                            $denissv_thumb_id,
                            'large',
                            false,
                            [
                                'sizes' => $c3_sizes,
                                'class' => 'swiper-image'
                            ]
                        );
                ?>
                    <div class="swiper-slide">
                        <?php echo wp_kses_post( $denissv_attachment_image ); ?>

                        <div class="ani-item description" data-ani="<?php echo esc_attr( $denissv_animation_type ); ?>">
                            <h2><?php echo esc_html( $denissv_slide->post_title ); ?></h2>
                            <?php
                            $denissv_content = do_shortcode( $denissv_slide->post_content );
                            echo wp_kses_post( $denissv_content ); 
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>

            <!-- Navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

        </div>
    </div>