<?php
require_once 'caja.php'; 
$caja=new caja();
$usuarioId=explode(",",base64_decode(apache_request_headers()['Authorization']))[0]; 
function tokenValido(){
  $header=apache_request_headers();
  $token=base64_decode($header['Authorization']);
  $datos=explode(",",$token);
  $valido=BaseDatos::Valido($datos[0],$datos[1]);  
  return $valido;
}
switch ($_GET['solicitud']){
  case 'formeditar':    
    $data=$caja->formEditar();
    echo json_encode($data,JSON_PRETTY_PRINT);  
  break;

  case 'crear':   
    if(tokenValido()){  
      if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);                          
          $caja->nombre=$request['nombre'];  
          $caja->banco=$request['banco'];  
          $caja->cuenta=$request['cuenta'];  
          $caja->saldo=$request['saldo'];  
          $caja->bloque_id=$request['bloque_id'];
          $caja->estado_id=1;  
        }else{          
          $caja->nombre=$_POST['nombre']; 
          $caja->banco=$_POST['banco']; 
          $caja->cuenta=$_POST['cuenta'];  
          $caja->saldo=$_POST['saldo'];  
          $caja->bloque_id=$_POST['bloque_id'];  
          $caja->estado_id=1;
        }     
        $data=$caja->crearCaja();     
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
          $caja->id=$request['id'];                  
          $caja->nombre=$request['nombre'];              
          $caja->banco=$request['banco']; 
          $caja->cuenta=$request['cuenta'];  
          $caja->saldo=$request['saldo'];  
          $caja->bloque_id=$request['bloque_id'];  
          $caja->estado_id=$request['estado_id']; 
        }else{
          $caja->id=$_POST['id'];          
          $caja->nombre=$_POST['nombre'];                
          $caja->banco=$_POST['banco']; 
          $caja->cuenta=$_POST['cuenta']; 
          $caja->saldo=$_POST['saldo'];  
          $caja->bloque_id=$_POST['bloque_id'];  
          $caja->estado_id=$_POST['estado_id'];
        }
        $data=$caja->editarGasto();           
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
          $caja->id=$request['id'];
        }else{
          $caja->id=$_POST['id'];
        }     
        $data=$caja->eliminarItem();     
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
          $bloque_id=$request['bloque_id'];
          $nombre=$request['nombre']; 
          $status=$request['status'];             
        }else{
          $bloque_id=$_POST['bloque_id'];
          $nombre=$_POST['nombre'];
          $status=$_POST['status'];            
        }     
        $data=$caja->buscarCajas($bloque_id,$nombre,$status);        
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

 