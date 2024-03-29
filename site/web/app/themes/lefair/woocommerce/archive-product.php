<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header( 'shop' );

global $woocommerce_loop, $porto_settings;

if (!(isset($woocommerce_loop['category-view']) && $woocommerce_loop['category-view'])) {
    $woocommerce_loop['category-view'] = isset($porto_settings['category-view-mode']) ? $porto_settings['category-view-mode'] : '';

    $term = get_queried_object();
    if ($term && isset($term->taxonomy) && isset($term->term_id)) {
        $cols = get_metadata($term->taxonomy, $term->term_id, 'product_cols', true);
        if (!$cols)
            $cols = $porto_settings['product-cols'];

        $addlinks_pos = get_metadata($term->taxonomy, $term->term_id, 'addlinks_pos', true);
        if (!$addlinks_pos)
            $addlinks_pos = $porto_settings['category-addlinks-pos'];

        $view_mode = get_metadata($term->taxonomy, $term->term_id, 'view_mode', true);

        $woocommerce_loop['columns'] = $cols;
        $woocommerce_loop['addlinks_pos'] = $addlinks_pos;
        if ($view_mode)
            $woocommerce_loop['category-view'] = $view_mode;
    }
}

if (is_shop()) {
    $woocommerce_loop['columns'] = $porto_settings['shop-product-cols'];
}

?>

	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20 : removed
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

			<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

		<?php endif; ?>

        <?php
            /**
             * woocommerce_archive_description hook.
             *
             * @hooked woocommerce_taxonomy_archive_description - 10
             * @hooked woocommerce_product_archive_description - 10
             */
            do_action( 'woocommerce_archive_description' );
        ?>

		<?php if ( have_posts() ) : ?>

            <div class="shop-loop-before clearfix"<?php if (!is_search() && !woocommerce_products_will_display()) echo ' style="display:none;"' ?>>
			<?php
				/**
				 * woocommerce_before_shop_loop hook.
				 *
				 * @hooked woocommerce_result_count - 20 : removed
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>
            </div>

            <div class="archive-products">

                <?php woocommerce_product_loop_start(); ?>

                    <?php woocommerce_product_subcategories(); ?>

                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php wc_get_template_part( 'content', 'product' ); ?>

                    <?php endwhile; // end of the loop. ?>

                <?php woocommerce_product_loop_end(); ?>

            </div>

            <div class="shop-loop-after clearfix"<?php if (!is_search() && !woocommerce_products_will_display()) echo ' style="display:none;"' ?>>
			<?php
				/**
				 * woocommerce_after_shop_loop hook.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>
            </div>

        <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

            <div class="shop-loop-before clearfix" style="display:none;"></div>

            <div class="archive-products">
			<?php wc_get_template( 'loop/no-products-found.php' ); ?>
            </div>

            <div class="shop-loop-after clearfix" style="display:none;"></div>

		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>

<?php get_footer( 'shop' ); ?>