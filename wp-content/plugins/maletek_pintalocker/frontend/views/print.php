<?php
session_start();
include_once '../../libs/mysql/conexion.php';

$itemId=trim($_GET['itemId']);
$schemeColor=trim($_GET['schemeColor']);

$schemaPosT=200;
$schemaPosL=200;
 
$content='';
if($itemId){
    
    #item
    $DB->select(WP_PREFIX . 'maletek_pintalocker_item', null, 'id="'.$itemId.'"' ); 
    $item=$DB->get_first('array');
    
    $content.= '<html><head>
        <title>Pinta tu Locker - Imprimir</title>
	<meta http-equiv="Content-Language" content="en-GB">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
    $content.= '<style>
        *{
            margin: 0;
            padding: 0;
        }
        #pageForm{
            font-family: Verdana;
            margin:auto;
            width: 600px;
            height:800px;
            padding: 20px;
            font-size: 14px;
            line-height: 16px;
            color: #000;
            border: 1px dotted #000;
            text-align: left;
            margin-bottom: 20px;
        }
        #pageForm .field{
           margin-bottom: 10px;
        }
        #pageForm .label{
            width: 200px;
            font-weight: bold;
            float: left;
        }
        #pageForm .input{
            margin-left: 200px;
        }
        .schema{
            border: 1px solid #000;
            width: 400px;
            height: 400px;
            overflow: hidden;
            position: absolute;
            top:'.$schemaPosT.'px;
            left:'.$schemaPosL.'px;
        }
        .locker{
            position:absolute;
            display:block;
            top:20px;
            left:20px;
        }
        #header{
            background: #820a11;
            padding: 10px 20px;
            width: 600px;
            margin: 15px auto 0;
        }
        #logo{
            width: 150px;
            height: 35px;
        }
        .control{
            font-family: Verdana;
            margin:auto;
            width: 640px;
            padding: 0 0 20px;
            font-size: 14px;
            line-height: 16px;
            color: #000;
            clear: both;
        }
        .control a{
            display: inline-block;
            padding: 5px 10px;
            background: #820a11;
            border: 1px solid #820a11;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
            margin-right: 10px;
        }
        .control a:hover{
            color: #000;
            background: #fff;
        }

        @media print
        {    
            .no-print, .no-print *
            {
                display: none !important;
            }
        }
</style>';
    $content.= '</head><body>';
    $content.= '<div id="header"><div id="logo"><img src="'.MALETEKPL__PLUGIN_URL.'frontend'.DS.'imgs'.DS.'f_maletek.png" /></div></div>';
    $content.= '<div id="pageForm">';       
        $content.= '<div class="field">';
            $content.= '<div class="label">Tipo de Locker:</div>';
            $content.= '<div class="input">'.$item['varName'].'</div>';
        $content.= '</div>';      
        $content.= '<div class="field">';
            $content.= '<div class="label">Esquema:</div>';
            $content.= '<div class="input">&nbsp;</div>';
        $content.= '</div>';
        
        #color
        $DB->select(WP_PREFIX . 'maletek_pintalocker_color', 'varName ASC'); 
        $colors=$DB->get('array');
        $colorList=array();
        if($colors){
            foreach ($colors as $c) {
                $colorList[$c['id']]=$c;
            }
        }
    $content.= '</div>';
    
    #coords
    $schemeColor=stripslashes($schemeColor);
    $colorLk=  $schemeColor?json_decode($schemeColor,true):array();

    $bg=MALETEKPL__PLUGIN_URL.'resources'.DS.'items'.DS.$item['varImageFront'];
    $content.= '<div style="background-image:url('.$bg.')" class="schema"></div>';
    
    $item['varCoord']=str_replace(array('"{','x1','x2','y1','y2','}"'),array('{','"x1"','"x2"','"y1"','"y2"','}'),$item['varCoord']);
    $varCoord= json_decode($item['varCoord'],true);
        
    if($varCoord){
            foreach ($varCoord as $id=>$value) {
                $w=  abs($value['x1']-$value['x2']);
                $h=  abs($value['y1']-$value['y2']);
                $l=$value['x1']+$schemaPosL;
                $t=$value['y1']+$schemaPosT;

                $colorId=array_key_exists($id, $colorLk)?$colorLk[$id]:'';

                $bg='';
                if(array_key_exists($colorId, $colorList)){
                    $bg='background-image:url('.MALETEKPL__PLUGIN_URL.'resources'.DS.'color'.DS.$colorList[$colorId]['varImage'].');';
                }

                $content.= '<div class="locker" itemid="'.$id.'" itemref="'.$colorId.'" style="'.$bg.'top:'.$t.'px;left:'.$l.'px;width:'.$w.'px;height:'.$h.'px;"></div>';
            }
        }
    
    $content.= '</body></html>';
    
    #print
    /*
    $content.= '<div class="control no-print">';
        $content.= '<a href="#" onclick="print();">Imprimir</a>';
    $content.= '</div>';
    */
    
    #pdf
    include(MALETEKPL__PLUGIN_DIR ."libs/mpdf.v5.7/mpdf.php");
    $mpdf=new mPDF('s','A4'); 
    
    $mpdf->mirrorMargins = 1;
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->useGraphs = true;
    $mpdf->hyphenate = true;

    $mpdf->debug  = true;

    // LOAD a stylesheet
    //$stylesheet = file_get_contents(MALETEKPL__PLUGIN_DIR.'css/pdf.css');
    $mpdf->WriteHTML($stylesheet,1);
    $mpdf->WriteHTML($content);

    #$mpdf->Output(MALETEKPL__PLUGIN_DIR.'resources/request/'.$reg['id'].'_'.date('Ymd-His', $dateCreate ).'.pdf','F');
    $mpdf->Output();
        
    $content='';
}
else{
    echo $_SESSION['errorInfo']='Solictud no encontrada';
    header('Location: index.php');
}

exit($content);
        
