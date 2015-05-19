<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

define('WC_LATEST_VERSION' , '2.3' );
/* === HOOKS === */
function yit_woocommerce_hooks() {

    if ( ! defined( 'YIT_DEBUG' ) || ! YIT_DEBUG ) {
        $message = get_option( 'woocommerce_admin_notices', array() );
        $message = array_diff( $message, array( 'template_files' ) );
        update_option( 'woocommerce_admin_notices', $message );
    }

    add_action( 'yit_activated', 'yit_woocommerce_default_image_dimensions' );

    add_filter( 'woocommerce_enqueue_styles', 'yit_enqueue_wc_styles' );
    add_filter( 'WC_TEMPLATE_PATH', 'yit_set_wc_template_path' );
    if( yit_is_old_ie() ) {
        add_action( 'wp_head', 'yit_add_wc_styles_to_assets', 0 );
    }
    add_action( 'wp_head', 'yit_size_images_style' );
    add_action( 'woocommerce_before_main_content', 'yit_shop_page_meta' );

    // Ajax search loading
    add_filter( 'yith_wcas_ajax_search_icon', 'yit_loading_search_icon', 15 );

    // Use WC 2.0 variable price format, now include sale price strikeout
    add_filter( 'woocommerce_variable_sale_price_html', 'wc_wc20_variation_price_format', 10, 2 );
    add_filter( 'woocommerce_variable_price_html', 'wc_wc20_variation_price_format', 10, 2 );


    // Custom Pagination
    add_filter( 'woocommerce_pagination_args', 'yit_pagination_shop_args' );



    /*============= SHOP PAGE ===============*/

    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );


    add_action( 'woocommerce_before_shop_loop_item_title', 'yit_out_stock_icon', 15 );

    add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 20 );

    add_filter( 'loop_shop_per_page', 'yit_products_per_page' );
    add_action( 'shop-page-meta', 'yit_wc_catalog_ordering', 15 );

    add_action( 'woocommerce_after_shop_loop_item_title', 'yit_shop_product_description', 25 );

    add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_sale_flash' );

    /*======== SINGLE PRODUCT PAGE =========*/

    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
    add_action( 'yit_single_page_breadcrumb', 'woocommerce_breadcrumb', 20, 0 );

    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

    add_action( 'woocommerce_single_product_summary', 'yit_product_modal_window', 25 );

    if ( yit_get_option('shop-single-product-name') == 'no' ) remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
    if ( yit_get_option( 'shop-single-metas' ) == 'no' ) remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

    /* tabs */
    add_filter( 'woocommerce_product_tabs', 'yit_woocommerce_add_tabs' );

    add_action('woocommerce_after_single_product_summary', 'yit_add_extra_content', 12);

    /*============== CART ============*/

    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
    add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );


    /*============== ADMIN  ==============*/

    add_action( 'woocommerce_product_options_general_product_data', 'yit_woocommerce_admin_product_ribbon_onsale' );
    add_action( 'woocommerce_process_product_meta', 'yit_woocommerce_process_product_meta', 2, 2 );

}
add_action( 'after_setup_theme', 'yit_woocommerce_hooks' );


if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', WC()->version ), '2.3', '<' ) ) {
    add_filter( 'add_to_cart_redirect' , 'yit_remove_add_to_cart_redirect' );
}else{
    add_filter( 'woocommerce_add_to_cart_redirect' , 'yit_remove_add_to_cart_redirect' );
}

// Useful for opening cart in header
function yit_remove_add_to_cart_redirect() {
    return false;
}
//add_filter( 'add_to_cart_redirect', 'yit_remove_add_to_cart_redirect' );


/********
* SIZES
**********/
// shop small
if ( ! function_exists( 'yit_shop_catalog_w' ) ) : function yit_shop_catalog_w() {
    $size = wc_get_image_size( 'shop_catalog' );
    return $size['width'];
} endif;
if ( ! function_exists( 'yit_shop_catalog_h' ) ) : function yit_shop_catalog_h() {
    $size = wc_get_image_size( 'shop_catalog' );
    return $size['height'];
} endif;
if ( ! function_exists( 'yit_shop_catalog_c' ) ) : function yit_shop_catalog_c() {
    $size = wc_get_image_size( 'shop_catalog' );
    return $size['crop'];
} endif;

// shop thumbnail
if ( ! function_exists( 'yit_shop_thumbnail_w' ) ) : function yit_shop_thumbnail_w() {
    $size = wc_get_image_size( 'shop_thumbnail' );
    return $size['width'];
} endif;
if ( ! function_exists( 'yit_shop_thumbnail_h' ) ) : function yit_shop_thumbnail_h() {
    $size = wc_get_image_size( 'shop_thumbnail' );
    return $size['height'];
} endif;
if ( ! function_exists( 'yit_shop_thumbnail_c' ) ) : function yit_shop_thumbnail_c() {
    $size = wc_get_image_size( 'shop_thumbnail' );
    return $size['crop'];
} endif;

//shop large
if ( ! function_exists( 'yit_shop_single_w' ) ) : function yit_shop_single_w() {
    $size = wc_get_image_size( 'shop_single' );
    return $size['width'];
} endif;
if ( ! function_exists( 'yit_shop_single_h' ) ) : function yit_shop_single_h() {
    $size = wc_get_image_size( 'shop_single' );
    return $size['height'];
} endif;
if ( ! function_exists( 'yit_shop_single_c' ) ) : function yit_shop_single_c() {
    $size = wc_get_image_size( 'shop_single' );
    return $size['crop'];
} endif;


if ( ! function_exists( 'yit_enqueue_wc_styles' ) ) {
    /**
     * Remove Woocommerce Styles add custom Yit Woocommerce style
     *
     * @param $styles
     *
     * @return array list of style files
     * @since    2.0.0
     */
    function yit_enqueue_wc_styles( $styles ) {

        $path = 'woocommerce';
        $version = WC()->version;

        if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $version ), WC_LATEST_VERSION, '<' ) ) {
            $path = 'woocommerce_' . substr( $version, 0, 3 ) . '.x';
        }
        /* 2.3 add select2 on cart page*/
        else{
            if(is_cart()){
                wp_enqueue_script( 'select2' );
                wp_enqueue_style( 'select2', WC()->plugin_url() . '/assets/css/select2.css' );
            }
        }

        unset( $styles['woocommerce-general'], $styles['woocommerce-layout'], $styles['woocommerce-smallscreen'] );

        $styles ['yit-layout'] = array(
            'src'     => get_stylesheet_directory_uri() . '/' . $path . '/style.css',
            'deps'    => '',
            'version' => '1.0',
            'media'   => ''
        );
        return $styles;
    }
}

if( ! function_exists( 'yit_add_wc_styles_to_assets' ) ){
    function yit_add_wc_styles_to_assets(){
         $stylepicker_css = array(
             'src'       => get_stylesheet_directory_uri() . '/woocommerce/style.css',
             'enqueue'   => true,
             'media'     => 'all'
        );

        if( function_exists( 'YIT_Asset' ) ){
            YIT_Asset()->set( 'style', 'yit-woocommerce', $stylepicker_css, 'after', 'theme-stylesheet' );
        }
    }
}

if ( ! function_exists( 'yit_set_wc_template_path' ) ) {
    /**
     * Return the folder of custom woocommerce templates
     *
     * @param $path
     *
     * @return string template folder
     *
     * @since    2.0.0
     */
    function yit_set_wc_template_path( $path ) {
        $path = 'woocommerce/';
        return $path;
    }
}

function woocommerce_template_loop_product_thumbnail() {

    global $product, $woocommerce_loop;

    $attachments = $product->get_gallery_attachment_ids();

    $original_size = wc_get_image_size( 'shop_catalog' );

    if ( $woocommerce_loop['view'] == 'masonry_item' ) {
        $size = $original_size;
        $size['height'] = 0;
        YIT_Registry::get_instance()->image->set_size('shop_catalog', $size );
    }

    if( isset( $attachments[0] ) ) {
        echo '<a href="' . get_permalink() . '" class="thumb backface"><span class="face">' . woocommerce_get_product_thumbnail() . '</span>';
        echo '<span class="face back">';
        yit_image( "id=$attachments[0]&size=shop_catalog&class=image-hover" );
        echo '</span></a>';
    }
    else {
        echo '<a href="' . get_permalink() . '" class="thumb"><span class="face">' . woocommerce_get_product_thumbnail() . '</span></a>';
    }

    if ( $woocommerce_loop['view'] == 'masonry_item' ) {
        YIT_Registry::get_instance()->image->set_size('shop_catalog', $original_size );
    }
}


if ( ! function_exists( 'yit_get_current_cart_info' ) ) {
    /**
     * Remove Woocommerce Styles add custom Yit Woocommerce style
     *
     * @internal param $styles
     *
     * @return array list of style files
     * @since    2.0.0
     */
    function yit_get_current_cart_info() {

        $items     = yit_get_option( 'shop-mini-cart-total-items' ) ? WC()->cart->get_cart_contents_count() : count( WC()->cart->get_cart() );
        $cart_icon = yit_get_option( 'shop-mini-cart-icon' );
        $cart_icon_dark = yit_get_option( 'header-dark-shop-mini-cart-icon' );

        return array(
            $items,
            $cart_icon,
            $cart_icon_dark
        );
    }
}

if ( ! function_exists( 'yit_shop_product_description' ) ) {
    /**
     * Add short product description in shop
     *
     */
    function yit_shop_product_description() {

        global $product;

        $excerpt = $product->post->post_excerpt;

        if ( $excerpt != "" ) :
            echo '<div class="product-description"><p>';
            echo wp_trim_words( $excerpt );
            echo '</p></div>';
        endif;

    }
}

function yit_woocommerce_admin_product_ribbon_onsale() {
    wc_get_template( 'admin/custom-onsale.php' );
}

function yit_woocommerce_process_product_meta( $post_id, $post ) {

    $active = ( isset( $_POST['_active_custom_onsale'] ) ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_active_custom_onsale', esc_attr( $active ) );

    if ( isset( $_POST['_preset_onsale_icon'] ) ) {
        update_post_meta( $post_id, '_preset_onsale_icon', esc_attr( $_POST['_preset_onsale_icon'] ) );
    }
    if ( isset( $_POST['_custom_onsale_icon'] ) ) {
        update_post_meta( $post_id, '_custom_onsale_icon', esc_attr( $_POST['_custom_onsale_icon'] ) );
    }
}

if ( ! function_exists( 'yit_add_to_cart_success_ajax' ) ) {

    function yit_add_to_cart_success_ajax( $datas ) {

        list( $cart_items, $cart_icon, $cart_icon_dark ) = yit_get_current_cart_info();
        $datas['.yit_cart_widget .cart_label .cart-items .yit-mini-cart-icon'] = '<span class="yit-mini-cart-icon"><span class="cart-items-number">' . $cart_items . '</span></span>';
        return $datas;
    }

    add_filter( 'add_to_cart_fragments', 'yit_add_to_cart_success_ajax' );
}

if ( ! function_exists( 'yit_size_images_style' ) ) {

    function yit_size_images_style() {

        $content_width      = $GLOBALS['content_width'];
        $shop_catalog_w     = ( 100 * yit_shop_catalog_w() ) / $content_width;
        $info_product_width = 100 - $shop_catalog_w;
        ?>
        <style type="text/css">
            .woocommerce ul.products li.product.list .product-wrapper .thumb-wrapper {
                width: <?php echo $shop_catalog_w ?>%;
                height: auto;
            }
            .woocommerce ul.products li.product.list .product-wrapper .product-actions-wrapper,
            .woocommerce ul.products li.product.list .product-wrapper .product-meta-wrapper {
                width: <?php echo $info_product_width -2?>%;
            }

        </style>
    <?php
    }
}


if ( ! function_exists( 'yit_products_per_page' ) ) {
    /*
     * Custom number of product per page
     */
    function yit_products_per_page() {

        $num_prod = ( isset( $_GET['products-per-page'] ) ) ? $_GET['products-per-page'] : yit_get_option( 'shop-products-per-page' ) ;

        if ( $num_prod == 'all' ) {
            $num_prod = wp_count_posts( 'product' )->publish;
        }

        return $num_prod;
    }
}

if ( ! function_exists( 'yit_shop_page_meta' ) ) {
    /*
     * Page meta for shop page
     */
    function yit_shop_page_meta() {
        if ( is_single() ) {
            return;
        }
        wc_get_template( '/global/page-meta.php' );
    }
}

if ( ! function_exists( 'yit_wc_catalog_ordering' ) ) {

    function yit_wc_catalog_ordering() {
        if ( ! is_single() && have_posts() ) {
            woocommerce_catalog_ordering();
        }
    }
}


/* variation price format */
function wc_wc20_variation_price_format( $price, $product ) {
    // Main Price
    $prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
    $price  = $prices[0] !== $prices[1] ? sprintf( __( '<span class="from">From: </span>%1$s', 'yit' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
    // Sale Price
    $prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
    sort( $prices );
    $saleprice = $prices[0] !== $prices[1] ? sprintf( __( '<span class="from">From: </span>%1$s', 'yit' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

    if ( $price !== $saleprice ) {
        $price = '<del>' . $saleprice . '</del> <ins>' . $price . '</ins>';
    }
    return $price;
}

if( ! function_exists( 'yit_remove_reviews_tab' ) ){

    function yit_remove_reviews_tab ( $tabs ) {

        unset( $tabs[ 'reviews' ] );
        return $tabs;
    }
}

/* CUSTOM TABS */

function yit_woocommerce_add_tabs( $tabs = array() ) {

    global $post;

    $custom_tabs = yit_get_post_meta( $post->ID, '_custom_tab' );

    if ( ! empty( $custom_tabs ) ) {
        foreach ( $custom_tabs as $tab ) {
            $tabs['custom' . $tab["position"]] = array(
                'title'      => $tab["name"],
                'priority'   => 30,
                'callback'   => 'yit_woocommerce_add_custom_panel',
                'custom_tab' => $tab
            );
        }
    }
    return $tabs;
}

function yit_woocommerce_add_custom_panel( $key, $tab ) {
    wc_get_template( 'single-product/tabs/custom.php', array( 'key' => $key, 'tab' => $tab ) );
}



if ( ! function_exists( 'yit_loading_search_icon' ) ) {

    function yit_loading_search_icon() {
        return '"' . YIT_THEME_ASSETS_URL . '/images/search.gif"';
    }
}


if ( ! function_exists( 'yit_product_modal_window' ) ){
    /**
     * Get template for modal in single product page
     */
    function yit_product_modal_window(){
        wc_get_template( 'single-product/modal-window.php');
    }
}

if ( ! function_exists( 'yit_pagination_shop_args' ) ) {
    /**
     * Custom pagination for shop page
     *
     * @return array
     * @since 1.0.0
     */
    function yit_pagination_shop_args(){

        global $wp_query;

        $args = array(
            'base'         => str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
            'format'       => '',
            'current'      => max( 1, get_query_var( 'paged' ) ),
            'total'        => $wp_query->max_num_pages,
            'type'         => 'plain',
            'prev_next'    => true,
            'prev_text' => '&lt;',
            'next_text' => '&gt;',
            'end_size'     => 3,
            'mid_size'     => 3,
            'add_fragment' => '',
            'before_page_number' => '',
            'after_page_number' => ''
        );

        return $args;
    }
}

// SET LAYOUT FOR SHOP PAGE

function yit_sidebar_shop_page( $value, $key, $id ) {

    $new_layout = ( isset( $_GET['layout-shop'] ) ) ? $_GET['layout-shop'] : '';

    if( isset( $value['layout'] ) && $new_layout != '' && $key == 'sidebars' ) {

        $value['layout'] = $new_layout;

        if( $value['sidebar-left'] == -1 ){
            $value['sidebar-left'] = $value['sidebar-right'];
        }
        elseif( $value['sidebar-right'] == -1 ){
            $value['sidebar-right'] = $value['sidebar-left'];
        }
    }

    return $value;
}
add_filter( 'yit_get_option_layout', 'yit_sidebar_shop_page', 10, 3 );


// add image for product category page

function woocommerce_taxonomy_archive_description() {

    if ( is_tax( array( 'product_cat', 'product_tag' ) ) && get_query_var( 'paged' ) == 0 ) {

        $description = apply_filters( 'the_content', term_description() );

        echo '<div class="term-description">' . $description . '</div>';

    }
}



if ( ! function_exists( 'yit_image_content_single_width' ) ) {
    /**
     * Set image and content width for single product image
     *
     * @return array
     * @since 1.0.0
     * @author Francesco Licando <francesco.licandro@yithemes.it>
     */
    function yit_image_content_single_width() {

        $size = array();

        $img_size = yit_shop_single_w();

        if ( intval( $img_size ) < $GLOBALS['content_width'] ) {
            $size['image'] = ( intval( $img_size ) * 100 ) / $GLOBALS['content_width'];
        }
        else {
            $size['image'] = 100;
        }

        $size['content'] = 100 - ( $size['image'] );
        $min_size = ( wp_is_mobile() ) ? '40' : '20';

        if ( $size['content'] < $min_size ) {
            $size['content'] = 100;
        }

        return $size;

    }
}

function yit_add_extra_content(){

    global $post;

    $extra = '';

    $add_extra = yit_get_post_meta( $post->ID, '_add_extra_content');
    if( $add_extra == "yes" ) {
        $extra = yit_get_post_meta( $post->ID, '_extra_content' );
    }

    echo do_shortcode( $extra );
}

if ( ! function_exists( 'yit_out_stock_icon' ) ) {
    /*
     * Add icon out of stock
     *
     * @since 2.0.0
     */
    function yit_out_stock_icon() {

        global $product;

        if ( ! $product->is_in_stock() ) : ?>
            <div class="out-of-stock-icon">
                <div class="out-of-stock">
                    <div class="out-of-stock-text">
                        <span><?php echo apply_filters( 'out_of_stock_add_to_cart_text', __( 'Out Of Stock', 'yit' ) ); ?></span>
                    </div>
                </div>
            </div>
        <?php endif;
    }
}

if( ! function_exists( 'yit_woocommerce_object' ) ) {

    function yit_woocommerce_object() {

        wp_localize_script( 'jquery', 'yit_woocommerce', array(
            'version' => WC()->version,

        ));

    }

}

if( defined('YITH_YWAR_PREMIUM') ) {

    add_filter( 'yith_advanced_reviews_loader_gif', 'yit_loading_search_icon' );

}
