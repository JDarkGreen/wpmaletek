<?php
function maletek_reservalocker_myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function maletek_reservalocker_myEndSession() {
    session_destroy ();
}

function maletek_reservalocker_load($content) {
    
    if (!session_id()){
        session_start();
    }
    
    if( strstr($content,'{RESERVA__LOCKER}') ){
        include_once(MALETEKPL_RSV_PLUGIN_DIR.'frontend/views/index.php');
        $content='';
    }
    
    return $content;
}
function maletek_reservalocker_frontend_scripts() {
    wp_enqueue_style('maletek_reservalocker_frontend_css', plugins_url( 'css/frontend.css', MALETEKPL_RSV_PLUGIN_PATH ) );
    //wp_enqueue_script( 'maletek_reservalocker_carouFredSel', plugins_url( 'js/jquery.carouFredSel.js', MALETEKPL_RSV_PLUGIN_PATH ), array( 'jquery' ) );
    wp_enqueue_script( 'maletek_reservalocker_jQueryForm', plugins_url( 'js/jquery.form.js', MALETEKPL_RSV_PLUGIN_PATH ), array( 'jquery' ) );
    wp_enqueue_script( 'maletek_reservalocker_frontend_js', plugins_url( 'js/frontend.js', MALETEKPL_RSV_PLUGIN_PATH ), array( 'jquery' ) );
}

add_action('init', 'maletek_reservalocker_myStartSession', 1);
add_action('maletek_reservalocker_logout', 'maletek_reservalocker_myEndSession');
add_action('maletek_reservalocker_login', 'maletek_reservalocker_myEndSession');

add_action( 'wp_enqueue_scripts', 'maletek_reservalocker_frontend_scripts' );
add_filter('the_content', 'maletek_reservalocker_load');