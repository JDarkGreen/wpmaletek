<?php

function maletek_store_load($content) {
    
    if( strstr($content,'{PINTA__LOCKER}') ){
        include_once(MALETEKPL__PLUGIN_DIR.'frontend/views/index.php');
        $content='';
    }
    
    return $content;
}
function maletek_frontend_scripts() {
    echo date('Ymd His');
    wp_register_style('maletek_frontend_css', plugins_url( '/css/frontend.css', __FILE__ ) );

    wp_register_script( 'maletek_frontend_ui', plugins_url( '/js/jquery-ui/jquery-ui.min.js', __FILE__ ), array( 'jquery' ) );
    wp_register_script( 'maletek_frontend_form', plugins_url( '/js/jquery.form.js', __FILE__ ), array( 'jquery' ) );
    wp_register_script( 'maletek_frontend_validate', plugins_url( '/js/jquery.validate.js', __FILE__ ), array( 'jquery' ) );
    wp_register_script( 'maletek_frontend_carouFredSel', plugins_url( '/js/jquery.carouFredSel.js', __FILE__ ), array( 'jquery' ) );
    wp_register_script( 'maletek_frontend_js', plugins_url( '/js/frontend.js', __FILE__ ), array( 'jquery' ) );
}

#add_action( 'wp_enqueue_scripts', 'maletek_frontend_scripts' );
add_filter('the_content', 'maletek_store_load');