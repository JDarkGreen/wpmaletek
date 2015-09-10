<?php
class ItemController {
    
    private $table_name=null;
    private $DB=null;
    private $object=null;
    private $locales=null;
    
    function __construct() 
    {
        global $DB;
        $this->DB =& $DB;
        
        $this->table_name=WP_PREFIX . 'maletek_reservalocker_item';
        $this->object='item';
        
        $this->DB->select(WP_PREFIX . 'maletek_reservalocker_local', 'varName ASC'); 
        $regs=$this->DB->get('array');
        
        $this->locales=array();
        if($regs){
            foreach ($regs as $r) {
                $this->locales[$r['id']]=$r['varName'].' / '.$r['varSubName'].' / '.$r['varPlace'];
            }
        }
    }
    
    function selectorArea(){
        $term=trim($_REQUEST['term']);   
        $this->DB->select($this->table_name, 'varArea', ($term?'varArea LIKE "%'.$term.'%"':''), 'varArea', '', 'varArea' ); 
        $regs=$this->DB->get('array');
        
        $result=array();
        if($regs){
            foreach ($regs as $r) {
                $result[]=array('id'=>$r['varArea'],'value'=>$r['varArea'],'label'=>$r['varArea']);
            }
        }
        
        header('Content-Type: application/json;');
        exit(json_encode($result));
        
    }
    
    
    function table(){
                
        $this->DB->query("SELECT COUNT(*) as TOTAL FROM ".$this->table_name);
        $result=$this->DB->get_first('array');           
        $iTotalRecords=$result?$result['TOTAL']:0;
                  
        $where='';
        $order=($_REQUEST['iSortCol_0']?$_REQUEST['sSortDir_0']:'varName').' '.($_REQUEST['sSortDir_0']?$_REQUEST['sSortDir_0']:'ASC');
        $limit='';      
        
        if($_REQUEST['sSearch']){
            $where='('.
                        'it.varName LIKE "%'.$_REQUEST['sSearch'].'%"'.
                        ' OR '.
                        'varArea LIKE "%'.$_REQUEST['sSearch'].'%"'.
                        ' OR '.
                        'lc.varName LIKE "%'.$_REQUEST['sSearch'].'%"'.
                    ')';
        }
               
        if($_REQUEST['iDisplayStart']){
            $limit=$_REQUEST['iDisplayStart'].','.$_REQUEST['iDisplayLength'];      
        }
        else{
            $limit='0,'.$_REQUEST['iDisplayLength'];       
        }
        
        #$this->DB->query("SELECT COUNT(*) as TOTAL FROM ".$this->table_name." as it ".($where?'WHERE '.$where:''));
        $sql="SELECT COUNT(*) as TOTAL FROM ".$this->table_name." as it".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_local as lc ON lc.id=it.idLocal".
                ($where?" WHERE ".$where:"");
        $this->DB->query($sql);
        
        $result=$this->DB->get_first('array');           
        $iTotalDisplayRecords=$result?$result['TOTAL']:0;
        
        $orderP='id';
        $orderD='DESC';
        $sortArray=array(
            'id','varName','dateUpdate'
        );
        if($_REQUEST['iSortCol_0'] && array_key_exists($_REQUEST['iSortCol_0'], $sortArray) ){
            $orderP=$sortArray[$_REQUEST['iSortCol_0']];
        }   
        if($_REQUEST['sSortDir_0']){
            $orderD=$_REQUEST['sSortDir_0'];
        }   
        
        $sql="SELECT it.*, lc.varName as varLocal FROM ".$this->table_name." as it".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_local as lc ON lc.id=it.idLocal".
                ($where?" WHERE ".$where:"").
                " ORDER BY ".$orderP." ".$orderD.
                " LIMIT ".$limit;
        
        $this->DB->query($sql);
        $regs=$this->DB->get('array');
        
        $aaData=array();
        if($regs){
            foreach ($regs as $r) {
                
                $sql="SELECT count(*) as nro, m.varName as varModel FROM ".WP_PREFIX."maletek_reservalocker_module as md".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_model as m ON m.id=md.idModel".
                " WHERE md.idItem='".$r['id']."'".
                " GROUP BY m.varName".        
                " ORDER BY m.varName";
                $this->DB->query($sql);
                $regsM=$this->DB->get('array'); 
                
                $nroLockers=array();
                if($regsM){
                    foreach ($regsM as $rg) {
                        $nroLockers[]=$rg['nro'].$rg['varModel'];
                    }
                    $nroLockers=implode(' + ',$nroLockers);
                }
                else{
                    $nroLockers='NO definidos';
                }
                
                
                $aaData[]=array(
                    $r['id'],
                    '<a itemid="'.$r['id'].'" itemref="Item" href="'.MALETEKPL_RSV_BACKEND_CONTROLLER.'?controller='.$this->object.'&action=edit" class="editReg">'.$r['varName'].'</a>',
                    $r['varArea'],
                    $this->locales[ $r['idLocal'] ],
                    '<a itemid="'.$r['id'].'" itemref="ItemModule" href="'.MALETEKPL_RSV_BACKEND_CONTROLLER.'?controller='.$this->object.'Module&action=module" class="editReg">'.$nroLockers.'</a>',
                    //formatDate($r['dateUpdate']),
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
            if( !$_POST['varName'] || !$_POST['varArea'] ){
                throw new Exception('Información incompleta');
            }
            if( !$_POST['idReg'] && !array_key_exists('uploadImage', $_FILES) ){
                throw new Exception('Debe incluir una imágen en un nuevo registro');
            }                 
            elseif( $_POST['idReg'] && !$_POST['varImage'] && !array_key_exists('uploadImage', $_FILES) ){
                throw new Exception('Debe incluir una imágen en un nuevo registro');
            }
            
            if(array_key_exists('uploadImage', $_FILES) && $_FILES['uploadImage']['name']){
                $fileInfo=  pathinfo($_FILES['uploadImage']['name']);
                
                if( $_FILES['uploadImage']['name'] && !in_array($fileInfo['extension'], $validFiles) ){
                    throw new Exception('Solo se permiten imágenes de formato JPG, GIF y PNG');
                }
                
                $varImage=friendlyUrl($fileInfo['filename']).'.'.$fileInfo['extension'];
                $dest=MALETEKPL_RSV_PLUGIN_DIR.'resources'.DS.'sector'.DS.$varImage;
            
                if( !move_uploaded_file($_FILES['uploadImage']['tmp_name'], $dest) ){
                    throw new Exception('Error al subir imagen');
                }
            
            }
            else{
                $varImage=$_POST['varImage'];
            }     
        
            $data=array(
                'varName'       =>$_POST['varName'],
                'varArea'       =>$_POST['varArea'],
                'idLocal'       =>$_POST['idLocal'],
                'varImage'      =>$varImage,
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
            $result['varLocal']=$this->locales[ $result['idLocal'] ];
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
            
            $this->DB->delete(WP_PREFIX . 'maletek_reservalocker_block', 'idSector="'.$idReg.'"' );
            $this->DB->delete($this->table_name, 'id="'.$idReg.'"' );
            
            $message='<div class="updated">Se eliminó el registro correctamente</div>';
            
        } catch (Exception $ex) {
            $message='<div class="error">'.$ex->getMessage().'</div>';
        }
        
        exit($message);
    }
}