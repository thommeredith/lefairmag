<?php
global $porto_settings;

$post_layout = 'medium';
?>

<article <?php post_class('post post-' . $post_layout); ?>>

    <div class="row">
        <?php if (has_post_thumbnail()) : ?>
        <div class="col-sm-5">
            <?php // Post Slideshow ?>
            <?php get_template_part('slideshow', 'medium'); ?>
        </div>
        <div class="col-sm-7">
            <?php else : ?>
            <div class="col-sm-12">
                <?php endif; ?>

                <div class="post-content nn">

                    <h2 class="entry-title"><?php the_title(); ?></h2>
                    <?php porto_render_rich_snippets( false ); ?>
                    <div class="entry-content">
                        <?php
                        the_content();
                        wp_link_pages( array(
                            'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'porto' ) . '</span>',
                            'after'       => '</div>',
                            'link_before' => '<span>',
                            'link_after'  => '</span>',
                            'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'porto' ) . ' </span>%',
                            'separator'   => '<span class="screen-reader-text">, </span>',
                        ) );
                        ?>
                    </div>

                </div>
            </div>
        </div>

        <div class="post-gap"></div>

        <div class="post-meta">
            <?php if (in_array('date', $porto_settings['post-metas'])) : ?><span><i class="fa fa-calendar"></i> <?php echo get_the_date() ?></span><?php endif; ?>
            <?php if (in_array('author', $porto_settings['post-metas'])) : ?><span><i class="fa fa-user"></i> <?php echo __('By', 'porto'); ?> <?php the_author_posts_link(); ?></span><?php endif; ?>
            <?php
            $cats_list = get_the_category_list(', ');
            if ($cats_list && in_array('cats', $porto_settings['post-metas'])) : ?>
                <span><i class="fa fa-folder-open"></i> <?php echo $cats_list ?></span>
            <?php endif; ?>
            <?php
            $tags_list = get_the_tag_list('', ', ');
            if ($tags_list && in_array('tags', $porto_settings['post-metas'])) : ?>
                <span><i class="fa fa-tag"></i> <?php echo $tags_list ?></span>
            <?php endif; ?>
            <?php if (in_array('comments', $porto_settings['post-metas'])) : ?><span><i class="fa fa-comments"></i> <?php comments_popup_link(__('0 Comments', 'porto'), __('1 Comment', 'porto'), '% '.__('Comments', 'porto')); ?></span><?php endif; ?>
            <?php
            if (function_exists('Post_Views_Counter') && Post_Views_Counter()->options['display']['position'] == 'manual') {
                $post_count = do_shortcode('[post-views]');
                if ($post_count) {
                    echo $post_count;
                }
            }
            ?>
        </div>

        <?php
        $share = porto_get_meta_value('post_share');
        if ($porto_settings['share-enable'] && 'no' !== $share && ('yes' === $share || ('yes' !== $share && $porto_settings['post-share']))) : ?>
            <div class="post-block post-share">
                <h3><i class="fa fa-share"></i><?php _e('Share this post', 'porto') ?></h3>
                <?php get_template_part('share') ?>
            </div>
        <?php endif; ?>

        <?php if ($porto_settings['post-author']) : ?>
            <div class="post-block post-author clearfix">
                <h3><i class="fa fa-user"></i><?php _e('Author', 'porto') ?></h3>
                <div class="img-thumbnail">
                    <?php echo get_avatar(get_the_author_meta('email'), '80'); ?>
                </div>
                <p><strong class="name"><?php the_author_posts_link(); ?></strong></p>
                <p><?php the_author_meta("description"); ?></p>
            </div>
        <?php endif; ?>

        <div class="post-gap"></div>

        <?php if ($porto_settings['post-comments']) : ?>
            <?php
            wp_reset_postdata();
            comments_template();
            ?>
        <?php endif; ?>

</article>