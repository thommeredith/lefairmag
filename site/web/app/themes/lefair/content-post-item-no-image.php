<?php
global $porto_settings;

$attachment = '';
if (has_post_thumbnail()) {
    $attachment_id = get_post_thumbnail_id();
    $attachment_related = porto_get_attachment($attachment_id, 'related-post');
    $attachment = porto_get_attachment($attachment_id);
}
if (isset($porto_settings['post-related-style']) && 'style-3' == $porto_settings['post-related-style']) {
    ?>
    <div class="post-item with-btn">
        <div class="post-date">
            <?php
            porto_post_date();
            //porto_post_format();
            ?>
        </div>
        <h4>
            <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
        </h4>
        <?php echo porto_get_excerpt(20, false); ?>
        <a href="<?php the_permalink(); ?>" class="btn <?php echo $porto_settings['post-related-btn-style'] ?> <?php echo $porto_settings['post-related-btn-color'] ?> <?php echo $porto_settings['post-related-btn-size'] ?> m-t-md m-b-md"><?php echo __('Read More', 'porto') ?></a>
    </div>
<?php } else if ('style-2' == $porto_settings['post-related-style']) { ?>
    <div class="post-item style-2">
        <h5>
            <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
        </h5>
        <?php echo porto_get_excerpt(20, false); ?>
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
    </div>
<?php } else { ?>
    <div class="post-item">
        <div class="post-date">
            <?php
            porto_post_date();
            //porto_post_format();
            ?>
        </div>
        <h4>
            <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
        </h4>
        <?php echo porto_get_excerpt(20); ?>
    </div>
<?php }?>