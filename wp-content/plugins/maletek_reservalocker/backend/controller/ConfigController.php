<?php
class ConfigController {
    
    private $table_name=null;
    private $DB=null;
    
    function __construct() 
    {
        global $DB;
        $this->DB =& $DB;
        
        $this->table_name=WP_PREFIX . 'maletek_reservalocker_config';
    }
    
    
    function form(){
        
        $message='';
        
        $validFiles=array('jpg','jpge','jpeg','gif','png');
        
        try{
            
            if($_POST){
                foreach ($_POST as $key=>$value) {
                    
                    $data=array('varValue'=>$value,'varName'=>$key);
                    
                    $this->DB->select($this->table_name, null, 'varName="'.$key.'"'); 
                    $result=$this->DB->get_first('array');
                    
                    if($result['id']){
                        $this->DB->update($this->table_name, $data, 'id="'.$result['id'].'"');
                    }
                    else{
                        $this->DB->insert($this->table_name, $data);
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
        
        $result=array();
        
        try{
            $this->DB->select($this->table_name); 
            $regs=$this->DB->get('array');
            
            if($regs){
                foreach ($regs as $key=>$r) {

                    $result[$r['varName']]=$r['varValue'];
                }   
            }
            
        } catch (Exception $ex) {
            $result=array('error'=>$ex->getMessage());
        }
        
        exit(json_encode($result));
    }
}