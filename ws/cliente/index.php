<?php
header('Access-Control-Allow-Origin: *');  
require_once 'cliente.php'; 
$cliente=new cliente();
$header=getallheaders();
switch ($_GET['solicitud']){

  case 'crear':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      var_dump($_POST);
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $cliente->nombre=$request['nombre'];
        $cliente->direccion=$request['direccion'];
        $cliente->dni=$request['dni'];  
        $cliente->ruc=$request['ruc'];  
        $cliente->telefono=$request['telefono'];  
        $cliente->estado=$request['estado'];  
      }else{
        $cliente->nombre=$_POST['nombre'];        
        $cliente->direccion=$_POST['direccion'];  
        $cliente->dni=$_POST['dni']; 
        $cliente->ruc=$_POST['ruc']; 
        $cliente->telefono=$_POST['telefono'];  
        $cliente->estado=$_POST['estado'];  
      }     
      $data=$cliente->crearCliente();
    }else{      
      $data['estado']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'editar':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $cliente->id=$request['id'];
        $cliente->nombre=$request['nombre'];
        $cliente->direccion=$request['direccion'];
        $cliente->dni=$request['dni'];  
        $cliente->ruc=$request['ruc'];  
        $cliente->telefono=$request['telefono'];  
        $cliente->estado=$request['estado'];  
      }else{
        $cliente->id=$_POST['id'];
        $cliente->nombre=$_POST['nombre'];
        $cliente->direccion=$_POST['direccion'];  
        $cliente->dni=$_POST['dni']; 
        $cliente->ruc=$_POST['ruc']; 
        $cliente->telefono=$_POST['telefono'];  
        $cliente->estado=$_POST['estado'];  
      }     
      $data=$cliente->editarCliente();     
    }else{      
      $data['estado']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'leer':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $cliente->id=$request['id'];
      }else{
        $cliente->id=$_POST['id'];
      }     
      $data=$cliente->leerCliente();     
    }else{      
      $data['estado']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'eliminar':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $cliente->id=$request['id'];
      }else{
        $cliente->id=$_POST['id'];
      }     
      $data=$cliente->eliminarCliente();     
    }else{      
      $data['estado']=false;
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
      $data=$cliente->listaClientes($filtro,$columna,$valor);
    }else{      
      $data['estado']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }    		
	  echo json_encode($data,JSON_PRETTY_PRINT);
  break;  

  default:
    $msj['estado']=false;
    $msj['mensaje']='API NO CONTIENE DATA';
    echo json_encode($msj,JSON_PRETTY_PRINT);
  break;
}
?>

 