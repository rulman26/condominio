<?php
header('Access-Control-Allow-Origin: *');  
require_once 'proveedor.php'; 
$proveedor=new proveedor();
$header=getallheaders();
switch ($_GET['solicitud']){

  case 'crear':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $proveedor->ruc=$request['ruc'];
        $proveedor->nombre=$request['nombre'];
        $proveedor->direccion=$request['direccion'];  
        $proveedor->telefono=$request['telefono'];  
        $proveedor->email=$request['email'];  
        $proveedor->estado=$request['estado'];  
      }else{
        $proveedor->ruc=$_POST['ruc'];
        $proveedor->nombre=$_POST['nombre'];
        $proveedor->direccion=$_POST['direccion'];  
        $proveedor->telefono=$_POST['telefono']; 
        $proveedor->email=$_POST['email']; 
        $proveedor->estado=$_POST['estado'];  
      }     
      $data=$proveedor->crearProveedor();     
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
        $proveedor->id=$request['id'];
        $proveedor->ruc=$request['ruc'];
        $proveedor->nombre=$request['nombre'];
        $proveedor->direccion=$request['direccion'];  
        $proveedor->telefono=$request['telefono'];  
        $proveedor->email=$request['email'];  
        $proveedor->estado=$request['estado'];  
      }else{
        $proveedor->id=$_POST['id'];
        $proveedor->ruc=$_POST['ruc'];
        $proveedor->nombre=$_POST['nombre'];
        $proveedor->direccion=$_POST['direccion'];  
        $proveedor->telefono=$_POST['telefono'];
        $proveedor->email=$_POST['email'];
        $proveedor->estado=$_POST['estado'];  
      }     
      $data=$proveedor->editarProveedor();     
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
        $proveedor->id=$request['id'];
      }else{
        $proveedor->id=$_POST['id'];
      }     
      $data=$proveedor->eliminarProveedor();     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;
  

  case 'filtrar':
    $filtro=$_GET['id'];
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                        
        $columna=$request['filtro'];
        $valor=$request['input_filtro'];
      }else{
        $columna=$_POST['filtro'];
        $valor=$_POST['input_filtro'];
      }     
      $data=$proveedor->listaProveedores($filtro,$columna,$valor);
    }else{      
      $data['estado']=false;
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

 