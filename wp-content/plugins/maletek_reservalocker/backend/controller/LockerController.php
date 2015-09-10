<?php
class LockerController {
    
    private $table_name=null;
    private $DB=null;
    private $object=null;
    
    function __construct() 
    {
        global $DB;
        $this->DB =& $DB;
        
        $this->table_name=WP_PREFIX . 'maletek_reservalocker_locker';
        $this->object='locker';
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
    
    function table(){
                
        $this->DB->query("SELECT COUNT(*) as TOTAL FROM ".$this->table_name);
        $result=$this->DB->get_first('array');           
        $iTotalRecords=$result?$result['TOTAL']:0;
                  
        $where='';
        $order=($_REQUEST['iSortCol_0']?$_REQUEST['sSortDir_0']:'varName').' '.($_REQUEST['sSortDir_0']?$_REQUEST['sSortDir_0']:'ASC');
        $limit='';      
        
        if($_REQUEST['sSearch']){
            #$where='varName LIKE "%'.$_REQUEST['sSearch'].'%"';
            $where='intSerie LIKE "%'.$_REQUEST['sSearch'].'%" '.
                'AND floatPrice LIKE "%'.$_REQUEST['sSearch'].'%" '.
                'AND varSector LIKE "%'.$_REQUEST['sSearch'].'%" '.
                'AND varArea LIKE "%'.$_REQUEST['sSearch'].'%" '.
                'AND varLocal LIKE "%'.$_REQUEST['sSearch'].'%" ';
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
            'intSerie','varSector','varArea','varLocal','floatPrice'
        );
        if($_REQUEST['iSortCol_0'] && array_key_exists($_REQUEST['iSortCol_0'], $sortArray) ){
            $orderP=$sortArray[$_REQUEST['iSortCol_0']];
        }   
        if($_REQUEST['sSortDir_0']){
            $orderD=$_REQUEST['sSortDir_0'];
        }   
        
        //$this->DB->select($this->table_name, $orderP.' '.$orderD, $where , '*', $limit); 
        $sql="SELECT lk.*, it.varName as varSector, it.varArea, CONCAT(l.varName,'/',l.varSubName,'/',l.varPlace) as varLocal ".
                " FROM ". WP_PREFIX . "maletek_reservalocker_locker as lk".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_module as md ON lk.idModule=md.id".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_item as it ON md.idItem=it.id".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_local as l ON l.id=it.idLocal".
                " ORDER BY intSerie, varSector, varArea, varLocal ";
        $this->DB->query($sql);
        $regs=$this->DB->get('array');
        
        $aaData=array();
        if($regs){
            foreach ($regs as $r) {
                
                $aaData[]=array(
                    '<a itemid="'.$r['id'].'" itemref="Local" href="'.MALETEKPL_RSV_BACKEND_CONTROLLER.'?controller='.$this->object.'&action=edit" class="editReg">'.$r['intSerie'].'</a>',
                    $r['varSector'],
                    $r['varArea'],
                    $r['varLocal'],
                    $r['floatPrice'],
                    '<input itemid="'.$r['id'].'" itemref="Locker" type="button" class="button deleteReg" value="Eliminar" />'
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