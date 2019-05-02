<?php
require_once 'cuota.php'; 
$cuota=new cuota();
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
          $cuota->periodo=$request['periodo'];
          $cuota->total=$request['total'];
          $cuota->cantidad=$request['cantidad'];
          $cuota->cuota=$request['cuota'];
          $cuota->descripcion="MANTENIMIENTO ".$request['periodo'];         
          $cuota->bloque_id=$request['bloque_id'];
          $cuota->estado_id=1;  
        }else{        
          $cuota->periodo=$_POST['periodo'];
          $cuota->total=$_POST['total'];
          $cuota->cantidad=$_POST['cantidad'];
          $cuota->cuota=$_POST['cuota'];
          $cuota->descripcion="MANTENIMIENTO ".$_POST['periodo']; ;
          $cuota->bloque_id=$_POST['bloque_id'];
          $cuota->estado_id=1;  
        }     
        $data=$cuota->crearCuota();     
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
          $cuota->id=$request['id'];        
          $cuota->nombre=$request['nombre']; 
          $cuota->estado_id=$request['estado_id'];  
        }else{
          $cuota->id=$_POST['id'];
          $cuota->nombre=$_POST['nombre'];
          $cuota->estado_id=$_POST['estado_id'];  
        }     
        $data=$cuota->editarLaboratorio();     
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
          $cuota->id=$request['id'];
        }else{
          $cuota->id=$_POST['id'];
        }     
        $data=$cuota->eliminarCuota();     
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
    $data=$cuota->formEditar();
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
        $data=$cuota->buscarCuotas($bloque_id,$periodo,$status);           
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

  case 'recibos':
    if (empty($_POST)) {
      $request  = json_decode(trim(file_get_contents('php://input')), true);                
      $cuota->id=$request['id'];   
    }else{
      $cuota->id=$_POST['id'];
    }               
    $data=$cuota->cuotaRecibos();
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  default:
    $msj['status']=false;
    $msj['mensaje']='API NO CONTIENE DATA';
    echo json_encode($msj,JSON_PRETTY_PRINT);
  break;
}
?>

 