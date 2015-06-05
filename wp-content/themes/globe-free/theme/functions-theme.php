<?php
/**
 * Your Inspiration Themes
 *
 * @package WordPress
 * @subpackage Your Inspiration Themes
 * @author Your Inspiration Themes Team <info@yithemes.com>
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */


/**
 * Set up all theme data.
 *
 * @return void
 * @since 1.0.0
 */
function yit_setup_theme() {

    /**
     * Set up the content width value based on the theme's design.
     *
     * @see yit_content_width()
     *
     * @since Twenty Fourteen 1.0
     */
    if ( ! isset( $GLOBALS['content_width'] ) ) {
        $GLOBALS['content_width'] = apply_filters( 'yit-container-width-std', 1170 );
    }

    //This theme have a CSS file for the editor TinyMCE
    add_editor_style( 'css/editor-style.css' );

    //This theme support post thumbnails
    add_theme_support( 'post-thumbnails' );

    //This theme uses the menus
    add_theme_support( 'menus' );

    //Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    //This theme support post formats
    add_theme_support( 'post-formats', apply_filters( 'yit_post_formats_support', array( 'audio', 'video', 'quote' ) ) );

    if ( ! defined( 'HEADER_TEXTCOLOR' ) )
        define( 'HEADER_TEXTCOLOR', '' );

    // The height and width of your custom header. You can hook into the theme's own filters to change these values.
    // Add a filter to twentyten_header_image_width and twentyten_header_image_height to change these values.
    define( 'HEADER_IMAGE_WIDTH', apply_filters( 'yiw_header_image_width', 1170 ) );
    define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'yiw_header_image_height', 410 ) );

    // Don't support text inside the header image.
    if ( ! defined( 'NO_HEADER_TEXT' ) )
        define( 'NO_HEADER_TEXT', true );

    //This theme support custom header
    add_theme_support( 'custom-header' );

    //This theme support custom backgrounds
    add_theme_support( 'custom-backgrounds' );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list',
    ) );

    // We'll be using post thumbnails for custom header images on posts and pages.
    // We want them to be 940 pixels wide by 198 pixels tall.
    // Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
    // set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );
    $image_sizes = array(
        'blog_small'                         => array( 264, 264, true ),
        'blog_single_big'                    => array( 1138, 547, true ),
        'blog_section'                       => array( 116, 84, true ),
        'blog_widget_compact'                => array( 43, 58, true ),
        'testimonial_thumb'                  => array( 58, 58, true ),
    );


    $image_sizes = apply_filters( 'yit_add_image_size', $image_sizes );

    foreach ( $image_sizes as $id_size => $size ) {
        add_image_size( $id_size, apply_filters( 'yit_' . $id_size . '_width', $size[0] ), apply_filters( 'yit_' . $id_size . '_height', $size[1] ), isset( $size[2] ) ? $size[2] : false );
    }

    //Set localization and load language file
    $locale = get_locale();
    $locale_file = YIT_THEME_PATH . "/languages/$locale.php";
    if ( is_readable( $locale_file ) ){
        require_once( $locale_file );
    }



    // Add support to woocommerce
    if ( defined('YIT_IS_SHOP') && YIT_IS_SHOP ) {
        add_theme_support( 'woocommerce' );
    }



    //Register menus
    register_nav_menus(
        array(
            'nav'                => __( 'Main Navigation', 'yit' ),
            'mobile-nav'         => __( 'Mobile Navigation', 'yit' ),
            'copyright_right'    => __( 'Copyright Right', 'yit' ),
            'copyright_left'     => __( 'Copyright Left', 'yit' ),
            'copyright_centered' => __( 'Copyright Centered', 'yit' )
        )
    );


    // Default Sidebar
    register_sidebar( yit_sidebar_args( "Default Sidebar", __( "The default widgets area ready to use.", 'yit' ), 'widget', 'h3' ) );

    //Register footer sidebar
    for( $i = 1; $i <= yit_get_option( 'footer-rows', 0 ); $i++ ) {
        register_sidebar( yit_sidebar_args( "Footer Row $i", sprintf(  __( "The widget area #%d used in Footer section", 'yit' ), $i ), 'widget col-sm-' . ( 12 / yit_get_option( 'footer-columns' ) ), apply_filters( 'yit_footer_sidebar_' . $i . '_wrap', 'h3' ) ) );
    }
}

/**
 * Remove the class no-js when javascript is activated
 *
 * We add the action at the start of head, to do this operation immediatly, without gap of all libraries loading
 */
function yit_detect_javascript() {
    ?>
    <script type="text/javascript">document.documentElement.className = document.documentElement.className.replace( 'no-js', '' ) + ' yes-js js_active js'</script>
<?php
}

/**
 * Adjust content_width value for image attachment template.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return void
 */
function yit_content_width() {
    $full_width = $GLOBALS['content_width'];
    $sidebar_width = $full_width * ( 25 / 100 );   // 25% (col-3)
    $sidebar = YIT_Layout()->sidebars;
    $sidebar = is_array( $sidebar ) ? $sidebar : array( 'layout' => $sidebar );

    if ( 'sidebar-double' == $sidebar['layout'] ) {
        $GLOBALS['content_width'] -= $sidebar_width * 2;
    } elseif ( 'sidebar-no' != $sidebar['layout'] ) {
        $GLOBALS['content_width'] -= $sidebar_width;
    }

    $GLOBALS['content_width'] -= 30;
}
add_action( 'template_redirect', 'yit_content_width' );


/**
 * Register the extra body classes to add in the pages
 *
 * @param array $classes
 *
 * @return array
 * @since 1.0.0
 */
function yit_add_body_class( $classes ) {
    $layout = yit_get_option('general-layout-type');
    $classes[] = $layout . '-layout';

    $classes = yit_detect_browser_body_class( $classes );

    if( is_singular( 'post' ) ){
        $blog_single_type = yit_get_option( 'blog-single-type' );
        $classes[] = empty( $blog_single_type ) ? 'blog-single' : 'blog-single blog-single-' . $blog_single_type;
    }

    if( yit_get_option( 'general-activate-responsive' ) == 'yes' ){
        $classes[] = 'responsive';
    }

    return $classes;
}

/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function yit_post_classes( $classes ) {
    if ( ! post_password_required() && has_post_thumbnail() ) {
        $classes[] = 'has-post-thumbnail';
    }

    return $classes;
}
add_filter( 'post_class', 'yit_post_classes' );

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
if ( ! function_exists( 'yit_wp_title' ) ) {
    function yit_wp_title( $title = '', $sep = '' ) {
        global $paged, $page;

        if ( is_feed() ) {
            return $title;
        }

        // Add the site name.
        $title .= get_bloginfo( 'name' );

        // Add the site description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) ) {
            $title = "$title $sep $site_description";
        }

        // Add a page number if necessary.
        if ( $paged >= 2 || $page >= 2 ) {
            $title = "$title $sep " . sprintf( __( 'Page %s', 'yit' ), max( $paged, $page ) );
        }

        return $title;
    }
}
add_filter( 'wp_title', 'yit_wp_title', 10, 2 );

if( ! function_exists( 'remove_equals_from_special_chars' ) ){
    function remove_equals_from_special_chars( $chars ){

        unset( $chars['/[=\[](.*?)[=\]]/'] );
        $chars[ '/[\[](.*?)[\]]/' ] = '<span class="title-highlight">$1</span>';

        return $chars;
    }
}

// Remove Open Sans that WP adds from frontend

if ( ! function_exists( 'remove_wp_open_sans' ) ) :
    function remove_wp_open_sans() {
        wp_deregister_style( 'open-sans' );
        wp_register_style( 'open-sans', false );
    }

    add_action( 'wp_enqueue_scripts', 'remove_wp_open_sans' );
    // Uncomment below to remove from admin
    // add_action('admin_enqueue_scripts', 'remove_wp_open_sans');
endif;

/**
 * === Start Blog Functions ===
 */

if( ! function_exists( 'yit_add_blog_stylesheet' ) ) {

    /**
     * Register/Enqueue the blog stylesheet
     *
     * @return void
     * @since 2.0.0
     * @author Andrea Grillo    <andrea.grillo@yithems.com>
     */

    function yit_add_blog_stylesheet(){
        $blog = array(
            'src'           => YIT_THEME_ASSETS_URL . '/css/blog.css',
            'enqueue'       => true,
            'registered'    => false
        );

        if( is_page_template( 'blog.php' ) || is_search() || is_singular( 'post' ) || is_home()|| is_archive() || is_category() || is_tag() || yit_is_old_ie() ){
            YIT_Asset()->set( 'style', 'blog-stylesheet', $blog, 'before', 'comment-stylesheet' );
        }
    }
}

if( ! function_exists( 'yit_get_comments_template' ) ){
    /**
     * Get the comments template
     *
     * @return mixed
     * @since 2.0.0
     * @author Andrea Grillo <andrea.grillo@yithems.com>
     */

    function yit_get_comments_template(){
        return include( YIT_PATH . '/comments.php' );
    }
}

//Hide the footer
if( ! function_exists( 'yit_hide_footer' ) ) {

    /**
     * Change the footer type options to hide the footer
     *
     * @return void
     * @since 2.0.0
     * @author Andrea Grillo    <andrea.grillo@yithems.com>
     */
    function yit_hide_footer() {
        return 'none';
    }
}


if( !function_exists('yit_curPageURL') ) {
    /**
     * Retrieve the current complete url
     *
     * @since 1.0
     */
    function yit_curPageURL() {
        $pageURL = 'http';
        if ( isset( $_SERVER["HTTPS"] ) AND $_SERVER["HTTPS"] == "on" )
            $pageURL .= "s";

        $pageURL .= "://";

        if ( isset( $_SERVER["SERVER_PORT"] ) AND $_SERVER["SERVER_PORT"] != "80" )
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        else
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

        return $pageURL;
    }
}
/**
 * === END Blog Functions ===
 */


if( !function_exists( 'yit_excerpt_text' ) ) {
    /**
     * Cut the text
     *
     * @author Andrea Grillo  <andrea.grillo@yithemes.com>
     *
     * @param string $text
     * @param int $excerpt_length
     * @param string $excerpt_more
     * @return string
     * @since 1.0.0
     */
    function yit_excerpt_text( $text, $excerpt_length = 50, $excerpt_more = '' ) {
        $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
        if ( count($words) > $excerpt_length ) {
            array_pop($words);
            $text = implode(' ', $words);
            $text = $text . $excerpt_more;
        } else {
            $text = implode(' ', $words);
        }

        echo $text;
    }
}




if( !function_exists( 'yit_get_registered_nav_menus' ) ) {
    /**
     * Retireve all registered menus
     *
     * @return array
     * @since 1.0.0
     */
    function yit_get_registered_nav_menus() {
        $menus = get_terms( 'nav_menu' );
        $return = array();

        foreach( $menus as $menu ) {
            array_push( $return, $menu->name );
        }

        return $return;
    }
}
if( !function_exists( 'yit_og' ) ) {
    function yit_og(){
        if(  yit_get_option('general-enable-open-graph') == 'no' ) {
            return;
        }

        /**
         * Create the og tag description with properly content, based on the current queried object.
         */
        $queried_object = get_queried_object();

        $ogcontent  = array();
        $ogcontent['site_name'] = get_bloginfo( 'name' );
        $ogcontent['title'] = yit_wp_title();

        // For posts, pages and products
        if( isset( $queried_object->post_type ) ) {
            $post    = get_post( $queried_object->ID );
            $ogcontent['url'] = get_permalink( $post );
            $ogcontent['description'] = $post->post_excerpt ? $post->post_excerpt : wp_trim_words( $post->post_content );


            if( has_post_thumbnail( $post->ID ) ) {
                $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) , 'medium');
                $ogcontent['image'] = $image_url[0];
            }

        } else if( isset( $queried_object->taxonomy ) && $queried_object->taxonomy ) {

            $ogcontent['description'] = $queried_object->description;

            if(  function_exists( 'WC' ) ){
                $term_thumbnail = get_woocommerce_term_meta( $queried_object->term_id, 'thumbnail_id', true );
                $imgs = wp_get_attachment_image_src( $term_thumbnail, 'medium' );
                if( $imgs[0] ){
                    $ogcontent['image'] = $imgs[0];
                }
            }
        }

        // If the taxonomy or post don't have content, use the site description
        if( (is_home() || is_front_page())  && empty( $ogcontent['description'] ) ) {
            $ogcontent['description'] = get_bloginfo( 'description' );
        }

        if( empty( $ogcontent['image'] ) && yit_get_option( 'header-custom-logo' ) == 'yes' && yit_get_option( 'header-custom-logo-image' ) != '' ) {
            $ogcontent['image'] = yit_get_option( 'header-custom-logo-image' );
        }

        $ogcontent['description'] = isset( $ogcontent['description'] ) ? apply_filters( 'yit_og_description', strip_tags(strip_shortcodes(preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $ogcontent['description'])))) : '';

        foreach( $ogcontent as $property => $content ){
            echo '<meta property="og:'. $property.'" content="' . esc_attr($content) . '"/>'."\n";
        }

    }

}

/**
 * SoundCloud functions
 */
if( ! function_exists( 'soundcloud_oembed_params' ) ){
    function soundcloud_oembed_params( $embed, $params ) {
        global $soundcloud_oembed_params;
        $soundcloud_oembed_params = $params;
        return preg_replace_callback( '/src="(https?:\/\/(?:w|wt)\.soundcloud\.(?:com|dev)\/[^"]*)/i', 'soundcloud_oembed_params_callback', $embed );
    }
}

if( ! function_exists( 'soundcloud_oembed_params_callback' ) ){
    function soundcloud_oembed_params_callback( $match ) {
        global $soundcloud_oembed_params;

        // Convert URL to array
        $url = parse_url( urldecode( $match[1] ) );
        // Convert URL query to array
        parse_str( $url['query'], $query_array );
        // Build new query string
        $query = http_build_query( array_merge( $query_array, $soundcloud_oembed_params ) );

        $search  = array( 'show_artwork=0', 'show_artwork=1', 'auto_play=0', 'auto_play=1', 'show_comments=0', 'show_comments=1' );
        $replace = array( 'show_artwork=false', 'show_artwork=true', 'auto_play=false', 'auto_play=true', 'show_comments=false', 'show_comments=true' );

        $query = str_replace( $search, $replace, $query );

        return 'src="' . $url['scheme'] . '://' . $url['host'] . $url['path'] . '?' . $query;
    }
}

if( ! function_exists( 'yit_string_is_serialized' ) ) {
    /**
     * Check if a string is serialized
     *
     * @author   Andrea Grillo  <andrea.grillo@yithemes.com>
     *
     * @param $string
     *
     * @internal param string $src
     * @return bool | true if string is serialized, false otherwise
     * @since    2.0.0
     */
    function yit_string_is_serialized( $string ) {
        $data = @unserialize( $string );
        return ! $data ? $data : true;
    }
}

if( ! function_exists( 'yit_string_is_json' ) ){
    /**
     * Check if a string is json
     *
     * @author   Andrea Grillo  <andrea.grillo@yithemes.com>
     *
     * @param $string
     *
     * @internal param string $src
     * @return bool | true if string is json, false otherwise
     * @since    2.0.0
     */
    function yit_string_is_json( $string ) {
        $data = @json_decode( $string );
        return $data == NULL ? false : true;
    }
}

if( ! function_exists( 'yit_remove_script_version' ) ) {
    /**
     * Remove the script version from the script and styles
     *
     * @author Andrea Grillo  <andrea.grillo@yithemes.com>
     *
     * @param string $src
     * @return string
     * @since 1.0.0
     */
    function yit_remove_script_version( $src ) {
        if( yit_get_option( 'general-remove-scripts-version' ) == 'yes' ) {
            $parts = explode( '?v', $src );
            return $parts[0];
        } else {
            return $src;
        }
    }

}

if ( ! function_exists( 'yit_exclude_categories_list_widget' ) ) {
    /*
     * exclude categories(selected in the theme options) from wordpress widget categories
     *
     * @return void
     * @since 2.0
     * @author Andrea Frascaspata <andrea.frascaspata@yithemes.com>
     */
    function yit_exclude_categories_list_widget($args){
        $cat_args = array('exclude' =>str_replace("-","",yit_get_excluded_categories(2)));
        return array_merge($args,$cat_args);
    }
}



if( ! function_exists( 'yit_get_testimonial_list_array' ) ){
    /**
     * Get the list of testimonials
     *
     * Retrieve an array of testimonials, if the plugin is active
     *
     * @param array
     * @since  2.0.0
     * @param array $default_value | an array with the default value
     * @return Array
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     */
    function yit_get_testimonial_list_array( $default_value = array() ){
        $testimonials_list = array();
        if( function_exists( 'YIT_Testimonial' ) ){
            $testimonials = new WP_Query(
                array(
                    'post_type' => YIT_Testimonial()->testimonial_post_type,
                    'posts_per_page' => -1
                )
            );
            if( ! empty( $testimonials ) ){

                if( ! empty( $default_value ) ){
                    $testimonials_list = $default_value;
                }

                foreach( $testimonials->posts as $testimonial ){
                    $testimonials_list[ $testimonial->ID ] = $testimonial->post_title;
                }
            }else{
                $testimonials_list = false;
            }
        }else {
            $testimonials_list = false;
        }

        return $testimonials_list;
    }
}

if( ! function_exists( 'yit_unregister_faq_widget' ) ){
    /**
     * Unregister Faq Filter Widget
     *
     *
     * @param array
     * @since  2.0.0
     * @return void
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     */
    function yit_unregister_faq_widget(){
        if( class_exists( 'YIT_Faq_Filters' ) ) {
            unregister_widget('YIT_Faq_Filters');
        }
    }
}
add_action( 'widgets_init', 'yit_unregister_faq_widget', 20 );

if( ! function_exists( 'yit_remove_default_shortcodes' ) ){
    /**
     * Remove Swiper Slider Shortcodes from shortcodes list
     *
     *
     * @param array
     * @since  2.0.0
     * @return void
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     */

    function yit_remove_default_shortcodes( $shortcodes_list ){

        unset(
        $shortcodes_list['swiper_products_slider'],
        $shortcodes_list['lastpost'],
        $shortcodes_list['banner'],
        $shortcodes_list['image_banner'],
        $shortcodes_list['review_slider']
        );

        return $shortcodes_list;
    }
}
add_filter( 'yit-shortcode-plugin-init', 'yit_remove_default_shortcodes' );


if( !function_exists( 'yit_theme_admin_enqueue' ) ) {
    /**
     * Add external css stylesheet in backend
     *
     *
     * @return void
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     * @since  2.0
     */
    function yit_theme_admin_enqueue(){
        wp_enqueue_style( 'yit-simple-line-icons', YIT_THEME_ASSETS_URL . '/fonts/Simple-Line-Icons-Webfont/simple-line-icons.css', false, '', 'all' );
    }
}

if( ! function_exists('yit_get_simple_line_icons') ){

    /*
    * return a list of simple line icons
    *
    * @return void
    * @since 2.0
    * @author Emanuela Castorina <emanuela.castorina@yithemes.com>
    */

    function yit_get_simple_line_icons(){
        return array(
            'e000' => 'icon-user-female',
            'e002' => 'icon-user-follow',
            'e003' => 'icon-user-following',
            'e004' => 'icon-user-unfollow',
            'e006' => 'icon-trophy',
            'e010' => 'icon-screen-smartphone',
            'e011' => 'icon-screen-desktop',
            'e012' => 'icon-plane',
            'e013' => 'icon-notebook',
            'e014' => 'icon-moustache',
            'e015' => 'icon-mouse',
            'e016' => 'icon-magnet',
            'e020' => 'icon-energy',
            'e021' => 'icon-emoticon-smile',
            'e022' => 'icon-disc',
            'e023' => 'icon-cursor-move',
            'e024' => 'icon-crop',
            'e025' => 'icon-credit-card',
            'e026' => 'icon-chemistry',
            'e005' => 'icon-user',
            'e007' => 'icon-speedometer',
            'e008' => 'icon-social-youtube',
            'e009' => 'icon-social-twitter',
            'e00a' => 'icon-social-tumblr',
            'e00b' => 'icon-social-facebook',
            'e00c' => 'icon-social-dropbox',
            'e00d' => 'icon-social-dribbble',
            'e00e' => 'icon-shield',
            'e00f' => 'icon-screen-tablet',
            'e017' => 'icon-magic-wand',
            'e018' => 'icon-hourglass',
            'e019' => 'icon-graduation',
            'e01a' => 'icon-ghost',
            'e01b' => 'icon-game-controller',
            'e01c' => 'icon-fire',
            'e01d' => 'icon-eyeglasses',
            'e01e' => 'icon-envelope-open',
            'e01f' => 'icon-envelope-letter',
            'e027' => 'icon-bell',
            'e028' => 'icon-badge',
            'e029' => 'icon-anchor',
            'e02a' => 'icon-wallet',
            'e02b' => 'icon-vector',
            'e02c' => 'icon-speech',
            'e02d' => 'icon-puzzle',
            'e02e' => 'icon-printer',
            'e02f' => 'icon-present',
            'e030' => 'icon-playlist',
            'e031' => 'icon-pin',
            'e032' => 'icon-picture',
            'e033' => 'icon-map',
            'e034' => 'icon-layers',
            'e035' => 'icon-handbag',
            'e036' => 'icon-globe-alt',
            'e037' => 'icon-globe',
            'e038' => 'icon-frame',
            'e039' => 'icon-folder-alt',
            'e03a' => 'icon-film',
            'e03b' => 'icon-feed',
            'e03c' => 'icon-earphones-alt',
            'e03d' => 'icon-earphones',
            'e03e' => 'icon-drop',
            'e03f' => 'icon-drawer',
            'e040' => 'icon-docs',
            'e041' => 'icon-directions',
            'e042' => 'icon-direction',
            'e043' => 'icon-diamond',
            'e044' => 'icon-cup',
            'e045' => 'icon-compass',
            'e046' => 'icon-call-out',
            'e047' => 'icon-call-in',
            'e048' => 'icon-call-end',
            'e049' => 'icon-calculator',
            'e04a' => 'icon-bubbles',
            'e04b' => 'icon-briefcase',
            'e04c' => 'icon-book-open',
            'e04d' => 'icon-basket-loaded',
            'e04e' => 'icon-basket',
            'e04f' => 'icon-bag',
            'e050' => 'icon-action-undo',
            'e051' => 'icon-action-redo',
            'e052' => 'icon-wrench',
            'e053' => 'icon-umbrella',
            'e054' => 'icon-trash',
            'e055' => 'icon-tag',
            'e056' => 'icon-support',
            'e057' => 'icon-size-fullscreen',
            'e058' => 'icon-size-actual',
            'e059' => 'icon-shuffle',
            'e05a' => 'icon-share-alt',
            'e05b' => 'icon-share',
            'e05c' => 'icon-rocket',
            'e05d' => 'icon-question',
            'e05e' => 'icon-pie-chart',
            'e05f' => 'icon-pencil',
            'e060' => 'icon-note',
            'e061' => 'icon-music-tone-alt',
            'e062' => 'icon-music-tone',
            'e063' => 'icon-microphone',
            'e064' => 'icon-loop',
            'e065' => 'icon-logout',
            'e066' => 'icon-login',
            'e067' => 'icon-list',
            'e068' => 'icon-like',
            'e069' => 'icon-home',
            'e06a' => 'icon-grid',
            'e06b' => 'icon-graph',
            'e06c' => 'icon-equalizer',
            'e06d' => 'icon-dislike',
            'e06e' => 'icon-cursor',
            'e06f' => 'icon-control-start',
            'e070' => 'icon-control-rewind',
            'e071' => 'icon-control-play',
            'e072' => 'icon-control-pause',
            'e073' => 'icon-control-forward',
            'e074' => 'icon-control-end',
            'e075' => 'icon-calendar',
            'e076' => 'icon-bulb',
            'e077' => 'icon-bar-chart',
            'e078' => 'icon-arrow-up',
            'e079' => 'icon-arrow-right',
            'e07a' => 'icon-arrow-left',
            'e07b' => 'icon-arrow-down',
            'e07c' => 'icon-ban',
            'e07d' => 'icon-bubble',
            'e07e' => 'icon-camcorder',
            'e07f' => 'icon-camera',
            'e080' => 'icon-check',
            'e081' => 'icon-clock',
            'e082' => 'icon-close',
            'e083' => 'icon-cloud-download',
            'e084' => 'icon-cloud-upload',
            'e085' => 'icon-doc',
            'e086' => 'icon-envelope',
            'e087' => 'icon-eye',
            'e088' => 'icon-flag',
            'e089' => 'icon-folder',
            'e08a' => 'icon-heart',
            'e08b' => 'icon-info',
            'e08c' => 'icon-key',
            'e08d' => 'icon-link',
            'e08e' => 'icon-lock',
            'e08f' => 'icon-lock-open',
            'e090' => 'icon-magnifier',
            'e091' => 'icon-magnifier-add',
            'e092' => 'icon-magnifier-remove',
            'e093' => 'icon-paper-clip',
            'e094' => 'icon-paper-plane',
            'e095' => 'icon-plus',
            'e096' => 'icon-pointer',
            'e097' => 'icon-power',
            'e098' => 'icon-refresh',
            'e099' => 'icon-reload',
            'e09a' => 'icon-settings',
            'e09b' => 'icon-star',
            'e09c' => 'icon-symbol-female',
            'e09d' => 'icon-symbol-male',
            'e09e' => 'icon-target',
            'e09f' => 'icon-volume-1',
            'e0a0' => 'icon-volume-2',
            'e0a1' => 'icon-volume-off',
            'e001' => 'icon-users'
        );
    }
}


/* ============================== */
/* MOBILE HEADER MENU             */
/* ============================== */

function yit_mobile_menu_trigger() {
    ?>
    <!-- HEADER MENU TRIGGER -->
    <div id="mobile-menu-trigger" class="mobile-menu-trigger"><a href="#" data-effect="st-effect-4" class="glyphicon glyphicon-align-justify visible-xs"></a></div>
<?php
}
add_action( 'yit_header_inner', 'yit_mobile_menu_trigger', 5 );


if( ! function_exists( 'yit_initialize_admin_theme' ) ) {
    /**
     * Set admin panel for free version
     *
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     * @return void
     * @since 2.0.0
     */

    function yit_initialize_admin_theme() {
        if( function_exists( 'YIT_Layout' ) ) {
            remove_action( 'after_setup_theme', array( YIT_Layout(), 'activate' ) );
            remove_action( 'admin_menu', array( YIT_Layout(), 'add_setting_page'), 11  );
            remove_action( 'admin_enqueue_scripts', array( YIT_Layout(), 'admin_enqueue_scripts' ) );
        }
    }
}