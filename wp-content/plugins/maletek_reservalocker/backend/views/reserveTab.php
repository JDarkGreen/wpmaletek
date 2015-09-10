<?php

$table_name=$wpdb->prefix . 'maletek_reservalocker_reserve';
$object='reserve';
$controller=  ucfirst($object);

#$sqlDp = "DROP TABLE $table_name"; 
#dbDelta( $sqlDp );
   
$sql = "CREATE TABLE IF NOT EXISTS $table_name (
  id int NOT NULL AUTO_INCREMENT,
  dateCreate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  dateUpdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  idItem int NOT NULL,
  idModule int NOT NULL,
  idLocker int NOT NULL,
  idUser int NOT NULL,
  charSt int NOT NULL,
  UNIQUE KEY id (id)
);"; 
dbDelta( $sql ); 

?>
<div id="reserveTree">    
    <h1>Espere por favor...</h1>
</div>
<div id="reserveModule">
</div>
<div class="clear"></div>

