<?php
class ReserveController {
    
    private $table_name=null;
    private $DB=null;
    private $object=null;
    
    function __construct() 
    {
        global $DB;
        $this->DB =& $DB;
        
        $this->object='reserve';
        $this->table_name=WP_PREFIX . 'maletek_reservalocker_'.$this->object;
        
        $this->DB->select(WP_PREFIX . 'maletek_reservalocker_model', 'varName ASC'); 
        $regs=$this->DB->get('array');        
        $this->listModel=array();
        if($regs){
            foreach ($regs as $r) {
                $this->listModel[$r['id']]=$r['varName'];
                $this->listModelTotal[$r['id']]=$r['intRow']*$r['intCol'];
            }
        }
        
        
        $this->DB->select(WP_PREFIX . 'maletek_reservalocker_local', 'varName ASC'); 
        $regs=$this->DB->get('array');
        
        $this->listLocales=array(0=>'NO disponible');
        if($regs){
            foreach ($regs as $r) {
                $this->listLocales[$r['id']]=$r['varName'].' / '.$r['varSubName'].' / '.$r['varPlace'];
            }
        }
    }
    
    function loadUser(){
        
        $result=array();
        
        $this->DB->select(WP_PREFIX . 'maletek_reservalocker_user', null, 'id="'.$_REQUEST['idUser'].'"'); 
        $user=$this->DB->get_first('array');
        
        $result['local'] = array(
            'varName'=>$this->listLocales[ $user['idLocal'] ],
            'idLocal'=>$user['idLocal']
        );
        
        $result['sector'] = array();
        
        $sql="SELECT ml.id as idModule, it.varName as varSector, varArea, intSerie, idModel FROM ". WP_PREFIX . "maletek_reservalocker_item as it ".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_module as ml ON ml.idItem=it.id".
                " WHERE idLocal='".$user['idLocal']."'".
                " ORDER BY varArea, varSector, intSerie ";
        $this->DB->query($sql);
        $regs=$this->DB->get('array');
        if($regs){
            foreach ($regs as $r) {
                $nro=($r['intSerie']+$this->listModelTotal[ $r['idModel'] ]);
                $result['sector'][ $r['varArea'].' / '.$r['varSector'] ][ $r['idModule'] ] = $this->listModel[ $r['idModel'] ].' / Serie N° '.$r['intSerie'].'-'.$nro;
            
                $result['module'][$r['idModule']]=array();
                for($i=$r['intSerie'];$i<=$nro;$i++){
                    $this->DB->select(WP_PREFIX . 'maletek_reservalocker_reserve', null, 'idLocker="'.$i.'" AND idModule="'.$r['idModel'].'"'); 
                    $reserve=$this->DB->get_first('array');
                    $result['module'][$r['idModule']][$i]=$reserve?'1':'0';
                }
            }
        }
        
        $this->DB->select(WP_PREFIX . 'maletek_reservalocker_reserve', null, 'idUser="'.$user['id'].'"'); 
        $result['reserve']=$this->DB->get_first('array');
        if(!$result['reserve']){
            $result['reserve']=null;
        }
        else{
            $result['module'][ $result['reserve']['idModule'] ][ $result['reserve']['idLocker'] ]=2;
        }
        
        exit(json_encode($result));
    }
    
    function module(){
        
        $idModule=intval($_GET['idModule']);       
        
        $this->DB->select(WP_PREFIX . 'maletek_reservalocker_module', null, 'id="'.$idModule.'"'); 
        $module=$this->DB->get_first('array');
        
        
        $this->DB->delete(WP_PREFIX . 'maletek_reservalocker_reserve', 'dateCreate<SUBDATE(NOW(),1)' );        
        $this->DB->select(WP_PREFIX . 'maletek_reservalocker_reserve', null, 'idModule="'.$idModule.'"'); 
        $reserves=$this->DB->get('array');
        $reserveList=array();
        if($reserves){
            foreach ($reserves as $r) {
                $reserveList[$r['idLocker']]=$r;
            }
        }
        
        
        $this->DB->select(WP_PREFIX . 'maletek_reservalocker_model', null, 'id="'.$module['idModel'].'"'); 
        $model=$this->DB->get_first('array');
        
        $lockerW=35;    $lockerH=130;
        $itemW=250;     $itemH=280;
        $lockerR=$model['intRow'];      $lockerC=$model['intCol']; 
        
        
        if($lockerC>6){
            $lockerW=intval(($itemW-7)/$lockerC)-5;
        }
        else{
            $itemW=($lockerW+7)*$lockerC;
        }
        if($lockerR>2){
            $lockerH=intval(($itemH-7)/$lockerR)-5;
        }
        else{
            $itemH=($lockerH+7)*$lockerR+5;
        }
        
        ?><div class="item" style="width:<?php echo $itemW ?>px;height:<?php echo $itemH ?>px;">
            <?php for($i=0;$i<($lockerR);$i++){?>
                <?php for($j=0;$j<$lockerC;$j++){
                    $index=($lockerR*$j)+$i+1;
                    ?>
            <div class="locker <?php echo array_key_exists($index, $reserveList)?'disable':'enable' ?>" itemid="<?php echo $index ?>" style="width:<?php echo $lockerW ?>px;height:<?php echo $lockerH ?>px;">
                <div class="status "><?php echo $index ?></div>
                <div class="lock"></div>
            </div>
            <?php }
            }?>
        </div><?php
        
    }
    
    function tree(){
        
        $this->DB->select(WP_PREFIX . 'maletek_reservalocker_local', 'varName'); 
        $local=$this->DB->get('array');
        
        if($local){
            echo '<ul>';
            foreach ($local as $l) {
                    
                $this->DB->select(WP_PREFIX . 'maletek_reservalocker_item', 'varArea', 'idLocal="'.$l['id'].'"','varArea','','varArea'); 
                $areas=$this->DB->get('array');
                    
                if($areas){
                    echo '<li>';
                    echo '<a href="#" class="group"><span class="ui-icon ui-icon-triangle-1-e"></span>'.$l['varName'].'</a>';

                    echo '<ul>'; 
                    foreach ($areas as $a) {

                            echo '<li>';
                            echo '<a href="#" class="group"><span class="ui-icon ui-icon-triangle-1-e"></span>'.$a['varArea'].'</a>';

                            $this->DB->select(WP_PREFIX . 'maletek_reservalocker_item', 'varName', 'varArea="'.$a['varArea'].'" AND idLocal="'.$l['id'].'"'); 
                            $sector=$this->DB->get('array');

                            if($sector){
                                echo '<ul>';
                                foreach ($sector as $s) {
                                    echo '<li>';
                                    echo '<a href="#" class="group"><span class="ui-icon ui-icon-triangle-1-e"></span>'.$s['varName'].'</a>';

                                    $this->DB->select(WP_PREFIX . 'maletek_reservalocker_module', 'id', 'idItem="'.$s['id'].'"'); 
                                    $module=$this->DB->get('array');

                                    if($module){
                                        echo '<ul>';
                                        foreach ($module as $m) {
                                            $this->DB->query("SELECT COUNT(*) as TOTAL FROM ".$this->table_name)." WHERE idModule='".$m['id']."'";
                                            $result=$this->DB->get_first('array');           
                                            $nroLocker=$result?$result['TOTAL']:0;
                                            $nroLocker=$this->listModelTotal[ $m['idModel'] ]-$nroLocker;
                                            
                                            echo '<li>';
                                            echo '<a class="link" href="#" itemid="'.$m['id'].'"><span class="ui-icon ui-icon-search"></span>'.$this->listModel[$m['idModel']].' -  Serie '.$m['intSerie'].' / <sub>Disponibles '.$nroLocker.'<sub></a>';
                                            echo '</li>';
                                        }
                                        echo '</ul>';
                                    }                                            

                                    echo '</li>';
                                }
                                echo '</ul>';
                            }
                            echo '</li>';

                    }
                    echo '</ul>';
                    
                    echo '</li>';
                }
            }
            echo '</ul>';
        }
                
        
    }
    
    
    function table(){
        
        #buscar vencidos
        $this->DB->query("DELETE FROM ".$this->table_name." WHERE dateCreate<DATE_SUB(NOW(),INTERVAL 24 HOUR) AND charSt=0");
                
        $this->DB->query("SELECT COUNT(*) as TOTAL FROM ".$this->table_name);
        $result=$this->DB->get_first('array');           
        $iTotalRecords=$result?$result['TOTAL']:0;
                  
        $where='';
        $order=($_REQUEST['iSortCol_0']?$_REQUEST['sSortDir_0']:'varName').' '.($_REQUEST['sSortDir_0']?$_REQUEST['sSortDir_0']:'ASC');
        $limit='';      
        
        if($_REQUEST['sSearch']){
            $where='('.
                        'us.varName LIKE "%'.$_REQUEST['sSearch'].'%"'.
                        ' OR '.
                        'us.varEmail LIKE "%'.$_REQUEST['sSearch'].'%"'.
                        ' OR '.
                        'md.varName LIKE "%'.$_REQUEST['sSearch'].'%"'.
                        ' OR '.
                        'it.varName LIKE "%'.$_REQUEST['sSearch'].'%"'.
                        ' OR '.
                        'it.varArea LIKE "%'.$_REQUEST['sSearch'].'%"'.
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
        $sql="SELECT COUNT(*) as TOTAL FROM ".$this->table_name." as rs".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_user as us ON us.id=rs.idUser".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_module as ml ON ml.id=rs.idModule".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_model as md ON md.id=ml.idModel".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_item as it ON it.id=ml.idItem".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_local as lc ON lc.id=it.idLocal".
                ($where?" WHERE ".$where:"");
        $this->DB->query($sql);
        
        $result=$this->DB->get_first('array');           
        $iTotalDisplayRecords=$result?$result['TOTAL']:0;
        
        $orderP='id';
        $orderD='DESC';
        $sortArray=array(
            'rs.dateCreate DESC','us.varName ASC',
        );
        if($_REQUEST['iSortCol_0'] && array_key_exists($_REQUEST['iSortCol_0'], $sortArray) ){
            $orderP=$sortArray[$_REQUEST['iSortCol_0']];
        }   
        if($_REQUEST['sSortDir_0']){
            $orderD=$_REQUEST['sSortDir_0'];
        }   
        
        $sql="SELECT rs.*, us.varName as varUser , us.varEmail, ml.idModel, us.idLocal, it.varArea, intSerie, it.varName as varSector, dateCreate FROM ".$this->table_name." as rs".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_user as us ON us.id=rs.idUser".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_item as it ON it.idLocal=us.idLocal".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_module as ml ON ml.id=rs.idModule AND ml.idItem=it.id".
                //" INNER JOIN ". WP_PREFIX . "maletek_reservalocker_model as md ON md.id=ml.idModel".
                ($where?" WHERE ".$where:"").
                " ORDER BY ".$orderP." ".$orderD.
                " LIMIT ".$limit;
        
        $this->DB->query($sql);
        $regs=$this->DB->get('array');
        
        $aaData=array();
        if($regs){
            foreach ($regs as $r) {
                
                $nro=($r['intSerie']+$this->listModelTotal[ $r['idModel'] ]);
                $item= $r['varArea'].' / '.$r['varSector'] .' / '. $this->listModel[ $r['idModel'] ].' / Serie N° '.$r['intSerie'].'-'.$nro;
            
                
               $aaData[]=array(
                    $r['id'],
                    '<a itemid="'.$r['id'].'" itemref="'.ucfirst($this->object).'" class="editReg " href="'.MALETEKPL_RSV_BACKEND_CONTROLLER.'?controller='.$this->object.'&action=edit" >'.$r['varUser'].' <br> '.$r['varEmail'].'</a>',
                    $this->listLocales[ $r['idLocal'] ],
                    $item,
                    $r['idLocker'],
                    $r['charSt']==='0'?'Reservado':'Ocupado',
                    formatDate($r['dateCreate']),
                    '<input itemid="'.$r['id'].'" itemref="Reserve" type="button" class="button reserveMail" value="Mensaje" />'.
                    '<input itemid="'.$r['id'].'" itemref="Reserve" type="button" class="button deleteReg" value="Eliminar" />'
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
            if( !$_POST['idUser'] || !$_POST['idLocker'] ){
                throw new Exception('Información incompleta');
            }
                    
            $this->DB->select(WP_PREFIX . 'maletek_reservalocker_module', null, 'id="'.$_POST['idModule'].'"'); 
            $module=$this->DB->get_first('array');
            
            $data=array(
                'idItem'        =>$module['idItem'],
                'idModule'      =>$_POST['idModule'],
                'idLocker'      =>$_POST['idLocker'],
                'idUser'        =>$_POST['idUser'],
                'charSt'        =>$_POST['charSt'],
                'dateUpdate'    =>date('Y-m-d H:i:s'),
                'varShareName'  =>$_POST['varShareName'],
                'varShareCode'  =>$_POST['varShareCode'],
                'varShareCareer'=>$_POST['varShareCareer'],
                'varShareLevel' =>$_POST['varShareLevel']
                
            );
            
            if($_POST['idReg']){
                $this->DB->update($this->table_name, $data, 'id="'.$_POST['idReg'].'"');
            }
            else{
                $data['dateCreate']=date('Y-m-d H:i:s');
                $this->DB->insert($this->table_name, $data);
            }            
            $message='<div class="updated">Se guardó la información correctamente</div>';
            
        } catch (Exception $ex) {
            $message='<div class="error">'.$ex->getMessage().'</div>';
        }
               
        exit($message);
        
    }
    
    function load(){
        $idReg=intval($_POST['id']);
        
        $result=array();
        
        try{
            if( !$idReg ){
                throw new Exception('Información incompleta para eliminar archivo');
            }
            
            $this->DB->select($this->table_name, null, 'id="'.$idReg.'"'); 
            $result=$this->DB->get_first('array');
            $result['idReg']=$result['id'];
            $result['idUserReserve']=$result['idUser'];
            
            $this->DB->select(WP_PREFIX . 'maletek_reservalocker_user', null, 'id="'.$result['idUser'].'"'); 
            $user=$this->DB->get_first('array');
            $result['varUser']=$user['varName'];
            
            if( !$result || !$result['id']){
                throw new Exception('Registro no existe');
            }
            
        } catch (Exception $ex) {
            $result=array('error'=>$ex->getMessage());
        }
        
        exit(json_encode($result));
    }
        
    function delete(){
        
        $idReg=intval($_POST['id']);
        
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
    
    
    function message(){
        
        $result=array(
            'message'=>'Error al enviar mensaje'
        );
        
        $idReg=intval($_POST['id']);
        
        $sql="SELECT rs.*, us.varName as varUser , us.varEmail, ml.idModel, us.idLocal, it.varArea, intSerie, it.varName as varSector, dateCreate FROM ".$this->table_name." as rs".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_user as us ON us.id=rs.idUser".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_item as it ON it.idLocal=us.idLocal".
                " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_module as ml ON ml.id=rs.idModule AND ml.idItem=it.id".
                " WHERE rs.id='".$idReg."'";
        
        $this->DB->query($sql);
        $data=$this->DB->get_first('array');
        
        if($data['id']){
            
            #mailing
            $this->DB->select(WP_PREFIX . 'maletek_reservalocker_config'); 
            $regs=$this->DB->get('array');    
            $config=array();
            if($regs){
                foreach ($regs as $key=>$r) {
                    $config[$r['varName']]=$r['varValue'];
                }   
            }
            
            $info='<table border="0">';
            $infoLabel=array(
                'varUser'=>'Usuario',
                'varEmail'=>'Email',
                'idLocal'=>'Instituto',
                'varArea'=>'Área',
                'varSector'=>'Sector',
                'idLocker'=>'N° Locker',
                'dateCreate'=>'Fecha de Solicitud',               
                'varShareName'=>'Usuario Compartido',
                'varShareCode'=>'Código de Usuario Compartido',
                'varShareCareer'=>'Carrera de Usuario Compartido',
                'varShareLevel'=>'Ciclo de Usuario Compartido',
                'charSt'=>'Estado de Locker',
            );
            foreach ($infoLabel as $i=>$l) {
                $info.='<tr>';
                if($i=='dateCreate'){
                    $time=  strtotime($data[$i]);
                                        
                    $info.= '<td><b>'.$l.'</b></td><td>'.date('d/m/Y H:i',$time).'</td></tr>'.
                            '<tr><td><b>Fecha Caducidad</b></td><td>'.date('d/m/Y H:i',$time+(60*60*24)).'</td>';
                }
                elseif($i=='charSt'){
                    $info.='<td><b>'.$l.'</b></td></td>'.($data[$i]?'Ocupado':'Reservado').'</td>';
                }
                elseif($i=='idLocal'){
                    $info.='<td><b>'.$l.'</b></td></td>'.$this->listLocales[ $data[$i] ].'</td>';
                }
                else{
                    $info.='<td><b>'.$l.'</b></td><td>'.($data[$i]?$data[$i]:'- NO disponible -').'</td>';
                }
                $info.='</tr>';
            }
            $info.='</table/>';
            
            try{
                
                add_filter( 'wp_mail_content_type', 'set_html_content_type' );
                
                $body='<div style="background:#820a11;padding:10px 20px;margin:15px auto 0;"><img src="'.MALETEKPL_RSV_PLUGIN_URL.'frontend/imgs/f_maletek.png" /></div><br/><br/>';
                $body.=str_replace('{info}',$info, nl2br($config['textBody']) );
                
                wp_mail( $data['varEmail'], $config['textSubject'], $body );
                /*
                $varReceptor=explode(',',$config['varReceptor']);
                if($varReceptor){
                    foreach ($varReceptor as $v) {
                        wp_mail( $v, $config['textSubject'], $body );
                    }
                }
                */

                // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
                remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
                                
                $result['message']='Email enviado correctamente.';
            }
            catch (Exception $e){
                $result['message']=$e->getMessage();
            }
            
            
        }
        else{
            $result['message']='Código de reserva no identificado';
        }
        
        exit(json_encode($result));
    }
}
function set_html_content_type() {
        return 'text/html';
}