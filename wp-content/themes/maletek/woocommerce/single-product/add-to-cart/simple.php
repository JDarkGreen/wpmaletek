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

<div class="clearfix"></div>

<?php if ( $product->is_in_stock() ) : ?> <!-- Si el producto está en stock (agregar un precio )-->

<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?> 

<!-- Sección que contiene 
	* tipo de attributo - cantidad de puertas
	* tipo de atributo  - tipos de cierres
-->

<section class="section__filter-product">
	
	<form class="variations_form" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
		
		<div class="number-doors col-xs-6 col-xs-offset-6 text-center">

			<p class="mnu-number-doors__label">Cantidad de Puertas</p>

			<!-- Menu que contiene los rangos  -->
			<ul id="id_rango" class="mnu-number-doors__menu">
				<?php  
					$tax_rango   = "pa_rango"; //tipo de taxonomia rango

					$args = array(
						'parent'        => 0, //solo los padres
					    'hide_empty'    => false   //mostrar terminos vacios
					);

					$terms_rango = get_terms( $tax_rango, $args ); //array de terminos con argumentos

					//Conseguimos el slug del primer termino
					$first_rango      = $terms_rango[0];
					$first_rango_slug = $first_rango->slug;
				?>


				<a id="js-mnu-number-doors__menu" class="js-mnu-number-doors__menu" href="#" data-product="<?= get_the_title(); ?>" data-message="<?= $product->single_add_to_cart_text(); ?>"><?= $first_rango_slug; ?>
				</a>

				<ul>
				<?php  
					foreach ($terms_rango as $term_rango ) : 
					//Ocultar si es el primer rango
					$mostrar = $term_rango->slug == $first_rango_slug ? "hide" : "";
				?>
					<li class="<?= $mostrar; ?>">
						<a href="#" data-rango="<?= $term_rango->slug?>"><?= $term_rango->slug ?></a>
					</li>
				<?php endforeach; ?>
				</ul>
			</ul>
			
		</div><!-- /number-doors -->

		<section class="flexbox__end">

		<!-- Agregamos pdf -->
		<section class="col-xs-6 text-center">
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
		</section>

		<!-- Tabla para organizar las variaciones  -->
		<table class="variations pull-right" cellspacing="0">
			<thead>
				<tr>
					<th>
						<h3 class="section__filter-product--title text-uppercase text-center">
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
				
				<tr>
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
										echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $term->slug ), false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
									}

								} else {

									foreach ( $options as $option ) {
										echo '<option value="' . esc_attr( sanitize_title( $option ) ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
									}

								}
							} 	
							?>
						</select>

						<!-- Creamos una variable global que almacenara el id del select oculto  -->
						<script> var _sl_clousure_type_ = "<?php echo esc_attr( sanitize_title( $name ) ); ?>" </script>

						<ul id="js-chkbox-type-closeures" data-attribute_name="attribute_<?php echo sanitize_title( $name ); ?>" class="list-inline list-type-closueres hidden-xs">

							<?php  
								/* Aquí desplagamos las variaciones del producto */
								global $post;
								$tax_var = "pa_tipo-de-cierre"; //taxonomia tipo de cierre

								if ( taxonomy_exists( $tax_var ) ) { //si la taxonomia existe
									$terms = wc_get_product_terms( $post->ID, $tax_var , array( 'fields' => 'all' ) );
									
									foreach ( $terms as $term ) {

										$image_html = s8_get_taxonomy_image( $term , array(200,210)); 
										$atribute   = "attribute_" . sanitize_title( $name );

										echo '<li><a href="#" data-attr="'. $term->name .'"><figure>' . $image_html . '</figure><p>'. $term->name . '<span>' . $term->description  . '</span></p></a></li>';
									}
								}
							?>


						</ul> <!-- /js-chkbox-type-closeures -->
					</td>
				</tr>

			</tbody>
		</table> <div class="clearfix"></div>
		
		</section> <!-- flexbox__end -->


		<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

		<!-- Desplegamos el mensaje de confirmacion -->
		<?php  wc_print_notices(); ?>

		<?php
			//Incluimos o mostramos la configuraciones por cada producto de acuerdo al rango
			include_once('/../../include/show-configurations.php');  
		?>

		<!-- Estos inputs contienen los parametros que se enviaran al cotizador no borrar  -->
		<input type="hidden" name="add-to-cart" value="<?php echo $product->id; ?>" />
		<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
		<input type="hidden" name="variation_id" class="variation_id" value="" />
		
		<!-- Estas son las nuevas modificaciones y campos agregados para enviar al carrito -->

		<!-- Input cierre -->
		<?php  
			//Conseguimos el primer termino del attributo tipo de cierre
			$first_tipo_cierre = $terms[0];
		?>

		<input id="input-tipo-cierre" type="hidden" name="cierre" value="<?= $first_tipo_cierre->name; ?>" />

		<!-- Input rango  -->
		<input id="input-rango" type="hidden" name="rango" value="<?= $first_rango_slug;  ?>" />
		
		<!-- Input imagen miniatura  -->
		<input id="input-img-model" type="hidden" name="img_modelo" value="<?= htmlentities($first_model_img , ENT_COMPAT,'UTF-8' ); ?>" />
		
		<!-- Input id Modelo -->
		<input id="input-id-modelo" type="hidden" name="id_modelo" value="<?= $first_model_id; ?>" />
	
		<!-- Input Modelo -->
		<input id="input-modelo" type="hidden" name="modelo" value="<?= $first_model_name; ?>" />
		
		<!-- Input valor todas las configuraciones  -->
		<input id="configurations" type="hidden" name="configurations" value="<?= htmlentities($configurations_html) ?>" />

	</form><!-- /form -->

</section> <!-- /section__config-product  -->

<div class="clearfix"></div>


<?php endif; ?>