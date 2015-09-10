<?php
	$args = array(
		'posts_per_page'		=>	-1,
		'post_type'				=>	'slide',
	);

	$the_query = new WP_Query($args);
	if ($the_query->have_posts()) :
		$i = 0;
		while ($the_query->have_posts()) :
			$the_query->the_post();

			$id = get_the_ID();
			$foto = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'full' );
			$src = $foto['0'];
			$active = ($i == 0) ? 'active' : '';
?>
	<div class="item <?php echo $active; ?>">
  		<img src="<?php echo $src; ?>" class="img-responsive" alt="" />
  		<div class="carousel-caption">
  			<h2><?php the_title(); ?></h2>
  			<?php the_content(); ?>
	  	</div>
	</div>

<?php $i++; endwhile; endif; wp_reset_postdata(); ?>