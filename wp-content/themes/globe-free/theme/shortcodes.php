<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Return the list of shortcodes and their settings
 *
 * @package Yithemes
 * @author  Francesco Licandro  <francesco.licandro@yithemes.com>
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly


$config          = YIT_Plugin_Common::load();
$awesome_icons   = YIT_Plugin_Common::get_awesome_icons();
$simple_line_icons   = yit_get_simple_line_icons();
$animate         = $config['animate'];

$shop_shortcodes = array ();

$theme_shortcodes = array(
    /*================= BLOG SECTION =================*/
    'blog_section' => array(
        'title'              => __( 'Blog', 'yit' ),
        'description'        => __( 'Print a blog section', 'yit' ),
        'tab'                => 'section',
        'has_content'        => false,
        'in_visual_composer' => true,
        'create'             => true,
        'attributes'         => array(
            'nitems'            => array(
                'title'       => __( 'Number of items', 'yit' ),
                'description' => __( '-1 to show all elements', 'yit' ),
                'type'        => 'number',
                'min'         => - 1,
                'max'         => 99,
                'std'         => - 1
            ),
            'ncolumns'          => array(
                'title'       => __( 'Number of columns', 'yit' ),
                'description' => __( 'Select number of columns to show', 'yit' ),
                'type'        => 'select',
                'options'     => array(
                    1 => 'One Column',
                    2 => 'Two Columns',
                    3 => 'Three Columns'
                ),
                'std'         => 1
            ),
            'enable_thumbnails' => array(
                'title' => __( 'Show Thumbnails', 'yit' ),
                'type'  => 'checkbox',
                'std'   => 'yes'
            ),
            'enable_date'       => array(
                'title' => __( 'Show Date', 'yit' ),
                'type'  => 'checkbox',
                'std'   => 'yes'
            ),
            'enable_title'      => array(
                'title' => __( 'Show Title', 'yit' ),
                'type'  => 'checkbox',
                'std'   => 'yes'
            ),
            'enable_author'     => array(
                'title' => __( 'Show Author', 'yit' ),
                'type'  => 'checkbox',
                'std'   => 'yes'
            ),
            'enable_comments'   => array(
                'title' => __( 'Show Comments', 'yit' ),
                'type'  => 'checkbox',
                'std'   => 'yes'
            )
        )
    ),

    /*================= SEPARATOR ================*/
    'separator'    => array(
        'title'              => __( 'Separator', 'yit' ),
        'description'        => __( 'Print a separator line', 'yit' ),
        'tab'                => 'shortcodes',
        'has_content'        => false,
        'create'             => true,
        'in_visual_composer' => true,
        'attributes'         => array(
            'style'         => array(
                'title'   => __( 'Separator style', 'yit' ),
                'type'    => 'select',
                'options' => array(
                    'single' => __( 'Single line', 'yit' ),
                    'double' => __( 'Double line', 'yit' ),
                    'dotted' => __( 'Dotted line', 'yit' ),
                    'dashed' => __( 'Dashed line', 'yit' )
                ),
                'std'     => 'single'
            ),
            'color'         => array(
                'title' => __( 'Separator color', 'yit' ),
                'type'  => 'colorpicker',
                'std'   => '#b5b4b4'
            ),
            'margin_top'    => array(
                'title' => __( 'Margin top', 'yit' ),
                'type'  => 'number',
                'min'   => 0,
                'max'   => 999,
                'std'   => 0
            ),
            'margin_bottom' => array(
                'title' => __( 'Margin bottom', 'yit' ),
                'type'  => 'number',
                'min'   => 0,
                'max'   => 999,
                'std'   => 35
            )
        )
    ),

    /* === MODAL === */
    'modal'        => array(
        'title'              => __( 'Modal Window', 'yit' ),
        'description'        => __( 'Create a modal window', 'yit' ),
        'tab'                => 'shortcodes',
        'in_visual_composer' => true,
        'has_content'        => true,
        'attributes'         => array(
            'title'              => array(
                'title' => __( 'Modal Title', 'yit' ),
                'type'  => 'text',
                'std'   => __( 'Your title here', 'yit' )
            ),
            'opener'             => array(
                'title'   => __( 'Type of modal opener', 'yit' ),
                'type'    => 'select',
                'options' => array(
                    'button' => __( 'Button', 'yit' ),
                    'text'   => __( 'Textual Link', 'yit' ),
                    'image'  => __( 'Image', 'yit' )
                ),
                'std'     => 'button'
            ),
            'button_text_opener' => array(
                'title' => __( 'Text of the button', 'yit' ),
                'type'  => 'text',
                'std'   => __( 'Open Modal', 'yit' ),
                'deps'  => array(
                    'ids'    => 'opener',
                    'values' => 'button'
                )
            ),
            'button_style'       => array(
                'title'   => __( 'Style of the button', 'yit' ),
                'type'    => 'select',
                'options' => array(
                    'normal'      => __( 'Flat', 'yit' ),
                    'alternative' => __( 'Ghost', 'yit' )
                ),
                'std'     => 'normal',
                'deps'    => array(
                    'ids'    => 'opener',
                    'values' => 'button'
                )
            ),
            'link_text_opener'   => array(
                'title' => __( 'Text of the link', 'yit' ),
                'type'  => 'text',
                'std'   => __( 'Open Modal', 'yit' ),
                'deps'  => array(
                    'ids'    => 'opener',
                    'values' => 'text'
                )
            ),
            'link_icon_type'     => array(
                'title'   => __( 'Icon type', 'yit' ),
                'type'    => 'select',
                'options' => array(
                    'none'       => __( 'None', 'yit' ),
                    'theme-icon' => __( 'Theme Icon', 'yit' ),
                    'custom'     => __( 'Custom Icon', 'yit' )
                ),
                'std'     => 'none',
                'deps'    => array(
                    'ids'    => 'opener',
                    'values' => 'text'
                )
            ),
            'link_icon_theme'    => array(
                'title'   => __( 'Icon', 'yit' ),
                'type'    => 'select-icon', // home|file|time|ecc
                'options' => $awesome_icons,
                'std'     => '',
                'deps'    => array(
                    'ids'    => 'link_icon_type',
                    'values' => 'theme-icon'
                )
            ),
            'link_icon_url'      => array(
                'title' => __( 'Icon URL', 'yit' ),
                'type'  => 'text',
                'std'   => '',
                'deps'  => array(
                    'ids'    => 'link_icon_type',
                    'values' => 'custom'
                )
            ),
            'link_text_size'     => array(
                'title' => __( 'Font size of the link', 'yit' ),
                'type'  => 'number',
                'std'   => 17,
                'min'   => 1,
                'max'   => 99,
                'deps'  => array(
                    'ids'    => 'opener',
                    'values' => 'text'
                )
            ),
            'image_opener'       => array(
                'title' => __( 'Url of the image', 'yit' ),
                'type'  => 'text',
                'std'   => '',
                'deps'  => array(
                    'ids'    => 'opener',
                    'values' => 'image'
                )
            ),
        )
    ),

    /* === BOX TITLE === */
    'box_title' => array(
        'title' => __('Box title', 'yit' ),
        'description' =>  __('Show a title centered with line', 'yit' ),
        'tab' => 'shortcodes',
        'in_visual_composer' => true,
        'has_content' => true,
        'attributes' => array(
            'class' => array(
                'title' => __('Class', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'subtitle' => array(
                'title' => __( 'Subtitle', 'yit' ),
                'type' => 'text',
                'std' => ''
            ),

            'subtitle_font_size' => array(
                'title' => __( 'Subtitle Font size (px)', 'yit' ),
                'type' => 'text',
                'min' => 1,
                'max' => 99,
                'std' => 15
            ),
            'font_size' => array(
                'title' => __( 'Title Font size (px)', 'yit' ),
                'type' => 'number',
                'min' => 1,
                'max' => 99,
                'std' => 15
            ),
            'font_alignment' => array(
                'title' => __( 'Font alignment', 'yit' ),
                'type' => 'select',
                'options' => array(
                    'left' => __( 'Left', 'yit' ),
                    'right' => __( 'Right', 'yit' ),
                    'center' => __( 'Center', 'yit' )
                ),
                'std' => 'center'
            ),
            'border' => array(
                'title' => __('Border', 'yit'),
                'type' => 'select',
                'options' => array(
                    'bottom' => __('Bottom', 'yit'),
                    'bottom-little-line' => __('Bottom Little Line', 'yit'),
                    'middle' => __('Middle', 'yit'),
                    'around' => __('Around', 'yit'),
                    'double' => __('Double', 'yit'),
                    'none' => __('none', 'yit')
                ),
                'std'  => 'middle'
            ),
            'border_color' => array(
                'title' => __('Border Color', 'yit'),
                'type' => 'colorpicker',
                'std'  => '#e1e1e1'
            ),
            'animate' => array(
                'title' => __('Animation', 'yit'),
                'type' => 'select',
                'options' => $animate,
                'std'  => ''
            ),
            'animation_delay' => array(
                'title' => __('Animation Delay', 'yit'),
                'type' => 'text',
                'desc' => __('This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit'),
                'std'  => '0'
            )        )
    ),

);


if ( function_exists( 'WC' ) ) {
    $shop_shortcodes = array(

    );
}
return ! empty( $shop_shortcodes ) ? array_merge( $theme_shortcodes, $shop_shortcodes ) : $theme_shortcodes;