	
	<?php  for ( $i= 0; $i < count($array_id_tax) ; $i++) {  ?>

		<?php 
			$term = get_term( $array_id_tax[$i] , $taxonomy_modelos ); 
		?>

		<option value="<?php echo $term->name; ?>" data-idmodel="<?= $array_id_tax[$i]; ?>"><?php echo $term->name; ?></option>

	<?php }; ?> 
