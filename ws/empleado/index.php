<?php
require_once 'empleado.php'; 
$empleado=new empleado();

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
          $empleado->dni=$request['dni'];
          $empleado->nombres=$request['nombres'];
          $empleado->apaterno=$request['apaterno'];  
          $empleado->amaterno=$request['amaterno'];  
          $empleado->correo=$request['correo'];  
          $empleado->imagen=""; 
          $empleado->telefono=$request['telefono'];  
          $empleado->estado_id=1;  
        }else{
          $empleado->dni=$_POST['dni'];        
          $empleado->nombres=$_POST['nombres'];  
          $empleado->apaterno=$_POST['apaterno']; 
          $empleado->amaterno=$_POST['amaterno']; 
          $empleado->correo=$_POST['correo'];  
          $empleado->imagen="";  
          $empleado->telefono=$_POST['telefono'];
          $empleado->estado_id=1;
        }     
        $data=$empleado->crearColaborador();
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
          $empleado->id=$request['id'];
          $empleado->dni=$request['dni'];
          $empleado->nombres=$request['nombres'];
          $empleado->apaterno=$request['apaterno'];  
          $empleado->amaterno=$request['amaterno'];  
          $empleado->correo=$request['correo'];  
          $empleado->imagen=""; 
          $empleado->telefono=$request['telefono'];  
          $empleado->estado_id=$request['estado_id'];  
        }else{
          $empleado->id=$_POST['id']; 
          $empleado->dni=$_POST['dni'];        
          $empleado->nombres=$_POST['nombres'];  
          $empleado->apaterno=$_POST['apaterno']; 
          $empleado->amaterno=$_POST['amaterno']; 
          $empleado->correo=$_POST['correo'];  
          $empleado->imagen="";  
          $empleado->telefono=$_POST['telefono'];
          $empleado->estado_id=$_POST['estado_id']; 
        }     
        $data=$empleado->editarColaborador();     
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
    $data=$empleado->formEditar();
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
        $data=$empleado->buscarColaboradores($columna,$valor,$status);        
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