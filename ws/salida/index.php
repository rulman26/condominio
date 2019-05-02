<?php
require_once 'salida.php'; 
$salida=new salida();
$usuarioId=explode(",",base64_decode(apache_request_headers()['Authorization']))[0]; 
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
          $salida->ingreso_id=$request['ingreso_id'];        
          $salida->precioventa=$request['precioventa'];
          $salida->cantidad=$request['cantidad'];
          $salida->fecha=$request['fecha'];          
          $salida->usuario_id=$usuarioId;
          $salida->estado_id=1;
        }else{
          $salida->ingreso_id=$_POST['ingreso_id'];        
          $salida->precioventa=$_POST['precioventa'];
          $salida->cantidad=$_POST['cantidad'];
          $salida->fecha=$_POST['fecha'];          
          $salida->usuario_id=$usuarioId;
          $salida->estado_id=1;
        }     
        $data=$salida->crearSalida();     
      }else{      
        $data['status']="ERROR";
        $data['mensaje']="NO EXISTEN DATOS POST";                    
      }
    }else{
      $data['status']=false;
      $data['mensaje']="token Seguridad Error";
    }               
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'editar':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $salida->id=$request['id'];
        $salida->ingreso_id=$request['ingreso_id'];        
        $salida->precioventa=$request['precioventa'];
        $salida->cantidad=$request['cantidad'];
        $salida->fecha=$request['fecha'];        
        $salida->usuario_id=$usuarioId;
        $salida->estado_id=$request['estado_id'];
      }else{
        $salida->id=$_POST['id'];
        $salida->ingreso_id=$_POST['ingreso_id'];        
        $salida->precioventa=$_POST['precioventa'];
        $salida->cantidad=$_POST['cantidad'];
        $salida->fecha=$_POST['fecha'];        
        $salida->usuario_id=$usuarioId;
        $salida->estado_id=$_POST['estado_id'];
      }     
      $data=$salida->editarSalida();     
    }else{      
      $data['status']=false;
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
      $data['status']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;
  

  case 'buscar':
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $fecha=$request['fecha'];
        $inicio=$request['inicio'];
        $final=$request['final'];
        $item_id=$request['item_id'];
        $status=$request['status'];
      }else{
        $fecha=$_POST['fecha'];
        $inicio=$_POST['inicio'];
        $final=$_POST['final'];
        $item_id=$_POST['item_id'];        
        $status=$_POST['status'];            
      }                 
      $data=$salida->buscarSalidas($fecha,$inicio,$final,$item_id,$status);
    }else{      
      $data['status']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }
    echo json_encode($data,JSON_PRETTY_PRINT);
  break; 
  
  case 'resumen':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $fecha->id=$request['fecha'];
      }else{
        $fecha=$_POST['fecha'];
      }     
      $data=$salida->ResumenVenta($fecha);     
    }else{      
      $data['status']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
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

 