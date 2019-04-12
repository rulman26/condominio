<?php
require_once 'proveedor.php'; 
$proveedor=new proveedor();
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
          $proveedor->ruc=$request['ruc'];
          $proveedor->nombre=$request['nombre'];
          $proveedor->direccion=$request['direccion'];  
          $proveedor->telefono=$request['telefono'];  
          $proveedor->email=$request['email'];  
          $proveedor->estado_id=1;  
        }else{
          $proveedor->ruc=$_POST['ruc'];
          $proveedor->nombre=$_POST['nombre'];
          $proveedor->direccion=$_POST['direccion'];  
          $proveedor->telefono=$_POST['telefono']; 
          $proveedor->email=$_POST['email']; 
          $proveedor->estado_id=1;  
        }     
        $data=$proveedor->crearProveedor();     
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
          $proveedor->id=$request['id'];
          $proveedor->ruc=$request['ruc'];
          $proveedor->nombre=$request['nombre'];
          $proveedor->direccion=$request['direccion'];  
          $proveedor->telefono=$request['telefono'];  
          $proveedor->email=$request['email'];  
          $proveedor->estado_id=$request['estado_id'];  
        }else{
          $proveedor->id=$_POST['id'];
          $proveedor->ruc=$_POST['ruc'];
          $proveedor->nombre=$_POST['nombre'];
          $proveedor->direccion=$_POST['direccion'];  
          $proveedor->telefono=$_POST['telefono'];
          $proveedor->email=$_POST['email'];
          $proveedor->estado_id=$_POST['estado_id'];  
        }     
        $data=$proveedor->editarProveedor();     
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
        $proveedor->id=$request['id'];
      }else{
        $proveedor->id=$_POST['id'];
      }     
      $data=$proveedor->eliminarProveedor();     
    }else{      
      $data['status']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;
  
  case 'formeditar':    
    $data=$proveedor->formEditar();
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
        $data=$proveedor->buscarProveedores($columna,$valor,$status);        
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

 