<?php
$object='item';
$table_name=$wpdb->prefix . 'maletek_reservalocker_'.$object;

$controller=  ucfirst($object);

#$sqlDp = "DROP TABLE $table_name"; 
#dbDelta( $sqlDp );
   
$sql = "CREATE TABLE IF NOT EXISTS $table_name (
  id int NOT NULL AUTO_INCREMENT,
  dateUpdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  varName tinytext NOT NULL,
  varImage tinytext NOT NULL,
  varArea varchar(50) NOT NULL,
  idLocal int NOT NULL,
  UNIQUE KEY id (id)
);"; 
dbDelta( $sql );   
$sql = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix . 'maletek_reservalocker_module'." (
  id int NOT NULL AUTO_INCREMENT,
  dateUpdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  idItem int NOT NULL,
  idModel int NOT NULL,
  intSerie int NOT NULL,
  UNIQUE KEY id (id)
);"; 
dbDelta( $sql ); 

?>
<a class="button addReg" itemref="<?php echo $controller ?>" href="#">Agregar Área/Sector</a>
<table id="table<?php echo $controller ?>" class="tableAdmin" itemid="<?php echo $object ?>" itemref="<?php echo MALETEKPL_RSV_BACKEND_CONTROLLER ?>?controller=<?php echo $object ?>&action=table" >
    <thead>
        <tr>
            <th >ID</th>
            <th >Sector</th>
            <th >Área</th>
            <th >Instituto</th>
            <th >Módulos/Lockers</th>
            <th >Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<div class="hidden">
    <a href="#adminForm<?php echo $controller ?>Module" id="adminForm<?php echo $controller ?>ModuleTrigger" class="fancybox"></a>
    <form action="<?php echo MALETEKPL_RSV_BACKEND_CONTROLLER ?>" method="post" class="adminForm" id="adminForm<?php echo $controller ?>Module" itemid="table<?php echo $controller ?>" enctype="multipart/form-data">
        <h2>Formulario de Módulos</h2>
        <p>Puede configurar hasta 10 módulos y para eliminar el contenido deje vacío el campo de nombre.</p>
        <div class="statusForm" ></div>
        <div class="field">
            <label>Lugar</label>
            <div class="input">
                <input name="varSector" id="varSector" class="input-text loadInput " type="text" readonly=""/>
            </div>
        </div>
            <?php for($i=1;$i<=10;$i++){ ?>
        <div class="field">
            <label>Modelo/Serie - N°<?php echo $i ?></label>
            <div class="input" style="width: 310px;">
                <input type="hidden" name="idReg<?php echo $i ?>" id="idReg<?php echo $i ?>" class="loadInput" value="" />
                <input style="width: 160px;" name="varModel<?php echo $i ?>" id="varModel<?php echo $i ?>" class="input-text loadInput input-autocomplete" type="text" title="Contenido inválido" itemid="idModel<?php echo $i ?>" itemref="Model" itemtype="selector"  />
                <input name="idModel<?php echo $i ?>" id="idModel<?php echo $i ?>" class="input-text loadInput " type="hidden"/>
                <input placeholder="1er Nro. Locker" style="width: 50px;" name="intSerie<?php echo $i ?>" id="intSerie<?php echo $i ?>" class="input-text digit loadInput" type="text" title="Contenido inválido" />
                <input placeholder="Precio S/." style="width: 80px;" name="floatPrice<?php echo $i ?>" id="floatPrice<?php echo $i ?>" class="input-text numeric loadInput" type="text" title="Contenido inválido" />
            </div>
        </div>
            <?php } ?>
        
        <div class="field">
            <label>&nbsp;</label>
            <div class="input">
                <input name="sendReg" type="submit" class="button" value="Guardar" />
                <input name="closeReg" type="button" class="closeReg button" value="Cancel" />
                <input type="hidden" name="idItem" id="idItem" value="" class="loadInput" />
                <input type="hidden" name="controller" value="itemModule" />
                <input type="hidden" name="action" value="form" />                
            </div>
        </div>
    </form>
    
    <a href="#adminForm<?php echo $controller ?>" id="adminForm<?php echo $controller ?>Trigger" class="fancybox"></a>
    <form action="<?php echo MALETEKPL_RSV_BACKEND_CONTROLLER ?>" method="post" class="adminForm" id="adminForm<?php echo $controller ?>" itemid="table<?php echo $controller ?>" enctype="multipart/form-data">
        <h2>Formulario de Sectores</h2>
        <div class="statusForm" ></div>
        <div class="field">
            <label>Instituto</label>
            <div class="input">
                <input name="varLocal" id="varLocal" class="input-text required loadInput input-autocomplete" type="text" title="Contenido inválido" itemid="idLocal" itemref="Local" itemtype="selector"  />
                <input name="idLocal" id="idLocal" class="input-text required loadInput " type="hidden"/>
            </div>
        </div>
        <div class="field">
            <label>Área</label>
            <div class="input">
                <input name="varArea" id="varArea" class="input-text required loadInput input-autocomplete" type="text" title="Contenido inválido" itemid="varArea" itemref="Item" itemtype="selectorArea"  />
            </div>
        </div>
        <div class="field">
            <label>Sector</label>
            <div class="input">
                <input name="varName" id="varName" class="input-text required loadInput" type="text" title="Contenido inválido" />
            </div>
        </div>
        <div class="field">
            <label>Imagen</label>
            <div class="input">
                <input name="uploadImage" id="uploadImage" class="input-text" type="file" />
                <input name="varImage" id="varImage" type="hidden" itemref="<?php echo MALETEKPL_RSV_PLUGIN_URL.'resources/sector/' ?>" class="loadInput loadImage" />
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