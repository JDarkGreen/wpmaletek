<?php
class StoreController {
    
    private $table_name=null;
    private $DB=null;
    
    function __construct() 
    {
        global $DB;
        $this->DB =& $DB;
        
        $this->table_name=WP_PREFIX . 'eternit_store';
    }
    
    function place(){
        extract($_REQUEST);
        
        $name=$where='';
        switch ($place) {
            case 'varPlace1':
                $name='Departamento';
                $where='';
                break;
            case 'varPlace2':
                $name='Provincia';
                $where='varPlace1="'.$id.'"';
                break;
            case 'varPlace3':
                $name='Distrito';
                $where='varPlace2="'.$id.'"';
                break;
        }
        
        $this->DB->select($this->table_name, $place.' ASC', $where , '*'); 
        $regs=$this->DB->get('array');
        
        
        
        $aaData=array();
        echo '<option value="">Seleccione '.$name.'...</option>';
        if($regs){
            foreach ($regs as $i=>$r) {
                $aaData[ $r[$place] ]=$r[$place];
            }
            
            foreach ($aaData as $r) {
                echo '<option value="'.$r.'">'.strtoupper($r).'</option>';
            } 
        } 
        
    }
        
    function load() 
    {
        extract($_REQUEST);
        
        $where=array();
        if($search){
            $where[]='('.
                    'varName LIKE "%'.$search.'%" '.
                    'OR varEmail LIKE "%'.$search.'%" '.
                    'OR varPhone LIKE "%'.$search.'%" '.
                    'OR varFax LIKE "%'.$search.'%" '.
                    'OR textAddress LIKE "%'.$search.'%" '.
                ')';
        }
        if($place1){
            $where[]='varPlace1="'.$place1.'"';
        }
        if($place2){
            $where[]='varPlace2="'.$place2.'"';
        }
        if($place3){
            $where[]='varPlace3="'.$place3.'"';
        }
        
        if($where){
            $where=implode(' AND ',$where);
        }
        else{
            $where='';
        }
        
        $this->DB->select($this->table_name, 'varName ASC', $where , '*'); 
        $regs=$this->DB->get('array');
        
        $aaData=array();
        if($regs){
            echo '<div id="storeBlockBody">';
            foreach ($regs as $i=>$r) {
                echo $i%5==0?'<div class="page">':'';
                
                ?><div class="block">
                    <h3><?php echo $r['varName'] ?></h3>
                    <div class="col-right">
                        <?php if($r['varPhone']){ ?>
                        <span>Teléfono:</span> <?php echo $r['varPhone'] ?>
                        <br/>
                        <?php } ?>
                        <?php if($r['varFax']){ ?>
                        <span>Fax:</span> <?php echo $r['varFax'] ?>
                        <br/>
                        <?php } ?>
                        <?php if($r['textGoogleMap']){ ?>
                        <a class="link-map" target="_blank" href="<?php echo $r['textGoogleMap'] ?>">Ver mapa</a>
                        <?php } ?>
                    </div>
                    <div class="col-left">
                        <span>Departamento:</span> <?php echo $r['varPlace1'] ?>
                        <br/>
                        <span>Provincia:</span> <?php echo $r['varPlace2'] ?>
                        <br/>
                        <span>Distrito:</span> <?php echo $r['varPlace3'] ?>
                        <br/>
                        <span>Dirección:</span> <?php echo $r['textAddress'] ?>
                        <br/>
                        <?php if($r['varEmail']){ ?>
                        <span>Email:</span> <a href="mailto:<?php echo $r['varEmail'] ?>"><?php echo $r['varEmail'] ?></a>
                        <?php } ?>
                    </div>
                </div><?php
            
                echo (($i+1)%5==0||($i+1)==count($regs))?'</div>':'';
            }   
            echo '</div>';
        }
        
    }
}