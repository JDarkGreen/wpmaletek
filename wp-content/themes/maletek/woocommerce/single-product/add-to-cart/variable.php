<?php
/**
 * Variable product add to cart
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $post;
?>

<!-- Vamos a hacer una variacion del codigo para que nos muestre las variaciones del producto en forma de botones para enviarlos
correctamente a la seccion del carrito de compras  -->

<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<!-- Vamos a modificar esta plantilla para que tambien nos de las opciones de elegir entre el tipo de cierre que 
	pueda tener el prducto 	 -->

	<div class="clearfix"></div> <!-- /clearfix -->
		
	<section class="section__type-closueres">
		
		<form class="variations_form" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
				
			<?php if ( ! empty( $available_variations ) ) : ?>

				<table class="variations pull-right" cellspacing="0">

					<thead>
						<tr>
							<th>
								<h3 class="section__type-closueres--title text-uppercase text-center">
									<?php _e('Tipo de Cierre', THEMEDOMAIN) ?>
								</h3>
							</th>
						</tr>
						<tr>
							<th>
								<h4 class="text-center hidden-xs">
									<?php _e('Selecciona tu tipo de cierre'); ?>
								</h4>
							</th>
						</tr>
					</thead> <!-- /thead -->

					<tbody>
						<?php $loop = 0; foreach ( $attributes as $name => $options ) : $loop++; ?>
						<tr>
							<!--td class="label"><label for="<?php echo sanitize_title( $name ); ?>"><?php echo wc_attribute_label( $name ); ?></label></td-->
							<td class="value">

								<!-- Mobile -->

								<select id="<?php echo esc_attr( sanitize_title( $name ) ); ?>" name="attribute_<?php echo sanitize_title( $name ); ?>" data-attribute_name="attribute_<?php echo sanitize_title( $name ); ?>" class="center-block visible-xs-block dropdown-red">

									<?php 
										if ( is_array( $options ) ) {

											if ( isset( $_REQUEST[ 'attribute_' . sanitize_title( $name ) ] ) ) {
												$selected_value = $_REQUEST[ 'attribute_' . sanitize_title( $name ) ];
											} elseif ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) {
												$selected_value = $selected_attributes[ sanitize_title( $name ) ];
											} else {
												$selected_value = '';
											}

											// Get terms if this is a taxonomy - ordered
											if ( taxonomy_exists( $name ) ) {

												$terms = wc_get_product_terms( $post->ID, $name, array( 'fields' => 'all' ) );

												foreach ( $terms as $term ) {
													if ( ! in_array( $term->slug, $options ) ) {
														continue;
													}
													echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $term->slug ), false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
												}

											} else {

												foreach ( $options as $option ) {
													echo '<option value="' . esc_attr( sanitize_title( $option ) ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
												}

											}
										} 	
									?>
								</select>


								<!-- WEB -->
								<!-- Creamos una variable global que almacenara el id del select oculto  -->
								<script> var _sl_clousure_type_ = "<?php echo esc_attr( sanitize_title( $name ) ); ?>" </script>

								<ul id="js-chkbox-type-closeures" data-attribute_name="attribute_<?php echo sanitize_title( $name ); ?>" class="list-inline list-type-closueres hidden-xs">

									<?php  
										/* Desplegaremos las variaciones del producto */
										if ( is_array( $options ) ) {

											// Get terms if this is a taxonomy - ordered
											if ( taxonomy_exists( $name ) ) {
												$terms = wc_get_product_terms( $post->ID, $name, array( 'fields' => 'all' ) );

												$i = 0;
												$variation_array = array();
												$variation_array = $product->get_available_variations();

															//var_dump( $variation_array );

												foreach ( $terms as $term ) {
													if ( ! in_array( $term->slug, $options ) ) {
														continue;
													}

													$image_html = s8_get_taxonomy_image( $term , array(200,210)); 
													$atribute   = "attribute_" . sanitize_title( $name );

													echo '<li><a href="#" data-attr="'. $variation_array[$i]['attributes'][$atribute] .'"><figure>' . $image_html . '</figure><p>'. $term->name . '<span>' . $term->description  . '</span></p></a></li>';

													$i++;
												}
											}
										} 
									?>

								</ul> <!-- /js-chkbox-type-closeures -->

								<?php
									if ( sizeof( $attributes ) === $loop ) {
										//echo '<a class="reset_variations" href="#reset">' . __( 'Clear selection', 'woocommerce' ) . '</a>';
									} 
								?>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>

				</table>

				<div class="clearfix"></div>

			<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

			<!-- Quitamos la propiedad none para mostrar el boton de agregar el carrito -->
			<!--div class="single_variation_wrap" style="display:none;"-->
			<div class="single_variation_wrap">
				<?php do_action( 'woocommerce_before_single_variation' ); ?>

				<div class="single_variation"></div>

				<!-- Add pdf content -->
				<?php 
					/* ID of page shop for display metabox mb_issuu */
					$theid     = woocommerce_get_page_id('shop');
					$linkissuu = get_post_meta($theid , 'mb_issuu'); 

				if (count($linkissuu) > 0 && $linkissuu[0] != '') : ?>
					<!-- content-pdf -->
					<div class="content-pdf content-pdf__cart">
						<figure>
							<a href="<?php echo $linkissuu[0]; ?>" target="_blank">
								<img src="<?php echo IMAGES; ?>/page-pdf-mobile.png" class="visible-xs-block" />
								<img src="<?php echo IMAGES; ?>/page-pdf.png" class="hidden-xs" />
								<span class="hidden-xs">Ver catálogo <br/> Maleték <strong><u>aquí</u></strong></span>
							</a>
						</figure>
					</div> <!-- /page-content-pdf -->
				<?php endif; ?>
			<!-- END PDF CONTENT -->

			<div class="variations_button">
				<?php woocommerce_quantity_input(); ?>

				<button type="submit" class="single_add_to_cart_button cart_button hidden-xs">
					<?php echo $product->single_add_to_cart_text(); ?>
				</button>
			</div>
			
			<button type="submit" class="single_add_to_cart_button button alt cart_button visible-xs-block">
				<?php echo $product->single_add_to_cart_text(); ?>
			</button>

			<input type="hidden" name="add-to-cart" value="<?php echo $product->id; ?>" />
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
			<input type="hidden" name="variation_id" class="variation_id" value="" />

			<?php do_action( 'woocommerce_after_single_variation' ); ?>
		</div>

			<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

			<?php else : ?>

			<p class="stock out-of-stock">
				<?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?>
			</p>

			<?php endif; ?>
		</form> <!-- /form -->

		<!-- Desplegamos el mensaje de confirmacion -->
		<?php  wc_print_notices(); ?>

	</section>


<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
