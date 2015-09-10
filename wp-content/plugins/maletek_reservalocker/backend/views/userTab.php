<?php
$object='user';
$table_name=$wpdb->prefix . 'maletek_reservalocker_'.$object;

$controller=  ucfirst($object);

$sql = "CREATE TABLE IF NOT EXISTS $table_name (
  id int NOT NULL AUTO_INCREMENT,
  dateUpdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  varName tinytext NOT NULL,
  varEmail tinytext NOT NULL,
  varPhone tinytext NOT NULL,
  idLocal int NOT NULL,
  varDni tinytext NOT NULL,
  varCode tinytext NOT NULL,
  varCareer tinytext NOT NULL,
  varLevel tinytext NOT NULL,
  varPass char(15) NOT NULL,
  UNIQUE KEY id (id)
);"; 
dbDelta( $sql );   

?>
<a class="button addReg" itemref="<?php echo $controller ?>" href="#">Agregar Usuario</a>
<table id="table<?php echo $controller ?>" class="tableAdmin" itemid="<?php echo $object ?>" itemref="<?php echo MALETEKPL_RSV_BACKEND_CONTROLLER ?>?controller=<?php echo $object ?>&action=table" >
    <thead>
        <tr>
            <th >ID</th>
            <th >Nombre</th>
            <th >Email</th>
            <th >Instituto</th>
            <th >Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<div class="hidden">
    <a href="#adminForm<?php echo $controller ?>" id="adminForm<?php echo $controller ?>Trigger" class="fancybox"></a>
    <form action="<?php echo MALETEKPL_RSV_BACKEND_CONTROLLER ?>" method="post" class="adminForm" id="adminForm<?php echo $controller ?>" itemid="table<?php echo $controller ?>" enctype="multipart/form-data">
        <h2>Formulario de Usuarios</h2>
        <div class="statusForm" ></div>
        <div class="field">
            <label>Nombre</label>
            <div class="input">
                <input name="varName" id="varName" class="input-text required loadInput" type="text" title="Contenido inválido" />
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
            <label>DNI / Carnét de Extranjería</label>
            <div class="input">
                <input name="varDni" id="varDni" class="input-text required digit loadInput" type="text" title="Contenido inválido" />
            </div>
        </div>
        <div class="field">
            <label>Instituto</label>
            <div class="input">
                <input name="varLocal" id="varLocal" class="input-text required loadInput input-autocomplete" type="text" title="Contenido inválido" itemid="idLocalUser" itemref="Local" itemtype="selector"  />
                <input name="idLocal" id="idLocalUser" class="input-text required loadInput " type="hidden"/>
            </div>
        </div>
        <div class="field">
            <label>Código de Estudiante</label>
            <div class="input">
                <input name="varCode" id="varCode" class="input-text required loadInput" type="text" title="Contenido inválido" />
            </div>
        </div>
        <div class="field">
            <label>Carrera</label>
            <div class="input">
                <input name="varCareer" id="varCareer" class="input-text required loadInput" type="text" title="Contenido inválido" />
            </div>
        </div>
        <div class="field">
            <label>Ciclo</label>
            <div class="input">
                <input name="varLevel" id="varLevel" class="input-text required loadInput" type="text" title="Contenido inválido" />
            </div>
        </div>        
        <div class="field">
            <label>Password</label>
            <div class="input">
                <input name="varPass" id="varPass" class="input-text required loadInput" type="password" title="Contenido inválido" />
            </div>
        </div>      
        <div class="field">
            <label>Re-Password</label>
            <div class="input">
                <input name="rePass" id="rePass" class="input-text required loadInput" type="password" title="Contenido inválido" />
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