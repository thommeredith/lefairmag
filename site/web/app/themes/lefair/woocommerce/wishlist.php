<?php
/**
 * Wishlist pages template; load template parts basing on the url
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.0
 */

global $wpdb, $woocommerce;

?>

<h3 id="fancy-title-12" class="mk-fancy-title responsive-align-center avantgarde-title center-align "><span class="fancy-title-span pointed">Lefair</span></h3>
         <h1 class="main-font" style="text-align: center;">WISHLIST</h1>
<div id="yith-wcwl-messages"></div>

<?php yith_wcwl_get_template( 'wishlist-' . $template_part . '.php', $atts ) ?>