<?php
class ReserveController {
    
    private $table_name=null;
    private $DB=null;
    
    function __construct() 
    {
        global $DB;
        $this->DB =& $DB;
        
        $this->table_name=WP_PREFIX . 'maletek_reservalocker_user';
        
        
        $this->DB->select(WP_PREFIX . 'maletek_reservalocker_local', 'varName ASC'); 
        $regs=$this->DB->get('array');
        
        $this->listLocales=array(0=>'NO disponible');
        if($regs){
            foreach ($regs as $r) {
                $this->listLocales[$r['id']]=$r['varName'].' / '.$r['varSubName'].' / '.$r['varPlace'];
            }
        }
        
    }
    function save(){
        if(!$_SESSION['reservalockerUser']){
            exit('ERROR');
        }
        if(!$_POST){
            exit('ERROR-POST');
        }
        
        $this->DB->select(WP_PREFIX . "maletek_reservalocker_reserve", null, 'idUser="'.$_SESSION['reservalockerUser'].'"'); 
        $data=$this->DB->get_first('array');
        
        if($data['id']){
            exit('ERROR-RESERVE');
        }

        
        $this->DB->select(WP_PREFIX . 'maletek_reservalocker_module', null, 'id="'.$_POST['module'].'"'); 
        $module=$this->DB->get_first('array');
                
        
        $sql="SELECT COUNT(*) as tot FROM ". WP_PREFIX . "maletek_reservalocker_reserve".
                " WHERE idModule='".$_POST['module']."' AND idLocker='".$_POST['locker']."'";

        $this->DB->query($sql);
        $reserve=$this->DB->get_first('array');
        if($reserve['tot']){
            exit('ERROR-DISABLE');
        }
            
        $locker=$_POST['locker'];
        $data=array(
            'idItem'        =>$module['idItem'],
            'idModule'      =>$_POST['module'],
            'idLocker'      =>$_POST['locker'],
            'idUser'        =>$_SESSION['reservalockerUser'],
            'charSt'        =>0,
            'dateUpdate'    =>date('Y-m-d H:i:s'),
            'dateCreate'    =>date('Y-m-d H:i:s'),
            'varShareName'  =>$_POST['name'],
            'varShareCode'  =>$_POST['code'],
            'varShareCareer'=>$_POST['career'],
            'varShareLevel' =>$_POST['level']

        );

        if( !$idReg=$this->DB->insert(WP_PREFIX . "maletek_reservalocker_reserve", $data) ){
            exit('ERROR-INSERT');
        }
            
        #mailing
        $this->DB->select(WP_PREFIX . 'maletek_reservalocker_config'); 
        $regs=$this->DB->get('array');    
        $config=array();
        if($regs){
            foreach ($regs as $key=>$r) {
                $config[$r['varName']]=$r['varValue'];
            }   
        }

        $sql="SELECT rs.*, us.varName as varUser , us.varEmail, ml.idModel, us.idLocal, it.varArea, intSerie, it.varName as varSector, dateCreate ".
                "FROM ". WP_PREFIX . "maletek_reservalocker_reserve as rs".
            " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_user as us ON us.id=rs.idUser".
            " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_item as it ON it.idLocal=us.idLocal".
            " INNER JOIN ". WP_PREFIX . "maletek_reservalocker_module as ml ON ml.id=rs.idModule AND ml.idItem=it.id".
            " WHERE rs.id='".$idReg."'";

        $this->DB->query($sql);
        $data=$this->DB->get_first('array');

        
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

            $varReceptor=explode(',',$config['varReceptor']);
            if($varReceptor){
                foreach ($varReceptor as $v) {
                    wp_mail( $v, $config['textSubject'], $body );
                }
            }


            remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

            $result['message']='Email enviado correctamente.';
        }
        catch (Exception $e){
            exit('ERROR-MAIL');
        }
           
                
        ?>
        Estimado estudiante se ha hecho paso a la reserva del locker N°<?php echo $locker ?>, 
        la reserva cadurará en las proximas 24 horas, de no hacer el pago y envío de voucher en ese lapso de
        tiempo la reserva no procederá.
        <?php
        exit;
    }
    function saveV2(){
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
}


function set_html_content_type() {
        return 'text/html';
}