<?php
require_once 'item.php'; 
$item=new item();
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
    $data=$item->formEditar();
    echo json_encode($data,JSON_PRETTY_PRINT);  
  break;

  case 'crear':   
    if(tokenValido()){  
      if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);                
          $item->codigo="";
          $item->nombre=$request['nombre'];        
          $item->unidades=1;  
          $item->laboratorio_id=$request['laboratorio_id'];  
          $item->proveedor_id=$request['proveedor_id'];  
          $item->presentacion_id=$request['presentacion_id'];  
          $item->categoria_id=$request['categoria_id'];  
          $item->usuario_id=$usuarioId;  
          $item->estado_id=1;  
        }else{
          $item->codigo=""; 
          $item->nombre=$_POST['nombre'];                
          $item->unidades=1; 
          $item->laboratorio_id=$_POST['laboratorio_id']; 
          $item->proveedor_id=$_POST['proveedor_id']; 
          $item->presentacion_id=$_POST['presentacion_id'];  
          $item->categoria_id=$_POST['categoria_id'];  
          $item->usuario_id=$usuarioId;  
          $item->estado_id=1;
        }     
        $data=$item->crearItem();     
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
          $item->id=$request['id'];        
          $item->codigo="";
          $item->nombre=$request['nombre'];              
          $item->unidades=1;  
          $item->laboratorio_id=$request['laboratorio_id']; 
          $item->proveedor_id=$request['proveedor_id'];  
          $item->presentacion_id=$request['presentacion_id'];  
          $item->categoria_id=$request['categoria_id'];  
          $item->estado_id=$request['estado_id']; 
        }else{
          $item->id=$_POST['id'];
          $item->codigo="";         
          $item->nombre=$_POST['nombre'];                
          $item->unidades=1; 
          $item->laboratorio_id=$_POST['laboratorio_id']; 
          $item->proveedor_id=$_POST['proveedor_id']; 
          $item->presentacion_id=$_POST['presentacion_id'];  
          $item->categoria_id=$_POST['categoria_id'];  
          $item->estado_id=$_POST['estado_id'];
        }
        $data=$item->editarItem();           
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
          $item->id=$request['id'];
        }else{
          $item->id=$_POST['id'];
        }     
        $data=$item->eliminarItem();     
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
          $columna=$request['filtro'];
          $valor=$request['input_filtro']; 
          $status=$request['status'];             
        }else{
          $columna=$_POST['filtro'];
          $valor=$_POST['input_filtro'];
          $status=$_POST['status'];            
        }     
        $data=$item->buscarItems($columna,$valor,$status);        
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

  /*PRESENTACIONES*/
  case 'formeditarpresentacion':    
    $data=$item->formEditarPresentacion();
    echo json_encode($data,JSON_PRETTY_PRINT);  
  break;

  case 'presentacioncrear'://itemtipocrear  
    if(tokenValido()){ 
      if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);                
          $nombre=$request['nombre'];        
        }else{
          $nombre=$_POST['nombre'];        
        }     
        $data=$item->crearItemPresentacion($nombre);     
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

  case 'presentacioneditar':
    if(tokenValido()){  
      if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);                
          $id=$request['id'];
          $nombre=$request['nombre'];
          $estado_id=$request['estado_id'];
        }else{
          $id=$_POST['id'];
          $nombre=$_POST['nombre'];
          $estado_id=$_POST['estado_id'];
        }     
        $data=$item->editaItemPresentacion($nombre,$estado_id,$id);     
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

  case 'presentacionbuscar':
    if(tokenValido()){
      if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);          
          $valor=$request['input_filtro']; 
          $status=$request['status'];             
        }else{          
          $valor=$_POST['input_filtro'];
          $status=$_POST['status'];            
        }     
        $data=$item->buscarItemPresentaciones($valor,$status);        
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
  /*PRESENTACIONES*/
  /*CATEGORIAS*/
  case 'formeditarcategoria':    
    $data=$item->formEditarCategoria();
    echo json_encode($data,JSON_PRETTY_PRINT);  
  break;

  case 'categoriacrear'://itemcategoriacrear   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $nombre=$request['nombre'];        
      }else{
        $nombre=$_POST['nombre'];        
      }     
      $data=$item->crearItemCategoria($nombre);     
    }else{      
      $data['status']=false;
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
        $estado_id=$request['estado_id'];
      }else{
        $id=$_POST['id'];
        $nombre=$_POST['nombre'];
        $estado_id=$_POST['estado_id'];
      }     
      $data=$item->editarItemCategoria($nombre,$estado_id,$id);     
    }else{      
      $data['status']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'categoriabuscar':
    if(tokenValido()){
      if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (empty($_POST)) {
          $request  = json_decode(trim(file_get_contents('php://input')), true);          
          $valor=$request['input_filtro']; 
          $status=$request['status'];             
        }else{          
          $valor=$_POST['input_filtro'];
          $status=$_POST['status'];            
        }     
        $data=$item->buscarItemCategorias($valor,$status);        
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
  /*CATEGORIAS*/
  

  case 'formingreso':              
    $data=$item->formItemIngreso();
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  default:
    $msj['status']=false;
    $msj['mensaje']='API NO CONTIENE DATA';
    echo json_encode($msj,JSON_PRETTY_PRINT);
  break;
}
?>

 