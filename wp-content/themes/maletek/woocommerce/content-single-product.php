<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 //do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>


<!-- Add class  -->
<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php $classes[] = "col-xs-12 col-sm-9 content-woocommerce" ; post_class( $classes ); ?>>

	<!-- Título del producto -->
	<?php  $title_product = get_the_title(); ?>	

	<h1 class="page-title title-ventas title-product hidden-xs">
		<span>
			<?= $title_product; ?>
		</span>
	</h1>

	<?php
		/**
		 * woocommerce_before_single_product_summary hook
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">

		<?php
		//Dentro del contenedor sumary modificamos o adjuntamos otros hooks para propositos de nuestros tema estos seran

			/**
			 * woocommerce_single_product_summary hook
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			//do_action( 'woocommerce_single_product_summary' );

		// Mostrar el titulo del producto
		//woocommerce_template_single_title();

		// Mostrar los tabs data del producto 
		woocommerce_output_product_data_tabs();

		// Mostrar upsell display
		//woocommerce_upsell_display();

		?>
	</div><!-- .summary -->

	<?php  
		// Mostrar agregar al carrito -->
		woocommerce_template_single_add_to_cart();

		// Mostrar los productos relacionados del producto -->
		//woocommerce_output_related_products();

		//Incluimos o mostramos la configuraciones por cada producto de acuerdo al rango
		require_once('include/show-configurations.php');
	?>

	
	

	
	<!-- Vamos a cambiar la estructura del mostrado de hooks para propositos de nuestro tema comentando la ultima linea
	 doA_ction woocommerce after single product summary para no mostrar estos hooks -->
	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		//do_action( 'woocommerce_after_single_product_summary' );
	?>


	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
