<?php
class UserController {
    
    private $table_name=null;
    private $DB=null;
    
    function __construct() 
    {
        global $DB;
        $this->DB =& $DB;
        
        $this->table_name=WP_PREFIX . 'maletek_reservalocker_user';
        
        $this->DB->select(WP_PREFIX . 'maletek_reservalocker_local', 'varName ASC'); 
        $regs=$this->DB->get('array');
        
        $this->listLocales=array(0=>'NO disponible');
        if($regs){
            foreach ($regs as $r) {
                $this->listLocales[$r['id']]=$r['varName'].' / '.$r['varSubName'].' / '.$r['varPlace'];
            }
        }
    }
    
    function login(){
        
        $varEmail=trim(mysql_real_escape_string($_POST['email']));
        $varPass=trim(mysql_real_escape_string($_POST['password']));
        try{
            if(!filter_var($varEmail,FILTER_VALIDATE_EMAIL)){
                throw new Exception('Email de formato incorrecto, inténlo de nuevo por favor');
            }
            elseif(!$varEmail || !$varPass){
                throw new Exception('Información incompleta, inténlo de nuevo por favor');
            }            
            
        
            $where='varEmail="'.$varEmail.'" AND varPass="'.$varPass.'"';
        
            $this->DB->select($this->table_name, 'varName ASC', $where , '*'); 
            $reg=$this->DB->get_first('array');
            
            if( !$reg || !array_key_exists('id',$reg) || !$reg['id'] ){
                throw new Exception('El usuario no existe, inténlo de nuevo por favor');
            }
            
            $_SESSION['reservalockerUser']=$reg['id'];
            exit('OK');
            
        }
        catch (Exception $e){
            exit('<div class="info">'.$e->getMessage().'</div>');
        }
    }
    function register(){
        
        $varCareer  =trim(mysql_real_escape_string($_POST['career']));
        $varCode    =trim(mysql_real_escape_string($_POST['code']));
        $varDni    =trim(mysql_real_escape_string($_POST['dni']));
        $varEmail   =trim(mysql_real_escape_string($_POST['email']));
        $varLevel   =trim(mysql_real_escape_string($_POST['level']));
        $varName    =trim(mysql_real_escape_string($_POST['name']));
        $varPass    =trim(mysql_real_escape_string($_POST['password']));
        $varPhone   =trim(mysql_real_escape_string($_POST['phone']));
        $idLocal    =trim(mysql_real_escape_string($_POST['place']));
        $rePass     =trim(mysql_real_escape_string($_POST['re-password']));
        
        try{
            
            $this->DB->select($this->table_name, 'varName ASC', 'varEmail="'.$varEmail.'"' , '*'); 
            $reg=$this->DB->get_first('array');
            
            if(!filter_var($varEmail,FILTER_VALIDATE_EMAIL)){
                throw new Exception('Email de formato incorrecto, inténlo de nuevo por favor');
            }
            elseif(!$varCareer || !$varCode || !$varDni || !$varEmail || !$varLevel 
                    || !$varName || !$varPass || !$varPhone || !$idLocal || !$rePass){
                throw new Exception('Información incompleta, inténlo de nuevo por favor');
            }
            elseif( $rePass != $varPass ){
                throw new Exception('Las contraseñas no coinciden, inténlo de nuevo por favor');
            }
            elseif( !array_key_exists($reg['idLocal'],$this->listLocales) ){
                throw new Exception('Instituto no identificado, inténlo de nuevo por favor');
            }
            elseif( $reg && array_key_exists('id',$reg) && $reg['id'] ){
                throw new Exception('La cuenta de correo esta siendo usada por otro usuario, inténlo de nuevo por favor');
            }
            
            $data=array(
                'varName'       =>$varName,
                'varEmail'      =>$varEmail,
                'varPhone'      =>$varPhone,
                'varDni'        =>$varDni,
                'idLocal'       =>$idLocal,
                'varCode'       =>$varCode,
                'varCareer'     =>$varCareer,
                'varLevel'      =>$varLevel,
                'varPass'       =>$varPass,
                'dateUpdate'    =>date('Y-m-d H:i:s')                
            );
                        
            $_SESSION['reservalockerUser']=$this->DB->insert($this->table_name, $data);
            
            exit('OK');
            
        }
        catch (Exception $e){
            exit('<div class="info">'.$e->getMessage().'</div>');
        }
        
    }
    
    function profile(){
        
        if(!$_SESSION['reservalockerUser']){
            exit('ERROR');
        }
        
        $this->DB->select($this->table_name, 'varName ASC', 'id="'.intval($_SESSION['reservalockerUser']).'"' , '*'); 
        $reg=$this->DB->get_first('array');
        
        if( !$reg || !array_key_exists('id',$reg) || !$reg['id'] ){
            exit('ERROR');
        }
        
        ?>
        <div class="field">
            <h3><?php echo $reg['varName'] ?></h3>
        </div>
        <div class="field">
            <b><?php echo $reg['varEmail'] ?></b>
        </div>
        <div class="field">
            <span class="input-label">Mi Teléfono es </span>
            <span class="input-data"><?php echo $reg['varPhone'] ?></span>
        </div>
        <div class="field">
            <span class="input-label">Estudio en </span>
            <span class="input-data"><?php echo $this->listLocales[ $reg['idLocal'] ] ?></span>
        </div>
        <div class="field">
            <span class="input-label">Mi código de estudiante es </span>
            <span class="input-data"><?php echo $reg['varCode'] ?></span>
        </div>

        <div class="field">
            <span class="input-label">Mi DNI o Carnét de Extranjería es </span>
            <span class="input-data"><?php echo $reg['varDni'] ?></span>
        </div>
        <div class="field">
            <span class="input-label">Estudio la carrera de </span>
            <span class="input-data"><?php echo $reg['varCareer'] ?></span>
        </div>
        <div class="field">
            <span class="input-label">Estoy en el Ciclo </span>
            <span class="input-data"><?php echo $reg['varLevel'] ?></span>
        </div>
        <div class="control">
            <input type="button" class="input-button" value="Editar" id="reservaLocker-editButton" />
        </div>
        <div class="close-session">
            <a href="<?php echo MALETEKPL_RSV_FRONTEND_CONTROLLER ?>?controller=user&action=logout" id="reservaLocker-close">[Cerrar Sesión]</a>
        </div>
        <?php
        
    }
    
    function edit(){
        ?><form class="ajaxForm" id="reservaLocker-edit" method="post" action="<?php echo MALETEKPL_RSV_FRONTEND_CONTROLLER ?>?controller=user&action=edit">
            <h3>Editar perfil</h3>
            <input type="text" name="name" class="input-text" placeholder="Nombre completo" />
            <input type="text" name="email" class="input-text" placeholder="Correo electrónico" />
            <input type="text" name="phone" class="input-text" placeholder="Teléfono o Celular" />
            <select name="place" class="input-select" >
                <option value="0">Sede de Centro de estudios</option>
            </select>
            <input type="text" name="dni" class="input-text" placeholder="DNI / Carnét de Extranjería" />
            <input type="text" name="code" class="input-text" placeholder="Código de Estudiante" />
            <input type="text" name="career" class="input-text" placeholder="Carrera" />
            <input type="text" name="level" class="input-text" placeholder="Ciclo" />
            <input type="password" name="password" class="input-text" placeholder="Contraseña" />                
            <input type="password" name="re-password" class="input-text" placeholder="Repetir Contraseña" />

            <input type="submit" name="register" value="Guardar" class="input-button" />
        </form><?php
    }
    
    function logout(){
        unset($_SESSION['reservalockerUser']);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}