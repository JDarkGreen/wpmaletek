<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

	<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<!--table class="shop_table cart" cellspacing="0"-->
		<table class="cart shop_table hidden-xs" cellspacing="0">
			<thead>
				<tr>
					<th class="product-thumbnail">&nbsp;</th>
					<th class="product-name"><?php _e( 'Producto',THEMEDOMAIN ); ?></th>
					<th class="product-quantity"><?php _e( 'Modulos',THEMEDOMAIN ); ?></th>
					<th class="product-closure-type"><?php _e( 'Tipo de Cierre' ,THEMEDOMAIN ); ?></th>
					<th class="product-remove">
						<input id="update_cart" type="submit" class="button" name="update_cart" value="Actualizar carrito" />
						<?php wp_nonce_field( 'woocommerce-cart' ); ?>
					</th>
				</tr>
			</thead>

			<tbody>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						?>
						<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> ">
							
							<!-- Miniatura del producto -->
							<td class="product-thumbnail">
								<?php
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

									if ( ! $_product->is_visible() )
										echo $thumbnail;
									else
										printf( '<a href="%s">%s</a>', $_product->get_permalink( $cart_item ), $thumbnail );
								?>
							</td>	
							
							<!-- Nombre del producto -->
							<td class="product-name">
								<?php
									if ( ! $_product->is_visible() )
										echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
									else
										echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s </a>', $_product->get_permalink( $cart_item ), $_product->get_title() ), $cart_item, $cart_item_key );

									//Post data -- agregar la descripcion corta del producto 
									echo apply_filters( 'woocommerce_short_description', $_product->post->post_excerpt );

		               				// Backorder notification
		               				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
		               					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
								?>
							</td>
							
							<!-- Cantidad del producto -->
							<td class="product-quantity">
								<?php
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input( array(
											'input_name'  => "cart[{$cart_item_key}][qty]",
											'input_value' => $cart_item['quantity'],
											'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
											'min_value'   => '0'
										), $_product, false );
									}

									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
								?>
							</td>
							
							<!-- campo para mostrar el tipo de cierre o variaciones de cada producto  -->
							<td class="product-closure-type">	
								<?php
									/*Conseguimos los terminos de  taxonomía de "tipo de cierre para obtener las variaciones del producto "*/

									$tax_cierre   = "pa_tipo-de-cierre";
									$array_terms  = array( "hide_empty" => false);
									$terms_cierre = get_terms( $tax_cierre , $array_terms );

									//Obtenemos el termino tipo de cierre del producto seleccionado
									$the_cart_item_cierre =  $cart_item['cierre'];

								?>

								<select name="select_variation_<?php echo $cart_item['product_id'] ?>" id="select_variation_<?php echo $cart_item['product_id'] ?>">
									<?php foreach ( $terms_cierre as $term_cierre ) : ?>
										<option value="<?php echo $term_cierre->slug ?>" <?php if( $term_cierre->name == $the_cart_item_cierre ){ echo 'selected'; } ?>>
											<?php echo $term_cierre->name ?>
										</option>
									<?php endforeach; ?>
								</select>

							</td>

							<!-- campo para remover o eliminar productos de la lista del carrito de compra  -->
							<td class="product-remove">
								<?php
								//Reemplazar la clase remove por item-remove
									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="item-remove" title="%s">Quitar de la lista</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
								?>
							</td>
						</tr>

						<?php

							$variation = explode( "-", $selected_variation);
							$variation = implode( " ", $variation);

							//Almacenamiento de datos en array
							$array_data_cart[] = array(
								'name' => $_product->get_title(),
								'var'  => $variation,	
								'cant' => $cart_item['quantity'],
							);
					} 
				}

				do_action( 'woocommerce_cart_contents' );
				?>
				
				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</tbody>
		</table>

		<!-- Panel para version mobile -->
		<div class="panel-group panel-group-product panel-group-cart visible-xs-block" id="accordion-cart" role="tablist" aria-multiselectable="true">
		
			<?php
				$i = 0;
				
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );


					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
			?>

			<div class="panel panel-default panel-product">
					
				<?php $colap = !( $i == 0 ) ? 'collapsed' : '' ; ?>

        		<a data-toggle="collapse" data-parent="#accordion-cart" href="<?php echo '#acordeon' . $i; ?>" aria-expanded="true" aria-controls="<?php echo 'acordeon' . $i; ?>" class="<?php echo $colap; ?>">

          			<?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;'; ?>
        		</a> 

      			<?php $in = ( $i == 0 ) ? 'in' : '' ; ?>

				<div id="<?php echo 'acordeon' . $i; ?>" class="panel-collapse collapse <?php echo ' ' . $in; ?>" role="tabpanel" aria-labelledby="headingOne">
			    	<div class="panel-body">
			        	
						<figure class="content-thumbnail-product">
							<?php 
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key ); 
								echo $thumbnail;
							?>
						</figure> <!-- /content-thumbnail-product -->
						
						<div class="quantity_select">
							<?php  
								global $product;
								$defaults = array(
									'input_name'    => "select[{$cart_item_key}][qty]",
									'input_value'   => $cart_item['quantity'],
									'max_value'     => apply_filters( 'woocommerce_quantity_input_max', '', $product ),
									'min_value'     => apply_filters( 'woocommerce_quantity_input_min', '', $product ),
									'step'          => apply_filters( 'woocommerce_quantity_input_step', '', $product ),
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

							<select name="<?php echo esc_attr( $defaults['input_name'] ); ?>" title="<?php _ex( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>" class="qty" data-cart="<?php echo "cart[{$cart_item_key}][qty]";  ?>">
								<?php
									for ( $count = $min; $count <= $max; $count = $count+$step ) {
										if ( $count ==  $defaults['input_value'] )
											$selected = ' selected';
										else $selected = '';
										echo '<option value="' . $count . '"' . $selected . '>' . $count . '</option>';
									}
								?>
							</select>
						</div> <!-- /quantity_select  -->

						<div class="product-closure-type">
							<?php 
								$all_taxs = get_taxonomies();
								//var_dump( $all_taxs );
								$findme   = 'pa_';

								foreach ($all_taxs as $all_tax ) {
									if ( preg_match( '/'.$findme.'/' , $all_tax) ) {
										$name_tax = $all_tax;
									}
								}
								
								$terms = wc_get_product_terms( $product_id , $name_tax , array( 'fields' => 'all' ) );

								$selected_variation = $cart_item['variation']['attribute_'.$name_tax];
								?>

								<select name="select_variation_<?php echo $cart_item['variation_id'] ?>" id="select_variation_<?php echo $cart_item['variation_id'] ?>" class="dropdown-red">
									<?php foreach ( $terms as $term ) : ?>
									<option value="<?php echo $term->slug ?>" <?php if( $term->slug === $selected_variation ){ echo 'selected'; } ?>>
										<?php echo $term->name ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>

			      	</div> <!--/panel-body  -->
    			</div> <!-- /collapseOne  -->
  			</div> <!-- /panel panel-default -->

			<?php } $i++; }  ?>
		</div> <!-- /panel-group-product -->

		<?php do_action( 'woocommerce_after_cart_table' ); ?>

	</form>


	


