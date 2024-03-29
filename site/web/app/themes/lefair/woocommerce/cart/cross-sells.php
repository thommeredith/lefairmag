<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product, $porto_settings;

$crosssells = WC()->cart->get_cross_sells();

if ( 0 === sizeof( $crosssells ) || !$porto_settings['product-crosssell']) return;

$meta_query = WC()->query->get_meta_query();

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => 1,
	'posts_per_page'      => apply_filters( 'woocommerce_cross_sells_total', $porto_settings['product-crosssell-count'] ),
	'orderby'             => $orderby,
	'post__in'            => $crosssells,
	'meta_query'          => $meta_query
);

$products = new WP_Query( $args );

if ( $products->have_posts() ) : ?>

	<div class="cross-sells">
	     <h3 id="fancy-title-12" class="mk-fancy-title responsive-align-center avantgarde-title center-align "><span class="fancy-title-span pointed">You may be</span></h3>
         <h1 class="main-font" style="text-align: center;">INTERESTED IN</h1>

        <div class="slider-wrapper">

            <?php
            global $woocommerce_loop, $porto_layout;
            $woocommerce_loop['view'] = 'products-slider';
            if ($porto_layout == 'wide-left-sidebar' || $porto_layout == 'wide-right-sidebar' || $porto_layout == 'left-sidebar' || $porto_layout == 'right-sidebar') {
                $woocommerce_loop['columns'] = 3;
            } else {
                $woocommerce_loop['columns'] = 4;
            }
            $woocommerce_loop['widget'] = true;

            woocommerce_product_loop_start(); ?>

                <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                    <?php wc_get_template_part( 'content', 'product' ); ?>

                <?php endwhile; // end of the loop. ?>

            <?php
            woocommerce_product_loop_end();
            ?>

        </div>

	</div>

<?php endif;

wp_reset_query();