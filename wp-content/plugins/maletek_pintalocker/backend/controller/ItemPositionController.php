<?php
class ItemPositionController {
    
    private $table_name=null;
    private $DB=null;
    
    function __construct() 
    {
        global $DB;
        $this->DB =& $DB;
        
        $this->table_name=WP_PREFIX . 'maletek_pintalocker_item';
    }
    
    function form(){
        
        $message='';
        
        $validFiles=array('jpg','jpge','jpeg','gif','png');
        
        try{
            if( !$_POST['idReg'] ){
                throw new Exception('Parámetros incompletos');
            }  
            
            $coord=null;
            if($_POST['area']){
                foreach ($_POST['area'] as $key=>$value) {
                    $coord[$key]=($value?$value:'{x1:0,y1:0,x2:0,y2:0}');
                }
            }
            //print_r($coord); echo '<hr>';
            $varCoord=json_encode($coord);
            //echo ($varCoord);
            $data=array(
                'varCoord'      =>$varCoord,
                'dateUpdate'    =>date('Y-m-d H:i:s')                
            );
            $this->DB->update($this->table_name, $data, 'id="'.$_POST['idReg'].'"');
              
            $message='<div class="updated">Se guardó la información correctamente</div>'.$varCoord;
            
        } catch (Exception $ex) {
            $message='<div class="error">'.$ex->getMessage().'</div>';
        }
               
        exit($message);
        
    }
    
    function load(){
        $idReg=$_POST['id'];
        
        $result=array();
        
        try{
            if( !$idReg ){
                throw new Exception('Información incompleta para eliminar archivo');
            }
            
            $this->DB->select($this->table_name, null, 'id="'.$idReg.'"'); 
            $result=$this->DB->get_first('array');
            $result['idReg']=$result['id'];
            
            $result['buttonPosition']=$result['coordPosition']='';   
            
            $result['imgPosition']=MALETEKPL__PLUGIN_URL.'resources/items/'.$result['varImageFront'].'?'.uniqid();
            $varCoord= json_decode(trim($result['varCoord']),true);
            
            if(!is_array($varCoord)){
                $varCoord=array();
            }
            
            ///print_r($varCoord);
            for($i=0;$i<$result['intRows'];$i++){
                $key=($i+1);
                $result['buttonPosition'].='<input type="button" value="Posición '.$key.'" itemid="'.$key.'" class="editPos button" /> ';  
                $result['coordPosition'].='<div class="loadPos" id="position'.$key.'" itemid="'.$key.'">'.
                    '<input type="hidden" class="loadPosArea" id="loadPosArea'.$key.'" name="area['.$key.']" value="'.(array_key_exists($key, $varCoord)?$varCoord[$key]:'').'"/>'.
                '</div>';  
            }   
            //exit($result['coordPosition']);
            //$result['ex']=  implode(';;;',$result);
            
        } catch (Exception $ex) {
            $result=array('error'=>$ex->getMessage());
        }
        
        exit(json_encode($result));
    }
}