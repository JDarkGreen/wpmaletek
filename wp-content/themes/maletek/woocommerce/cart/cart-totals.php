<?php
/**
 * Cart totals
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>
	
	<!-- Modificando la el titulo para uso del template -->
	<h2 class="cart_totals__title"><?php _e( 'total', THEMEDOMAIN ); ?><span><?php _e(' de pedido', THEMEDOMAIN ); ?></span></h2>

	<table cellspacing="0">
		
		<!-- seccion para el subtotal -->
		<tr class="cart-subtotal">
			<th><?php _e( 'Subtotal pedido', THEMEDOMAIN ); ?></th>
			<td><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>
		
		<!-- seccion envio  -->
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( $code ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php //do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
			<?php //wc_cart_totals_shipping_html(); ?>
			<?php //do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

			<tr class="shipping">
				<th><?php echo _e('Delivery' , THEMEDOMAIN ); ?></th>
				<td><?php wc_cart_totals_shipping_html(); ?></td>
			</tr>

		<?php /* elseif ( WC()->cart->needs_shipping() ) : ?>

			<tr class="shipping">
				<th><?php echo _e('Delivery' , THEMEDOMAIN ); ?></th>
				<td><?php //woocommerce_shipping_calculator(); ?></td>
			</tr> */

		endif; ?>
		
		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<!-- seccion igv  -->
		<?php if ( WC()->cart->tax_display_cart == 'excl' ) : ?>
			<?php if ( get_option( 'woocommerce_tax_total_display' ) == 'itemized' ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<th><?php echo esc_html( $tax->label ); ?></th>
						<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td><?php echo wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<!-- seccion orden total de compra -->

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php _e( 'Total', 'woocommerce' ); ?></th>
			<!--td><?php wc_cart_totals_order_total_html(); ?></td-->
			<!-- Crear una nueva funcion que permita obtener el total de la compra con el igv incluido 
			 formateando la funcion anterior para quitarle los estilos y colocar de acuerdo a la plantila -->
			<td><?php 
				echo WC()->cart->get_total();

				 // Si los precios tiene el igv incluido
  				if ( wc_tax_enabled() && WC()->cart->tax_display_cart == 'incl' ) {
    				$tax_string_array = array();
    			if ( get_option( 'woocommerce_tax_total_display' ) == 'itemized' ) {
      				foreach ( WC()->cart->get_tax_totals() as $code => $tax )
        			$tax_string_array[] = sprintf( '%s %s', $tax->formatted_amount, $tax->label );
    			} else {
      				$tax_string_array[] = sprintf( '%s %s', wc_price( WC()->cart->get_taxes_total( true, true ) ), WC()->countries->tax_or_vat() );
    			}

    			if ( ! empty( $tax_string_array ) )
      				echo '<small class="includes_tax">' . sprintf( __( '(Includes %s)', 'woocommerce' ), implode( ', ', $tax_string_array ) ) . '</small>';
  				} ?>
			</td>
		</tr>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	</table>

	<?php if ( WC()->cart->get_cart_tax() ) : ?>
		<p><small><?php

			$estimated_text = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
				? sprintf( ' ' . __( ' (taxes estimated for %s)', 'woocommerce' ), WC()->countries->estimated_for_prefix() . __( WC()->countries->countries[ WC()->countries->get_base_country() ], 'woocommerce' ) )
				: '';

			printf( __( 'Note: Shipping and taxes are estimated%s and will be updated during checkout based on your billing and shipping information.', 'woocommerce' ), $estimated_text );

		?></small></p>
	<?php endif; ?>
	
	<!-- Seccion checkout  -->
	<div class="wc-proceed-to-checkout">
		
		<!-- Reemplazamos esta accion para cambiar el texto, donde tambien le damos estilos al boton de proceder a checkout 
		<?php // do_action( 'woocommerce_proceed_to_checkout' ); ?>  no olvidar agregar el global woocommerce es importante -->
		<?php global $woocommerce; ?>
		<a class="checkout-button button alt wc-forward text-uppercase" href="<?php echo $woocommerce->cart->get_checkout_url(); ?>">
			<?php _e('realizar pago' , THEMEDOMAIN ); ?>
		</a>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
