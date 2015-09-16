<?php global $wpo_wcpdf; ?>

<!-- Cabecera del documento  -->
<header class="header-order">
	<?php
		//Si hay un logo id 
		if( $wpo_wcpdf->get_header_logo_id() ) {
			$wpo_wcpdf->header_logo();
		} 
		else {
						//Si no mostrar "Albarán de Entrega" que es la traducción  
			echo apply_filters( 'wpo_wcpdf_packing_slip_title', __( 'Packing Slip', 'wpo_wcpdf' ) );
		}
	?>	
</header>

<hr>


<!-- Encabezado del documento Lo ocultamos -->
<!--h1 class="document-type-label">
	<?php/* 
		if( $wpo_wcpdf->get_header_logo_id() ) 
			echo apply_filters( 'wpo_wcpdf_packing_slip_title', __( 'Packing Slip', 'wpo_wcpdf' ) ); 
			echo "ok"; */
	?>
</h1-->


<!-- Importamos todos la informacion antes de la orden de compra /detalles -->
<?php do_action( 'wpo_wcpdf_before_order_details', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>




<section>
	<!-- Conseguimos todos los detalles  -->
	<?php 
		$items = $wpo_wcpdf->get_order_items(); 

		if( sizeof( $items ) > 0 ) : 

		foreach( $items as $item_id => $item ) : 
	
		//Obtenemos toda la data del producto y lo almacenamos en la variable
		//product 
		$_product = $item['product'];
	?>
	
	<!-- Mostramos el nombre del producto y su respectivo modelo : -->
	<h2>
		<?php  
			$name    = $item['name']; //titulo o nombre del producto 
			$_modelo = $item['item']['item_meta']['modelo'][0];	//conseguimos el modelo
			
			if ( !empty($name) && !empty($_modelo ) ) 
				echo $name . ": " . $_modelo;
		?>
	</h2>

	<br>
	
	<!-- Mostramos la imagen del producto  -->
	<figure class="item-thumbnail">
		<?php  
			//Conseguir la url imagen
			$image = $_product->get_image();
			
			if ( !empty($image) ) {  echo $image; }
		?>
	</figure>

	<br>
	
	<!-- Fecha de orden de compra -->
	<h3> Fecha Orden de Compra: </h3>
	
	<?php
		$export     = $wpo_wcpdf->export;
		$order      = $export->order;
		$order_date = $order->order_date;

		//Una vez obtenido la fecha procedemos a setear los datos dia, mes y anio
		$fecha  =  explode(" ", $order_date );
		$fecha  =  explode('-', $fecha[0] );

		$dia = $fecha[2]; $mes = $fecha[1] ; $anio = $fecha[0];
	?>

	<span>dia: <?= $dia; ?></span> <br>
	<span>mes: <?= $mes; ?></span> <br>
	<span>año: <?= $anio; ?></span> <br>

	<br>

	<!-- 4.- Descripcion del Producto  -->
	<h3> Descripción del Producto </h3>
	<?php  
		$p_description = $_product->post->post_excerpt;

		if ( !empty($p_description) ) { echo $p_description; }
	?>

	<!-- 5.- Tipo de Cierre -->
	<h3> Tipo de Cierre </h3>
	<?php  
		$_cierre = $item['item']['item_meta']['cierre'][0];	//conseguimos el tipo de cierre
		if ( !empty($_cierre) ) { echo $_cierre; }
	?>

	<!-- 6.- Tipo de Rango -->
	<h3> Rango </h3>
	<?php  
		$_rango = $item['item']['item_meta']['rango'][0];	//conseguimos el tipo de rango
		if ( !empty($_rango) ) { echo $_rango; }
	?>

	<!-- 6.- Configuraciones -->
	<h3> Configuraciones: </h3>
	<br>
	

	<section class="sec__configurations-products">
		<?php  
			$_configurations = $item['item']['item_meta']['configurations'][0];	//conseguimos el tipo de confgurations
			if ( !empty($_configurations) ){ 
				echo $_configurations; 
			}else{
				echo "No hay configuraciones para mostrar";
			}
		?>		
	</section>	

	<div style="page-break-before: always;"></div>	

	<?php endforeach; endif; ?>
</section>


<!-- Esta seccion mostrará el comentario u observacion de la compra  -->
<section>
	<h2>Observaciones: </h2>
	<p>
		<?php  
			//Extraemos el campo orden que contiene parametros de la orden de compra 
			// y se encuentra alojado en la variable creada $order 
			//luego lo seteamos a otra donde se almacenará el mensaje de compra 

			$customer_message = $order->customer_message;
			if( !empty($customer_message) ) { echo $customer_message; }
		?>
	</p>
</section>



<!-- Conseguimos toda la información o lo que queramos desplegar en el footer -->

<?php if ( $wpo_wcpdf->get_footer() ): ?>
<div id="footer">
	<?php $wpo_wcpdf->footer(); ?>
</div><!-- #letter-footer -->
<?php endif; ?>