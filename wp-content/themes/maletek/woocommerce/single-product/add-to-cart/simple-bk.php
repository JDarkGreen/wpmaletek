<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

?>

<?php
	// Availability
	$availability      = $product->get_availability();
	$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';

	echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
?>



<?php if ( $product->is_in_stock() ) : ?>



	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<div class="clearfix"></div>

	<form class="cart" method="post" enctype='multipart/form-data'>
	 	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	 	<!-- Add pdf content -->
		<?php 
			/* ID of page shop for display metabox mb_issuu */
			$theid     = woocommerce_get_page_id('shop');
			$linkissuu = get_post_meta($theid , 'mb_issuu'); 
		?>
		<?php if (count($linkissuu) > 0 && $linkissuu[0] != '') : ?>
			<!-- content-pdf -->
			<div class="content-pdf content-pdf__cart">
				<figure>
					<a href="<?php echo $linkissuu[0]; ?>" target="_blank">
						<img src="<?php echo IMAGES; ?>/page_content_pdf.png" />
						<span>Ver catálogo <br/> Maleték <strong><u>aquí</u></strong></span>
					</a>
				</figure>
			</div> <!-- /page-content-pdf -->
		<?php endif; ?>
		<!-- END PDF CONTENT -->

		<div class="container_add_to_cart">
			<?php if ( ! $product->is_sold_individually() ) : ?>
	 			<label class="quantity-label" for="quantity"> Cantidad </label>
		 	<?php 
		 		woocommerce_quantity_input( array(
		 			'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
		 			'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
		 		) );
		 	 	endif; 
		 	?>

	 		<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

	 		<button type="submit" class="single_add_to_cart_button button alt"><?php echo $product->single_add_to_cart_text(); ?></button>

		</div> <!-- /end container_add_to_cart  -->


		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
