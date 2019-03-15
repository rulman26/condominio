<?php
header('Access-Control-Allow-Origin: *');  
require_once 'salida.php'; 
$salida=new salida();
$header=getallheaders();
switch ($_GET['solicitud']){

  case 'crear':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $salida->inventario_id=$request['inventario_id'];        
        $salida->precio=$request['precio'];
        $salida->cantidad=$request['cantidad'];
        $salida->fecha=$request['fecha'];
        $salida->tipo=$request['tipo'];
        $salida->usuario_id=$request['usuario_id'];
        $salida->estado=$request['estado'];
      }else{
        $salida->inventario_id=$_POST['inventario_id'];        
        $salida->precio=$_POST['precio'];
        $salida->cantidad=$_POST['cantidad'];
        $salida->fecha=$_POST['fecha'];
        $salida->tipo=$_POST['tipo'];
        $salida->usuario_id=$_POST['usuario_id'];
        $salida->estado=$_POST['estado'];
      }     
      $data=$salida->crearSalida();     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'editar':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $salida->id=$request['id'];
        $salida->inventario_id=$request['inventario_id'];        
        $salida->precio=$request['precio'];
        $salida->cantidad=$request['cantidad'];
        $salida->fecha=$request['fecha'];
        $salida->tipo=$request['tipo'];
        $salida->usuario_id=$request['usuario_id'];
        $salida->estado=$request['estado'];
      }else{
        $salida->id=$_POST['id'];
        $salida->inventario_id=$_POST['inventario_id'];        
        $salida->precio=$_POST['precio'];
        $salida->cantidad=$_POST['cantidad'];
        $salida->fecha=$_POST['fecha'];
        $salida->tipo=$_POST['tipo'];
        $salida->usuario_id=$_POST['usuario_id'];
        $salida->estado=$_POST['estado'];
      }     
      $data=$salida->editarSalida();     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'leer':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $proveedor->id=$request['id'];
      }else{
        $proveedor->id=$_POST['id'];
      }     
      $data=$proveedor->leerProveedor();     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'eliminar':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $salida->id=$request['id'];
      }else{
        $salida->id=$_POST['id'];
      }     
      $data=$salida->eliminarSalida();     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;
  

  case 'filtrar':
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $fecha=$request['fecha'];
        $inicio=$request['inicio'];
        $final=$request['final'];
        $item_id=$request['item_id'];
      }else{
        $fecha=$_POST['fecha'];
        $inicio=$_POST['inicio'];
        $final=$_POST['final'];
        $item_id=$_POST['item_id'];
      }           
      $filtro=$_GET['id'];      
      $data=$salida->listaSalidas($filtro,$fecha,$inicio,$final,$item_id);
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }
	  echo json_encode($data,JSON_PRETTY_PRINT);
  break;  

  default:
    $msj['estado']='ERROR';
    $msj['mensaje']='API NO CONTIENE DATA';
    echo json_encode($msj,JSON_PRETTY_PRINT);
  break;
}
?>

 