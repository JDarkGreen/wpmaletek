<?php  

/*********************************************************************************/
/*Archivo que muestra los diferentes tipos de configuraciones de acuerdo al rango*/
/*********************************************************************************/

//Argumentos para hacer la consulta

$model_producto = get_the_title();  //el producto actual donde se encuentra


//conseguimos el slug primer atributo de la taxonomia rango 
$tax_rango = get_terms( "pa_rango" , array( "hide_empty" => false ) );
//$model_rango = "9-12";
$model_rango = $tax_rango[0]->slug;


//Array vacio donde se almacenarán todos los id de los terminos los cuales estan filtrados 
//por el producto y el rango.
$array_id_tax = [];

//Conseguimos todos los id de los terminos de tanonomia modelos creadas y la comparamos con nuestros 
// argumentos si son iguales entonces guardamos su id en otro array

$taxonomy = "pa_modelos";
$args     = array(
	'hide_empty' => false, 
);

$array_modelos = get_terms( $taxonomy , $args );

foreach ($array_modelos as $modelo ) {
	$t_ID = $modelo->term_id; //conseguimos el id
	$term_data = get_option("taxonomy_$t_ID"); //asignamos a la taxonomia ubicada en tabla wp_options

	$product = $term_data['texto01']; //conseguimos el producto
	$rango  =  $term_data['texto02']; //conseguimos el rango 

	//Hacemos la comparacion y si es verdadera agregamos al nuevo array 
	if ( ($product == $model_producto)  && ($rango == $model_rango) ) {
		array_push( $array_id_tax , $t_ID );
	}
}

//Hacemos el var_dump del array
//var_dump($array_id_tax);

?>




<section class="sec__configurations-products">
	
	<h2 class="sec__configurations-products__title text-uppercase">
		<span>tipos de <strong>configuraciones</strong></span>
	</h2>

	<!-- Contenedor que almacena los articulos -->
	<div id="js-sec__configurations-products__content">
		
		<?php if (!empty($array_id_tax)): ?>

			<?php  for ( $i= 0; $i < count($array_id_tax) ; $i++) {  ?>

				<?php 
					$term = get_term( $array_id_tax[$i] , $taxonomy ); 

					//conseguir term ID
					//$t_ID = $term->term_id;
					//$term_data = get_option("taxonomy_$t_ID");
					//echo $t_ID;
					//var_dump($term_data);

					$active = $i == 0 ? "active" : "";
				?>


				<article class="sec__configurations-products__article text-center col-xs-3 <?= $active; ?>">
					<?php 
						//Extraemos la url imagen
						$image   = s8_get_taxonomy_image( $term );  //colocamos el termino
						//Extraemos el título del termino
						$title   = $term->name;
						//Extraemos la descripcion del termino (medidas)
						$medidas = $term->description;
					?>

					<figure>
						<?php echo $image; ?>
					</figure>

					<h3 class="sec__configurations-products__article__title"><?php echo $title; ?></h3>

					<?php if (!empty($medidas)): ?>

						<p class="semibold">Dimensiones</p>
						<p><?php echo $medidas; ?></p>
					
					<?php endif ?>
					
					<hr>
					<button type="submit" class="single_add_to_cart_button cart_button hidden-xs">
						Agregar al cotizador		
					</button>

				</article> <!-- /article -->

			<?php }; ?> 

			<div class="clearfix"></div>
		
		<?php else: ?>
			
			<p> No hay configuraciones disponibles.</p>

		<?php endif; ?>
	
	</div> <!-- /#js-sec__configurations-products__content -->

	<p class="loading">cargando...</p>
 
</section><!-- /section sec__configurations-products -->

