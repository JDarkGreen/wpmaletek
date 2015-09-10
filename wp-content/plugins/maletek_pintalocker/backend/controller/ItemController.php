<?php
class ItemController {
    
    private $table_name=null;
    private $DB=null;
    
    function __construct() 
    {
        global $DB;
        $this->DB =& $DB;
        
        $this->table_name=WP_PREFIX . 'maletek_pintalocker_item';
    }
    
    function selector(){
        $this->DB->select($this->table_name, 'varName ASC'); 
        $regs=$this->DB->get('array');
        
        echo '<select id="idItem" name="idItem" class="input-select">';
        if($regs){
            foreach ($regs as $r) {
                echo '<option value="'.$r['id'].'">'.$r['varName'].'</option>';
            }
        }
        echo '</select>';
    }
    
    
    function position(){
        $this->DB->select($this->table_name, 'varName ASC'); 
        $regs=$this->DB->get('array');
        
        echo '<select id="idItem" name="idItem" class="input-select">';
        if($regs){
            foreach ($regs as $r) {
                echo '<option value="'.$r['id'].'">'.$r['varName'].'</option>';
            }
        }
        echo '</select>';
    }
    
    function table(){
                
        $this->DB->query("SELECT COUNT(*) as TOTAL FROM ".$this->table_name);
        $result=$this->DB->get_first('array');           
        $iTotalRecords=$result?$result['TOTAL']:0;
                  
        $where='';
        $order=($_REQUEST['iSortCol_0']?$_REQUEST['sSortDir_0']:'varName').' '.($_REQUEST['sSortDir_0']?$_REQUEST['sSortDir_0']:'ASC');
        $limit='';      
        
        if($_REQUEST['sSearch']){
            $where='varName LIKE "%'.$_REQUEST['sSearch'].'%" OR varContent LIKE "%'.$_REQUEST['sSearch'].'%"';
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
            'id','varName','intRows','dateUpdate'
        );
        if($_REQUEST['iSortCol_0'] && array_key_exists($_REQUEST['iSortCol_0'], $sortArray) ){
            $orderP=$sortArray[$_REQUEST['iSortCol_0']];
        }   
        if($_REQUEST['sSortDir_0']){
            $orderD=$_REQUEST['sSortDir_0'];
        }   
        
        
        $this->DB->select($this->table_name, $orderP.' '.$orderD, $where , '*', $limit); 
        $regs=$this->DB->get('array');
        
        $aaData=array();
        if($regs){
            foreach ($regs as $r) {
                
                $aaData[]=array(
                    $r['id'],
                    '<a itemid="'.$r['id'].'" itemref="Item" href="'.MALETEKPL__BACKEND_CONTROLLER.'?controller=item&action=edit" class="editReg">'.$r['varName'].'</a>',
                    '<a itemid="'.$r['id'].'" itemref="ItemPosition" href="'.MALETEKPL__BACKEND_CONTROLLER.'?controller=itemPosition&action=position" class="editReg">'.$r['intRows'].' posiciones</a>',
                    '<a href="'.MALETEKPL__PLUGIN_URL.'resources/items/'.$r['varImageFront'].'" class="fancybox">Galería</a> | <a href="'.MALETEKPL__PLUGIN_URL.'resources/items/'.$r['varImageFront'].'" class="fancybox">Frente</a>',
                    formatDate($r['dateUpdate']),
                    '<input itemid="'.$r['id'].'" itemref="Item" type="button" class="button deleteReg" value="Eliminar" />'
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
            if( !$_POST['varName'] ){
                throw new Exception('Información incompleta');
            }
            
            if( !$_POST['idReg'] && !array_key_exists('uploadImage', $_FILES) ){
                throw new Exception('Debe incluir una imágen en un nuevo registro');
            }                 
            elseif( $_POST['idReg'] && !$_POST['varImage'] && !array_key_exists('uploadImage', $_FILES) ){
                throw new Exception('Debe incluir una imágen en un nuevo registro');
            }
            
            if( !$_POST['idReg'] && !array_key_exists('uploadImageFront', $_FILES) ){
                throw new Exception('Debe incluir una imágen en un nuevo registro');
            }                 
            elseif( $_POST['idReg'] && !$_POST['varImageFront'] && !array_key_exists('uploadImageFront', $_FILES) ){
                throw new Exception('Debe incluir una imágen en un nuevo registro');
            }
            
            if(array_key_exists('uploadImage', $_FILES) && $_FILES['uploadImage']['name']){
                $fileInfo=  pathinfo($_FILES['uploadImage']['name']);
                
                if( $_FILES['uploadImage']['name'] && !in_array($fileInfo['extension'], $validFiles) ){
                    throw new Exception('Solo se permiten imágenes de formato JPG, GIF y PNG');
                }
                
                $varImage=friendlyUrl($fileInfo['filename']).'.'.$fileInfo['extension'];
                $dest=MALETEKPL__PLUGIN_DIR.'resources'.DS.'items'.DS.$varImage;
            
                if( !move_uploaded_file($_FILES['uploadImage']['tmp_name'], $dest) ){
                    throw new Exception('Error al subir imagen Principal');
                }
            
                include_once '../../libs/ImageManager.php';
                $img=new ImageManager($dest);
                $img->thumbnail(400,400);
                $img->save();
            }
            else{
                $varImage=$_POST['varImage'];
            }
            
            if(array_key_exists('uploadImageFront', $_FILES) && $_FILES['uploadImageFront']['name']){
                $fileInfo=  pathinfo($_FILES['uploadImageFront']['name']);
                
                if( $_FILES['uploadImageFront']['name'] && !in_array($fileInfo['extension'], $validFiles) ){
                    throw new Exception('Solo se permiten imágenes de formato JPG, GIF y PNG');
                }
                
                $varImageFront=friendlyUrl($fileInfo['filename']).'.'.$fileInfo['extension'];
                $dest=MALETEKPL__PLUGIN_DIR.'resources'.DS.'items'.DS.$varImageFront;
            
                if( !move_uploaded_file($_FILES['uploadImageFront']['tmp_name'], $dest) ){
                    throw new Exception('Error al subir imagen Frontal');
                }
            
                include_once '../../libs/ImageManager.php';
                $img=new ImageManager($dest);
                $img->thumbnail(400,400);
                $img->save();
            }
            else{
                $varImageFront=$_POST['varImageFront'];
            }
                        
            $data=array(
                'varName'       =>$_POST['varName'],
                'varContent'    =>$_POST['varContent'],
                'intRows'       =>$_POST['intRows'],
                //'intCols'       =>$_POST['intCols'],
                'varImage'      =>$varImage,
                'varImageFront' =>$varImageFront,
                'dateUpdate'    =>date('Y-m-d H:i:s')                
            );
            
            if($_POST['idReg']){
                $this->DB->update($this->table_name, $data, 'id="'.$_POST['idReg'].'"');
            }
            else{
                $this->DB->insert($this->table_name, $data);
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
            
            $varImage=MALETEKPL__PLUGIN_DIR.'resources'.DS.'items'.DS.$reg['varImage'];
            unlink($varImage);
            
            $this->DB->delete($this->table_name, 'id="'.$idReg.'"' );
            
            $message='<div class="updated">Se eliminó el registro correctamente</div>';
            
        } catch (Exception $ex) {
            $message='<div class="error">'.$ex->getMessage().'</div>';
        }
        
        exit($message);
    }
}