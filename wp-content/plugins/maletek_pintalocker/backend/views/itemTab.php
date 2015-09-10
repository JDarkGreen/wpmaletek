<?php

$table_name=$wpdb->prefix . 'maletek_pintalocker_item';
$object='item';
$controller=  ucfirst($object);

#$result=$wpdb->get_results('DROP TABLE '.$table_name.';',ARRAY_A);
#print_r($result);

$sql = "CREATE TABLE IF NOT EXISTS $table_name (
  id int NOT NULL AUTO_INCREMENT,
  dateUpdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  varName tinytext NOT NULL,
  varContent text NOT NULL,
  intRows int NOT NULL,
  intCols int NOT NULL,
  varCoord tinytext DEFAULT '' NOT NULL,
  varImage tinytext DEFAULT '' NOT NULL,
  varImageFront tinytext DEFAULT '' NOT NULL,
  UNIQUE KEY id (id)
);"; 
dbDelta( $sql );   

#$result=$wpdb->get_results('SHOW COLUMNS FROM '.$table_name.';',ARRAY_A);
#print_r($result);

//$result = $wpdb->get_results( "ALTER TABLE $table_name ADD varCoord tinytext;", ARRAY_A );

?>
<a class="button addReg" itemref="<?php echo $controller ?>" href="#">Agregar Locker</a>
<table id="table<?php echo $controller ?>" class="tableAdmin" itemid="<?php echo $object ?>" itemref="<?php echo MALETEKPL__BACKEND_CONTROLLER ?>?controller=<?php echo $object ?>&action=table" >
    <thead>
        <tr>
            <th >ID</th>
            <th >Item</th>
            <th >Posiciones</th>
            <th >Galería</th>
            <th >Última actualización</th>
            <th >Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<div class="hidden">
    <!--Editor-->
    <a href="#adminForm<?php echo $controller ?>" id="adminForm<?php echo $controller ?>Trigger" class="fancybox"></a>
    <form action="<?php echo MALETEKPL__BACKEND_CONTROLLER ?>" method="post" class="adminForm" id="adminForm<?php echo $controller ?>" itemid="table<?php echo $controller ?>" enctype="multipart/form-data">
        <h2>Formulario de Lockers</h2>
        <div class="statusForm" ></div>
        <div class="field">
            <label>Nombre</label>
            <div class="input">
                <input name="varName" id="varName" class="input-text required loadInput" type="text" title="Contenido inválido" />
            </div>
        </div>
        <div class="field">
            <label>Contenido</label>
            <div class="input">
                <textarea name="varContent" id="varContent" class="input-textarea loadInput"  ></textarea>
            </div>
        </div>
        
        <div class="field">
            <label>Posiciones</label>
            <div class="input">
                <input name="intRows" id="intRows" class="input-text required loadInput" type="number" min="1" value="1" title="Contenido inválido" />
            </div>
        </div>
        
        <div class="field">
            <label>Imagen Galería</label>
            <div class="input">
                <input name="uploadImage" id="uploadImage" class="input-text" type="file" />
                <input name="varImage" id="varImage" type="hidden" class="loadInput" />
            </div>
        </div>
        <div class="field">
            <label>Imagen Frente</label>
            <div class="input">
                <input name="uploadImageFront" id="uploadImageFront" class="input-text" type="file" />
                <input name="varImageFront" id="varImageFront" type="hidden" class="loadInput" />
            </div>
        </div>
        
        <div class="field">
            <label>&nbsp;</label>
            <div class="input">
                <input name="sendReg" type="submit" class="button" value="Guardar" />
                <input name="closeReg" type="button" class="closeReg button" value="Cancel" />
                
                <input type="hidden" name="controller" value="<?php echo $object ?>" />
                <input type="hidden" name="action" value="form" />
                <input type="hidden" name="idReg" id="idReg" class="loadInput" value="" />
            </div>
        </div>
    </form>
    
    <!--Position-->
    <a href="#adminForm<?php echo $controller ?>Position" id="adminForm<?php echo $controller ?>PositionTrigger" class="fancybox"></a>
    <form action="<?php echo MALETEKPL__BACKEND_CONTROLLER ?>" method="post" class="adminForm" id="adminForm<?php echo $controller ?>Position" itemid="table<?php echo $controller ?>" enctype="multipart/form-data">
        <h2>Posiciones de Lockers</h2>
        <div class="statusForm" ></div>
        <div class="field">
            <label>Nombre</label>
            <div class="input" >
                <input readonly="true" name="varName" id="varName" class="input-text required loadInput" type="text" title="Contenido inválido" />
            </div>
        </div>
        <div class="field">
            <label>Esquema</label>
            <div class="loadInput" id="buttonPosition" ></div>
            <div class="input" >
                <div id="coordPosition" class="loadInput"></div>
                <div id="loadPosBlock">
                    <div id="loadPosLoading"></div>
                    <img id="loadPosImg" width="400" height="400" src="" />
                </div>
            </div>
        </div>
        <div class="field">
            <label>&nbsp;</label>
            <div class="input">
                <input name="sendReg" type="submit" class="button" value="Guardar" />
                <input name="closeReg" type="button" class="closeReg button" value="Cancel" />
                
                <input type="hidden" name="controller" value="itemPosition" />
                <input type="hidden" name="action" value="form" />
                <input type="hidden" name="idReg" id="idReg" class="loadInput" value="" />
            </div>
        </div>
    </form>
    
    <!--Delete-->
    <a href="#adminDelete<?php echo $controller ?>" class="fancybox" id="triggerDelete<?php echo $controller ?>"></a>
    <form itemid="table<?php echo $controller ?>" action="<?php echo MALETEKPL__BACKEND_CONTROLLER ?>" method="post" id="adminDelete<?php echo $controller ?>" class="adminDelete">
        <h3>¿Seguro de eliminar el registro del Locker?</h3>
        <input name="sendReg" type="submit" class="button" value="Eliminar" />
        <input name="sendReg" type="button" class="button closeReg" value="Cancelar" />
        
        <input type="hidden" name="controller" value="<?php echo $object ?>" />
        <input type="hidden" name="action" value="delete" />
        <input type="hidden" name="id" id="idReg" value="" />
    </form>
</div>