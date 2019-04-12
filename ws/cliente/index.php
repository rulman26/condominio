<?php
require_once 'cliente.php'; 
$cliente=new cliente();

function tokenValido(){
  $header=apache_request_headers();
  $token=base64_decode($header['Authorization']);
  $datos=explode(",",$token);
  $valido=BaseDatos::Valido($datos[0],$datos[1]);  
  return $valido;
}

switch ($_GET['solicitud']){
  case 'crear':
    if(tokenValido()){      
      if ($_SERVER['REQUEST_METHOD']=="POST") {      
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);                
          $cliente->nombre=$request['nombre'];
          $cliente->direccion=$request['direccion'];
          $cliente->dni=$request['dni'];  
          $cliente->ruc=$request['ruc'];  
          $cliente->telefono=$request['telefono'];  
          $cliente->estado_id=1;  
        }else{
          $cliente->nombre=$_POST['nombre'];        
          $cliente->direccion=$_POST['direccion'];  
          $cliente->dni=$_POST['dni']; 
          $cliente->ruc=$_POST['ruc']; 
          $cliente->telefono=$_POST['telefono'];  
          $cliente->estado_id=1;  
        }     
        $data=$cliente->crearCliente();
      }else{      
        $data['status']=false;
        $data['mensaje']="NO EXISTEN DATOS POST";                    
      }
    }else{
      $data['status']=false;
      $data['mensaje']="token Seguridad Error";
    }
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'editar':
    if(tokenValido()){   
      if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);                
          $cliente->id=$request['id'];
          $cliente->nombre=$request['nombre'];
          $cliente->direccion=$request['direccion'];
          $cliente->dni=$request['dni'];  
          $cliente->ruc=$request['ruc'];  
          $cliente->telefono=$request['telefono'];  
          $cliente->estado_id=$request['estado_id'];  
        }else{
          $cliente->id=$_POST['id'];
          $cliente->nombre=$_POST['nombre'];
          $cliente->direccion=$_POST['direccion'];  
          $cliente->dni=$_POST['dni']; 
          $cliente->ruc=$_POST['ruc']; 
          $cliente->telefono=$_POST['telefono'];  
          $cliente->estado_id=$_POST['estado_id'];  
        }     
        $data=$cliente->editarCliente();     
      }else{      
        $data['status']=false;
        $data['mensaje']="NO EXISTEN DATOS POST";                    
      }
    }else{
      $data['status']=false;
      $data['mensaje']="token Seguridad Error";
    }                 
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'eliminar':
    if(tokenValido()){     
      if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);                
          $cliente->id=$request['id'];
        }else{
          $cliente->id=$_POST['id'];
        }     
        $data=$cliente->eliminarCliente();     
      }else{      
        $data['status']=false;
        $data['mensaje']="NO EXISTEN DATOS POST";                    
      }
    }else{
      $data['status']=false;
      $data['mensaje']="token Seguridad Error";
    }               
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'formeditar':    
    $data=$cliente->formEditar();
    echo json_encode($data,JSON_PRETTY_PRINT);  
  break;

  case 'buscar':
    if(tokenValido()){
      if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);
          $columna=$request['filtro'];
          $valor=$request['input_filtro'];  
          $status=$request['status'];          
        }else{
          $columna=$_POST['filtro'];
          $valor=$_POST['input_filtro'];
          $status=$_POST['status'];
        }     
        $data=$cliente->buscarClientes($columna,$valor,$status);        
      }else{      
        $data['status']=false;
        $data['mensaje']="NO EXISTEN DATOS POST";                    
      }
    }else{
      $data['status']=false;
      $data['mensaje']="token Seguridad Error";
    }               
    echo json_encode($data,JSON_PRETTY_PRINT);
  break; 

  default:
    $msj['status']=false;
    $msj['mensaje']='API NO CONTIENE DATA';
    echo json_encode($msj,JSON_PRETTY_PRINT);
  break;
}
?>

 