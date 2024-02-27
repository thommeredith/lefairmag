<?php
    defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
?>
<div style="display: none;" id="load-cl">
    <div class="title-loag-cl"></div>
    <div class="img-loag-cl"></div>
    <div class="description-loag-cl"></div>
</div>
<div id="info-details" style="display: none;">
    <div class="title-info-details">
        <?php lang::get("Link anchors explanation"); ?> 
    </div>
    <div class="content-info-details">
        <?php lang::get("Words and phrases, which will be used for an automatic linking between texts in corresponding category.");?>
        <br />
        <?php lang::get("Also, you can use a roots of the words, that you want to use for linking. The plugin will find the word with corresponding roots automatically and will use their words for link creating.");?>
    </div>
    <div class="button-close">
        <input type="button" value="<?php lang::get('Close');?>" class="lp-button-close" onclick="jQuery('#info-details').arcticmodal('close');">
    </div>
</div>
<div id="info-details-auto" style="display: none;">
    <div class="title-info-details">
        <?php lang::get("Extraction button explanation"); ?> 
    </div>
    <div class="content-info-details">
        <?php lang::get("This button will <strong>extract words and phrases for <span class=\"change-text\">each</span> category automatically</strong>, which will be used for an automatic linking between texts in <span class=\"change-text2\">corresponding</span> category.");?>
        <br />
        <?php lang::get("After automated extracting of words and phrases, <strong><span class=\"change-text3\">all</span> lists will be saved automatically</strong>, and you will be <strong>able to edit</strong> the list of extracted words and phrases. Some of these words you can cut to make roots of the words, that can also take part in searching for words for linking. The plugin will search the word with corresponding roots automatically and will use these words for link-building.");?>
    </div>
    <div class="button-close">
        <input type="button" value="<?php lang::get('Close');?>" class="lp-button-close" onclick="jQuery('#info-details-auto').arcticmodal('close');">
    </div>
</div>
<div id="content-link-support" style="display: none;">
    <form action="<?php echo admin_url("admin-post.php?action=cl_support");?>" method="post">
        <div class="title"><?php lang::get("Sending of suggestions");?></div>
        <div class="body">
            <table>
                <tr id="loading-field" style="display: none;">
                    <td colspan="3">
                        <img src="<?php echo plugins_url('/assets/img/wpadmloader.gif', dirname(__FILE__));?>" alt="loading" >
                    </td>
                </tr>
                <tr id="message-field">
                    <th style="vertical-align: top;"><?php lang::get('Suggestion');?></th>
                    <td colspan="2"><textarea id="message" name="message"></textarea></td>
                </tr>
                <tr id="message-result" style="display: none;">
                    <td colspan="2" style="font-size:16px; vertical-align: middle; height: 120px;"></td>
                </tr>
                <tr id="button-sent">
                    <td width="0"></td>
                    <td style="padding-top: 20px; text-align: left;">
                        <span style="margin-left:10px">
                            <input type="button" class="button button-primary" value="<?php lang::get("Send");?>" onclick="sendMessageSupport(this)">
                        </span>
                    </td>
                    <td style="padding-top: 20px; text-align: right;">
                        <span style="float: right;margin-right: 10px;line-height: 33px;">
                            <a href="javascript:void(0)" onclick="closeSupport();"><?php lang::get("Cancel and go back")?></a>
                        </span>
                    </td>
                </tr>
                <tr id="button-ok" style="display: none;">
                    <td colspan="2" style="text-align: center;">
                        <a href="javascript:void(0)" onclick="closeSupport();"><?php lang::get("Go back")?></a>
                    </td>
                </tr>
            </table>
        </div>
    </form>
</div>

<div class="wrap">
    <?php if (!empty($error)) {
            echo '<div class="error" style="text-align: center; color: red; font-weight:bold;">
            <p style="font-size: 16px;">
            ' . $error . '
            </p></div>'; 
    }?>
    <?php if (!empty($msg)) {
            echo '<div class="updated" style="text-align: center; font-weight:bold;">
            <p style="font-size: 16px;">
            ' . $msg . '
            </p></div>'; 
    }?>
    <!-- 
    Posts to Posts
    Posts to Categories
    Posts to Pages
    Categories to Posts
    Categories to Categories
    Categories to Pages
    Pages to Posts
    Pages to Categories
    Pages to Pages
    Custom linking from Post, Page or Category to other Post(s), Page(s), Category or Categories.
    -->
    <div class="block-pro-stars">
        <div class="main-block-pro-stars">
            <div class="block-pro">
                <div class="pro-title">
                    <?php lang::get('Use Professional version of <strong>"SEO Post Content Links"</strong> plugin and get:') ; ?>  
                </div>
                <ul class="pro-list">
                    <li>
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Link anchors corresponds to the target text'); ?>
                        </span>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Linking to ALL directions:'); ?>
                        </span>   <br />
                        <div class="description-pro-list">- <?php lang::get('Articles to Articles'); ?></div>

                        <div class="description-pro-list">- <?php lang::get('Articles to Categories'); ?></div>

                        <div class="description-pro-list">- <?php lang::get('Articles to Pages'); ?></div>

                        <div class="description-pro-list">- <?php lang::get('Categories to Articles'); ?></div>

                        <div class="description-pro-list">- <?php lang::get('Categories to Categories'); ?></div>

                        <div class="description-pro-list">- <?php lang::get('Categories to Pages'); ?></div>

                        <div class="description-pro-list">- <?php lang::get('Pages to Articles'); ?></div>

                        <div class="description-pro-list">- <?php lang::get('Pages to Categories'); ?></div>

                        <div class="description-pro-list">- <?php lang::get('Pages to Pages'); ?></div>

                        <div class="clear"></div>
                    </li>
                    <li>     
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Custom linking'); ?>:

                        </span><br />
                        <div class="description-pro-list"><?php lang::get('from Article, Page or Category to other Article(s), Page(s), Category or Categories'); ?>
                        </div>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Relevancy algorithm to rank all content and pages higher'); ?>
                        </span>
                        <div class="clear"></div>
                    </li>

                    <li>
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Link anchors style customization'); ?>
                        </span>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Link attributes like "nofollow", "open link in new window", etc.'); ?>
                        </span>
                        <div class="clear"></div>
                    </li>
                    <li class="hide-pro">
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Prevention of links in Title tags like h1/h2/h3/h4/h5/h6'); ?>
                        </span>
                        <div class="clear"></div>
                    </li>
                    <li class="hide-pro">
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Prevention of links in custom HTML tags &lt;span&gt;,&lt;li&gt;,&lt;ul&gt;,&lt;strong&gt;... etc.'); ?>
                        </span>
                        <div class="clear"></div>
                    </li>                    
                    <li class="hide-pro">
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Repetitions prevention for links in each text. '); ?>
                        </span>  <br />
                        <div class="description-pro-list"><?php lang::get('Each link-direction in text will be unique.'); ?></div>
                        <div class="clear"></div>
                    </li>
                    <li class="hide-pro">
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Unique words processing for interlinking.'); ?>
                        </span>  <br />
                        <div class="description-pro-list"><?php lang::get('Anchor words/phrases in lists will be checked for unique.'); ?></div>
                        <div class="clear"></div>
                    </li>
                    <li class="hide-pro">
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Cron tasks processing.'); ?>
                        </span> <br />
                        <div class="description-pro-list"><?php lang::get('Processing of any number of pages and links. <br />No dependencies on web server restrictions.'); ?></div>
                        <div class="clear"></div>
                    </li>
                    <li class="hide-pro">
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Excludes'); ?>
                        </span>
                        <div class="clear"></div>
                        <div class="description-pro-list"><?php lang::get('Exclude some pages and/or articles from linking.<br />Exclude pages by "slug" or some part of "slug".'); ?></div>
                        <div class="clear"></div>
                    </li>
                    <li class="hide-pro">
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Priority support for PRO version'); ?>
                        </span>
                        <div class="clear"></div>
                    </li>
                    <li class="hide-pro">
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('One year free updates'); ?>
                        </span>
                        <div class="clear"></div>
                    </li>
                </ul>

                <form id="content_links_pro_form" name="content_links_pro_form" method="post" action="<?php echo cl_api::$url_secure; ?>/api/">
                    <input type="hidden" name="site" value="<?php echo home_url(); ?>">
                    <input type="hidden" name="actApi" value="<?php echo 'proBackupPay'; ?>">
                    <input type="hidden" name="email" value="<?php echo get_option('admin_email');?>">
                    <input type="hidden" name="plugin" value="<?php echo 'content-links'; ?>">
                    <input type="hidden" name="success_url" value="<?php echo admin_url("admin.php?page=link-settings&pay=success&cl_nonce=" . $nonce_p); ?>">
                    <input type="hidden" name="cancel_url" value="<?php echo admin_url("admin.php?page=link-settings&pay=cancel&cl_nonce=" . $nonce_p); ?>">
                    <input class="button button-primary button-hero" type="submit" value="<?php lang::get('Get PRO'); ?>">
                </form>
            </div>
            <div class="block-stars" style="">
                <div class="block-stars-click" style="cursor: pointer;" onclick="window.open('https://wordpress.org/support/view/plugin-reviews/content-links?filter=5')">
                    <div class="stars"><?php lang::get('Leave us '); ?> <a><?php lang::get('5 stars'); ?></a></div>
                    <div class="stars"><img src="<?php echo plugins_url('/assets/img/stars-5.png', dirname(__FILE__));?>" alt="<?php lang::get('5 stars'); ?>" title="<?php lang::get('5 stars'); ?>" /></div>
                    <div class="stars" style="font-size: 16px"><?php lang::get('It will help us to develop this plugin for you'); ?></div>
                </div>
                <div class="block-support">
                    <?php lang::get('If you have any suggestions or wishes')?>
                    <input type="button" onclick="showModal('content-link-support')" class="button button-primary" value="<?php lang::get('Contact us')?>">
                </div>
            </div>
            <div class="block-description">
                <h3><?php lang::get('About SEO Post Content Links PRO plugin'); ?></h3>
                <?php lang::get('It builds internal links between website pages/texts and articles, without changing source articles (it does not change any user’s original text or page).<br />Plugin provides higher ranking and higher visibility range of the website in all searching engines like Google, Yahoo, Bing, Baido, Yandex etc. <br />Also it increases website indexing, as a result boosts sales through this website.'); ?>
            </div>
            <div class="clear"></div>
            <div class="more-pro" onclick="displayProInfo()">
                <div class="title-more-pro">
                    <?php lang::get('Show more...'); ?>
                </div>
            </div>
            <div class="clear;"></div> 
        </div>
    </div>
    <form action="" method="post">
        <input type="hidden" value="setting" name="type">
        <div class="main-form-setting">
            <div class="title-setting" onclick="shows_form('.setting-linking', '#icon-title');">
                <?php lang::get('Settings'); ?>
                <span style="font-size: 10px; float:none;">(<?php echo lang::get('plugin version ', false) . $plugin_version; ?>)</span>
                <span id="icon-title" class="dashicons dashicons-arrow-down"></span>

            </div>
            <div class="setting-linking" style="display: none;">
                <table class="form-table">
                    <tr>
                        <th>
                            <label for="count-links"><?php lang::get('Count of links in text')?></label>
                            <br />
                            <span style="font-size: 12px; font-weight: 300;">(<?php lang::get('Links won\'t be created if the word roots or words or phrases wasn\'t found' )?>)</span>
                        </th>
                        <td><input type="text" name="count_links" id="count-links" value="<?php echo $link_count; ?>"></td>
                    </tr>
                    <tr>
                        <th>
                            <label for="black-words"><?php lang::get('Black Words')?></label> 
                            <br />
                            <span style="font-size: 12px; font-weight: 300;">
                                (<?php lang::get('Stop words')?>)
                            </span>
                        </th>
                        <td> <textarea cols="80" style="resize:none;" name="black_words" id="black-words"><?php echo $black_words; ?></textarea> </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="single-article"><?php lang::get('Show in single articles')?></label> 
                        </th>
                        <td>
                            <input type="checkbox" id="single-article" name="single-article" value="1" <?php echo ( $single_article == 1 ? 'checked="checked"' :  ''); ?>>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <input type="radio" name="links_category" id="links-category_1" value="1" <?php echo ( ($link_in_one_category == 1) ? 'checked="checked"' : '' );?> > 
                            <label for="links-category_1"><?php lang::get('Linking posts among themselves within one category')?></label>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <input type="radio" name="links_category" id="links-category_0" value="0" <?php echo ( ($link_in_one_category == 0) ? 'checked="checked"' : '' );?> >
                            <label for="links-category_0"><?php lang::get('Linking posts among themselves between all categories')?></label>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="links-links_to_pages"><?php lang::get('Set links from category-posts to pages')?></label> 
                        </th>
                        <td> <input type="checkbox" name="links_to_pages" id="links-links_to_pages" value="1" <?php echo ( ($links_to_pages == 1) ? 'checked="checked"' : '' );?> > </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="links-links_to_pages"><?php lang::get('Add links to H-tags')?></label> 
                        </th>
                        <td> <input type="checkbox" name="links_to_htags" id="links-links_to_htags" value="1" <?php echo ( ($links_to_htags == 1) ? 'checked="checked"' : '' );?> > </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><input type="submit" class="button button-primary" value="<?php lang::get("Save"); ?>" /> 
                        <input type="hidden" value="<?php echo $nonce?>" name="cl_nonce">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
    <form action="" method="post" id="form_field" name="form_field">
        <input type="hidden" value="fields" name="type">
        <input type="hidden" value="auto_links_anchors" name="action">
        <input type="hidden" value="" name="cat_id">
        <input type="hidden" value="<?php echo $nonce?>" name="cl_nonce">
    </form> 

    <?php include_once 'tasks.php';?>


    <table class="wp-list-table widefat fixed tags cl-table" style="border:1px solid #aaa;">
        <thead>
            <tr>
                <th align="center" width="150" class="title-table"><?php lang::get("Category <br /> Name"); ?></th>
                <th align="center" width="200" class="title-table"><?php lang::get("Category <br /> Description"); ?></th>
                <th align="center" width="100" class="title-table">
                    <?php lang::get("Label"); ?> <br />
                    <span style="font-size: 12px;">(<?php lang::get("slug"); ?>)</span>
                </th>
                <th width="90" align="center" class="title-table"><?php lang::get("Count of <br /> Posts"); ?></th>
                <th align="center" class="title-table"><?php lang::get("Link anchors"); ?><br />(<a style="font-size: 12px;" href="javascript:void(0)" onclick="showModal('info-details')"><?php lang::get("explanation"); ?></a>)</th>
                <th width="252" align="center" style="text-align: center;" >
                    <input class="button" type="button" value="<?php lang::get('Get words & phrases from all categories'); ?>" onclick="submitFilter('all');">
                    (<a style="font-size: 12px;" href="javascript:void(0)" onclick="showModal('info-details-auto', ['.change-text', '.change-text2', '.change-text3'], ['<?php lang::get('each');?>', '<?php lang::get('corresponding');?>', '<?php lang::get('all');?>'])"><?php lang::get("explanation"); ?></a>)
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($categories as $category) {
                    $linking_text = self::getLiningByCat($category->cat_ID);
                ?> 
                <tr>
                    <td >
                        <strong>
                            <a href="edit-tags.php?action=edit&taxonomy=category&tag_ID=<?php echo $category->cat_ID; ?>&post_type=post" target="_blank"><?php echo $category->name?></a>
                        </strong>
                    </td>
                    <td ><?php echo $category->description; ?></td>
                    <td ><?php echo urldecode( $category->slug ); ?></td>
                    <td ><a href="edit.php?category_name=<?php echo $category->slug;?>"><?php echo $category->count; ?></a></td>
                    <td >
                        <form method="post" action="" >
                            <input type="hidden" value="words" name="type"> 
                            <input type="hidden" value="<?php echo $category->cat_ID?>" name="cat_id"> 
                            <textarea class="words-links" name="linking-text"><?php echo isset($linking_text[0]['linking_text']) ? $linking_text[0]['linking_text'] : ''; ?></textarea>
                            <input type="hidden" value="<?php echo $nonce?>" name="cl_nonce">
                            <br />
                            <input type="submit" value="<?php lang::get('Save');?>" class="button button-primary">
                        </form>
                    </td>
                    <td align="center">
                        <input class="button" type="button" value="<?php lang::get('Get words & phrases')?>" onclick="submitFilter('<?php echo $category->cat_ID; ?>');">
                        <br />
                        (<a style="font-size: 12px;" href="javascript:void(0)" onclick="showModal('info-details-auto', ['.change-text', '.change-text2', '.change-text3'], [ '\'<?php echo $category->name?>\'', '\'<?php echo $category->name?>\'', '<?php lang::get('the');?>']);"><?php lang::get("explanation"); ?></a>)
                    </td>
                </tr>
                <?php
            }?>
        </tbody>
    </table>

</div>