<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>
	
		<?php
			/**
			 * woocommerce_before_main_content hook
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 */
			do_action( 'woocommerce_before_main_content' );
		?>		

	<!-- Add Sidebar -->
	<div class="col-sm-3 hidden-xs">
		<aside class="sidebar">
			<?php 
					wp_nav_menu(
					array(
						'theme_location' 	=>	'products-menu',
					));
			?>
		</aside><!-- /sidebar -->
		<span class="bg_triangle_sidebar"></span> 
		<!-- /.bg_triangle_sidebar -->

	</div> <!--/col-sm-3 -->

	<div class="col-xs-12 col-sm-9 content-woocommerce">

		<div class="col-sm-6">
			<!-- Este es el titulo principal  -->
			<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

				<h1 class="page-title title-ventas hidden-xs">
					<strong><?php woocommerce_page_title(); ?></strong>
					
				</h1>
				
				<h1 class="page-title title-ventas visible-xs-block">
					<?php  
						$page_title =  woocommerce_page_title( false );
						$datas = explode(" ", $page_title );

						echo $datas[0] . " " . "<span>";

						for ( $i=1; $i < count($datas) ; $i++) { 
							echo $datas[$i] . " ";
						}

						echo "</span>";

						/*$page_title = apply_filters( 'woocommerce_page_title', $page_title );
						var_dump( $title );
                    	$data = explode(" ", $title ); */
					?>
				</h1>

			<?php endif; ?>

			<?php do_action( 'woocommerce_archive_description' ); ?>

			<!-- Mostrar el icono de catalgo en pdf si se encuentra en la seccion de categoria de producto -->
			<?php if( is_product_category() ) : ?>
				<?php  
					/* ID of page shop for display metabox mb_issuu */
					$theid     = woocommerce_get_page_id('shop');
					$linkissuu = get_post_meta($theid , 'mb_issuu'); 
				?>
					<!-- content-pdf -->
					<div class="content-pdf content-pdf--mobile">
						<figure>
							<a href="<?php echo $linkissuu[0]; ?>" target="_blank">
								<img src="<?php echo IMAGES; ?>/page_content_pdf.png" />
								<span>Ver catálogo Maletek <strong><u>aquí</u></strong></span>
							</a>
						</figure>
					</div> <!-- /page-content-pdf -->

			<?php endif; ?>
		</div>

		<!-- Aquí se crea un slider que se muestra solo si esta en la categoria de producto con las imagenes
		que se asignaron a cada categoria  -->
		<!-- aqui va un slider -->
	
		<!-- conseguir las imagenes  -->
		<?php 
			
			global $post, $woocommerce;
			$queried_object = get_queried_object_id();
			//echo $queried_object->name;
			//var_dump($queried_object);

			$args = array(
				'post_type'     => 'attachment',
				'posts_per_page' => -1,
				'tax_query' => array(
			        array(
			        'taxonomy' => 'product_cat',
			        'field'    => 'term_id',
			        'terms'    => $queried_object
			    ))
			);

			$query = get_posts( $args );
			//var_dump($query);

			if( is_product_category() ) :
		?>
				<div id="carousel-categories-wc" class="carousel slide carousel-categories-wc col-xs-6 hidden-xs" data-ride="carousel">
					<!-- Indicators -->
					<ol class="carousel-indicators">
						<?php for($i = 0; $i < count($query); $i++) : ?>
						<?php $active = ($i == 0) ? 'class="active"' : ''; ?>
						<li data-target="#carousel-categories-wc" data-slide-to="<?php echo $i; ?>" <?php echo $active; ?>></li>
						<?php endfor; ?>
					</ol>

					<div class="carousel-inner">
						<?php $i = 0; ?>
						<?php foreach($query as $post) : ?>
						<?php  setup_postdata($post); ?>
						<?php $active = ($i == 0) ? 'active' : ''; ?>
						<div class="item <?php echo $active; ?>">
							<a href="#"  title="">
								<?php  
								$image = wp_get_attachment_image_src( $post->ID, 'full', false );
								?>
								<img src="<?php echo $image[0]; ?>" class="img-responsive" alt="" />
							</a>
						</div> <!-- /item -->
						<?php $i++; ?>
						<?php endforeach; ?>
					</div> <!-- /carousel-inner -->
				</div> <!-- /slider -->

				<div class="clearfix"></div>

				<h2 class="product-category-title-models">
					<?php _e('Nuestros ') ?><span><strong><?php _e('Modelos'); ?></strong></span>
				</h2>

			<?php endif; ?>

		<?php if ( have_posts() ) : ?>

			<?php
			/**
			 * woocommerce_before_shop_loop hook
			 *
			 * @hooked woocommerce_result_count - 20
			 * @hooked woocommerce_catalog_ordering - 30
			 */
			do_action( 'woocommerce_before_shop_loop' );
			?>

			<?php woocommerce_product_loop_start(); ?>

			<?php woocommerce_product_subcategories(); ?>

			<?php while ( have_posts() ) : the_post(); ?>

			<?php wc_get_template_part( 'content', 'product' ); ?>

		<?php endwhile; // end of the loop. ?>

		<?php woocommerce_product_loop_end(); ?>

		<?php
			/**
			 * woocommerce_after_shop_loop hook
			 *
			 * @hooked woocommerce_pagination - 10
			 */
			do_action( 'woocommerce_after_shop_loop' );
		?>

		<?php //elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php // wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php else : 
			wp_redirect( home_url('shop') ); exit;
		?>

		<?php endif; ?>


	</div> <!-- /col-sm-9 -->



	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		//do_action( 'woocommerce_sidebar' );
	?>
	



<?php get_footer( 'shop' ); ?>
