<?php

$table_name=$wpdb->prefix . 'maletek_pintalocker_config';
$object='config';
$controller=  ucfirst($object);

#$sqlDp = "DROP TABLE $table_name"; 
#dbDelta( $sqlDp );
   
$sql = "CREATE TABLE IF NOT EXISTS $table_name (
  id int NOT NULL AUTO_INCREMENT,
  dateUpdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  varName tinytext NOT NULL,
  varValue longtext DEFAULT '' NOT NULL,
  UNIQUE KEY id (id)
);"; 
dbDelta( $sql );   

#$result = $wpdb->get_results( "ALTER TABLE $table_name ADD varCoord tinytext;", ARRAY_A );
?>
<form action="<?php echo MALETEKPL__BACKEND_CONTROLLER ?>" method="post" class="adminForm" id="adminForm<?php echo $controller ?>" itemid="table<?php echo $controller ?>" enctype="multipart/form-data">
    <h2>Formulario de Configuración</h2>
    <div class="statusForm" ></div>
    
    <div class="field clear">
        <label>Instrucciones</label>
        <div class="input">
            <textarea name="textTutorial" id="textTutorial" class="input-textarea loadInput" title="Contenido inválido"></textarea>
        </div>
    </div>
    <div class="field">
        <label>Asunto de Email</label>
        <div class="input">
            <input name="textSubject" id="textSubject" class="input-text required loadInput" type="text" title="Contenido inválido" />
        </div>
    </div>
    <div class="field clear">
        <label>Cuerpo de Email</label>
        <div class="input">
            <textarea name="textBody" id="textBody" class="input-textarea required loadInput" title="Contenido inválido"></textarea>
            <br>
            Debe usar la cadena de texto <i>{link}</i> para definir la posición del enlace de la copia de la solicitud.
        </div>
    </div> 
    <div class="field clear">
        <label>Email emisor</label>
        <div class="input">
            <input name="varEmisorEmail" id="varEmisorEmail" class="input-text required email loadInput" type="text" title="Contenido inválido" />
        </div>
    </div> 
    <div class="field clear">
        <label>Nombre email emisor</label>
        <div class="input">
            <input name="varEmisorName" id="varEmisorName" class="input-text required loadInput" type="text" title="Contenido inválido" />
        </div>
    </div>
    <div class="field clear">
        <label>Emails Receptores: (separarlos por comas)</label>
        <div class="input">
            <textarea name="varReceptor" id="varReceptor" class="input-textarea required loadInput" title="Contenido inválido" ></textarea>
        </div>
    </div>
    <div class="field clear">
        <label>&nbsp;</label>
        <div class="input">
            <input name="sendReg" type="submit" class="button" value="Guardar" />
            
            <input type="hidden" name="controller" value="<?php echo $object ?>" />
            <input type="hidden" name="action" value="form" />
            <input type="hidden" name="idReg" id="idReg" class="loadInput" value="" />
        </div>
    </div>
</form>
    
