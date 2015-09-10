<?php

if (is_page('conocenos')) {
    $url = home_url('conocenos/nuestra-historia/');
    header('Location:' . $url);
}

if(is_page('lineas-de-producto')) {
    $url = home_url('lineas-de-producto/alquiler-de-lockers');
    header('Location:' . $url);
}

/*if(is_page('venta-de-lockers')) {
    if(is_mobile() || is_iphone() )  { 
        //$url = home_url('lineas-de-producto/venta-de-lockers');
    }else{
        $url = home_url('lineas-de-producto/venta-de-lockers/locker-linea-m');
        header('Location:' . $url);
    }
}*/

if(is_page('informacion-de-contacto')) {
    if(is_mobile() || is_iphone() )  { 
    }else{
        $url = home_url('contactanos');
        header('Location:' . $url);
    }
}

//Redigir a la misma pagina si accede manualmente a uno de las siguietes paginas
if( is_page('my-account') ){
    wp_redirect(home_url()); 
    exit;
}


?>
<!DOCTYPE html>
<!--[if IE 8]> <html <?php language_attributes(); ?> class="ie8"> <![endif]-->
<!--[if !IE]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <meta charset="<?php bloginfo('charset'); ?>">

    <meta name="author" content="">
    <!-- Meta view -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>

    <!-- font source sans pro -->
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,700' rel='stylesheet' type='text/css'>

    <!-- font roboto  -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,400italic,500,500italic,700,700italic' rel='stylesheet' type='text/css'/>

    <!-- Google Maps -->
    <!--script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script-->

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" />

    <!--[if lt IE 10]>
<link href="<?php echo THEMEROOT; ?>/css/ie.css" rel="stylesheet">
<![endif]-->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

    <!-- Favicon and Apple Icons -->
    <link rel="shortcut icon" href="<?php print IMAGES; ?>/favicon.png">
    <?php wp_head(); ?>
</head>
<?php //$map = (is_page('contactanos')) ? 'onload="loadMap();"' : ''; ?>
<!--body <?php body_class(); ?> <?php // echo $map; ?>-->
<body <?php body_class(); ?>>
    <!-- Google Tag Manager -->
    <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-MPGH4F"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                                                          new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                                })(window,document,'script','dataLayer','GTM-MPGH4F');</script>
    <!-- End Google Tag Manager -->

    <?php $options = get_option('maletek_custom_settings'); ?>
    <!-- header -->
    <header class="mainHeader sb-slide">
        <div class="container">
            <div class="row">
                <div class="col-xs-3 hidden-xs">
                    <h1 class="logo">
                        <?php $options['logo'] == '' ? $logo = IMAGES . '/logo.png' : $logo = $options['logo']; ?>
                        <a href="<?php echo home_url(); ?>"><img class="img-responsive" src="<?php echo $logo; ?>" alt="<?php bloginfo('name'); ?> | <?php bloginfo('description'); ?>" /></a>
                    </h1>
                </div> <!-- /col-xs-3 -->
                <div class="col-xs-9 hidden-xs">

                    <ul class="user-menu list-inline text-right">
                        <li class="social-web">
                            <label for="sl_webside">Website</label>
                            <select name="sl_webside" id="sl_webside" class="sl_webside">
                                <option value="1">Perú</option>
                                <option value="2">Chile</option>
                            </select> <!-- /select -->
                        </li>
                        <li>
                            <a class="user-menu__cart" href="<?php echo WC()->cart->get_cart_url(); ?>">
                                <?php _e('Ver Cotizador', THEMEDOMAIN ); ?>
                                <!--span> ( <?php echo WC()->cart->cart_contents_count ?> artículos )</span-->
                            </a>
                        </li>
                        <?php 
                            $current_user = wp_get_current_user();
                            $current_page = $_SERVER["REQUEST_URI"];

                            if( !is_user_logged_in() ) : 
                        ?>
                            <li><a href="#" data-toggle="modal" data-target="#modal__login_wc">Iniciar Sesión</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#modal__register_wc">Regístrate</a></li>
                        <?php else :?>
                            <li>
                                <a class="current-user" href="#"><?php echo "Hola " . $current_user->user_firstname; ?></a>

                                <a class="logout-url" href="<?php echo wp_logout_url($current_page); ?>">
                                    <span class="glyphicon glyphicon-log-out"></span>
                                    Logout
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul> <!-- end user-menu -->

                    <!-- main menu -->
                    <nav class="mainMenu">
                        <?php wp_nav_menu(
                                array(
                                    'theme_location' 	=>	'main-menu',
                                    'menu_class'		=>	'list-inline'
                                ));
                        ?>
                    </nav> <!-- /mainMenu -->
                </div> <!-- /col-xs-9 -->
            </div> <!-- /row -->

            <!-- navigation-xs-browser for browser small resolution -->
            <nav class="navigation-buttons-xs visible-xs-block" role="navigation">
                <?php $bg_trans = 'background-color: rgba(0, 0, 0, 0.3); '; ?>

                <!-- Button menu -->
                <button id="openMenu" class="nav-xs__button nav-xs__button--menu">
                    <span class="nav-xs__button--menu"></span>
                </button>
                <!-- link to formulary -->
                <a class="nav-xs__button pull-right" href="<?php echo home_url('contactanos'); ?>">
                    <span class="nav-xs__button--message"></span>
                </a>
                <!-- Link to woomerce -->
                <a class="nav-xs__button pull-right" href="<?php echo home_url('cart'); ?>" style='<?php if ( is_cart() ) {
                    echo $bg_trans; } ?>'>
                    <span class="nav-xs__button--cart"></span>
                </a>

                <?php if( is_page('novedades') ) : ?>
                <!-- Search  -->
                <a class="nav-xs__button pull-right" href="#" style='<?php echo $bg_trans; ?>'>
                    <span class="nav-xs__button--search"></span>
                </a>
                <?php endif; ?>

                <!-- Link to parent page or back -->
                <?php if( is_product_category() || is_product() )  : ?>
                    <a class="nav-xs__button pull-right" href="javascript:history.go(-1)">
                        <span class="nav-xs__button--back"></span>
                    </a>
                <?php endif; ?>
                
            </nav>
            

        </div> <!-- /container -->
    </header> <!-- /header -->
    <div class="post-header hidden-xs"></div>

    
    <!-- Modal para el login en woocommerce ( Nuevo ) -->

    <?php do_action( 'woocommerce_before_customer_login_form' ); ?>

    <section class="modal fade fade modal__wc" id="modal__login_wc" tabindex="-1" role="dialog" aria-labelledby="modal__login_wc" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h2><?php _e( 'Bienvenido a Maletek', 'woocommerce' ); ?></h2>
                </div>
                <div class="modal-body">
                    <form method="post" class="login">
                        <?php do_action( 'woocommerce_login_form_start' ); ?>

                        <p>Para hacer uso del cotizador debe de ingresar su correo electrónico y contraseña.</p>

                        <p class="form-row form-row-wide">
                            <input type="text" class="input-text input_login_wc" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" placeholder="Correo Electrónico" />
                        </p>

                        <p class="form-row form-row-wide">
                            <input class="input-text input_login_wc" type="password" name="password" id="password" placeholder="Contraseña" />
                        </p>

                        <?php do_action( 'woocommerce_login_form' ); ?>

                        <p class="form-row remember">
                            <input name="rememberme" type="checkbox" id="rememberme" value="forever" checked />
                            <label for="rememberme" class="inline remember_checkbox">
                            </label>
                            Recordar sesión.
                        </p>

                         <p class="form-row">
                             <?php wp_nonce_field( 'woocommerce-login' ); ?>
                            <input type="submit" class="button " name="login" value="<?php _e( 'Enviar  ', 'woocommerce' ); ?>" />
                         </p>

                        <p class="lost_password">
                            <a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php _e( 'Olvidaste tu Contraseña?', 'woocommerce' ); ?></a>
                        </p>

                        <p class="wc_account"> 
                            <a data-toggle="modal" data-target="#modal__register_wc" data-dismiss="modal" href="#">Aún no tienes tu cuenta. Regístrate aquí</a>
                        </p>

                        <?php do_action( 'woocommerce_login_form_end' ); ?>
                    </form> <!-- /form -->
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </section><!-- /.modal__login_wc -->

    <!-- Modal para el registro en woocommerce ( Nuevo ) -->
    <section class="modal fade fade modal__wc" id="modal__register_wc" tabindex="-1" role="dialog" aria-labelledby="modal__login_wc" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h2 class="text-center register__title">Formulario de Registro</h2>
                </div>
                <div class="modal-body">
                    
                    <form id="register_form_wc" method="post" class="register">
                
                        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                            <div class="form-group">
                                <label for="reg_username"><?php _e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
                                <input type="text" class="input-text" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
                            </div>
                        <?php endif; ?>

                        <?php do_action( 'woocommerce_register_form_start' ); ?>

                        <div class="form-group">
                            <!--label for="reg_email"><?php _e( 'Correo Electrónico', 'woocommerce' ); ?> <span class="required">*</span></label-->
                            <input type="email" class="input-text" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" placeholder="Correo Electrónico" />
                        </div>

                        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                        <div class="form-group">
                            <!--label for="reg_password"><?php _e( 'Contraseña', 'woocommerce' ); ?> <span class="required">*</span></label-->
                            <input type="password" class="input-text" name="password" id="reg_password" placeholder="Contraseña" />
                        </div>

                        <div class="form-group">
                            <!--label for="conf_reg_password"><?php _e( 'Confirmar Contraseña', 'woocommerce' ); ?> <span class="required">*</span></label-->
                            <input type="password" class="input-text" name="conf_password" id="conf_reg_password" placeholder="Confirmar contraseña" />
                        </div>

                        <?php endif; ?>

                        <!-- Spam Trap -->
                        <div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php _e( 'Anti-spam', 'woocommerce' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

                        <?php do_action( 'woocommerce_register_form' ); ?>
                        <?php do_action( 'register_form' ); ?>

                        <?php wp_nonce_field( 'woocommerce-register' ); ?>
                        <input type="submit" class="button" name="register" value="<?php _e( 'Crear Cuenta', 'woocommerce' ); ?>" />

                        <p class="wc_account"> 
                            <a data-toggle="modal" data-target="#modal__login_wc" data-dismiss="modal" href="#">
                                ¿Ya tienes una cuenta creada aquí?
                            </a>
                        </p>

                        <?php do_action( 'woocommerce_register_form_end' ); ?>


                     </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </section><!-- /.modal__register_wc -->

    <!-- Modal despues de la orden de compra (nuevo ) -->
    <div class="modal fade" id="modal__success_wc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel3">Cotización Enviada</h4>
                </div>
                <div class="modal-body">
                    <p>
                        <?php $current_user = wp_get_current_user();  ?>
                        Gracias <strong><?php echo $current_user->user_firstname; ?></strong> por preferir Maletek tu 
                        cotización ha sido enviada con éxito, pronto contactaremos contigo.
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!-- Wrapper for Slidebar -->
    <div id="sb-site" class="slidebar__site">
        
        <!-- Logo for small resolutions -->
        <h1 class="logo--small visible-xs-block ">
            <?php $options['logo'] == '' ? $logo = IMAGES . '/logo.png' : $logo = $options['logo']; ?>
            <a href="<?php echo home_url(); ?>"><img class="img-responsive center-block" src="<?php echo $logo; ?>" alt="<?php bloginfo('name'); ?> | <?php bloginfo('description'); ?>" /></a>
        </h1>