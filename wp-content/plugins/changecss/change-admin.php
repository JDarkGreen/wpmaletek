<?php  

/*
	Plugin Name: Customize the Admin Dashboard
	Plugin URI: 
	Description: Customiza el dashboard.
	Version: 1.0
	Author: Green
	Author URI: 
	License: GPLv2
*/

/***********************************************************************************************/
/* Cambiar los estilos del administrador  */
/***********************************************************************************************/

function maletek_admin_styles() 
{
    wp_register_style( 'maletek_admin_stylesheet', plugins_url( '/css/style.css', __FILE__ ) );
    wp_enqueue_style( 'maletek_admin_stylesheet' );
}
add_action( 'admin_enqueue_scripts', 'maletek_admin_styles' );







?>



