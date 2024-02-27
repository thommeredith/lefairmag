<?php

global $post, $porto_settings, $porto_layout, $porto_member_columns, $porto_member_view, $porto_member_overview, $porto_member_socials;

$member_columns = $porto_member_columns ? $porto_member_columns : $porto_settings['member-columns'];
$post_class = array();
$post_class[] = 'member';
$post_class[] = 'member-col-' . $member_columns;
$item_cats = get_the_terms($post->ID, 'member_cat');
if ($item_cats):
    foreach ($item_cats as $item_cat) {
        $post_class[] = urldecode($item_cat->slug);
    }
endif;

$featured_images = porto_get_featured_images();
$member_link = get_post_meta($post->ID, 'member_link', true);
$show_external_link = $porto_settings['member-external-link'];

$thumb_class = 'thumb-info-hide-wrapper-bg';
$view_type = $porto_settings['member-view-type'];
if ($porto_member_view && $porto_member_view != 'classic') {
    if ($porto_member_view == 'onimage') $view_type = 0;
    if ($porto_member_view == 'outimage') $view_type = 2;
}
if ($view_type == 2) {
    $thumb_class .= ' thumb-info-no-zoom';
}
if ($view_type == 2 && $porto_settings['member-archive-readmore']) {
    $thumb_class = 'thumb-info-centered-info';
}

if (count($featured_images)) :
    $attachment_id = $featured_images[0]['attachment_id'];
    $attachment = porto_get_attachment($attachment_id);
    if ($member_columns == 2) {
        $attachment_medium = porto_get_attachment($attachment_id, 'member-two');
    } else {
        $attachment_medium = porto_get_attachment($attachment_id, 'member');
    }
    if ($attachment && $attachment_medium) :
        $role = get_post_meta($post->ID, 'member_role', true);
        $member_id = get_the_ID();
        $show_info = false;
        ?>
        <article <?php post_class($post_class); ?>>
            <?php porto_render_rich_snippets(); ?>
            <div class="member-item <?php echo !$view_type ? '' : ' align-center' ?>">
                <span class="thumb-info <?php echo $thumb_class ?>">
                    <span class="thumb-info-wrapper">
                        <a href="<?php if ($show_external_link && $member_link) echo $member_link; else the_permalink() ?>">
                            <img class="img-responsive" width="<?php echo $attachment_medium['width'] ?>" height="<?php echo $attachment_medium['height'] ?>" src="<?php echo $attachment_medium['src'] ?>" alt="<?php echo $attachment_medium['alt'] ?>" />
                            <?php if (!$view_type) : ?>
                            <span class="thumb-info-title">
                                <span class="thumb-info-inner<?php echo ($member_columns > 4 && ($porto_layout == 'fullwidth' || $porto_layout == 'left-sidebar' || $porto_layout == 'right-sidebar')) ? ' font-size-xs line-height-xs' : '' ?>"><?php the_title(); ?></span>
                                <?php
                                if ($role) : ?>
                                    <span class="thumb-info-type"><?php echo $role ?></span>
                                <?php endif; ?>
                            </span>
                            <?php endif;
                            if ($view_type == 2 && $porto_settings['member-archive-readmore']) : ?>
                            <span class="thumb-info-title">
                                <span class="thumb-info-inner"><?php echo ($porto_settings['member-archive-readmore-label'] ? $porto_settings['member-archive-readmore-label'] : __('View More...', 'porto')); ?></span>
                            </span>
                            <?php endif; ?>
                        </a>
                        <?php if ($porto_settings['member-zoom']) : ?>
                            <span class="zoom" data-src="<?php echo $attachment['src'] ?>" data-title="<?php echo $attachment['caption'] ?>"><i class="fa fa-search"></i></span>
                        <?php endif; ?>
                    </span>
                <?php if ($view_type == 2) :
                    $show_info = true;
                    ?>
                    </span>
                    <a class="text-decoration-none" href="<?php if ($show_external_link && $member_link) echo $member_link; else the_permalink() ?>">
                        <?php if ($member_columns > 4) :?><h5 class="m-t-md m-b-none"><?php the_title(); ?></h5><?php
                        else : ?><h4 class="m-t-md m-b-none"><?php the_title(); ?></h4><?php endif; ?>
                    </a>
                    <?php
                    if ($role) : ?>
                        <p class="m-b-sm"><?php echo $role ?></p>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($porto_member_overview == 'yes' || (!$porto_member_overview && $porto_settings['member-overview']) || $porto_member_socials == 'yes' || (!$porto_member_socials && $porto_settings['member-socials'])) : ?>
                <span class="thumb-info-caption">
                    <?php if ($porto_member_overview == 'yes' || (!$porto_member_overview && $porto_settings['member-overview'])) : ?>
                        <span class="thumb-info-caption-text<?php echo !$view_type ? '' : ' p-t-none' ?>">
                        <?php
                        $show_info = true;
                        $member_overview = do_shortcode(get_post_meta($member_id, 'member_overview', true));
                        if ($porto_settings['member-excerpt']) {
                            $member_overview = porto_strip_tags( apply_filters( 'the_content', $member_overview ) );
                            $limit = $porto_settings['member-excerpt-length'] ? $porto_settings['member-excerpt-length'] : 15;
                            $member_overview = explode(' ', $member_overview, $limit);

                            if (count($member_overview) >= $limit) {
                                array_pop($member_overview);
                                $member_overview = implode(" ",$member_overview).__('...', 'porto');
                            } else {
                                $member_overview = implode(" ",$member_overview);
                            }
                        }
                        echo do_shortcode(wpautop($member_overview));
                        ?>
                        </span>
                    <?php endif; ?>
                    <?php if ($porto_member_socials == 'yes' || (!$porto_member_socials && $porto_settings['member-socials'])) : ?>
                        <span class="thumb-info-social-icons share-links<?php echo $show_info ? '' : ' b-none' ?><?php echo !$view_type ? '' : ' m-r-none m-l-none' ?>">
                            <?php
                            // Social Share
                            $share_facebook = get_post_meta($member_id, 'member_facebook', true);
                            $share_twitter = get_post_meta($member_id, 'member_twitter', true);
                            $share_linkedin = get_post_meta($member_id, 'member_linkedin', true);
                            $share_googleplus = get_post_meta($member_id, 'member_googleplus', true);
                            $share_pinterest = get_post_meta($member_id, 'member_pinterest', true);
                            $share_email = get_post_meta($member_id, 'member_email', true);
                            $share_vk = get_post_meta($member_id, 'member_vk', true);
                            $share_xing = get_post_meta($member_id, 'member_xing', true);
                            $share_tumblr = get_post_meta($member_id, 'member_tumblr', true);
                            $share_reddit = get_post_meta($member_id, 'member_reddit', true);
                            $share_vimeo = get_post_meta($member_id, 'member_vimeo', true);
                            $share_instagram = get_post_meta($member_id, 'member_instagram', true);
                            $share_whatsapp = get_post_meta($member_id, 'member_whatsapp', true);
                            $target = (isset($porto_settings['member-social-target']) && $porto_settings['member-social-target']) ? ' target="_blank"' : '';

                            if ($share_facebook) :
                                ?><a href="<?php echo esc_url($share_facebook) ?>"<?php echo $target ?> data-tooltip data-placement="bottom" title="<?php echo __('Facebook', 'porto') ?>" class="share-facebook"><?php echo __('Facebook', 'porto') ?></a><?php
                            endif;

                            if ($share_twitter) :
                                ?><a href="<?php echo esc_url($share_twitter) ?>"<?php echo $target ?> data-tooltip data-placement="bottom" title="<?php echo __('Twitter', 'porto') ?>" class="share-twitter"><?php echo __('Twitter', 'porto') ?></a><?php
                            endif;

                            if ($share_linkedin) :
                                ?><a href="<?php echo esc_url($share_linkedin) ?>"<?php echo $target ?> data-tooltip data-placement="bottom" title="<?php echo __('LinkedIn', 'porto') ?>" class="share-linkedin"><?php echo __('LinkedIn', 'porto') ?></a><?php
                            endif;

                            if ($share_googleplus) :
                                ?><a href="<?php echo esc_url($share_googleplus) ?>"<?php echo $target ?> data-tooltip data-placement="bottom" title="<?php echo __('Google +', 'porto') ?>" class="share-googleplus"><?php echo __('Google +', 'porto') ?></a><?php
                            endif;

                            if ($share_pinterest) :
                                ?><a href="<?php echo esc_url($share_pinterest) ?>"<?php echo $target ?> data-tooltip data-placement="bottom" title="<?php echo __('Pinterest', 'porto') ?>" class="share-pinterest"><?php echo __('Pinterest', 'porto') ?></a><?php
                            endif;

                            if ($share_email) :
                                ?><a href="mailto:<?php echo esc_attr($share_email) ?>"<?php echo $target ?> data-tooltip data-placement="bottom" title="<?php echo __('Email', 'porto') ?>" class="share-email"><?php echo __('Email', 'porto') ?></a><?php
                            endif;

                            if ($share_vk) :
                                ?><a href="<?php echo esc_url($share_vk) ?>"<?php echo $target ?> data-tooltip data-placement="bottom" title="<?php echo __('VK', 'porto') ?>" class="share-vk"><?php echo __('VK', 'porto') ?></a><?php
                            endif;

                            if ($share_xing) :
                                ?><a href="<?php echo esc_url($share_xing) ?>"<?php echo $target ?> data-tooltip data-placement="bottom" title="<?php echo __('Xing', 'porto') ?>" class="share-xing"><?php echo __('Xing', 'porto') ?></a><?php
                            endif;

                            if ($share_tumblr) :
                                ?><a href="<?php echo esc_url($share_tumblr) ?>"<?php echo $target ?> data-tooltip data-placement="bottom" title="<?php echo __('Tumblr', 'porto') ?>" class="share-tumblr"><?php echo __('Tumblr', 'porto') ?></a><?php
                            endif;

                            if ($share_reddit) :
                                ?><a href="<?php echo esc_url($share_reddit) ?>"<?php echo $target ?> data-tooltip data-placement="bottom" title="<?php echo __('Reddit', 'porto') ?>" class="share-reddit"><?php echo __('Reddit', 'porto') ?></a><?php
                            endif;

                            if ($share_vimeo) :
                                ?><a href="<?php echo esc_url($share_vimeo) ?>"<?php echo $target ?> data-tooltip data-placement="bottom" title="<?php echo __('Vimeo', 'porto') ?>" class="share-vimeo"><?php echo __('Vimeo', 'porto') ?></a><?php
                            endif;

                            if ($share_instagram) :
                                ?><a href="<?php echo esc_url($share_instagram) ?>"<?php echo $target ?> data-tooltip data-placement="bottom" title="<?php echo __('Instagram', 'porto') ?>" class="share-instagram"><?php echo __('Instagram', 'porto') ?></a><?php
                            endif;

                            if ($share_whatsapp) :
                                ?><a href="whatsapp://send?text=<?php echo esc_url($share_whatsapp) ?>"<?php echo $target ?> data-tooltip data-placement="bottom" title="<?php echo __('WhatsApp', 'porto') ?>" class="share-whatsapp" style="display:none"><?php echo __('WhatsApp', 'porto') ?></a><?php
                            endif;

                            ?>
                        </span>
                    <?php endif; ?>
                </span>
                <?php endif; ?>
                <?php if ($view_type != 2) : ?></span><?php endif; ?>
            </div>
        </article>
    <?php
    endif;
endif;