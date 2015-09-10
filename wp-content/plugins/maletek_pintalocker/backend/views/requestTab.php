<?php

$table_name=$wpdb->prefix . 'maletek_pintalocker_request';
$object='request';
$controller=  ucfirst($object);

#$sqlDp = "DROP TABLE $table_name"; 
#dbDelta( $sqlDp );
   
$sql = "CREATE TABLE IF NOT EXISTS $table_name (
  id int NOT NULL AUTO_INCREMENT,
  idItem int NOT NULL,
  dateCreate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  dateUpdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  varName tinytext NOT NULL,
  varLastName tinytext NOT NULL,
  varBusiness tinytext NOT NULL,
  varEmail tinytext NOT NULL,
  varPhone tinytext NOT NULL,
  varSample tinytext NOT NULL,
  varContent text NOT NULL,
  UNIQUE KEY id (id)
);"; 
dbDelta( $sql ); 

$result=$wpdb->get_results('SHOW COLUMNS FROM '.$table_name.';',ARRAY_A);
//print_r($result);
#$result = $wpdb->get_results( "ALTER TABLE $table_name ADD varBusiness tinytext;", ARRAY_A );

?>
<a class="button addReg" itemref="<?php echo $controller ?>" href="#">Agregar Solicitud</a>
<table id="table<?php echo $controller ?>" class="tableAdmin" itemid="<?php echo $object ?>" itemref="<?php echo MALETEKPL__BACKEND_CONTROLLER ?>?controller=<?php echo $object ?>&action=table" >
    <thead>
        <tr>
            <th >ID</th>
            <th >Nombre</th>
            <th >Contactos</th>
            <th >Esquema</th>
            <th >Fecha de solicitud</th>
            <th >Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<div class="hidden">
    <a href="#adminForm<?php echo $controller ?>" id="adminForm<?php echo $controller ?>Trigger" class="fancybox"></a>
    <form action="<?php echo MALETEKPL__BACKEND_CONTROLLER ?>" method="post" class="adminForm" id="adminForm<?php echo $controller ?>" itemid="table<?php echo $controller ?>" enctype="multipart/form-data">
        <h2>Formulario de Solicitudes</h2>
        <div class="statusForm" ></div>
        <div class="field">
            <label>Nombres</label>
            <div class="input">
                <input name="varName" id="varName" class="input-text required loadInput" type="text" title="Contenido inválido" />
            </div>
        </div>
        <div class="field">
            <label>Apellidos</label>
            <div class="input">
                <input name="varLastName" id="varLastName" class="input-text required loadInput" type="text" title="Contenido inválido" />
            </div>
        </div>
        <div class="field">
            <label>Email</label>
            <div class="input">
                <input name="varEmail" id="varEmail" class="input-text email required loadInput" type="text" title="Contenido inválido" />
            </div>
        </div>
        <div class="field">
            <label>Teléfono</label>
            <div class="input">
                <input name="varPhone" id="varPhone" class="input-text required loadInput" type="text" title="Contenido inválido" />
            </div>
        </div>
        <div class="field">
            <label>Empresa (opcional)</label>
            <div class="input">
                <input name="varBusiness" id="varBusiness" class="input-text loadInput" type="text" title="Contenido inválido" />
            </div>
        </div>        
        <div class="field">
            <label>Esquema</label>
            <input type="hidden" name="varContent" id="varContent" value="" class="loadInput" />
            <div class="input loadBlock" id="requestScheme"></div>
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
    
    <a href="#adminDelete<?php echo $controller ?>" class="fancybox" id="triggerDelete<?php echo $controller ?>"></a>
    <form itemid="table<?php echo $controller ?>" action="<?php echo MALETEKPL__BACKEND_CONTROLLER ?>" method="post" id="adminDelete<?php echo $controller ?>" class="adminDelete">
        <h3>¿Seguro de eliminar el registro del Color?</h3>
        <input name="sendReg" type="submit" class="button" value="Eliminar" />
        <input name="sendReg" type="button" class="button closeReg" value="Cancelar" />
        
        <input type="hidden" name="controller" value="<?php echo $object ?>" />
        <input type="hidden" name="action" value="delete" />
        <input type="hidden" name="id" id="idReg" value="" />
    </form>
</div>

