<?php

function friendlyUrl($str){
    //Title to friendly URL conversion
    #$str=substr($str,0,20); // First 6 words
    $str=preg_replace('/[^a-z0-9 ]/i','', $str);
    $str=str_replace(" ","-",$str);
    #$str=preg_replace('/[^a-z0-9 ñÑáÁéÉíÍóÓúÚüÜ]/i','', $str);
    #$str=str_replace(array(" ",'á','Á'),"-",$str);

    return strtolower($str);
}
    
function formatDate($date='',$format='%d-%m-%Y %I:%M%p'){    
    $date=(empty($date)?date('Y-m-d H:i:s'):$date);
    
    if(strstr($format,'%')){
        $dateResult=strftime($format,strtotime($date));
    }
    else{
        $dateResult=date($format,strtotime($date));
    }

    return ucwords(htmlentities($dateResult));
}

function maletek_pintalocker_menu(){
    add_menu_page('Maletek Pinta-Locker', 'Pinta-Locker', 'manage_options', 'Maletek-Pinta-Locker', 'maletek_pintalocker_view',null,null);
    #add_options_page('Maletek Pinta-Locker', 'Pinta-Locker', 'manage_options', 'Maletek-Pinta-Locker', 'maletek_pintalocker_view');
}

function maletek_pintalocker_view(){
    
    include_once(MALETEKPL__PLUGIN_DIR.'backend/views/index.php');
}

function maletek_pintalocker_install() {
    /*creating tables*/
    global $wpdb;
    
    $tablePrefix=$wpdb->prefix . 'maletek_pintalocker';
    
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

   #lockers
   $table_name =  $tablePrefix."item"; 
   
   $sql = "CREATE TABLE IF NOT EXIST $table_name (
     id int NOT NULL AUTO_INCREMENT,
     dateUpdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
     varName tinytext NOT NULL,
     varContent text NOT NULL,
     intRows int NOT NULL,
     intCols int NOT NULL,
     varImage tinytext DEFAULT '' NOT NULL,
     UNIQUE KEY id (id)
   ) $charset_collate;";
   dbDelta( $sql );
   
   $table_name = $tablePrefix."item"; 
   
   $sql = "CREATE TABLE IF NOT EXIST $table_name (
     id int NOT NULL AUTO_INCREMENT,
     dateUpdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
     varName tinytext NOT NULL,
     varCode tinytext NOT NULL,
     UNIQUE KEY id (id)
   ) $charset_collate;";
   dbDelta( $sql );
     
   #add_option( $tablePrefix."version", $current_version );
}

function maletek_pintalocker_remove() {
    global $wpdb;

    $table_name = $wpdb->prefix . "maletek_pintalocker"; 
    
    $sql = "DROP TABLE $table_name"; 
        
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    #dbDelta( $sql );
}