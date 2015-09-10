<?php
class ItemController {
    
    private $table_name=null;
    private $DB=null;
    private $user=null;
    
    function __construct() 
    {
        global $DB;
        $this->DB =& $DB;
        
        $this->table_name=WP_PREFIX . 'maletek_reservalocker_item';
        
        #buscar vencidos
        $this->DB->query("DELETE FROM ".WP_PREFIX . "maletek_reservalocker_reserve WHERE dateCreate<DATE_SUB(NOW(),INTERVAL 24 HOUR) AND charSt=0");
         
        if($_SESSION['reservalockerUser']){
            $this->DB->select(WP_PREFIX . 'maletek_reservalocker_user', 'varName ASC', 'id="'.intval($_SESSION['reservalockerUser']).'"' , '*'); 
            $this->user=$this->DB->get_first('array');            
        }
        
    }
    
    function load(){
        
        if($_SESSION['reservalockerUser']){
            
            $sql="SELECT rs.*, us.varName as varUser , us.varEmail, ml.idModel, us.idLocal, ".
                    "l.varName as varLocal, it.varArea, intSerie, it.varName as varSector, dateCreate ".
                "FROM ". WP_PREFIX . "maletek_reservalocker_reserve as rs".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_user as us ON us.id=rs.idUser".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_item as it ON it.idLocal=us.idLocal".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_module as ml ON ml.id=rs.idModule AND ml.idItem=it.id".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_local as l ON l.id=us.idLocal".
                " WHERE rs.idUser='".$_SESSION['reservalockerUser']."'";
            
            $this->DB->query($sql);
            $data=$this->DB->get_first('array');
            
            $info='';
            $infoLabel=array(
                'varUser'=>'Usuario',
                'varEmail'=>'Email',
                'varLocal'=>'Instituto',
                'varArea'=>'Área',
                'varSector'=>'Sector',
                'idLocker'=>'N° Locker',
                'dateCreate'=>'Fecha de Solicitud'               
                
            );
            foreach ($infoLabel as $i=>$l) {
                if($i=='dateCreate' && $data['charSt']==0 ){
                    $time=  strtotime($data[$i]);
                    $info.= '<b>'.$l.'</b>'.date('d/m/Y H:i',$time).'<br>'.
                            '<b>Fecha Caducidad</b>'.date('d/m/Y',$time+(60*60*24)).'<br>';
                }
                else{
                    $info.='<b>'.$l.'</b>'.$data[$i].'<br>';
                }
            }
            
            if($data['id']){
                $result='<div id="reservaLocker-ticket">';
                if($data['charSt']){
                    $result.='<h1>Usted tiene un locker a su disposición</h1>'.
                            $info;
                }
                else{
                    $result.='<h1>Usted tiene una reserva pendiente</h1>'.
                        '<p><i>Recuerde que tiene 24 horas luego de hecha la solicitud para concretar el pago o su reserva será descartada.</i></p>'.
                        $info;
                }
                $result.='</div>';
                exit($result);
            }            
        }
               
        
        $sql="SELECT * FROM ". WP_PREFIX . "maletek_reservalocker_item as it".
                " WHERE it.idLocal='".$this->user['idLocal']."' GROUP BY varArea ORDER BY varArea";
            
        $this->DB->query($sql);
        $regs=$this->DB->get('array');
        
        if($regs){
            ?>
    <div id="reservaLocker-area">
        <div class="tab">
            <?php foreach ($regs as $i=>$r) {?>
            <a href="#reservaLocker-area<?php echo $i ?>"><?php echo $r['varArea'] ?></a> 
            <?php }?>
        </div>
        <?php foreach ($regs as $i=>$r) {
             $sql="SELECT * FROM ". WP_PREFIX . "maletek_reservalocker_item as it".
                " WHERE it.idLocal='".$this->user['idLocal']."' AND it.varArea='".$r['varArea']."' ORDER BY varName";
            
            $this->DB->query($sql);
            $regsS=$this->DB->get('array');
            ?>
        <div id="reservaLocker-area<?php echo $i ?>" class="tab-block">
            <?php foreach ($regsS as $j=>$rj) {
                
                $image=$rj['varImage']?'background-image: url('.MALETEKPL_RSV_PLUGIN_URL.'resources/sector/'.$rj['varImage'].');background-size:100%;':'';
                
                $sql="SELECT SUM(intCol*intRow) as tot FROM ". WP_PREFIX . "maletek_reservalocker_module as ml".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_model as m ON m.id=ml.idModel".
                " WHERE ml.idItem='".$rj['id']."'";
                $this->DB->query($sql);
                $countTot=$this->DB->get_first('array');
                $countTot=$countTot?$countTot['tot']:0;
                
                $sql="SELECT COUNT(*) as tot FROM ". WP_PREFIX . "maletek_reservalocker_reserve".
                    " WHERE idItem='".$rj['id']."'";
            
                $this->DB->query($sql);
                $countRes=$this->DB->get_first('array');
                $countRes=$countRes?$countRes['tot']:0;
                ?>
            <a href="#" itemid="<?php echo md5($rj['id']) ?>" class="sector" style="<?php echo $image ?>">    
                <label>
                    <?php echo $rj['varName'] ?>
                    <span>Quedan <?php echo $countTot-$countRes ?> lockers</span>
                </label>
            </a>
            <?php }?>
        </div>
        <?php }?>
    </div>
    <div id="reservaLocker-locker">
        <div class="popupDialog">
            <div class="popupDialog-close">[cerrar]</div>
            <h1>Reserva de Locker</h1>
            <div class="popupContent"></div>
        </div>    
    </div>
            <?php
        }
        else{
            exit('<h2>Sin información disponible.</h2>');
        }
        
    }
    
    
    #item
    function sector(){
        
        $idSector=trim($_POST['idSector']);
        
        $sql="SELECT ml.*, m.varName as varModel, intCol, intRow, it.varName as varItem ".
                "FROM ". WP_PREFIX . "maletek_reservalocker_model as m".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_module as ml ON m.id=ml.idModel".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_item as it ON it.id=ml.idItem".
                " WHERE md5(ml.idItem)='".$idSector."'";            
        $this->DB->query($sql);
        $regs=$this->DB->get('array');
        
        if($regs){
            foreach ($regs as $r) {
         
                $lockerW=35;    $lockerH=130;
                $itemW=250;     $itemH=280;
                
                $lockerR=$r['intCol'];
                $lockerC=$r['intRow'];
                $lockerTot=$lockerR*$lockerC;
                
                if($lockerC>6){
                    $lockerW=intval(($itemW-7)/$lockerC)-3;
                }
                else{
                    $itemW=($lockerW+7)*$lockerC;
                }
                if($lockerR>2){
                    $lockerH=intval(($itemH-7)/$lockerR)-3;
                }
                else{
                    $itemH=($lockerH+7)*$lockerR+3;
                }
                
        ?>
<div class="popupDialog-reserveForm" id="popupDialog-module-<?php echo $r['id'] ?>">
    <div class="popupDialog-locker">
        <a href="#" class="popupDialog-lockerNext">&rsaquo;</a>
        <a href="#" class="popupDialog-lockerPrev">&lsaquo;</a>
        <h3><?php echo $r['varModel'].' - Serie N° '.$r['intSerie'].' al '.($r['intSerie']+$lockerTot-1) ?></h3>
        
        <div class="item" style="width:<?php echo $itemW ?>px;height:<?php echo $itemH ?>px;">
            <?php for($i=0;$i<($lockerR);$i++){?>
                <?php for($j=0;$j<$lockerC;$j++){
                    $index=($lockerR*$j)+$i+$r['intSerie'];
                    
                    $sql="SELECT COUNT(*) as tot FROM ". WP_PREFIX . "maletek_reservalocker_reserve".
                    " WHERE idModule='".$r['id']."' AND idLocker='".$index."'";
            
                    $this->DB->query($sql);
                    $reserve=$this->DB->get_first('array');
                    
                    ?>
            <div class="locker <?php echo $reserve['tot']?'disable':'enable' ?>" itemid="<?php echo $index ?>" style="width:<?php echo $lockerW ?>px;height:<?php echo $lockerH ?>px;">
                <div class="status "><?php echo $index; ?></div>
                <div class="lock"></div>
            </div>
            <?php }
            }?>
        </div>
        
    </div>
    <form class="popupDialog-text" method="post" action="<?php echo MALETEKPL_RSV_FRONTEND_CONTROLLER ?>?controller=reserve&action=save">
        <input value="<?php echo $r['id'] ?>" name="module" id="lockerNumber" type="hidden" />
        <p>
            <b>N° Locker:</b> <input value="" class="input-text input-locker lockerNumber" name="locker" id="lockerNumber" type="text" readonly=""  />
        </p>
        <p>
            <b>Sector:</b> <?php echo $r['varItem'] ?>
        </p> 
        <p>
            <b>Dimensiones:</b> <br/>
            Locker <?php echo $r['varModel'] ?>
        </p>
        <p>
            <b>Compartir  Locker:</b> 
            <select id="shareSel" name="share" class=" shareSel input-select">
                <option value="0">No</option>
                <option value="1">Si</option>
            </select>
        </p> 
        <p id="shareData" class="shareData">
            <b>Compartir con:</b>
            <br/>
            <input type="text" name="name" placeholder="Nombre Completos" class="input-text" />
            <input type="text" name="dni" placeholder="DNI / Carnet extrangería" class="input-text" />
            <input type="text" name="code" placeholder="Código de Estudiante" class="input-text" />
            <input type="text" name="career" placeholder="Carrera" class="input-text" />
            <input type="text" name="level" placeholder="Ciclo" class="input-text" />
        </p> 
        <p class="control">
            <input type="submit" value="Reservar" class="input-button" />
        </p>
    </form>
    <div class="popupDialog-message"></div>
    <div class="clear"></div>
</div>
        <?php        
                
            }
        }
        
    }
    
}