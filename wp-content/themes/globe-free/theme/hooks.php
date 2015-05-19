<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * @package Yithems
 */

/*
 * =SETUP=
 */

/**
 * @see yit_setup_theme
 */
add_action( 'wp_head', 'yit_detect_javascript', 0 );
add_action( 'after_setup_theme', 'yit_setup_theme' );
add_filter( 'body_class', 'yit_add_body_class' );
add_action( 'admin_init', 'yit_add_field_to_testimonial_meta' );
add_action( 'admin_enqueue_scripts', 'yit_theme_admin_enqueue' );

add_action( 'admin_action_yit_shortcode_popup', 'yit_theme_admin_enqueue' );
if(is_shop_installed()) add_action( 'wp_enqueue_scripts', 'yit_woocommerce_object', 110 );

// MISC
add_filter( 'yit_button_style', 'yit_button_style' );
add_filter( 'yit_get_testimonial_categories', 'yit_get_testimonial_categories' );

add_filter( 'script_loader_src', 'yit_remove_script_version', 99, 1 );
add_filter( 'style_loader_src', 'yit_remove_script_version', 99, 1 );


add_filter( 'wp_head', 'yit_body_background' );

add_action( 'yit_searchform', 'yit_searchform', 10 );



if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {

    /**
     * organizzati per macroaree:
     *
     * - head
     * - header
     * - primary
     *   - content
     *   - sidebar
     * - footer
     */


    /*
     * =HEAD=
     */
    add_action( 'wp_head', 'yit_favicon' );
    add_action( 'wp_head', 'yit_change_container_width' );

    add_action( 'wp_head', 'yit_header_background' );
    add_action( 'wp_head', 'yit_add_blog_stylesheet', 0 );
    add_action( 'wp_head', 'yit_og' );

    /*
     * =HEADER=
     */
    add_action( 'yit_header', 'yit_back_to_top', 4 );
    add_action( 'yit_header', 'yit_start_wrapper', 5 );
    add_action( 'yit_header', 'yit_start_header', 20 );
    add_action( 'yit_header', 'yit_header', 30 );
    add_action( 'yit_header_inner', 'yit_logo', 10 );
    add_action( 'yit_header_inner', 'yit_nav', 20 );
    add_action( 'yit_header_inner', 'yit_header_sidebar', 30 );
    add_action( 'yit_header', 'yit_end_header', 100 );
    add_action( 'yit_header', 'yit_map', 150 );

      /*
     * =PRIMARY=
     */

    add_action( 'yit_primary', 'yit_start_primary', 5 );
    add_action( 'yit_primary', 'yit_end_primary', 90 );

    // content
    add_action( 'yit_primary', 'yit_primary_content', 10 );
    add_action( 'yit_primary', 'yit_primary_sidebar_two', 20 );
    add_action( 'yit_primary', 'yit_primary_sidebar', 30 );

    // loop
    add_action( 'yit_content_loop', 'yit_content_loop', 10 );

    /*
     * =FOOTER=
     */
    add_action( 'yit_footer', 'yit_footer', 10 );
    add_action( 'yit_footer_big', 'yit_footer_big', 20 );
    add_action( 'yit_copyright', 'yit_copyright', 30 );
    add_action( 'yit_footer', 'yit_end_wrapper', 90 );

    /*
     * =WIDGET=
     */
    add_filter( 'widget_title', 'yit_decode_title' );
    add_filter( 'widget_text', 'do_shortcode' );


    /* widget categories */

    add_filter( 'widget_categories_args', 'yit_exclude_categories_list_widget' );
    add_filter( 'widget_categories_dropdown_args', 'yit_exclude_categories_list_widget' );


    /*
     * PAGE
     */

    add_action( 'yit_404', 'yit_404', 10 );
}
