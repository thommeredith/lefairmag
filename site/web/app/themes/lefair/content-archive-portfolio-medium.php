<?php

global $porto_settings, $post;

$portfolio_layout = 'medium';

$item_classes = ' ';
$item_cats = get_the_terms($post->ID, 'portfolio_cat');
if ($item_cats):
    foreach ($item_cats as $item_cat) {
        $item_classes .= urldecode($item_cat->slug) . ' ';
    }
endif;

$portfolio_link = get_post_meta($post->ID, 'portfolio_link', true);
$skill_list = get_the_term_list($post->ID, 'portfolio_skills', '', '</li><li><i class="fa fa-check-circle"></i> ', '');
$portfolio_client = get_post_meta($post->ID, 'portfolio_client', true);

$show_external_link = $porto_settings['portfolio-external-link'];
?>

<article <?php post_class('portfolio portfolio-' . $portfolio_layout . $item_classes); ?>>

    <div class="row">

        <?php
        $attachment_id = get_post_thumbnail_id();
        $attachment = porto_get_attachment($attachment_id);
        if ($attachment) : ?>
        <div class="col-sm-4">
            <?php // Portfolio Slideshow ?>
            <?php get_template_part('slideshow', 'portfolio-medium'); ?>
        </div>
        <div class="col-sm-8">
            <?php else : ?>
            <div class="col-sm-12">
                <?php endif; ?>
                <h2 class="entry-title"><a href="<?php if ($show_external_link && $portfolio_link) echo $portfolio_link; else the_permalink() ?>"><?php the_title() ?></a></h2>

                <?php
                porto_render_rich_snippets( false );
                if ($porto_settings['portfolio-excerpt']) {
                    echo porto_get_excerpt( $porto_settings['portfolio-excerpt-length'], false );
                } else {
                    echo '<div class="entry-content">';
                    the_content();
                    wp_link_pages( array(
                        'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'porto' ) . '</span>',
                        'after'       => '</div>',
                        'link_before' => '<span>',
                        'link_after'  => '</span>',
                        'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'porto' ) . ' </span>%',
                        'separator'   => '<span class="screen-reader-text">, </span>',
                    ) );
                    echo '</div>';
                }
                ?>

                <?php
                if ($portfolio_link) :
                    ?>
                    <div class="post-gap-small"></div>

                    <a target="_blank" class="btn btn-primary btn-icon" href="<?php echo esc_url($portfolio_link) ?>">
                        <i class="fa fa-external-link"></i><?php _e('Live Preview', 'porto') ?>
                    </a>

                    <span data-appear-animation-delay="800" data-appear-animation="rotateInUpLeft" class="dir-arrow <?php echo is_rtl() ? 'hrb' : 'hlb' ?> appear-animation"></span>
                <?php endif; ?>
                <div class="post-gap"></div>

                <?php if ((in_array('skills', $porto_settings['portfolio-metas']) && $skill_list) || (in_array('client', $porto_settings['portfolio-metas']) && $portfolio_client)) : ?>
                <ul class="portfolio-details">
                    <?php
                    if (in_array('skills', $porto_settings['portfolio-metas']) && $skill_list) : ?>
                        <li>
                            <p><strong><?php _e('Skills', 'porto') ?>:</strong></p>

                            <ul class="list list-skills icons list-unstyled list-inline">
                                <li><i class="fa fa-check-circle"></i>
                                    <?php echo $skill_list ?>
                                </li>
                            </ul>
                        </li>
                    <?php endif;
                    if (in_array('client', $porto_settings['portfolio-metas']) && $portfolio_client) : ?>
                        <li>
                            <p><strong><?php _e('Client', 'porto') ?>:</strong></p>
                            <p><?php echo esc_html($portfolio_client) ?></p>
                        </li>
                    <?php endif; ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>

        <div class="clearfix post-gap-small">
            <div class="portfolio-info pt-left">
                <ul>
                    <?php if (in_array('like', $porto_settings['portfolio-metas'])) : ?>
                        <li>
                            <?php echo porto_portfolio_like() ?>
                        </li>
                    <?php endif;
                    if (in_array('date', $porto_settings['portfolio-metas'])) : ?>
                        <li>
                            <i class="fa fa-calendar"></i> <?php echo get_the_date() ?>
                        </li>
                    <?php endif;
                    $cat_list = get_the_term_list($post->ID, 'portfolio_cat', '', ', ', '');
                    if (in_array('cats', $porto_settings['portfolio-metas']) && $cat_list) : ?>
                        <li>
                            <i class="fa fa-tags"></i> <?php echo $cat_list ?>
                        </li>
                    <?php endif; ?>
                    <?php
                    if (function_exists('Post_Views_Counter') && Post_Views_Counter()->options['display']['position'] == 'manual') {
                        $post_count = do_shortcode('[post-views]');
                        if ($post_count) : ?>
                            <li>
                                <?php echo $post_count ?>
                            </li>
                        <?php endif;
                    }
                    ?>
                </ul>
            </div>
            <a class="btn btn-xs btn-primary pt-right" href="<?php echo esc_url( apply_filters( 'the_permalink', get_permalink() ) ) ?>"><?php _e('Read more...', 'porto') ?></a>
        </div>

</article>