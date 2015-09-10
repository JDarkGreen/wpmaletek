<?php
class ItemModuleController {
    
    private $table_name=null;
    private $DB=null;
    private $object=null;
    private $locales=null;
    
    private $listModel=null;
    
    function __construct() 
    {
        global $DB;
        $this->DB =& $DB;
        
        $this->table_name=WP_PREFIX . 'maletek_reservalocker_module';
        $this->object='module';        
        
        $this->DB->select(WP_PREFIX . 'maletek_reservalocker_model', 'varName ASC'); 
        $regs=$this->DB->get('array');        
        $this->listModel=array();
        if($regs){
            foreach ($regs as $r) {
                $this->listModel[$r['id']]=$r['varName'];
            }
        }
        
        $this->DB->select(WP_PREFIX . 'maletek_reservalocker_local', 'varName ASC'); 
        $regs=$this->DB->get('array');
        
        $this->listLocales=array();
        if($regs){
            foreach ($regs as $r) {
                $this->listLocales[$r['id']]=$r['varName'];
            }
        }
    }
        
    function form(){
        
        $message='';
        
        try{
            if( !$_POST['idItem'] ){
                throw new Exception('Información incompleta');
            }
        
            for($i=1;$i<=10;$i++){ 
                $data=array();
                
                if( $_POST['varModel'.$i] ){
                    $data=array(
                        'idItem'       =>$_POST['idItem'],
                        'idModel'       =>$_POST['idModel'.$i],
                        'intSerie'      =>$_POST['intSerie'.$i],
                        'floatPrice'    =>$_POST['floatPrice'.$i],
                        'dateUpdate'    =>date('Y-m-d H:i:s')                
                    );
                
                    if($_POST['idReg'.$i]){
                        $this->DB->update($this->table_name, $data, 'id="'.$_POST['idReg'.$i].'"');
                    }
                    else{
                        $this->DB->insert($this->table_name, $data);
                    }
                    
                }
                else{
                    if($_POST['idReg'.$i]){
                        $this->DB->delete($this->table_name, 'id="'.$_POST['idReg'.$i].'"');
                    }
                }                
                      
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
            
            $this->DB->select(WP_PREFIX . 'maletek_reservalocker_item', null, 'id="'.$idReg.'"'); 
            $item=$this->DB->get_first('array');
                        
            $this->DB->select($this->table_name, null, 'idItem="'.$idReg.'"'); 
            $regs=$this->DB->get('array');
            
            $result['idItem']=$idReg;
            $result['varSector']=$this->listLocales[ $item['idLocal'] ].' / '.$item['varArea'].' / '.$item['varName'];
            if($regs){
                foreach($regs as $i=>$r){ 
                    $i++;
                    $result['idReg'.$i]=$r['id'];
                    $result['idModel'.$i]=$r['idModel'];
                    if($r['idModel']){
                        $result['varModel'.$i]=$this->listModel[ $r['idModel'] ];
                    }
                    $result['intSerie'.$i]=$r['intSerie'];
                    $result['floatPrice'.$i]=$r['floatPrice'];
                }    
            }
            
                        
        } catch (Exception $ex) {
            $result=array('error'=>$ex->getMessage());
        }
        
        exit(json_encode($result));
    }
    
}