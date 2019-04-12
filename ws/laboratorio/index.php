<?php
require_once 'laboratorio.php'; 
$laboratorio=new laboratorio();
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
          $laboratorio->nombre=$request['nombre'];
          $laboratorio->estado_id=1;  
        }else{        
          $laboratorio->nombre=$_POST['nombre'];
          $laboratorio->estado_id=1;  
        }     
        $data=$laboratorio->crearLaboratorio();     
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
          $laboratorio->id=$request['id'];        
          $laboratorio->nombre=$request['nombre']; 
          $laboratorio->estado_id=$request['estado_id'];  
        }else{
          $laboratorio->id=$_POST['id'];
          $laboratorio->nombre=$_POST['nombre'];
          $laboratorio->estado_id=$_POST['estado_id'];  
        }     
        $data=$laboratorio->editarLaboratorio();     
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
          $laboratorio->id=$request['id'];
        }else{
          $laboratorio->id=$_POST['id'];
        }     
        $data=$laboratorio->eliminarLaboratorio();     
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
    $data=$laboratorio->formEditar();
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
        $data=$laboratorio->buscarLaboratorios($columna,$valor,$status);        
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

 