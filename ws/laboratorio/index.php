<?php
header('Access-Control-Allow-Origin: *');  
require_once 'laboratorio.php'; 
$laboratorio=new laboratorio();
$header=getallheaders();
switch ($_GET['solicitud']){

  case 'crear':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                        
        $laboratorio->nombre=$request['nombre'];
        $laboratorio->estado=$request['estado'];  
      }else{        
        $laboratorio->nombre=$_POST['nombre'];
        $laboratorio->estado=$_POST['estado'];  
      }     
      $data=$laboratorio->crearLaboratorio();     
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
        $laboratorio->id=$request['id'];        
        $laboratorio->nombre=$request['nombre']; 
        $laboratorio->estado=$request['estado'];  
      }else{
        $laboratorio->id=$_POST['id'];
        $laboratorio->nombre=$_POST['nombre'];
        $laboratorio->estado=$_POST['estado'];  
      }     
      $data=$laboratorio->editarLaboratorio();     
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
        $laboratorio->id=$request['id'];
      }else{
        $laboratorio->id=$_POST['id'];
      }     
      $data=$laboratorio->eliminarLaboratorio();     
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
      $data=$laboratorio->listaLaboratorio($filtro,$columna,$valor);
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

 