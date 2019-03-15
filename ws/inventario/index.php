<?php
header('Access-Control-Allow-Origin: *');  
require_once 'inventario.php'; 
$inventario=new inventario();
$header=getallheaders();
switch ($_GET['solicitud']){

  case 'crear':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $inventario->item_id=$request['item_id'];
        $inventario->codigobarra=$request['codigobarra'];
        $inventario->ubicacion=$request['ubicacion'];
        $inventario->cantidad=$request['cantidad'];        
        $inventario->fechaingreso=$request['fechaingreso'];  
        $inventario->fechafabricacion=$request['fechafabricacion'];  
        $inventario->fechavencimiento=$request['fechavencimiento'];          
        $inventario->estado=$request['estado'];  
      }else{
        $inventario->item_id=$_POST['item_id'];  
        $inventario->codigobarra=$_POST['codigobarra'];  
        $inventario->ubicacion=$_POST['ubicacion'];  
        $inventario->cantidad=$_POST['cantidad'];
        $inventario->fechaingreso=$_POST['fechaingreso']; 
        $inventario->fechafabricacion=$_POST['fechafabricacion']; 
        $inventario->fechavencimiento=$_POST['fechavencimiento'];  
        $inventario->estado=$_POST['estado'];  
      }     
      $data=$inventario->crearInventario();     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'editar':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $inventario->id=$request['id'];
        $inventario->item_id=$request['item_id'];
        $inventario->codigobarra=$request['codigobarra'];
        $inventario->ubicacion=$request['ubicacion'];
        $inventario->cantidad=$request['cantidad'];
        $inventario->fechaingreso=$request['fechaingreso'];  
        $inventario->fechafabricacion=$request['fechafabricacion'];  
        $inventario->fechavencimiento=$request['fechavencimiento'];  
        $inventario->estado=$request['estado'];  
      }else{
        $inventario->id=$_POST['id'];
        $inventario->item_id=$_POST['item_id'];
        $inventario->codigobarra=$_POST['codigobarra'];
        $inventario->ubicacion=$_POST['ubicacion'];
        $inventario->cantidad=$_POST['cantidad'];  
        $inventario->fechaingreso=$_POST['fechaingreso']; 
        $inventario->fechafabricacion=$_POST['fechafabricacion']; 
        $inventario->fechavencimiento=$_POST['fechavencimiento'];  
        $inventario->estado=$_POST['estado'];  
      }       
      $data=$inventario->EditarInventario();     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'leer':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $inventario->id=$request['id'];
      }else{
        $inventario->id=$_POST['id'];
      }     
      $data=$inventario->leerInventario();     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'eliminar':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $inventario->id=$request['id'];
      }else{
        $inventario->id=$_POST['id'];
      }     
      $data=$inventario->eliminarInventario();     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'filtrar':
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $fecha=$request['fecha'];
        $inicio=$request['inicio'];
        $final=$request['final'];
        $item_id=$request['item_id'];
      }else{
        $fecha=$_POST['fecha'];
        $inicio=$_POST['inicio'];
        $final=$_POST['final'];
        $item_id=$_POST['item_id'];
      }           
      $filtro=$_GET['id'];      
      $data=$inventario->listaInventarios($filtro,$fecha,$inicio,$final,$item_id);
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }
	  echo json_encode($data,JSON_PRETTY_PRINT);
  break; 
  
  case 'salidas':
    if (empty($_POST)) {
      $request  = json_decode(trim(file_get_contents('php://input')), true);                
      $inventario->id=$request['id'];   
    }else{
      $inventario->id=$_POST['id'];
    }               
    $data=$inventario->inventarioSalida();
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'forminventariosalida':              
    $data=$inventario->formItemInventarioSalida();
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;  
  case 'stock': 
    if (empty($_POST)) {
      $request  = json_decode(trim(file_get_contents('php://input')), true);                
      $item_id=$request['item_id'];   
      $proveedor_id=$request['proveedor_id']; 
      $item_tipo_id=$request['item_tipo_id']; 
      $item_categoria_id=$request['item_categoria_id']; 
    }else{
      $item_id=$_POST['item_id'];
      $proveedor_id=$_POST['proveedor_id']; 
      $item_tipo_id=$_POST['item_tipo_id']; 
      $item_categoria_id=$_POST['item_categoria_id']; 
    }              
    $data=$inventario->inventarioStock($item_id,$proveedor_id,$item_tipo_id,$item_categoria_id);
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  default:
    $msj['estado']='ERROR';
    $msj['mensaje']='API NO CONTIENE DATA';
    echo json_encode($msj,JSON_PRETTY_PRINT);
  break;
}
?>

 