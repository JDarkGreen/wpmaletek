<?php
/*
	Template Name: Content Page
*/

$options = get_option('maletek_custom_settings');

?>
<?php get_header(); ?>

<section class="main-container">
    <div class="container">
        <div class="row" style="min-height: 200px;">
            <?php if (have_posts()) : while(have_posts()) : 
                the_post(); the_content(); 
            endwhile; endif;?>
        </div> <!-- /row -->
    </div> <!-- /container- -->
</section><!-- end main-container -->

<?php get_footer(); ?>