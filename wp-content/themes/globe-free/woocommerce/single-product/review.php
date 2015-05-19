<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
?>
<li itemprop="review" itemscope itemtype="http://schema.org/Review" <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

	<div id="comment-<?php comment_ID(); ?>" class="comment_container">

		<?php echo get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '60' ), '', get_comment_author() ); ?>

		<div class="comment-text arrow-left">



			<?php if ( $comment->comment_approved == '0' ) : ?>

				<p class="meta"><em><?php _e( 'Your comment is awaiting approval', 'yit' ); ?></em></p>

			<?php else : ?>

				<div class="meta">

                        <p itemprop="author"><?php comment_author(); ?></p> <?php

                        $user = get_user_by('id', $comment->user_id ); // fix notice user not found in db 

						if ( get_option( 'woocommerce_review_rating_verification_label' ) === 'yes' && $user ) 
							if ( wc_customer_bought_product( $comment->comment_author_email, $comment->user_id, $comment->comment_post_ID ) )
								echo '<em class="verified">(' . __( 'verified owner', 'yit' ) . ')</em> ';

					    ?>
                    <?php if ( $rating && get_option( 'woocommerce_enable_review_rating' ) == 'yes' ) : ?>

                    <div class="product-rating">
                            <span class="star-empty">
                                <span class="star" style="width:<?php echo $rating*20; ?>%">
                                </span>
                            </span>
                    </div>

                <?php endif; ?>

                    <time itemprop="datePublished" datetime="<?php echo get_comment_date( 'c' ); ?>"><?php echo get_comment_date( __( get_option( 'date_format' ), 'yit' ) ); ?></time>

                </div>

			<?php endif; ?>

			<div itemprop="description" class="description"><?php comment_text(); ?></div>
		</div>
	</div>
