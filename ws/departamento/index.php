<?php
require_once 'departamento.php'; 
$departamento=new departamento();

function tokenValido(){
  $header=apache_request_headers();
  $token=base64_decode($header['Authorization']);
  $datos=explode(",",$token);
  $valido=BaseDatos::Valido($datos[0],$datos[1]);  
  return $valido;
}

switch ($_GET['solicitud']){
  case 'formeditar':    
    $data=$departamento->formEditar();
    echo json_encode($data,JSON_PRETTY_PRINT);  
  break;

  case 'crear':
    if(tokenValido()){      
      if ($_SERVER['REQUEST_METHOD']=="POST") {      
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);                
          $departamento->numero=$request['numero'];
          $departamento->ocupado=$request['ocupado'];
          $departamento->bloque_id=$request['bloque_id'];  
          $departamento->propietario_id=$request['propietario_id'];            
          $departamento->estado_id=1;  
        }else{
          $departamento->numero=$_POST['numero'];        
          $departamento->ocupado=$_POST['ocupado'];  
          $departamento->bloque_id=$_POST['bloque_id']; 
          $departamento->propietario_id=$_POST['propietario_id'];           
          $departamento->estado_id=1;  
        }             
        $data=$departamento->crearDeapartamento();
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
          $departamento->id=$request['id'];
          $departamento->numero=$request['numero'];
          $departamento->ocupado=$request['ocupado'];
          $departamento->bloque_id=$request['bloque_id'];  
          $departamento->propietario_id=$request['propietario_id'];            
          $departamento->estado_id=$request['estado_id'];  
        }else{
          $departamento->id=$_POST['id'];
          $departamento->numero=$_POST['numero'];
          $departamento->ocupado=$_POST['ocupado'];  
          $departamento->bloque_id=$_POST['bloque_id']; 
          $departamento->propietario_id=$_POST['propietario_id'];           
          $departamento->estado_id=$_POST['estado_id'];  
        }     
        $data=$departamento->editarDepartamento();     
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
          $departamento->id=$request['id'];
        }else{
          $departamento->id=$_POST['id'];
        }     
        $data=$departamento->eliminardepartamento();     
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
        $data=$departamento->buscardepartamentos($columna,$valor,$status);        
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

  case 'bloque':
    if(tokenValido()){
      if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);
          $bloque_id=$request['bloque_id'];                   
        }else{
          $bloque_id=$_POST['bloque_id'];          
        }     
        $data=$departamento->departamentosByBloqueId($bloque_id);        
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

 