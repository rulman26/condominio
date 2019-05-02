<?php
require_once 'propietario.php'; 
$propietario=new propietario();

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
          $propietario->dni=$request['dni'];
          $propietario->nombres=$request['nombres'];
          $propietario->apaterno=$request['apaterno'];  
          $propietario->amaterno=$request['amaterno'];  
          $propietario->correo=$request['correo'];            
          $propietario->telefono=$request['telefono'];  
          $propietario->estado_id=1;  
        }else{
          $propietario->dni=$_POST['dni'];        
          $propietario->nombres=$_POST['nombres'];  
          $propietario->apaterno=$_POST['apaterno']; 
          $propietario->amaterno=$_POST['amaterno']; 
          $propietario->correo=$_POST['correo'];              
          $propietario->telefono=$_POST['telefono'];
          $propietario->estado_id=1;
        }     
        $data=$propietario->crearPropietario();
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
          $propietario->id=$request['id'];
          $propietario->dni=$request['dni'];
          $propietario->nombres=$request['nombres'];
          $propietario->apaterno=$request['apaterno'];  
          $propietario->amaterno=$request['amaterno'];  
          $propietario->correo=$request['correo'];  
          $propietario->imagen=""; 
          $propietario->telefono=$request['telefono'];  
          $propietario->estado_id=$request['estado_id'];  
        }else{
          $propietario->id=$_POST['id']; 
          $propietario->dni=$_POST['dni'];        
          $propietario->nombres=$_POST['nombres'];  
          $propietario->apaterno=$_POST['apaterno']; 
          $propietario->amaterno=$_POST['amaterno']; 
          $propietario->correo=$_POST['correo'];  
          $propietario->imagen="";  
          $propietario->telefono=$_POST['telefono'];
          $propietario->estado_id=$_POST['estado_id']; 
        }     
        $data=$propietario->editarColaborador();     
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
    $data=$propietario->formEditar();
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
        $data=$propietario->buscarColaboradores($columna,$valor,$status);        
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