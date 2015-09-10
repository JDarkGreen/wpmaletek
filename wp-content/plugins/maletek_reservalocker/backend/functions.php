<?php

function rsvlk_friendlyUrl($str){
    //Title to friendly URL conversion
    #$str=substr($str,0,20); // First 6 words
    $str=preg_replace('/[^a-z0-9 ]/i','', $str);
    $str=str_replace(" ","-",$str);
    #$str=preg_replace('/[^a-z0-9 ñÑáÁéÉíÍóÓúÚüÜ]/i','', $str);
    #$str=str_replace(array(" ",'á','Á'),"-",$str);

    return strtolower($str);
}
    
function rsvlk_formatDate($date='',$format='%d-%m-%Y %I:%M%p'){    
    $date=(empty($date)?date('Y-m-d H:i:s'):$date);
    
    if(strstr($format,'%')){
        $dateResult=strftime($format,strtotime($date));
    }
    else{
        $dateResult=date($format,strtotime($date));
    }

    return ucwords(htmlentities($dateResult));
}

function maletek_reservalocker_menu(){
    add_menu_page('Maletek Reserva-Locker', 'Reserva-Locker', 'manage_options', 'Maletek-Reserva-Locker', 'maletek_reservalocker_view',null,null);
    #add_options_page('Maletek Reserva-Locker', 'Reserva-Locker', 'manage_options', 'Maletek-Reserva-Locker', 'maletek_reservalocker_view');
}

function maletek_reservalocker_view(){
    
    include_once(MALETEKPL_RSV_PLUGIN_DIR.'backend/views/index.php');
}

function maletek_reservalocker_install() {
    /*creating tables*/
    global $wpdb;
    
    $tablePrefix=$wpdb->prefix . 'maletek_reservalocker';
    
    /*
    * We'll set the default character set and collation for this table.
    * If we don't do this, some characters could end up being converted 
    * to just ?'s when saved in our table.
    */
   $charset_collate = '';

   if ( ! empty( $wpdb->charset ) ) {
     $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
   }

   if ( ! empty( $wpdb->collate ) ) {
     $charset_collate .= " COLLATE {$wpdb->collate}";
   }
   
   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

   #add_option( $tablePrefix."version", $current_version );
}

function maletek_reservalocker_remove() {
    global $wpdb;

    $table_name = $wpdb->prefix . "maletek_reservalocker"; 
    
    $sql = "DROP TABLE $table_name"; 
        
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    #dbDelta( $sql );
}