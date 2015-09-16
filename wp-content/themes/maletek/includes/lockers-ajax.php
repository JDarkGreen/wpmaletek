	
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

			<!-- Boton para agregar el producto al carrito  -->
			<button type="submit" class="single_add_to_cart_button cart_button hidden-xs" data-nmodel="<?= $term->name; ?>" data-idmodel="<?= $array_id_tax[$i] ?>">
				<?= $the_message; ?> 		
			</button>

		</article> <!-- /article -->

	<?php }; ?> 

	<div class="clearfix"></div>