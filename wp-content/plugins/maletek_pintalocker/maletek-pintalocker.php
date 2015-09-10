<?php
/*
Plugin Name: Maletek Pinta-Locker
Plugin URI: http://www.adinspector.com/
Description: Crea registros de locker y permite pintarlos
Author: AdInspector.com
Author URI: http://www.adinspector.com/
Version: 1.0
License: GPLv2
*/

define( 'MALETEKPL__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MALETEKPL__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MALETEKPL__BACKEND_CONTROLLER', plugin_dir_url( __FILE__ ).'backend/controller/' );
define( 'MALETEKPL__FRONTEND_CONTROLLER', plugin_dir_url( __FILE__ ).'frontend/controller/' );

define( 'DS', DIRECTORY_SEPARATOR);

global $current_version;
$current_version = '1.0';

/* What to do when the plugin is activated? */
register_activation_hook(__FILE__,'maletek_pintalocker_install');

/* What to do when the plugin is deactivated? */
register_deactivation_hook( __FILE__, 'maletek_pintalocker_remove' );

/*Admin menus*/
add_action('admin_menu','maletek_pintalocker_menu');

include_once MALETEKPL__PLUGIN_DIR.'backend/functions.php';
include_once MALETEKPL__PLUGIN_DIR.'frontend/functions.php';



