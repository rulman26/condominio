<?php
header('Access-Control-Allow-Origin: *');  
require_once 'item.php'; 
$item=new item();
$header=getallheaders();
switch ($_GET['solicitud']){

  case 'crear':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $item->codigo="";
        $item->nombre=$request['nombre'];
        $item->preciocompra=$request['preciocompra'];
        $item->precioventa=$request['precioventa'];        
        $item->unidades=1;  
        $item->laboratorio_id=$request['laboratorio_id'];  
        $item->proveedor_id=$request['proveedor_id'];  
        $item->item_tipo_id=$request['item_tipo_id'];  
        $item->item_categoria_id=$request['item_categoria_id'];  
        $item->estado=$request['estado'];  
      }else{
        $item->codigo=""; 
        $item->nombre=$_POST['nombre'];        
        $item->preciocompra=$_POST['preciocompra'];  
        $item->precioventa=$_POST['precioventa']; 
        $item->unidades=1; 
        $item->laboratorio_id=$_POST['laboratorio_id']; 
        $item->proveedor_id=$_POST['proveedor_id']; 
        $item->item_tipo_id=$_POST['item_tipo_id'];  
        $item->item_categoria_id=$_POST['item_categoria_id'];  
        $item->estado=$_POST['estado'];
      }     
      $data=$item->crearItem();     
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
        $item->id=$request['id'];        
        $item->codigo="";
        $item->nombre=$request['nombre'];
        $item->preciocompra=$request['preciocompra'];
        $item->precioventa=$request['precioventa'];        
        $item->unidades=1;  
        $item->laboratorio_id=$request['laboratorio_id']; 
        $item->proveedor_id=$request['proveedor_id'];  
        $item->item_tipo_id=$request['item_tipo_id'];  
        $item->item_categoria_id=$request['item_categoria_id'];  
        $item->estado=$request['estado']; 
      }else{
        $item->id=$_POST['id'];
        $item->codigo="";         
        $item->nombre=$_POST['nombre'];        
        $item->preciocompra=$_POST['preciocompra'];  
        $item->precioventa=$_POST['precioventa']; 
        $item->unidades=1; 
        $item->laboratorio_id=$_POST['laboratorio_id']; 
        $item->proveedor_id=$_POST['proveedor_id']; 
        $item->item_tipo_id=$_POST['item_tipo_id'];  
        $item->item_categoria_id=$_POST['item_categoria_id'];  
        $item->estado=$_POST['estado'];
      }     
      $data=$item->editarItem();     
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
        $item->id=$request['id'];
      }else{
        $item->id=$_POST['id'];
      }     
      $data=$item->leerItem();     
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
        $item->id=$request['id'];
      }else{
        $item->id=$_POST['id'];
      }     
      $data=$item->eliminarItem();     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'filtrar':     
    $filtro=$_GET['id'];
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                        
        $columna=$request['filtro'];
        $valor=$request['input_filtro'];
      }else{
        $columna=$_POST['filtro'];
        $valor=$_POST['input_filtro'];
      }     
      $data=$item->listaItems($filtro,$columna,$valor);
    }else{      
      $data['estado']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }    		
	  echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  /*TABLAS DE GENERICAS*/
  case 'tipocrear'://itemtipocrear   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $nombre=$request['nombre'];
        $estado=$request['estado'];
      }else{
        $nombre=$_POST['nombre'];
        $estado=$_POST['estado'];
      }     
      $data=$item->crearItemTipo($nombre,$estado);     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'tipoleer':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $id=$request['id'];
      }else{
        $id=$_POST['id'];
      }     
      $data=$item->leerItemTipo($id);     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'tipoeditar'://itemtipocrear   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $id=$request['id'];
        $nombre=$request['nombre'];
        $estado=$request['estado'];
      }else{
        $id=$_POST['id'];
        $nombre=$_POST['nombre'];
        $estado=$_POST['estado'];
      }     
      $data=$item->editarItemTipo($nombre,$estado,$id);     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'tipoeliminar':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $id=$request['id'];
      }else{
        $id=$_POST['id'];
      }     
      $data=$item->eliminarItemTipo($id);     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'tipofiltrar':     
    $filtro=$_GET['id'];      
    $data=$item->listaItemTipos($filtro);
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;
  /*TABLAS DE GENERICAS*/
  case 'categoriacrear'://itemcategoriacrear   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $nombre=$request['nombre'];
        $estado=$request['estado'];
      }else{
        $nombre=$_POST['nombre'];
        $estado=$_POST['estado'];
      }     
      $data=$item->crearItemCategoria($nombre,$estado);     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'categorialeer':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $id=$request['id'];
      }else{
        $id=$_POST['id'];
      }     
      $data=$item->leerItemCategoria($id);     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'categoriaeditar'://itemtipocrear   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $id=$request['id'];
        $nombre=$request['nombre'];
        $estado=$request['estado'];
      }else{
        $id=$_POST['id'];
        $nombre=$_POST['nombre'];
        $estado=$_POST['estado'];
      }     
      $data=$item->editarItemCategoria($nombre,$estado,$id);     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'categoriaeliminar':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $id=$request['id'];
      }else{
        $id=$_POST['id'];
      }     
      $data=$item->eliminarItemCategoria($id);     
    }else{      
      $data['estado']="ERROR";
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'categoriafiltrar':     
    $filtro=$_GET['id'];      
    $data=$item->listaItemCategorias($filtro);
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;
  /*FORMULARIOS*/
  case 'formitemregistrar':              
    $data=$item->formItemRegistrar();
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'forminventario':              
    $data=$item->formItemInventario();
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;
  
  default:
    $msj['estado']='ERROR';
    $msj['mensaje']='API NO CONTIENE DATA';
    echo json_encode($msj,JSON_PRETTY_PRINT);
  break;
}
?>

 