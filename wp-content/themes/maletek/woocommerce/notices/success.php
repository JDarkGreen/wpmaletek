<?php
/**
 * Show messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! $messages ){
	return;
}

?>

<?php if( is_product() ) : ?>
	<?php foreach ( $messages as $message ) : ?>
	<div class="woocommerce-message woocommerce-message--red">
		<?php  
			$title = get_the_title();
				echo '<strong><u>' . $title . '</u></strong>' . " " . 'fue agregado satisfactoriamente al carrito';
		?>
		<?php //echo wp_kses_post( $message ); ?>
	</div>
<?php endforeach; endif; ?>
