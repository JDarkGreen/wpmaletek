<?php
class LocalController {
    
    private $table_name=null;
    private $DB=null;
    private $object=null;
    
    function __construct() 
    {
        global $DB;
        $this->DB =& $DB;
        
        $this->table_name=WP_PREFIX . 'maletek_reservalocker_local';
        $this->object='local';
    }
    
    function selector(){
        
        $term=trim($_REQUEST['term']);        
        $this->DB->select($this->table_name, 'varName', $term?'varName LIKE "%'.$term.'%"':'', '*', '' ); 
        $regs=$this->DB->get('array');
        
        $result=array();
        if($regs){
            foreach ($regs as $r) {
                $result[ $r['id'] ]=array('id'=>$r['id'],'value'=>$r['varName'].' /  '.$r['varSubName'].' / '.$r['varPlace'],'label'=>$r['varName'].' /  '.$r['varSubName'].' / '.$r['varPlace'].'');
            }
        }
        
        header('Content-Type: application/json;');
        exit(json_encode($result));
        
    }
    function selectorName(){
        
        $term=trim($_REQUEST['term']);        
        $this->DB->select($this->table_name, 'varName', $term?'varName LIKE "%'.$term.'%"':'', '*', '' ,'varName' ); 
        $regs=$this->DB->get('array');
        
        $result=array();
        if($regs){
            foreach ($regs as $r) {
                $result[]=array('id'=>$r['varName'],'value'=>$r['varName'],'label'=>$r['varName']);
            }
        }
        
        header('Content-Type: application/json;');
        exit(json_encode($result));
        
    }
    function selectorSubName(){
        
        $term=trim($_REQUEST['term']);        
        $this->DB->select($this->table_name, 'varSubName', $term?'varSubName LIKE "%'.$term.'%"':'', '*', '' ,'varSubName' ); 
        $regs=$this->DB->get('array');
        
        $result=array();
        if($regs){
            foreach ($regs as $r) {
                $result[ $r['varSubName'] ]=array('id'=>$r['varSubName'],'value'=>$r['varSubName'],'label'=>$r['varSubName']);
            }
        }
        
        header('Content-Type: application/json;');
        exit(json_encode($result));
        
    }
    function selectorPlace(){
        
        $term=trim($_REQUEST['term']);        
        $this->DB->select($this->table_name, 'varPlace', $term?'varPlace LIKE "%'.$term.'%"':'', '*', '' ,'varPlace' ); 
        $regs=$this->DB->get('array');
        
        $result=array();
        if($regs){
            foreach ($regs as $r) {
                $result[ $r['varPlace'] ]=array('id'=>$r['varPlace'],'value'=>$r['varPlace'],'label'=>$r['varPlace']);
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
            $where='varName LIKE "%'.$_REQUEST['sSearch'].'%"';
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
            'id','varName','varSubName','varSubName','dateUpdate'
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
                    '<a itemid="'.$r['id'].'" itemref="Local" href="'.MALETEKPL_RSV_BACKEND_CONTROLLER.'?controller='.$this->object.'&action=edit" class="editReg">'.$r['varName'].'</a>',
                    $r['varSubName'],
                    $r['varPlace'],
                    $r['varDesc'],
                    //formatDate($r['dateUpdate']),
                    '<input itemid="'.$r['id'].'" itemref="Local" type="button" class="button deleteReg" value="Eliminar" />'
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
        
        try{
            if( !$_POST['varName'] ){
                throw new Exception('Información incompleta');
            }     
        
            $data=array(
                'varName'       =>$_POST['varName'],
                'varSubName'    =>$_POST['varSubName'],
                'varPlace'      =>$_POST['varPlace'],
                'varDesc'       =>$_POST['varDesc'],
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
            
            $this->DB->delete($this->table_name, 'id="'.$idReg.'"' );
            
            $message='<div class="updated">Se eliminó el registro correctamente</div>';
            
        } catch (Exception $ex) {
            $message='<div class="error">'.$ex->getMessage().'</div>';
        }
        
        exit($message);
    }
}