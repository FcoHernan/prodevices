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
 * Cat Admin View
 *
 * @package	Yithemes
 * @author Antonino Scarfi' <antonino.scarfi@yithemes.com>
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$cats = get_categories( 'orderby=name&use_desc_for_title=1&hierarchical=1&style=0&hide_empty=0' );

$class = $descr = $ext = '';
$nr_cols = 1;

$show_heads = false;
if ( isset( $cols ) && $cols ) {
    if ( isset( $heads ) ) {
        $show_heads = true;
    }

    $nr_cols = $cols;
    $class   = ' small';
}

$checked_items = yit_get_option( $id );

?>

<div id="<?php echo $id ?>-container" <?php if ( isset($deps) ): ?>data-field="<?php echo $id ?>" data-dep="<?php echo $deps['ids'] ?>" data-value="<?php echo $deps['values'] ?>" <?php endif ?>class="<?php echo isset( $disabled ) && 'yes' == $disabled ? 'disabled ' : '' ?>yit_options rm_option rm_input rm_multi_checkbox">
    <label for="<?php echo $id ?>"><?php echo $name ?><?php if ( $nr_cols > 1 ): ?>
            <small><?php echo $desc ?></small><?php endif ?></label>

    <?php for ( $i = 1; $i <= $nr_cols; $i ++ ) : $ext = ( $nr_cols > 1 ) ? "$i" : '' ?>
        <ul id="<?php echo( $id . $ext ); ?>" class="list-sortable<?php echo $class ?>">

            <?php

            if ( $show_heads && isset( $heads[ $i -1 ] ) ) {
                echo '<li class="head">' . $heads[$i - 1] . '</li>';
            }

            $c = 0;
            foreach ( $cats as $cat ) {
                $checked = isset( $checked_items[$i] ) ? $checked_items[$i] : array(); ?>

                <li>
                    <label class="radio-inline">
                        <input type="checkbox" class="checkbox" name="<?php yit_field_name( $id ); ?>[<?php echo $i ?>][]" value="<?php echo $cat->cat_ID; ?>" <?php checked( in_array( $cat->cat_ID, $checked ), true ); ?> id="<?php echo( $id ); ?>-<?php echo $c . $ext ?>" />&nbsp;
                        <?php echo $cat->cat_name; ?>
                    </label>
                </li>
                <?php $c ++;
            }  ?>
        </ul>

    <?php endfor ?>

    <?php if ( $nr_cols == 1 ): ?>
        <small><?php echo $desc ?></small><?php endif ?>
    <div class="clear"></div>
</div>
