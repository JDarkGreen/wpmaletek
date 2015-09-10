<?php
/*
Plugin Name: Maletek Reserva-Locker
Plugin URI: http://www.adinspector.com/
Description: Crea registros de locker y permite a usuario reservaros
Author: Adinspector.com
Author URI: http://www.adinspector.com/
Version: 1.0
License: GPLv2
*/

define( 'MALETEKPL_RSV_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MALETEKPL_RSV_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MALETEKPL_RSV_PLUGIN_PATH', __FILE__  );
define( 'MALETEKPL_RSV_BACKEND_CONTROLLER', plugin_dir_url( __FILE__ ).'backend/controller/' );
define( 'MALETEKPL_RSV_FRONTEND_CONTROLLER', plugin_dir_url( __FILE__ ).'frontend/controller/' );

#define( 'DS', DIRECTORY_SEPARATOR);

global $current_version;
$current_version = '1.0';

/* What to do when the plugin is activated? */
register_activation_hook(__FILE__,'maletek_reservalocker_install');

/* What to do when the plugin is deactivated? */
register_deactivation_hook( __FILE__, 'maletek_reservalocker_remove' );

/*Admin menus*/
add_action('admin_menu','maletek_reservalocker_menu');
/*
add_action( 'phpmailer_init', 'maletek_reservalocker_phpmailer' );
function maletek_reservalocker_phpmailer( PHPMailer $phpmailer ) {
    $phpmailer->Host = 'smtp.gmail.com';
    $phpmailer->Port = 25; // could be different
    $phpmailer->Username = 'dlmo18@gmail.com'; // if required
    $phpmailer->Password = 'Slifer83#Dragn'; // if required
    $phpmailer->SMTPAuth = true; // if required
    // $phpmailer->SMTPSecure = 'ssl'; // enable if required, 'tls' is another possible value
    
    $phpmailer->IsSMTP();
}
*/
include_once MALETEKPL_RSV_PLUGIN_DIR.'backend/functions.php';
include_once MALETEKPL_RSV_PLUGIN_DIR.'frontend/functions.php';
