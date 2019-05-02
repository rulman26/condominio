<?php
require_once 'movimiento.php'; 
$movimiento=new movimiento();
$usuarioId=explode(",",base64_decode(apache_request_headers()['Authorization']))[0]; 
function tokenValido(){
  $header=apache_request_headers();
  $token=base64_decode($header['Authorization']);
  $datos=explode(",",$token);
  $valido=BaseDatos::Valido($datos[0],$datos[1]);  
  return $valido;
}
switch ($_GET['solicitud']){
  /*INGRESOS*/
  case 'crear':
    if(tokenValido()){  
      if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);                                    
          $movimiento->monto=$request['monto'];
          $movimiento->descripcion=$request['descripcion'];          
          $movimiento->operacion=$request['operacion'];
          $movimiento->caja_id=$request['caja_id'];
          $movimiento->tipo_id=$request['tipo_id'];
          $movimiento->recibo_id=$request['recibo_id'];          
          $movimiento->estado_id=1;
        }else{                     
          $movimiento->monto=$_POST['monto'];
          $movimiento->descripcion=$_POST['descripcion'];          
          $movimiento->operacion=$_POST['operacion'];
          $movimiento->caja_id=$_POST['caja_id'];
          $movimiento->tipo_id=$_POST['tipo_id'];
          $movimiento->recibo_id=$_POST['recibo_id'];
          $movimiento->estado_id=1;                    
        }     
        $data=$movimiento->crearMovimiento();     
      }else{      
        $data['status']=false;
        $data['mensaje']="NO EXISTEN DATOS POST";                    
      }
    }else{
      $data['status']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";  
    }               
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'editar':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $movimiento->id=$request['id'];
        $movimiento->monto=$request['monto'];
        $movimiento->descripcion=$request['descripcion'];        
        $movimiento->operacion=$request['operacion'];
        $movimiento->tipo_id=$request['tipo_id'];
        $movimiento->estado_id=$request['estado_id'];
      }else{
        $movimiento->id=$_POST['id'];
        $movimiento->monto=$_POST['monto'];
        $movimiento->descripcion=$_POST['descripcion'];        
        $movimiento->operacion=$_POST['operacion'];
        $movimiento->tipo_id=$_POST['tipo_id'];
        $movimiento->estado_id=$_POST['estado_id'];
      }       
      $data=$movimiento->EditarMovimiento();     
    }else{      
      $data['status']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'formeditar':    
    $data=$movimiento->formEditar();
    echo json_encode($data,JSON_PRETTY_PRINT);  
  break;

  case 'buscar':
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                        
        $inicio=$request['inicio'];
        $final=$request['final'];
        $caja_id=$request['caja_id'];
        $tipo=$request['tipo'];
        $status=$request['status'];
      }else{        
        $inicio=$_POST['inicio'];
        $final=$_POST['final'];
        $caja_id=$_POST['caja_id'];
        $tipo=$_POST['tipo'];        
        $status=$_POST['status'];            
      }                 
      $data=$movimiento->buscarMovimientos($inicio,$final,$caja_id,$tipo,$status);
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

 