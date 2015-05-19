<?php
/**
 * Cross-sells
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

global $product, $woocommerce_loop;

$crosssells = WC()->cart->get_cross_sells();

if ( sizeof( $crosssells ) == 0 ) {
    return;
}

$meta_query = WC()->query->get_meta_query();

$args = array(
    'post_type'           => 'product',
    'ignore_sticky_posts' => 1,
    'no_found_rows'       => 1,
    'posts_per_page'      => apply_filters( 'woocommerce_cross_sells_total', $posts_per_page ),
    'orderby'             => $orderby,
    'post__in'            => $crosssells,
    'meta_query'          => $meta_query
);

//force grid view
$woocommerce_loop['view'] = 'grid';


$products = new WP_Query( $args );

if ( $products->have_posts() ) : ?>

    <div class="clearfix"></div>

    <div class="cross-sells">

        <?php if( shortcode_exists('box_title') ) {
            echo do_shortcode("[box_title class='cross-sells-title' font_size='24' border_color='#e1e1e1' font_alignment='center' border='double']" . __( 'You may be interested in', 'yit' ) . "[/box_title]");
        }
        else {
            echo "<h3>" . __( 'You may be interested in', 'yit' ) . "</h3>";
        } ?>

            <?php woocommerce_product_loop_start(); ?>

            <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                <?php wc_get_template_part( 'content', 'product' ); ?>

            <?php endwhile; // end of the loop. ?>

            <?php woocommerce_product_loop_end(); ?>

    </div>

<?php endif;

wp_reset_query();