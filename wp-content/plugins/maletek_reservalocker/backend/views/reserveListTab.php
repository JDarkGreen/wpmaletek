<?php

$table_name=$wpdb->prefix . 'maletek_reservalocker_reserve';
$object='reserve';
$controller=  ucfirst($object);


$sql = "CREATE TABLE IF NOT EXISTS $table_name (
  id int NOT NULL AUTO_INCREMENT,
  dateCreate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  dateUpdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  idItem int NOT NULL,
  idModule int NOT NULL,
  idLocker int NOT NULL,
  idUser int NOT NULL,
  charSt int NOT NULL,
  varShareName varchar(100) NOT NULL,
  varShareCode varchar(100) NOT NULL,
  varShareCareer varchar(100) NOT NULL,
  varShareLevel varchar(100) NOT NULL,
  UNIQUE KEY id (id)
);"; 
dbDelta( $sql ); 


?>
<a class="button addReg" itemref="<?php echo $controller ?>" href="#">Agregar Reserva</a>
<table id="table<?php echo $controller ?>" class="tableAdmin" itemid="<?php echo $object ?>" itemref="<?php echo MALETEKPL_RSV_BACKEND_CONTROLLER ?>?controller=<?php echo $object ?>&action=table" >
    <thead>
        <tr>
            <th >ID</th>
            <th >Nombre</th>
            <th >Instituto</th>
            <th >Modelo</th>
            <th> N° Locker</th>
            <th> Estado</th>
            <th >Fecha de solicitud</th>
            <th >Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<div class="hidden">
    <a href="#adminForm<?php echo $controller ?>" id="adminForm<?php echo $controller ?>Trigger" class="fancybox"></a>
    <form action="<?php echo MALETEKPL_RSV_BACKEND_CONTROLLER ?>" method="post" class="adminForm" id="adminForm<?php echo $controller ?>" itemid="table<?php echo $controller ?>" enctype="multipart/form-data">
        <h2>Formulario de Solicitudes</h2>
        <p>Luego de tener elegido al estudiante, se cargarán las opciones.</p>
        <div class="statusForm" ></div>
        <div class="field">
            <label>Usuario</label>
            <div class="input">
                <input name="varUser" id="varUser" class="input-text required loadInput " type="text" title="Contenido inválido" itemid="idUserReserve" itemref="User" itemtype="selector"  />
                <input name="idUser" id="idUserReserve" class="input-text required loadInput " type="hidden"/>
            </div>
        </div>
        <div class="field">
            <label>Instituto</label>
            <div class="input loadReserve" id="idLocal">
                Debe elegir usuario 1ro...
            </div>
        </div>
        <div class="field">
            <label>Sector / Módulo</label>
            <div class="input loadReserve" id="idModule">
                Debe elegir usuario 1ro...
            </div>
        </div>         
        <div class="field">
            <label>N° Locker</label>
            <div class="input loadReserve" id="idLocker">
                Debe elegir usuario 1ro...
            </div>
        </div>    
        <div class="field">
            <label>Estatus</label>
            <div class="input">
                <select name="charSt" id="charSt" class="input-text required loadInput">
                    <option value="0">Reservado</option>
                    <option value="1">Ocupado</option>
                </select>
            </div>
        </div>
        
        <h3>Datos de usuario compartido</h3>
        <div class="field">
            <label>Usuario</label>
            <div class="input">
                <input name="varShareName" id="varShareName" class="input-text loadInput " type="text" title="Contenido inválido" />
            </div>
        </div>
        <div class="field">
            <label>Código de Estudiante</label>
            <div class="input">
                <input name="varShareCode" id="varShareCode" class="input-text loadInput " type="text" title="Contenido inválido" />
            </div>
        </div>
        <div class="field">
            <label>Carrera</label>
            <div class="input">
                <input name="varShareCareer" id="varShareCareer" class="input-text loadInput " type="text" title="Contenido inválido" />
            </div>
        </div>
        <div class="field">
            <label>Ciclo</label>
            <div class="input">
                <input name="varShareLevel" id="varShareLevel" class="input-text loadInput " type="text" title="Contenido inválido" />
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

