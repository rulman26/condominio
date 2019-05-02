<?php
require_once 'gasto.php'; 
$gasto=new gasto();
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
          $gasto->periodo=$request['periodo'];
          $gasto->descripcion=$request['descripcion'];
          $gasto->monto=$request['monto'];  
          $gasto->servicio_id=$request['servicio_id'];  
          $gasto->bloque_id=$request['bloque_id'];  
          $gasto->estado_id=1;            
        }else{
          $gasto->periodo=$_POST['periodo'];
          $gasto->descripcion=$_POST['descripcion'];
          $gasto->monto=$_POST['monto'];  
          $gasto->servicio_id=$_POST['servicio_id'];    
          $gasto->bloque_id=$_POST['bloque_id'];           
          $gasto->estado_id=1;  
        }     
        $data=$gasto->crearGasto();     
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
          $gasto->id=$request['id'];
          $gasto->periodo=$request['periodo'];
          $gasto->descripcion=$request['descripcion'];
          $gasto->monto=$request['monto'];  
          $gasto->servicio_id=$request['servicio_id'];  
          $gasto->bloque_id=$request['bloque_id'];  
          $gasto->estado_id=$request['estado_id'];  
        }else{
          $gasto->id=$_POST['id'];
          $gasto->periodo=$_POST['periodo'];
          $gasto->descripcion=$_POST['descripcion'];
          $gasto->monto=$_POST['monto'];  
          $gasto->servicio_id=$_POST['servicio_id'];
          $gasto->bloque_id=$_POST['bloque_id'];
          $gasto->estado_id=$_POST['estado_id'];  
        }     
        $data=$gasto->editarGasto();     
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
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $gasto->id=$request['id'];
      }else{
        $gasto->id=$_POST['id'];
      }     
      $data=$gasto->eliminarProveedor();     
    }else{      
      $data['status']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;
  
  case 'formeditar':    
    $data=$gasto->formEditar();
    echo json_encode($data,JSON_PRETTY_PRINT);  
  break;

  case 'buscar':
    if(tokenValido()){
      if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);
          $bloque_id=$request['bloque_id'];
          $periodo=$request['periodo']; 
          $status=$request['status'];             
        }else{
          $bloque_id=$_POST['bloque_id'];
          $periodo=$_POST['periodo'];
          $status=$_POST['status'];            
        }     
        $data=$gasto->buscarGastos($bloque_id,$periodo,$status);        
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
  
  case 'programar':
    if(tokenValido()){
      if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);
          $bloque_id=$request['bloque_id'];
          $periodo=$request['periodo'];           
        }else{
          $bloque_id=$_POST['bloque_id'];
          $periodo=$_POST['periodo'];          
        }     
        $data=$gasto->programarGastos($bloque_id,$periodo);        
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

 