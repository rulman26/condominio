<?php
header('Access-Control-Allow-Origin: *');  
require_once 'empleado.php'; 
$empleado=new empleado();
$header=getallheaders();
switch ($_GET['solicitud']){

  case 'crear':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {      
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $empleado->dni=$request['dni'];
        $empleado->nombres=$request['nombres'];
        $empleado->apaterno=$request['apaterno'];  
        $empleado->amaterno=$request['amaterno'];  
        $empleado->correo=$request['correo'];  
        $empleado->imagen=""; 
        $empleado->telefono=$request['telefono'];  
        $empleado->estado=$request['estado'];  
      }else{
        $empleado->dni=$_POST['dni'];        
        $empleado->nombres=$_POST['nombres'];  
        $empleado->apaterno=$_POST['apaterno']; 
        $empleado->amaterno=$_POST['amaterno']; 
        $empleado->correo=$_POST['correo'];  
        $empleado->imagen="";  
        $empleado->telefono=$_POST['telefono'];
        $empleado->estado=$_POST['estado'];
      }     
      $data=$empleado->crearColaborador();
    }else{      
      $data['estado']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }             
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  case 'editar':   
    if ($_SERVER['REQUEST_METHOD']=="POST") {
      if (empty($_POST)) {
        $request  = json_decode(trim(file_get_contents('php://input')), true);                
        $empleado->id=$request['id'];
        $empleado->dni=$request['dni'];
        $empleado->nombres=$request['nombres'];
        $empleado->apaterno=$request['apaterno'];  
        $empleado->amaterno=$request['amaterno'];  
        $empleado->correo=$request['correo'];  
        $empleado->imagen=""; 
        $empleado->telefono=$request['telefono'];  
        $empleado->estado=$request['estado'];  
      }else{
        $empleado->id=$_POST['id']; 
        $empleado->dni=$_POST['dni'];        
        $empleado->nombres=$_POST['nombres'];  
        $empleado->apaterno=$_POST['apaterno']; 
        $empleado->amaterno=$_POST['amaterno']; 
        $empleado->correo=$_POST['correo'];  
        $empleado->imagen="";  
        $empleado->telefono=$_POST['telefono'];
        $empleado->estado=$_POST['estado']; 
      }     
      $data=$empleado->editarColaborador();     
    }else{      
      $data['estado']=false;
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
      $data=$empleado->listaColaboradores($filtro,$columna,$valor);
    }else{      
      $data['estado']=false;
      $data['mensaje']="NO EXISTEN DATOS POST";                    
    }    		
	  echo json_encode($data,JSON_PRETTY_PRINT);
  break;  

  default:
    $msj['estado']=false;
    $msj['mensaje']='API NO CONTIENE DATA';
    echo json_encode($msj,JSON_PRETTY_PRINT);
  break;
}
?>