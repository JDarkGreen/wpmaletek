<?php $options = get_option('maletek_custom_settings'); ?>

</div> <!-- /wrapper slidebar -->

<!-- Left slidebar content -->
<aside class="nav-main-menu sb-slidebar sb-static sb-left sb-width-custom visible-xs-block" data-sb-width="250px">
    <h1 class="logo"> 
        <?php $options['logo'] == '' ? $logo = IMAGES . '/logo.png' : $logo = $options['logo']; ?>
        <a href="<?php echo home_url(); ?>">
            <img class="img-responsive" src="<?php echo $logo; ?>" alt="<?php bloginfo('name'); ?> | <?php bloginfo('description'); ?>" />
        </a>
    </h1>
    <nav class="navigation-menu-xs text-uppercase">
        <?php wp_nav_menu(
            array(
                'theme_location'    =>  'slidebar-menu',
            ));
        ?>
    </nav>
    <p class="text-uppercase text-center sb-slidebar__follow"> <?php echo _e('Síguenos en :',THEMEDOMAIN);?>
        <a target="_blank" href="http://www.facebook.com"><img src="<?php echo IMAGES; ?>/facebook.png" alt="..." class="img-responsive" /></a>
    </p>
</aside>

<?php 
    if ( is_page('novedades') ) {
        $styles = 'position:absolute;width:100%;z-index:9;bottom:0';
    } else{
        $styles = null;
    }

?>	

<!-- footer -->
<footer class="mainFooter hidden-xs" style="<?php echo $styles; ?>">
    <!--footer class="mainFooter navbar-fixed-bottom"-->
    <div class="container">
        <div class="row">
            <div class="col-xs-4">
                <h4>Derechos Reservados. &copy; Copyright <?php echo date('Y'); ?><br>Desarrollado por <a href="http://adinspector.pe/" target="_blank">Ad+INSPECTOR</a></h4>
            </div>
            <div class="col-xs-4">
                <p class="text-center">
                    <a title="Ir a home" href="<?php echo home_url(); ?>">
                        <img src="<?php echo IMAGES; ?>/f_maletek.png" alt="" />
                    </a>
                </p>
            </div>
            <?php //if ($options['display_social_link']) : ?>
            <div class="col-xs-4">
                <!-- facebook y twitter -->
                <ul class="contact-footer pull-right text-right">
                    <li>ventas@maletek.com.pe</li>
                    <li>Teléfono: (511) 717 - 2149</li>

                    <?php /*if (!empty($options['contact_email'])) : ?>
								<li>Email: <?php echo $options['contact_email']; ?></li>
							<?php endif; ?>
							<?php if (!empty($options['telefono'])) : ?>
								<li>Teléfono: <?php echo $options['telefono']; ?></li>
							<?php endif; ?>

						<?php /*if (!empty($options['facebook'])) : ?>
							<li><a title="Síguenos en facebook" class="facebook text-hide" href="http://www.facebook.com/<?php echo $options['facebook'] ?>" target="_blank">Facebook</a></li>
						<?php endif; */ ?>
                </ul> <!-- /facebook y twitter -->
            </div>
            <?php //endif; ?>
        </div> <!-- /row -->
    </div>
</footer> <!--/footer  -->

<script type='text/javascript'> var WPWidth = window.innerWidth ; console.log(WPWidth) </script>
<script> var _root_  = <?php echo " ' " . THEMEROOT . " ' " ?>;</script>

<?php wp_footer(); ?>


</body>
</html>