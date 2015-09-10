<?php
/**
 * Single Product tabs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>

	<!-- Modificamos los tabs para obtener los datos de los campos personalizados creados en cada producto -->

	<?php 
			/*
				<div class="woocommerce-tabs">
					<!-- modicamos los tabls para propositos del template  -->
					<ul class="tabs">
						<?php foreach ( $tabs as $key => $tab ) : ?>

							<li class="<?php echo $key ?>_tab">
								<a href="#tab-<?php echo $key ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></a>
							</li>

						<?php endforeach; ?>
					</ul>
					<?php foreach ( $tabs as $key => $tab ) : ?>
						<!-- add class panel_attributes for our template -->
						<div class="panel entry-content panel_attributes" id="tab-<?php echo $key ?>">
							<?php call_user_func( $tab['callback'], $key, $tab ) ?>
						</div>

					<?php endforeach; ?>
				</div>
			*/
	?>

	<!-- Variables Globales -->
	<?php  global $post; ?>

		<!-- SE DESPLEGARA EN FORMA DE ACORDEON -->
		<div class="panel-group panel-group-product visible-xs-block" id="accordion" role="tablist" aria-multiselectable="true">
			<?php  
				//$titles_product_info = [ 'Detalle Técnico', 'Usos Ideales' ,'Adicionales'];
				$content             = apply_filters('the_content', get_the_content() );
				$meta_ideal_product  = get_post_meta( $post->ID , 'mb_uses_ideal_product' , true ); 
				$meta_additional     = get_post_meta( $post->ID , 'mb_additional_product' , true ); 

				$i = 0;

				$titles_product_info = array(
					array(
						'name'    => 'Sobre el producto',
						'content' =>  $content 
					),
					array(
						'name'    => 'Usos Ideales',
						'content' => $meta_ideal_product
					),
					array(
						'name'    => 'Adicionales',
						'content' =>  $meta_additional
					),
				);

				foreach ( $titles_product_info as $tpi ) :
			?>
				<div class="panel panel-default panel-product">
					
					<?php $colap = !( $i == 0 ) ? 'collapsed' : '' ; ?>

        			<a data-toggle="collapse" data-parent="#accordion" href="<?php echo '#acordeon' . $i; ?>" aria-expanded="true" aria-controls="<?php echo 'acordeon' . $i; ?>" class="<?php echo $colap; ?>">
          				<?php echo $tpi['name']; ?>
        			</a> 

      				<?php $in = ( $i == 0 ) ? 'in' : '' ; ?>

					<div id="<?php echo 'acordeon' . $i; ?>" class="panel-collapse collapse <?php echo ' ' . $in; ?>" role="tabpanel" aria-labelledby="headingOne">
			    		<div class="panel-body">
			        		<?php echo $tpi['content']; ?>
			      		</div> <!--/panel-body  -->
    				</div> <!-- /collapseOne  -->
  				</div> <!-- /panel panel-default -->

  				<?php $i++; ?>
			<?php endforeach; ?>
		</div> 	<!-- /panel-group -->
		
		<!-- En esta parte llamamos a los tabs si es version web -->
		<div class="woocommerce-tabs-product hidden-xs" role="tabpanel">

			<!-- Nav tabs -->
			<ul class="nav nav-tabs tabs-product" role="tablist">
				<li role="presentation" class="active">
					<a href="#detalle-tec" aria-controls="detalle-tec" role="tab" data-toggle="tab">
						<?php _e('Sobre el Producto', THEMEDOMAIN ); ?>
					</a>
				</li>
				<li role="presentation">
					<a href="#uso-ideal" aria-controls="uso-ideal" role="tab" data-toggle="tab">
						<?php _e('Usos Ideales', THEMEDOMAIN ); ?>
					</a>
				</li>
				<li role="presentation">
					<a href="#adicionales" aria-controls="adicionales" role="tab" data-toggle="tab">
						<?php _e('Adicionales', THEMEDOMAIN ); ?>
					</a>
				</li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content ">
				<div role="tabpanel" class="tab-pane fade in active" id="detalle-tec">
					<?php the_content(); ?>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="uso-ideal">
					<?php  
					$meta = get_post_meta( $post->ID , 'mb_uses_ideal_product' , true ); 
					echo $meta;
					?>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="adicionales">
					<?php  
					$meta_additional = get_post_meta( $post->ID , 'mb_additional_product' , true ); 
					echo $meta_additional;
					?>
				</div>
			</div>
		</div> <!-- /woocommerce-tabs-product -->

 
<?php endif; ?>
