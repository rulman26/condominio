<?php
require_once 'recibo.php'; 
$recibo=new recibo();

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
          $recibo->fecha=$request['fecha'];
          $recibo->descripcion=$request['descripcion'];  
          $recibo->monto=$request['monto'];  
          $recibo->cuota_id=0;  
          $recibo->departamento_id=$request['departamento_id'];                      
          $recibo->estado_id=1;  
        }else{
          $recibo->bloque_id=$_POST['bloque_id'];        
          $recibo->fecha=$_POST['fecha'];  
          $recibo->descripcion=$_POST['descripcion']; 
          $recibo->monto=$_POST['monto']; 
          $recibo->cuota_id=0;              
          $recibo->departamento_id=$_POST['departamento_id'];                        
          $recibo->estado_id=1;
        }     
        $data=$recibo->crearRecibo();
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
          $recibo->id=$request['id'];
          $recibo->descripcion=$request['descripcion'];
          $recibo->monto=$request['monto'];          
          $recibo->estado_id=$request['estado_id'];            
        }else{
          $recibo->id=$_POST['id']; 
          $recibo->fecha=$_POST['fecha']; 
          $recibo->descripcion=$_POST['descripcion'];        
          $recibo->monto=$_POST['monto'];            
          $recibo->estado_id=$_POST['estado_id'];            
        }     
        $data=$recibo->editarRecibo();     
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
    $data=$recibo->formEditar();
    echo json_encode($data,JSON_PRETTY_PRINT);  
  break;

  case 'buscar':
    if(tokenValido()){
      if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);
          $inicio=$request['inicio'];
          $final=$request['final'];
          $numero=$request['numero'];
          $status=$request['status'];
        }else{
          $inicio=$_POST['inicio'];
          $final=$_POST['final'];
          $numero=$_POST['numero'];
          $status=$_POST['status'];
        }     
        $data=$recibo->buscarRecibos($inicio,$final,$numero,$status);        
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