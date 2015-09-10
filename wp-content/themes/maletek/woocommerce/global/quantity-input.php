<?php
/**
 * Product quantity inputs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!-- Vamos a sobreescribir este template de incrementador de productos por un select para lo cual ocultaremos esto  -->
<?php 
	/*
	<div class="quantity">
		<input type="number" step="<?php echo esc_attr( $step ); ?>" <?php if ( is_numeric( $min_value ) ) : ?> min="<?php echo esc_attr( $min_value ); ?>"<?php endif; ?> <?php if ( is_numeric( $max_value ) ) : ?>max="<?php echo esc_attr( $max_value ); ?>"<?php endif; ?> name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" title="<?php _ex( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>" class="input-text qty text" size="4" />
	</div>
		*/
?>


<!-- Y agregaremos lo siguiente para que las cantidades se muestren en un select -->
<?php 

global $product;

 $defaults = array(
    'input_name'    => 'quantity',
    'input_value'   => '1',
    'max_value'     => apply_filters( 'woocommerce_quantity_input_max', '', $product ),
    'min_value'     => apply_filters( 'woocommerce_quantity_input_min', '', $product ),
    'step'          => apply_filters( 'woocommerce_quantity_input_step', '1', $product ),
    'style'         => apply_filters( 'woocommerce_quantity_style', 'float:left; margin-right:10px;', $product )
);

 	if ( ! empty( $defaults['min_value'] ) )
        $min = $defaults['min_value'];
    else $min = 1;

    if ( ! empty( $defaults['max_value'] ) )
        $max = $defaults['max_value'];
    else $max = 5;

    if ( ! empty( $defaults['step'] ) )
        $step = $defaults['step'];
    else $step = 1;
?>

<?php  if ( is_cart() ) : ?>

	<div class="quantity">
		<div class="dec btn_qty_input" data-qty="-"></div>
		<input type="text" name="<?php echo esc_attr( $input_name ); ?>" title="<?php _ex( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>" class="input-text qty text" size="1" value="<?php echo esc_attr( $input_value ); ?>" data-minvalue="<?php echo esc_attr( $min ); ?>" data-maxvalue="<?php echo esc_attr( $max ); ?>" />
		<div class="inc btn_qty_input" data-qty="+"></div>
	</div>

<?php else : ?>

	<div class="quantity_select">
		
		<label for="<?php echo esc_attr( $input_name ); ?>" class="quantity-label"><?php _e( 'Cantidad' , THEMEDOMAIN ); ?></label>

		<select name="<?php echo esc_attr( $input_name ); ?>" title="<?php _ex( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>" class="qty">
			<?php
				for ( $count = $min; $count <= $max; $count = $count+$step ) {
					if ( $count == $input_value )
						$selected = ' selected';
					else $selected = '';
					echo '<option value="' . $count . '"' . $selected . '>' . $count . '</option>';
				}
			?>
		</select>
	</div>

<?php endif; ?>
