<?php
class RequestController {
    
    private $table_name=null;
    private $DB=null;
    
    private $aCreateFunctions = array(
        IMAGETYPE_GIF=>'imagecreatefromgif',
        IMAGETYPE_JPEG=>'imageCreateFromJpeg',
        IMAGETYPE_PNG=>'imagecreatefrompng'
    );
    private $aCreateImagen = array(
        IMAGETYPE_GIF=>'imagegif',
        IMAGETYPE_JPEG=>'imagejpeg',
        IMAGETYPE_PNG=>'imagepng',
    );
    
    function __construct() 
    {
        global $DB;
        $this->DB =& $DB;
        
        $this->table_name=WP_PREFIX . 'maletek_pintalocker_request';
    }
    
    
    function scheme(){
        
        $idReg=$_REQUEST['id'];
        $this->DB->select($this->table_name, null, 'id="'.$idReg.'"'); 
        $reg=$this->DB->get_first('array');
        
        $fItem=null;
        $this->DB->select(WP_PREFIX . 'maletek_pintalocker_item', 'varName ASC'); 
        $items=$this->DB->get('array');
        
        echo '<div class="sub-input"><select id="idItem" name="idItem" class="input-select">';
        if($items){
            foreach ($items as $it) {
                if(!$fItem || $reg['idItem']==$it['id'] ){
                    $fItem=$it;
                }
                echo '<option value="'.$it['id'].'" '.($reg['idItem']==$it['id']?'selected':'').'>'.$it['varName'].' - Lockers '.$it['intRows'].'</option>';
            }
        }
        echo '</select></div>';
        
        $this->DB->select(WP_PREFIX . 'maletek_pintalocker_color', 'varName ASC'); 
        $colors=$this->DB->get('array');
        $colorList=array();
        
        echo '<div class="sub-input">';
        if($colors){
            $bg=MALETEKPL__PLUGIN_URL.'resources'.DS.'color'.DS.'none.jpg';
            echo '<a title="Ninguno" href="'.$bg.'" class="item-color" style="background-image:url('.$bg.')" itemid="0" itemref="'.$bg.'"></a>';
            foreach ($colors as $c) {
                $colorList[$c['id']]=$c;
                $bg=MALETEKPL__PLUGIN_URL.'resources'.DS.'color'.DS.$c['varImage'];
                echo '<a title="'.$c['varName'].'" href="'.$bg.'" class="item-color" style="background-image:url('.$bg.')" itemid="'.$c['id'].'" itemref="'.$bg.'"></a>';
            }
        }
        echo '<div class="clear"><i>Click para selecciona el color y luego en el area a colorear en la imágen</i></div></div>';
        
        $reg['varContent']=stripslashes($reg['varContent']);
        $colorLk=  $reg['varContent']?json_decode($reg['varContent'],true):array();
       
        echo '<div id="schemaBlock">';
        if($items){
            foreach ($items as $it) {
                $bg=MALETEKPL__PLUGIN_URL.'resources'.DS.'items'.DS.$it['varImageFront'];
                echo '<div id="item-'.$it['id'].'" style="background-image:url('.$bg.')" class="itemBlock">';
                
                $it['varCoord']=str_replace(array('"{','x1','x2','y1','y2','}"'),array('{','"x1"','"x2"','"y1"','"y2"','}'),$it['varCoord']);
                $varCoord= json_decode($it['varCoord'],true);
                
                    if($varCoord){
                        foreach ($varCoord as $id=>$value) {
                            $w=  abs($value['x1']-$value['x2']);
                            $h=  abs($value['y1']-$value['y2']);
                            $l=$value['x1'];
                            $t=$value['y1'];

                            $colorId=array_key_exists($id, $colorLk)?$colorLk[$id]:'';

                            $bg='';
                            if(array_key_exists($colorId, $colorList)){
                                $bg='background-image:url('.MALETEKPL__PLUGIN_URL.'resources'.DS.'color'.DS.$colorList[$colorId]['varImage'].');';
                            }

                            echo '<div class="locker" itemid="'.$id.'" itemref="'.$colorId.'" style="'.$bg.'top:'.$t.'px;left:'.$l.'px;width:'.$w.'px;height:'.$h.'px;"></div>';
                        }
                }
                echo '</div>';
            }
        }
        echo '</div><div class="clear"></div>';
                
    }
    
    function table(){
                
        $this->DB->query("SELECT COUNT(*) as TOTAL FROM ".$this->table_name);
        $result=$this->DB->get_first('array');           
        $iTotalRecords=$result?$result['TOTAL']:0;
                  
        $where='';
        $order=($_REQUEST['iSortCol_0']?$_REQUEST['sSortDir_0']:'varName').' '.($_REQUEST['sSortDir_0']?$_REQUEST['sSortDir_0']:'ASC');
        $limit='';      
        
        if($_REQUEST['sSearch']){
            $where='varName LIKE "%'.$_REQUEST['sSearch'].'%" OR '.
                    'varLastName LIKE "%'.$_REQUEST['sSearch'].'%" OR '.
                    'varEmail LIKE "%'.$_REQUEST['sSearch'].'%" OR '.
                    'varPhone LIKE "%'.$_REQUEST['sSearch'].'%"';
        }
               
        if($_REQUEST['iDisplayStart']){
            $limit=$_REQUEST['iDisplayStart'].','.$_REQUEST['iDisplayLength'];      
        }
        else{
            $limit='0,'.$_REQUEST['iDisplayLength'];       
        }
        
        $this->DB->query("SELECT COUNT(*) as TOTAL FROM ".$this->table_name." ".($where?'WHERE '.$where:''));
        $result=$this->DB->get_first('array');           
        $iTotalDisplayRecords=$result?$result['TOTAL']:0;
        
        $orderP='id';
        $orderD='DESC';
        $sortArray=array(
            'id','CONTACT(varName,", ",varLastName)','CONTACT(varEmail,"|",varPhone)'
        );
        if($_REQUEST['iSortCol_0'] && array_key_exists($_REQUEST['iSortCol_0'], $sortArray) ){
            $orderP=$sortArray[$_REQUEST['iSortCol_0']];
        }   
        if($_REQUEST['sSortDir_0']){
            $orderD=$_REQUEST['sSortDir_0'];
        }   
        
        $cols='*, CONCAT(varLastName,", ",varName) AS fullName';        
        $this->DB->select($this->table_name, $orderP.' '.$orderD, $where , $cols, $limit); 
        $regs=$this->DB->get('array');
        
        $itemList   =$this->DB->getList(WP_PREFIX . 'maletek_pintalocker_item');
        $colorList  =$this->DB->getList(WP_PREFIX . 'maletek_pintalocker_color');
        
                        
        $aaData=array();
        if($regs){
            foreach ($regs as $r) {
                $schema='';
                
                $aaData[]=array(
                    $r['id'],
                    '<a itemid="'.$r['id'].'" itemref="Request" href="'.MALETEKPL__BACKEND_CONTROLLER.'?controller=request&action=edit" class="editReg">'.$r['fullName'].'</a>',
                    '<a href="mailto:'.$r['varEmail'].'">'.$r['varEmail'].'</a> | '.$r['varPhone'],
                    '<a href="'.MALETEKPL__PLUGIN_URL.'frontend/views/pdf.php?request='.md5($r['id']).'" target="_blank" class="zoom">ver PDF</a>',
                    formatDate($r['dateCreate']),
                    '<input itemid="'.$r['id'].'" itemref="Request" type="button" class="button deleteReg" value="Eliminar" />'
                );
            }   
        }
        
        $output = array(
            "sEcho" => intval($_REQUEST['sEcho']),
            "iTotalRecords" => $iTotalRecords,
            "iTotalDisplayRecords" => $iTotalDisplayRecords,
            "aaData" => $aaData
	);        
        
        exit(json_encode( $output ));
        
    }
    
    function form(){
        
        $message='';
        
        $validFiles=array('jpg','jpge','jpeg','gif','png');
        
        try{
            if( !$_POST['varName'] || !$_POST['varLastName']  || !$_POST['varEmail']  || !$_POST['varContent'] ){
                throw new Exception('Información incompleta');
            }     
            //$_POST['idReg']
            $data=array(
                'varName'       =>$_POST['varName'],
                'varLastName'   =>$_POST['varLastName'],
                'varBusiness'   =>$_POST['varBusiness'],
                'varEmail'      =>$_POST['varEmail'],
                'varPhone'      =>$_POST['varPhone'],  
                'idItem'        =>$_POST['idItem'],
                'varContent'    =>$_POST['varContent'],
                'dateUpdate'    =>date('Y-m-d H:i:s')                
            );
            
            $idReg=$_POST['idReg'];
            if($_POST['idReg']){
                $this->DB->update($this->table_name, $data, 'id="'.$_POST['idReg'].'"');
            }
            else{
                $data['dateCreate']=date('Y-m-d H:i:s');
                $idReg=$this->DB->insert($this->table_name, $data);
            }
                        
            $message='<div class="updated">Se guardó la información correctamente</div>';
            
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
            if( !$result || !$result['id']){
                throw new Exception('Registro no existe');
            }
            
            $result['varContent']=stripslashes($result['varContent']);
            
        } catch (Exception $ex) {
            $result=array('error'=>$ex->getMessage());
        }
        
        
        exit(json_encode($result));
    }
    function delete(){
        
        $idReg=$_POST['id'];
        
        $message='';
        
        try{
            if( !$idReg ){
                throw new Exception('Información incompleta para eliminar archivo');
            }
            
            $this->DB->select($this->table_name, null, 'id="'.$idReg.'"'); 
            $reg=$this->DB->get_first('array');
        
            if( !$reg || !$reg['id']){
                throw new Exception('Registro no existe');
            }
            
            $this->DB->delete($this->table_name, 'id="'.$idReg.'"' );
            
            $message='<div class="updated">Se eliminó el registro correctamente</div>';
            
        } catch (Exception $ex) {
            $message='<div class="error">'.$ex->getMessage().'</div>';
        }
        
        exit($message);
    }
}