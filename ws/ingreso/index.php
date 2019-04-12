<?php
require_once 'ingreso.php'; 
$ingreso=new ingreso();
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
          $ingreso->codigobarra=$request['codigobarra'];
          $ingreso->preciocompra=$request['preciocompra'];
          $ingreso->precioventa=$request['precioventa'];
          $ingreso->lote=$request['lote'];
          $ingreso->cantidad=$request['cantidad'];
          $ingreso->factura=$request['factura'];
          $ingreso->fechaingreso=$request['fechaingreso'];
          $ingreso->fechavencimiento=$request['fechavencimiento'];          
          $ingreso->item_id=$request['item_id'];
          $ingreso->usuario_id=$usuarioId;
          $ingreso->estado_id=1;
        }else{                     
          $ingreso->codigobarra=$_POST['codigobarra'];
          $ingreso->preciocompra=$_POST['preciocompra'];
          $ingreso->precioventa=$_POST['precioventa'];
          $ingreso->lote=$_POST['lote'];
          $ingreso->cantidad=$_POST['cantidad'];
          $ingreso->factura=$_POST['factura'];
          $ingreso->fechaingreso=$_POST['fechaingreso'];
          $ingreso->fechavencimiento=$_POST['fechavencimiento'];          
          $ingreso->item_id=$_POST['item_id'];
          $ingreso->usuario_id=$usuarioId;
          $ingreso->estado_id=1;
        }     
        $data=$ingreso->crearIngreso();     
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
        $ingreso->id=$request['id'];
        $ingreso->codigobarra=$request['codigobarra'];
        $ingreso->preciocompra=$request['preciocompra'];
        $ingreso->precioventa=$request['precioventa'];
        $ingreso->lote=$request['lote'];
        $ingreso->cantidad=$request['cantidad'];
        $ingreso->factura=$request['factura'];
        $ingreso->fechaingreso=$request['fechaingreso'];
        $ingreso->fechavencimiento=$request['fechavencimiento'];          
        $ingreso->item_id=$request['item_id'];
        $ingreso->usuario_id=$usuarioId;
        $ingreso->estado_id=$request['estado_id'];
      }else{
        $ingreso->id=$_POST['id'];
        $ingreso->codigobarra=$_POST['codigobarra'];
        $ingreso->preciocompra=$_POST['preciocompra'];
        $ingreso->precioventa=$_POST['precioventa'];
        $ingreso->lote=$_POST['lote'];
        $ingreso->cantidad=$_POST['cantidad'];
        $ingreso->factura=$_POST['factura'];
        $ingreso->fechaingreso=$_POST['fechaingreso'];
        $ingreso->fechavencimiento=$_POST['fechavencimiento'];          
        $ingreso->item_id=$_POST['item_id'];
        $ingreso->usuario_id=$usuarioId;
        $ingreso->estado_id=$_POST['estado_id']; 
      }       
      $data=$ingreso->EditarIngreso();     
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
        $ingreso->id=$request['id'];
      }else{
        $ingreso->id=$_POST['id'];
      }     
      $data=$ingreso->eliminarIngreso();     
    }else{      
      $data['status']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'formeditar':    
    $data=$ingreso->formEditar();
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
      $data=$ingreso->buscarIngresos($fecha,$inicio,$final,$item_id,$status);
    }else{      
      $data['status']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }
	  echo json_encode($data,JSON_PRETTY_PRINT);
  break; 
  /*INGRESOS*/
  case 'salidas':
    if (empty($_POST)) {
      $request  = json_decode(trim(file_get_contents('php://input')), true);                
      $ingreso->id=$request['id'];   
    }else{
      $ingreso->id=$_POST['id'];
    }               
    $data=$ingreso->ingresoSalida();
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'forminventariosalida':              
    $data=$ingreso->formItemInventarioSalida();
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;  
  case 'stock': 
    if (empty($_POST)) {
      $request  = json_decode(trim(file_get_contents('php://input')), true);                
      $item_id=$request['item_id'];   
      $proveedor_id=$request['proveedor_id']; 
      $presentacion_id=$request['presentacion_id']; 
      $categoria_id=$request['categoria_id']; 
    }else{
      $item_id=$_POST['item_id'];
      $proveedor_id=$_POST['proveedor_id']; 
      $presentacion_id=$_POST['presentacion_id']; 
      $categoria_id=$_POST['categoria_id']; 
    }              
    $data=$ingreso->inventarioStock($item_id,$proveedor_id,$presentacion_id,$categoria_id);
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  default:
    $msj['status']=false;
    $msj['mensaje']='API NO CONTIENE DATA';
    echo json_encode($msj,JSON_PRETTY_PRINT);
  break;
}
?>

 