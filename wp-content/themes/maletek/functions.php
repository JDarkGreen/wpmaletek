<?php
/***********************************************************************************************/
/* 	Define Constants */
/***********************************************************************************************/

define('THEMEROOT', get_stylesheet_directory_uri());
define('IMAGES', THEMEROOT.'/images');
define('THEMEDOMAIN', 'maletek-framework');


function flush_rules(){
flush_rewrite_rules();
}
add_action('init','flush_rules');

/***********************************************************************************************/
/* Load JS Files */
/***********************************************************************************************/
function load_custom_scripts() {
    //wp_deregister_script('jquery');
    //wp_register_script('jquery', ("http://code.jquery.com/jquery-1.11.2.min.js"), false, '1.11.2', true);
    //wp_enqueue_script('jquery'); 
    wp_enqueue_script('jquery-ui', THEMEROOT . '/js/jquery-ui.min.js');
    wp_enqueue_script('easing', THEMEROOT . '/js/ jquery.easing.1.3.js', array('jquery'), 1.3 , true);
    wp_enqueue_script('touchswipe', THEMEROOT . '/js/jquery.mobile.custom.min.js', array('jquery'), false, true);
    wp_enqueue_script('modernizr', THEMEROOT . '/js/modernizr.js', array(), 2.8, true);
    //wp_enqueue_script('holder', THEMEROOT . '/js/holder.js', array(), 2.1, true);
    wp_enqueue_script('bootstrap', THEMEROOT . '/js/bootstrap.min.js', array('jquery'), 3.2, true);

    /*-- Framework validator ------------------------------------------------------------------------------------*/
    wp_enqueue_script('form-validator', THEMEROOT . '/js/formValidation.min.js', array('jquery'), true , true);
    wp_enqueue_script('bootstrap-validator', THEMEROOT . '/js/bootstrapvalidator.min.js', array('jquery'), true , true);

    wp_enqueue_script('fancybox', THEMEROOT . '/js/jquery.fancybox.min.js', array('jquery'), 2.1, true);
    wp_enqueue_script('slidebars', THEMEROOT . '/js/slidebars.js', array('jquery'), false, true);
	wp_enqueue_script('custom_script', THEMEROOT . '/js/script.js', array('jquery'), false, true);

    wp_localize_script('custom_script', 'MyAjax', array( 'url' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'myajax-post-comment-nonce' )));
}

add_action('wp_enqueue_scripts', 'load_custom_scripts');



/**********************************************************************************/
/* Add Theme Support for Post Formats, Post Thumbnails and Automatic Feed Links */
/**********************************************************************************/
if (function_exists('add_theme_support')) {
	add_theme_support('post-formats', array('link', 'quote', 'gallery', 'video'));
	add_theme_support('post-thumbnails', array('post', 'page', 'slide','product'));

	//set_post_thumnail_size(210, 210, true);
	//add_image_size('page-full', 750, 218); // Imagen que aparece en la página de about
	//add_image_size('post-subfeatured', 167, 120, true);

	//add_theme_support('automatic-feed-links');
}


/***********************************************************************************************/
/* Add Menus */
/***********************************************************************************************/
function register_my_menus(){
	register_nav_menus(
		array(
			'main-menu' 	  =>	__('Main Menu', THEMEDOMAIN),
			'about-menu'	  =>	__('About Menu', THEMEDOMAIN),
            'products-menu'   =>    __('Products Menu', THEMEDOMAIN),
            'slidebar-menu'   =>    __('Slidebar Menu', THEMEDOMAIN),
		)
	);
}
add_action('init', 'register_my_menus');

/***********************************************************************************************/
/* Add class active to current li from menu */
/***********************************************************************************************/
add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);
function special_nav_class($classes, $item){
     if( in_array('current-menu-item', $classes) ){
        $classes[] = 'active ';
     }
     if( in_array('current_page_parent', $classes) ){
        $classes[] = 'current-menu-item active';
     }
     if( $item->url == get_permalink(woocommerce_get_page_id( 'shop' ) ) && ( is_shop() || is_product_category() || is_cart() || is_checkout() ) ){
        $classes[]  = 'current-menu-item active';
     }
     return $classes;
}

/***********************************************************************************************/
/* Add Sidebar Support */
/***********************************************************************************************/
if (function_exists('register_sidebar')) {
	register_sidebar(
		array(
			'name' => __('Main Sidebar', THEMEDOMAIN),
			'id' => 'main-sidebar',
			'description' => __('Main sidebar', THEMEDOMAIN),
			'before_widget' => '<div class="botoner">',
			'after_widget' => '</div> <!-- end of botoner -->',
		)
	);
}

/**********************************************************************************/
/* Meta Boxes Posts */
/**********************************************************************************/
add_action('add_meta_boxes', 'cd_mb_page');
function cd_mb_page() {
	add_meta_box('mb_page_id', 'Campos Extras', 'cd_mb_page_print', 'page', 'side', 'low');
}

function cd_mb_page_print() {
	global $post;
	$values = get_post_custom($post->ID);

	$url_issuu = isset($values['mb_issuu'][0]) ? $values['mb_issuu'][0] : '';

	wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce');

?>
<!-- Link Issuu -->
<p>
    <label for="mb_issuu">Link Issuu: </label><br />
    <input type="text" name="mb_issuu" id="mb_issuu" value="<?php echo $url_issuu; ?>" />
</p>
<?php
}

add_action('save_post', 'cd_mb_page_save');

function cd_mb_page_save($post_id) {
	// Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;

    // now we can actually save the data
    $allowed = array(
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );

	// Name PDF
    if( isset( $_POST['mb_issuu'] ) )
    update_post_meta( $post_id, 'mb_issuu', wp_kses( $_POST['mb_issuu'], $allowed ) );
}



/**********************************************************************************/
/* Register Custom Post Type Links and Publicidad */
/**********************************************************************************/
function wptutsplus_create_post_type() {
    $labels = array(
        'name' => __( 'Sliders', THEMEDOMAIN ),
        'singular_name' => __( 'slide', THEMEDOMAIN ),
        'add_new' => __( 'Nuevo slide', THEMEDOMAIN ),
        'add_new_item' => __( 'Agregar nuevo slide', THEMEDOMAIN ),
        'edit_item' => __( 'Editar slide', THEMEDOMAIN ),
        'new_item' => __( 'Nuevo slide', THEMEDOMAIN ),
        'view_item' => __( 'Ver slide', THEMEDOMAIN ),
        'search_items' => __( 'Buscar slide', THEMEDOMAIN ),
        'not_found' =>  __( 'Slide no encontrado', THEMEDOMAIN ),
        'not_found_in_trash' => __( 'Slide no encontrado en la papelera', THEMEDOMAIN ),
    );
    $args = array(
        'labels' => $labels,
        'has_archive' => true,
        'public' => true,
        'hierarchical' => false,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'custom-fields',
            'thumbnail',
            'page-attributes'
        ),
        'taxonomies' => array( 'post_tag', 'category'),
    );
    register_post_type( 'slide', $args );
}
add_action( 'init', 'wptutsplus_create_post_type' );





/**********************************************************************************/
/* Change Logo and footer Area Administration */
/**********************************************************************************/
// Cambiamos el logo para el formulario de acceso al wordpress
function my_custom_login_logo() {
    echo '<style type="text/css">h1 a {background-image:url('.get_bloginfo('template_directory').'/images/logo.png) !important; background-size: 100% !important; width: 100% !important;}</style>';
}

add_action('login_head', 'my_custom_login_logo');


// Cambiar el pie de pagina del panel de Administración
function change_footer_admin() {
    echo 'Copyright © ' . date('Y') . ' Maletek - Web desarrollada por <a href="http://adinspector.pe">Ad+INSPECTOR</a>';
}
add_filter('admin_footer_text', 'change_footer_admin');



/**********************************************************************************/
/* WOOCOMMERCE */
/**********************************************************************************/
add_action('woocommerce_before_main_content','my_theme_wrapper_start',10);
add_action('woocommerce_after_main_content','my_theme_wrapper_end',10);

function my_theme_wrapper_start(){
    echo '<section id="main" class="container"><div class="row">';
}
function my_theme_wrapper_end(){
    echo '</div></section>';
}

//Add theme support
add_theme_support('woocommerce');

/* Change title page Shop replace return string */
/*add_filter( 'woocommerce_page_title', 'woo_shop_page_title');
function woo_shop_page_title( $page_title ) {
    if( 'Shop' == $page_title) {
        return "venta de locker";
    }else{
        return $page_title;
    }
}*/


/* Change the ‘Home’ text */
add_filter( 'woocommerce_breadcrumb_defaults', 'jk_change_breadcrumb_home_text' );
function jk_change_breadcrumb_home_text( $defaults ) {
    // Change the breadcrumb home text from 'Home' to 'Líneas de Producto'
    $defaults['home'] = 'Líneas de Producto';
    //Cambiar o modifica la estructura de los breadcrumbs 
    $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb col-xs-9" itemprop="breadcrumb">';

    return $defaults;
}
/* Change the home link to a different URL */
add_filter( 'woocommerce_breadcrumb_home_url', 'woo_custom_breadrumb_home_url' );
function woo_custom_breadrumb_home_url() {
    return home_url('lineas-de-producto/alquiler-de-lockers/');
}

/* Remove the breadcrumbs 
add_action( 'init', 'jk_remove_wc_breadcrumbs' );
function jk_remove_wc_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
} */



/* Reemplacing separators breadcrumb default */
add_filter( 'woocommerce_breadcrumb_defaults', 'd_woo_breadcrumb_defaults');
function d_woo_breadcrumb_defaults($defaults) {
    $defaults['delimiter'] = ' > '; //whatever delimiter you want
    return $defaults;
}

/*Display empty categories */
add_filter( 'woocommerce_product_subcategories_hide_empty', 'show_empty_categories', 10, 1 );
function show_empty_categories ( $show_empty ) {
    $show_empty  =  true;
    // You can add other logic here too
    return $show_empty;
}

/* Removing tabs only show  description for product */
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    //unset( $tabs['description'] );             // Remove the description tab
    //unset( $tabs['reviews'] );                   // Remove the reviews tab
    //unset( $tabs['additional_information'] );   // Remove the additional information tab

    return $tabs;

}


/* Cambiar el texto del boton "AGREGAR AL CARRITO "*/
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );    // 2.1 +
function woo_custom_cart_button_text() {
    return __( 'Agregar al cotizador', 'woocommerce' );
}

/*****************************************************************************************/
/* Vamos a agregar nuevos campos al producto para luego extraer esa informacion en el carrito  */
/****************************************************************************************/

//Filtro de woocomerce  - woocommerce_add_cart_item_data
add_filter( 'woocommerce_add_cart_item_data', 'add_cart_item_custom_data_vase', 10, 2 );
function add_cart_item_custom_data_vase( $cart_item_meta, $product_id ) {
    global $woocommerce;
    $cart_item_meta['cierre']           = $_POST['cierre'];
    $cart_item_meta['rango']            = $_POST['rango'];
    $cart_item_meta['configurations']   = $_POST['configurations'];
    return $cart_item_meta;  //retornamos el valor 
}

add_filter( 'woocommerce_get_cart_item_from_session', 'get_cart_items_from_session', 1, 3 );
//Get it from the session and add it to the cart variable
function get_cart_items_from_session( $item, $values, $key ) {
    if ( array_key_exists( 'cierre', $values ) )
        $item[ 'cierre' ] = $values['cierre'];

    if ( array_key_exists( 'rango', $values ) )
        $item[ 'rango' ] = $values['rango'];

    if ( array_key_exists( 'configurations', $values ) )
        $item[ 'configurations' ] = $values['configurations'];

    return $item;
}


//Salvar los datos cuando se hace el pedido
add_action('woocommerce_add_order_item_meta','wdm_add_values_to_order_item_meta',1,3);

function wdm_add_values_to_order_item_meta($item_id, $values, $key )
{
    global $woocommerce,$wpdb;
    
    if ( isset( $values['cierre'] ) && !empty( $values['cierre']) )
        wc_add_order_item_meta( $item_id ,'cierre', $values['cierre'] ); 

    if ( isset( $values['rango'] ) && !empty( $values['rango']) )
        wc_add_order_item_meta( $item_id ,'rango', $values['rango'] );

    if ( isset( $values['configurations'] ) && !empty( $values['configurations']) )
        wc_add_order_item_meta( $item_id ,'configurations', $values['configurations'] );
  }





/* Con estas acciones posicionamos el boton de agregar carrito despues de los atributos del producto pero antes
de los productos relacionados*/
//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
//add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 80 );

/*****************************************************************************************/
/* Creación de dos metabox para obtener los usos ideales y los adicionales del PRODUCTO  */
/****************************************************************************************/

/* USOS IDEALES */
add_action('add_meta_boxes', 'cd_mb_uses_product');

function cd_mb_uses_product() {
    add_meta_box('mb_uses_product_id', 'Usos ideales del producto ', 'cd_mb_uses_product_print', 'product', 'normal', 'low');
}

function cd_mb_uses_product_print() {
    global $post;
    $values = get_post_custom($post->ID);

    $content = isset($values['mb_uses_ideal_product'][0]) ? $values['mb_uses_ideal_product'][0] : '';

    wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce');

    echo '<label for="mb_uses_ideal_product">Usos ideal del producto </label>';
    wp_editor( $content , 'mb_uses_ideal_product', array( 'textarea_name' => 'mb_uses_ideal_product', 'media_buttons' => false ) ); 
}

add_action('save_post', 'cd_mb_uses_product_save');

function cd_mb_uses_product_save($post_id) {
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;

    // now we can actually save the data
    $allowed = array(
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );

    // UPDATE TEXT
    if( isset( $_POST['mb_uses_ideal_product'] ) )
    update_post_meta( $post_id, 'mb_uses_ideal_product', wp_kses( $_POST['mb_uses_ideal_product'], $allowed ) );
}

/* ADICIONALES DEL PRODUCTO */

add_action('add_meta_boxes', 'cd_mb_additional_product');

function cd_mb_additional_product() {
    add_meta_box('mb_additional_product_id', ' Adicionales del producto ', 'cd_mb_additional_product_print', 'product', 'normal', 'low');
}

function cd_mb_additional_product_print() {
    global $post;
    $values = get_post_custom($post->ID);

    $content = isset($values['mb_additional_product'][0]) ? $values['mb_additional_product'][0] : '';

    wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce');

    echo '<label for="mb_additional_product"> Adicionales del producto </label>';
    wp_editor( $content , 'mb_additional_product', array( 'textarea_name' => 'mb_additional_product', 'media_buttons' => false ) ); 
}

add_action('save_post', 'cd_mb_additional_product_save');

function cd_mb_additional_product_save($post_id) {
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;

    // now we can actually save the data
    $allowed = array(
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );

    // UPDATE TEXT
    if( isset( $_POST['mb_additional_product'] ) )
    update_post_meta( $post_id, 'mb_additional_product', wp_kses( $_POST['mb_additional_product'], $allowed ) );
}



/**********************************************************************************/
/* Get Posts by Filter SubCategory Blog Ajax and Seach */
/**********************************************************************************/
add_action( 'wp_ajax_get_posts_byfilter', 'get_posts_byfilter_callback' );
add_action( 'wp_ajax_nopriv_get_posts_byfilter', 'get_posts_byfilter_callback' );

function get_posts_byfilter_callback()
{
    $nonce  = $_POST['nonce'];
    $result = array( 'result' => FALSE );

    if ( !wp_verify_nonce( $nonce, 'myajax-post-comment-nonce') )
    {
        die( 'Te atrapamos maldito!' );
    }

    global $post;

    $filter      = sanitize_text_field( $_POST['filter'] );
    $filter      = strtolower( $filter );
    $filterName  = explode(' ', $filter);
    $filterName  = implode('-', $filterName);

    $value       = sanitize_text_field( $_POST['value']);

    $page = ( isset( $_POST['page'] ) ) ? $_POST['page'] : 1;

    $posts_per_page = ( $page > 1 ) ? 6 : 7;

    $args = array(
        'posts_per_page'      => $posts_per_page, 
        'offset'              => ( $page - 1 ) * $posts_per_page,
        'orderby'             => 'menu_order'
    );

    if ( $value == null ) {
        $args = array_merge( $args ,array('category_name' => $filterName ));
    }
    if ( $filter == null ){
        $args = array_merge( $args ,array( 'category_name' => 'blog' , 's' => $value ));
    }


    $the_query = new WP_Query( $args );

    if ( $the_query->have_posts() )
    {
        $result['result'] = TRUE;

        ob_start();

        include TEMPLATEPATH . '/includes/blog-ajax.php';

        $content = ob_get_contents();

        ob_get_clean();

        $result['content'] = $content;

    }

    wp_reset_postdata();

    echo json_encode( $result );

    die();
}



/**********************************************************************************/
/*  Conseguir la configuracion de los lockers mediante filtro rango - Ajax  */
/**********************************************************************************/
add_action( 'wp_ajax_get_lockers_byfilter', 'get_lockers_byfilter_callback' );
add_action( 'wp_ajax_nopriv_get_lockers_byfilter', 'get_lockers_byfilter_callback' );

function get_lockers_byfilter_callback()
{
    $nonce  = $_POST['nonce'];
    $result = array( 'result' => FALSE );

    if ( !wp_verify_nonce( $nonce, 'myajax-post-comment-nonce') )
    {
        die( 'Te atrapamos maldito!' );
    }

    global $post;

    $the_product = $_POST['product'];
    $the_rango   = $_POST['rango'];
    $the_message = $_POST['message'];

    //Array vacio donde se almacenarán todos los id de los terminos los cuales estan filtrados 
    //por el producto y el rango.
    $array_id_tax = [];

    //Conseguimos todos los id de los terminos de tanonomia modelos creadas y la comparamos con nuestros 
    // argumentos si son iguales entonces guardamos su id en otro array

    $taxonomy = "pa_modelos";
    $args     = array(
        'hide_empty' => false, 
    );

    $array_modelos = get_terms( $taxonomy , $args );

    foreach ($array_modelos as $modelo ) {
        $t_ID = $modelo->term_id; //conseguimos el id
        $term_data = get_option("taxonomy_$t_ID"); //asignamos a la taxonomia ubicada en tabla wp_options

        $producto = $term_data['texto01']; //conseguimos el producto
        $rango  =  $term_data['texto02']; //conseguimos el rango 

        //Hacemos la comparacion y si es verdadera agregamos al nuevo array 
        if ( ($producto == $the_product )  && ($rango == $the_rango) ) {
            array_push( $array_id_tax , $t_ID );
        }
    }

    //Si el array de ids no se encuentra vaacio
    if ( !empty($array_id_tax) )
    {
        $result['result'] = TRUE;

        ob_start();

        include TEMPLATEPATH . '/includes/lockers-ajax.php';

        $content = ob_get_contents();

        ob_get_clean();

        $result['content'] = $content;

    }

    wp_reset_postdata();

    echo json_encode( $result );

    die();
}

/**********************************************************************************/
/*  REGISTRACION WOOCOMMERCE */
/**********************************************************************************/
//Agregar campos al inicio del registro 

add_action( 'woocommerce_register_form_start', 'adding_custom_registration_fields' );
function adding_custom_registration_fields( ) 
{

    //Solicitaremos los nombres y apellidos del nuevo usuario

    $firstname = empty( $_POST['firstname'] ) ? '' : $_POST['firstname'];
    $lastname  = empty( $_POST['lastname'] ) ? 'x' : $_POST['lastname'];
    ?>
    <div class="form-group">
        <input type="text" class="input-text" name="firstname" id="reg_firstname" value="<?php echo esc_attr( $firstname ) ?>" placeholder="Nombre Completo" />
    </div>
    <!--div class="form-group">
        <input type="text" class="input-text" name="lastname" id="reg_lastname" value="<?php echo esc_attr( $lastname ) ?>" placeholder="" />
    </div-->

<?php
}

// Formulario de registro de validación después de la presentación utilizando los filtros registration_errors
add_filter( 'woocommerce_registration_errors', 'registration_errors_validation' );

function registration_errors_validation( $reg_errors ) {

    if ( empty( $_POST['firstname'] ) /*|| empty( $_POST['lastname'] )*/ ) {
        $reg_errors->add( 'empty required fields', __( 'Please fill in the required fields.', 'woocommerce' ) );
    }

    return $reg_errors;
}


//Verficicacion que el email no esté registrado por ajax
add_action( 'wp_ajax_nopriv_check_email', 'check_email_callback' );
function check_email_callback() {
    global $wpdb; // this is how you get access to the database

    // Check its existence (for example, execute a query from the database) ...
    $isAvailable = true; // or false

    if(email_exists($_POST['email'])){
        echo json_encode(array( 'valid' => false ));
    }
    else{
        echo json_encode(array( 'valid' => $isAvailable ));
    }
    die();
}

//Actualización uso meta después de Registrarse registro exitoso
add_action('woocommerce_created_customer','adding_extra_reg_fields');

function adding_extra_reg_fields($user_id) {
    extract($_POST);
    update_user_meta($user_id, 'first_name', $firstname);
    update_user_meta($user_id, 'last_name', ' ');
    update_user_meta($user_id, 'billing_first_name', $firstname);
    update_user_meta($user_id, 'shipping_first_name', $firstname);
    update_user_meta($user_id, 'billing_last_name', 'x');
    update_user_meta($user_id, 'shipping_last_name', 'x');

    //Update fields for checkout order
    update_user_meta($user_id, 'billing_address_1', 'x');
    update_user_meta($user_id, 'billing_postcode', 'PERU01');
    update_user_meta($user_id, 'billing_city', 'x');
    update_user_meta($user_id, 'billing_country', 'PE');
    update_user_meta($user_id, 'billing_state', 'LIM');
    update_user_meta($user_id, 'billing_phone', '000000');
    
}


/**********************************************************************************/
/* REDIRIGIR A LA MISMA PAGINA DESPUES DEL REGISTRO */
/**********************************************************************************/
add_filter( 'woocommerce_registration_redirect', 'my_custom_redirect' );
add_filter( 'woocommerce_login_redirect', 'my_custom_redirect', 10,2);

function my_custom_redirect()
{
    $redirect = $_SERVER["REQUEST_URI"];
    return $redirect;
    exit;
}

/**********************************************************************************/
/* REDIRIGIR A LA MISMA PAGINA DESPUES DE LA COTIZACION EXITOSA  */
/**********************************************************************************/

add_action( 'woocommerce_thankyou', function( $order_id ){
    $order = new WC_Order( $order_id );

    if ( $order->status != 'failed' ) { 

        $order->update_status( 'processing');

        wp_redirect( home_url('cart/order-received/?order='. $order_id) );
    }
});

/*add_action( 'template_redirect', 'wc_custom_redirect_after_purchase' ); 
function wc_custom_redirect_after_purchase() {
    global $wp;
    
    if ( !empty( $wp->query_vars['order-received'] ) ) {
        $order_id = absint( $wp->query_vars['order-received'] );
        $url_cart = home_url( 'cart/order-received/?order='. $order_id );
        wp_redirect( $url_cart );
        exit;
    }
}

*/


/**********************************************************************************/
/* Crear un campo personalizado para la taxonomia "pa_modelos " */
/**********************************************************************************/


//Agregar campo
function categorias_add_new_meta_fields(){

    //Conseguir todos los productos
    $args = array(
        "post_type" => "product",
    );
    $products = get_posts( $args );

    //Conseguir todas las terminos de la taxonomia rango
    $tax  = "pa_rango";
    $args = array(
        "hide_empty" => false,  //mostrar los terminos vacios
    );
 
    $rangos = get_terms( $tax , $args );

    ?>

    <div class="form-field">
        <label for="term_meta[texto01]">Seleccionar producto</label>
        <select name="term_meta[texto01]" id="term_meta[texto01]" value="">
            <?php foreach ($products as $product ) : ?>
                <option value="<?= $product->post_title; ?>"><?= $product->post_title; ?></option>
            <?php endforeach; ?>
        </select>
        <p class="description">Seleccionar producto al que pertenece este modelo</p>
    </div>

    <div class="form-field">
        <label for="term_meta[texto02]">Seleccionar rango</label>
        <select name="term_meta[texto02]" id="term_meta[texto02]" value="">
            <?php foreach ($rangos as $rango ) : ?>
                <option value="<?= $rango->slug; ?>"><?= $rango->name; ?></option>
            <?php endforeach; ?>
        </select>
        <p class="description">Seleccionar el rango al que pertenece este modelo</p>
    </div>

    <?php
}

add_action( 'pa_modelos_add_form_fields', 'categorias_add_new_meta_fields', 10, 2 );


function categorias_edit_meta_fields($term){

    $t_id = $term->term_id;

    $term_meta = get_option("taxonomy_$t_id");
    ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="term_meta[texto01]">Seleccionar producto</label>
            </th>
            <td>
                <select name="term_meta[texto01]" id="term_meta[texto01]" value="<?php echo esc_attr( $term_meta['texto01'] ) ? esc_attr( $term_meta['texto01'] ) : ''; ?>">

                    <?php  
                        $args = array(
                            "post_type" => "product",
                        );

                        $products = get_posts( $args );
                    ?>

                    <?php  foreach ($products as $product ) : ?>
                            
                        <?php  
                            //Si el valor de la opcion es igual al valor del select
                            $selected = $product->post_title == esc_attr( $term_meta['texto01'] ) ? "selected" : "";
                        ?>

                        <option value="<?php echo $product->post_title; ?>" <?= $selected; ?> ><?php echo $product->post_title ?></option>
                    <?php endforeach; ?>

                </select>
                <p class="description">Edite producto al que pertenece este modelo</p>
            </td>
        </tr>
        <tr>
            <th scope="row" valign="top">
                <label for="term_meta[texto02]">Seleccionar rango</label>
            </th>
            <td>
                <select name="term_meta[texto02]" id="term_meta[texto02]" value="<?php echo esc_attr( $term_meta['texto02'] ) ? esc_attr( $term_meta['texto02'] ) : ''; ?>">

                    <?php  
                        $tax  = "pa_rango";
                        $args = array(
                            "hide_empty" => false,  //mostrar los terminos vacios
                        );
                     
                        $rangos = get_terms( $tax , $args );
                    ?>

                    <?php  foreach ($rangos as $rango) : ?>
                            
                        <?php  
                            //Si el valor de la opcion es igual al valor del select
                            $selected = $rango->slug == esc_attr( $term_meta['texto02'] ) ? "selected" : "";
                        ?>

                        <option value="<?= $rango->slug; ?>" <?= $selected; ?> ><?= $rango->name ?></option>
                    <?php endforeach; ?>

                </select>
                <p class="description">Edite rango al que pertenece este modelo</p>
            </td>
        </tr>
    <?php
}
add_action( 'pa_modelos_edit_form_fields', 'categorias_edit_meta_fields', 10, 2 );


function categorias_save_custom_meta( $term_id ) {
    if ( isset( $_POST['term_meta'] ) ) {
        $t_id = $term_id;
        $term_meta = get_option( "taxonomy_$t_id" );
        $cat_keys = array_keys( $_POST['term_meta'] );
        foreach ( $cat_keys as $key ) {
            if ( isset ( $_POST['term_meta'][$key] ) ) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        update_option( "taxonomy_$t_id", $term_meta );
    }
}  
add_action( 'edited_pa_modelos', 'categorias_save_custom_meta', 10, 2 );  
add_action( 'create_pa_modelos', 'categorias_save_custom_meta', 10, 2 );



/***********************************************************************************************/
/*  Customizar columnas en la taxonomía Modelos */
/***********************************************************************************************/
function add_pa_modelos_columns($columns){
    $columns['producto'] = __('Producto Asociado', THEMEDOMAIN); 
    $columns['rango']    = __('Rango Asociado', THEMEDOMAIN); 

    return $columns;
}

add_filter('manage_edit-pa_modelos_columns', 'add_pa_modelos_columns', 10 , 2 );
 

//el primer parametro no envia nada -- investigar $out
//segundo enviar el nombre de columna
//tercero id del post 

function add_pa_modelos_column_content(  $out , $column , $post_id ){

    //texto01 - es para recibir el producto asociado
    //texto02 - para recibir el rango;

    if (  $column == 'producto' ) {
        $product = get_option("taxonomy_$post_id");
        $out     =  $product['texto01'];
    }
    if ( $column == 'rango') {
        $product = get_option("taxonomy_$post_id");
        $out     =  $product['texto02'];
    }
    
    return $out;
}
add_filter('manage_pa_modelos_custom_column', 'add_pa_modelos_column_content', 10, 3 );




/**********************************************************************************/
/* Load Theme Options Page and Custom Widgets */
/**********************************************************************************/
require_once('functions/maletek-theme-customizer.php');
require_once('functions/widget-link-main.php');

function login_errors_message() {
    return 'Ooooops!';
}
add_filter('login_errors', 'login_errors_message');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

add_filter('pre_comment_content', 'wp_specialchars');

