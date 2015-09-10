<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';
?>

<!-- Add class to <li> for product category page -->

<li <?php $classes[] = 'category__item' ; post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<!--a href="<?php the_permalink(); ?>"-->

		<!-- Modificacion para el hover de cada producto 
		<div class="hover__product-and-category hover__product">
			<span class="icon__lupa"><?php _e('Ver detalle' , THEMEDOMAIN ); ?></span>
		</div-->
		
		<!-- Imagen destacada del producto -->
		<figure class="category__item__image">
			<?php  
				do_action( 'woocommerce_before_shop_loop_item_title' );
			?>
		</figure>

		<section class="category__item__caption pull-right">
			
			<!-- Título del producto -->
			<h3 class="category__item--title hidden-xs">
				<?php the_title(); ?>
			</h3> <!-- /category__item--title -->
			
			<!--  Mostrar "descripcion corta" del producto. -->
			<p class="category__item--description">
				<?php 
					$short_description = $post->post_excerpt;
					if ( !empty( $short_description ) ) {
					 	echo $short_description;
					} 
				?>
			</p>

			<!-- Botón examinar para acceder al producto -->
			<a class="category__item__permalink" href="<?php the_permalink(); ?>">
				<span class="glyphicon glyphicon-search"></span>
				<span>examinar</span>
			</a>

		</section> <!-- /category__item__caption -->


		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			//do_action( 'woocommerce_before_shop_loop_item_title' );
		?>

		<!--h3><?php the_title(); ?></h3-->

		<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
		?>

	<!--/a-->

	<?php

		/**
		 * woocommerce_after_shop_loop_item hook
		 *
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item' ); 

	?>

</li>
