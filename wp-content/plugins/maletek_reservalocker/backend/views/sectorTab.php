<?php
$object='sector';
$table_name=$wpdb->prefix . 'maletek_reservalocker_'.$object;

$controller=  ucfirst($object);

#$sqlDp = "DROP TABLE $table_name"; 
#dbDelta( $sqlDp );
   
$sql = "CREATE TABLE IF NOT EXISTS $table_name (
  id int NOT NULL AUTO_INCREMENT,
  dateUpdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  varName tinytext NOT NULL,
  varArea varchar(50) NOT NULL,
  idLocal int NOT NULL,
  UNIQUE KEY id (id)
);"; 
dbDelta( $sql );   

?>
<a class="button addReg" itemref="<?php echo $controller ?>" href="#">Agregar Sector/Área</a>
<table id="table<?php echo $controller ?>" class="tableAdmin" itemid="<?php echo $object ?>" itemref="<?php echo MALETEKPL_RSV_BACKEND_CONTROLLER ?>?controller=<?php echo $object ?>&action=table" >
    <thead>
        <tr>
            <th >ID</th>
            <th >Sector</th>
            <th >Área</th>
            <th >Local</th>
            <th >Última actualización</th>
            <th >Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<div class="hidden">
    <a href="#adminForm<?php echo $controller ?>" id="adminForm<?php echo $controller ?>Trigger" class="fancybox"></a>
    <form action="<?php echo MALETEKPL_RSV_BACKEND_CONTROLLER ?>" method="post" class="adminForm" id="adminForm<?php echo $controller ?>" itemid="table<?php echo $controller ?>" enctype="multipart/form-data">
        <h2>Formulario de Sectores</h2>
        <div class="statusForm" ></div>
        <div class="field">
            <label>Local</label>
            <div class="input">
                
            </div>
        </div>
        <div class="field">
            <label>Área</label>
            <div class="input">
                <input name="varArea" id="varName" class="input-text required loadInput" type="text" title="Contenido inválido" />
            </div>
        </div>
        <div class="field">
            <label>Sector</label>
            <div class="input">
                <input name="varName" id="varName" class="input-text required loadInput" type="text" title="Contenido inválido" />
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
    
    <a href="#adminDelete<?php echo $controller ?>" class="fancybox" id="triggerDelete<?php echo $controller ?>"></a>
    <form itemid="table<?php echo $controller ?>" action="<?php echo MALETEKPL_RSV_BACKEND_CONTROLLER ?>" method="post" id="adminDelete<?php echo $controller ?>" class="adminDelete">
        <h3>¿Seguro de eliminar el registro?</h3>
        <input name="sendReg" type="submit" class="button" value="Eliminar" />
        <input name="sendReg" type="button" class="button closeReg" value="Cancelar" />
        
        <input type="hidden" name="controller" value="<?php echo $object ?>" />
        <input type="hidden" name="action" value="delete" />
        <input type="hidden" name="id" id="idReg" value="" />
    </form>
</div>