<?php
class RequestController {
    
    private $table_name=null;
    private $DB=null;
        
    function __construct() 
    {
        global $DB;
        $this->DB =& $DB;
        
        $this->table_name=WP_PREFIX . 'maletek_pintalocker_request';
    }
    
    function save(){
        $resources=MALETEKPL__PLUGIN_URL.'resources/';
        
        $result=array(
            'message'=>array(),
            'status'=>'error'
        );
        
        $this->DB->select(WP_PREFIX . 'maletek_pintalocker_item', 'varName ASC'); 
        $items=$this->DB->get('array');
        $itemList=array();
        foreach ($items as $c){
            $itemList[$c['id']]=$c['varName'];
        }
        
        foreach ($_POST as $key=>$value) {   
            switch ($key){
                case 'idItem':   
                    if(!filter_var($value, FILTER_VALIDATE_INT) || !array_key_exists($value, $itemList)){
                        $result['message'][]='* Modelo inválido';
                    }
                break;
                case 'varBusiness': 
                    break;
                case 'varContent': 
                    if(empty($value)){
                        //$result['message'][]='* Configuración inválida';
                    }
                    break;
                case 'varEmail': 
                    if(empty($value) || !filter_var($value, FILTER_VALIDATE_EMAIL)){
                        $result['message'][]='* Email inválido';
                    }
                    break;
                case 'varLastName': 
                    if(empty($value)){
                        $result['message'][]='* Apellidos inválidos';
                    }
                    break;
                case 'varName': 
                    if(empty($value)){
                        $result['message'][]='* Nombres inválidos';
                    }
                    break;
                case 'varPhone': 
                    if(empty($value) || !filter_var($value, FILTER_VALIDATE_INT)){
                        $result['message'][]='* Telefono inválido';
                    }
                    break;
            }
        };        
        
        #save
        if(!$result['message']){
            
            $data=array(
                'varName'       =>$_POST['varName'],
                'varLastName'   =>$_POST['varLastName'],
                'varBusiness'   =>$_POST['varBusiness'],
                'varEmail'      =>$_POST['varEmail'],
                'varPhone'      =>$_POST['varPhone'],  
                'idItem'        =>$_POST['idItem'],
                'varContent'    =>$_POST['varContent'],
                'dateUpdate'    =>date('Y-m-d H:i:s'),
                'dateCreate'    =>date('Y-m-d H:i:s')   
            );
            
            $data['dateCreate']=date('Y-m-d H:i:s');
            $idReg=$this->DB->insert($this->table_name, $data);
            
            #mailing
            $this->DB->select(WP_PREFIX . 'maletek_pintalocker_config'); 
            $regs=$this->DB->get('array');    
            $config=array();
            if($regs){
                foreach ($regs as $key=>$r) {
                    $config[$r['varName']]=$r['varValue'];
                }   
            }
            
            include_once MALETEKPL__PLUGIN_DIR.'libs/phpmailer.v5/class.phpmailer.php';
            
            $mail=new PHPMailer();
            $mail->IsHTML();
            $mail->isMail();
            $mail->setLanguage('es');
            
            $mail->setFrom($config['varEmisorEmail'],$config['varEmisorName']);
            $mail->Subject=$config['textSubject'];
            
            $mail->Body='<div style="background:#820a11;padding:10px 20px;margin:15px auto 0;"><img src="'.MALETEKPL__PLUGIN_URL.'frontend'.DS.'imgs'.DS.'f_maletek.png" /></div><br/><br/>';
            $mail->Body.=str_replace('{link}',MALETEKPL__PLUGIN_URL.'frontend/views/pdf.php?request='.md5($idReg), nl2br($config['textBody']) );
            
            $mail->addAddress($data['varEmail'], $data['varLastName'].', '.$data['varName']);
            
            $varReceptor=explode(',',$config['varReceptor']);
            if($varReceptor){
                foreach ($varReceptor as $v) {
                    $mail->AddBCC($v);
                }
            }
            
            $mail->send();
            
            $result['status']='info';
            $result['message'][]='Solicitud fue enviada correctamente, se le ha enviado una copia a su Email. Nos contactaremos a la brevedad. Para culminar el proceso de compra.';
        }
        $result['message']=implode('<br/>',$result['message']);
        exit(json_encode($result));
    }
    
    function loader(){
        
        $resources=MALETEKPL__PLUGIN_URL.'resources/';
        
        $this->DB->select(WP_PREFIX . 'maletek_pintalocker_item', 'varName ASC'); 
        $items=$this->DB->get('array');
        
        $this->DB->select(WP_PREFIX . 'maletek_pintalocker_color', 'varName ASC'); 
        $colors=$this->DB->get('array');
        
        #mailing
        $this->DB->select(WP_PREFIX . 'maletek_pintalocker_config'); 
        $regs=$this->DB->get('array');    
        $config=array();
        if($regs){
            foreach ($regs as $key=>$r) {
                $config[$r['varName']]=$r['varValue'];
            }   
        }
        
        echo '<p>'.nl2br($config['textTutorial']).'</p>';
        
        if($items){?>
        <div id="pintaLockerGalleryBlock">
            <div id="pintaLockerControl">
                <?php foreach ($colors as $c){?>
                <a href="<?php echo $resources.'color/'.$c['varImage'] ?>" itemid="<?php echo $c['id'] ?>" class="color">
                        <?php echo $c['varName'] ?> <span style="background-image: url(<?php echo $resources.'color/'.$c['varImage'] ?>);"></span>
                    </a>
                <?php }?>
                <a href="#" class="color" itemid="0" >
                    Ninguno <span style="background-image: url(<?php echo $resources.'color/none.jpg' ?>);"></span>
                </a>
                <img class="applyAll" src="<?php echo MALETEKPL__PLUGIN_URL ?>frontend/imgs/btn-applyAll.png" />
            </div>
            <div id="pintaLockerGallery">
                <?php foreach ($items as $it){?>
                <div class="slide" id="photo<?php echo $it['id'] ?>">
                    <div class="image">
                        <div class="text">
                            <div class="info">
                                <h3><?php echo $it['varName'] ?></h3>
                                <?php echo nl2br($it['varContent']) ?>
                            </div>
                            <div class="button">
                                <img class="applyRequest" src="<?php echo MALETEKPL__PLUGIN_URL ?>frontend/imgs/btn-request.png" />
                                <a target="_blank" href="#" class="printRequest" >
                                    <img src="<?php echo MALETEKPL__PLUGIN_URL ?>frontend/imgs/btn-print.png" />
                                </a>
                            </div>
                        </div>
                        <div class="scheme" style="background-image: url(<?php echo $resources.'items/'.$it['varImageFront'] ?>);">
                            <?php
                            $it['varCoord']=str_replace(array('"{','x1','x2','y1','y2','}"'),array('{','"x1"','"x2"','"y1"','"y2"','}'),$it['varCoord']);
                            $varCoord= json_decode($it['varCoord'],true);
                            if($varCoord){
                                foreach ($varCoord as $id=>$value) {
                                    if(array_key_exists('x1', $value)){
                                        $w=  abs($value['x1']-$value['x2']);
                                        $h=  abs($value['y1']-$value['y2']);
                                        $l=$value['x1'];
                                        $t=$value['y1'];
                                        echo '<div class="locker" itemid="'.$id.'" itemref="" style="top:'.$t.'px;left:'.$l.'px;width:'.$w.'px;height:'.$h.'px;"></div>';
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        <div id="pintaLockerThumbBlock">
            <div id="pintaLockerThumb">
                <?php foreach ($items as $it){?>
                <a href="#photo<?php echo $it['id'] ?>" class="thumb" itemid="<?php echo $it['id'] ?>">
                    <img src="<?php echo $resources.'items/'.$it['varImage'] ?>" width="80" height="80" />
                    <label><?php echo $it['varName'] ?></label>
                </a>
                <?php }?>
            </div>
        </div>
        <a id="pintaLockerThumb-next" href="#">Siguiente &raquo;</a>
        <a id="pintaLockerThumb-prev" href="#">&laquo; Anterior</a>
        <br/><br/>&nbsp;
        
        <div id="pintaLockerPopup" >
            <div id="pintaLockerMessage">
                <div class="text"></div>
                <div><a href="#" id="pintaLockerBack">Regresar</a></div>
            </div>
            <form id="pintaLockerRequest" method="post" >                
                <p>Ingresa los siguientes datos para hacer tu solicitud de tu configuración.</p>
                <div class="field">
                    <input class="input-text" type="text" name="varName" autocomplete="off" placeholder="Nombres"/>
                </div>
                <div class="field">
                    <input class="input-text" type="text" name="varLastName" autocomplete="off" placeholder="Apellidos" />
                </div>
                <div class="field">
                    <input class="input-text" type="text" name="varEmail" autocomplete="off" placeholder="Email" />
                </div>
                <div class="field">
                    <input class="input-text" type="text" name="varPhone" autocomplete="off" placeholder="Teléfono Fijo o Celular" />
                </div>
                <div class="field">
                    <input class="input-text" type="text" name="varBusiness" autocomplete="off" placeholder="Empresa (opcional)" />
                </div>
                <div class="button">
                    <input class="input-text" type="hidden" name="idItem" id="idItem"/>
                    <input class="input-text" type="hidden" name="varContent" id="varContent"/>
                    <input class="input-button" type="image" name="send" src="<?php echo MALETEKPL__PLUGIN_URL ?>frontend/imgs/btn-send.png" />
                    <img src="<?php echo MALETEKPL__PLUGIN_URL ?>frontend/imgs/btn-close.png" id="pintaLockerRequestClose"  />
                </div>               
            </form>            
        </div>
        <?php }                
    }
}