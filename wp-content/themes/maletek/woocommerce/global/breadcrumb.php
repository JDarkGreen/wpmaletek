<?php
/**
 * Shop breadcrumb
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $breadcrumb ) {

	echo $wrap_before;


	foreach ( $breadcrumb as $key => $crumb ) {

		echo $before;

		// esta parte está modificada para propósitoss de visualización donde * es la linea de modificacion
		// si se encuentra con una categoría "producto" no la mostraremos 

		if ( $crumb[0] !== "productos" ) {   //*

			if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) {
				echo '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>';
			} else {
				echo '<span class="last-breadcrumb">' . esc_html( $crumb[0] ) . '<span>';
			}

			echo $after;

			if ( sizeof( $breadcrumb ) !== $key + 1 ) {
				echo $delimiter;
			}

		} //*

	}

	echo $wrap_after;

}
