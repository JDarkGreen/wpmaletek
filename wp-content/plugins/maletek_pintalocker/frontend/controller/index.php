<?php 
include_once '../../libs/mysql/conexion.php';

$error=false;
$controllerNm=trim($_REQUEST['controller']);
$methodNm=trim($_REQUEST['action']);

try{
    $controller=  ucfirst($controllerNm).'Controller.php';
    
    if( !$controllerNm || !$methodNm){
        throw new Exception('Solicitud inválida');        
    }
    elseif(!is_file($controller)){
        throw new Exception('Controlador inválido');     
    }
    
    $controllerNm=  ucfirst($controllerNm).'Controller';
    
    include_once $controller;
    
    eval('$obj=new '.$controllerNm.'();');
    
    $methods=get_class_methods($controllerNm);
 
    if(!in_array($methodNm, $methods)){
        throw new Exception('Método de Controlador inválido');
    }
    
    eval('$obj->'.$methodNm.'();');
    
} catch (Exception $ex) {
    $_SESSION['error']=$ex->getMessage();
}
exit;