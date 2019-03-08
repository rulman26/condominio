<?php
header('Access-Control-Allow-Origin: *');  
require_once 'usuario.php'; 
$usuario=new usuario();
$header=getallheaders();
switch ($_GET['solicitud']){

	case 'login': 	
		if ($_SERVER['REQUEST_METHOD']=="POST") {
			if (empty($_POST)) {
				$request  = json_decode(trim(file_get_contents('php://input')), true);			          
				$usuario->usuario=$request['usuario'];
				$usuario->password=$request['password'];				
			}else{
				$usuario->usuario=$_POST['usuario'];
				$usuario->password=$_POST['password'];				
			}			
			$data=$usuario->loginUsuario();			
		}else{			
      $data['estado']="ERROR";
			$data['mensaje']="NO EXISTEN DATOS POST";    			           
		}        	    
    echo json_encode($data,JSON_PRETTY_PRINT);
  	break;

	case 'crear': 	
		if ($_SERVER['REQUEST_METHOD']=="POST") {
			if (empty($_POST)) {
				$request  = json_decode(trim(file_get_contents('php://input')), true);			          
				$usuario->perfil=$request['perfil'];
				$usuario->usuario=$request['usuario'];
				$usuario->password=$request['usuario'];
				$usuario->nombres=$request['nombres'];	
			}else{
				$usuario->perfil=$_POST['perfil'];
				$usuario->usuario=$_POST['usuario'];
				$usuario->password=$_POST['usuario'];
				$usuario->nombres=$_POST['nombres'];	
			}			
			$data=$usuario->crearUsuario();			
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
				$usuario->id=$request['id'];
				$usuario->perfil=$request['perfil'];
				$usuario->usuario=$request['usuario'];				
				$usuario->nombres=$request['nombres'];	
				$usuario->estado=$request['estado'];	
			}else{
				$usuario->id=$_POST['id'];
				$usuario->perfil=$_POST['perfil'];
				$usuario->usuario=$_POST['usuario'];				
				$usuario->nombres=$_POST['nombres'];
				$usuario->estado=$_POST['estado'];		
			}			
			$data=$usuario->editarUsuario();			
		}else{			
      $data['estado']="ERROR";
			$data['mensaje']="NO EXISTEN DATOS POST";    			           
		}        	    
    echo json_encode($data,JSON_PRETTY_PRINT);
 	break;

  	case 'cambiarclave': 	
		if ($_SERVER['REQUEST_METHOD']=="POST") {
			if (empty($_POST)) {
				$request  = json_decode(trim(file_get_contents('php://input')), true);			          
				$usuario->id=$request['id'];
				$usuario->usuario=$request['usuario'];
				$usuario->password=$request['password'];					
			}else{
				$usuario->id=$_POST['id'];	
				$usuario->usuario=$_POST['usuario'];
				$usuario->password=$_POST['password'];				
			}			
			$data=$usuario->cambiarClave();			
		}else{			
      $data['estado']="ERROR";
			$data['mensaje']="NO EXISTEN DATOS POST";    			           
		}        	    
    echo json_encode($data,JSON_PRETTY_PRINT);
  break;

  	case 'resetear': 	
		if ($_SERVER['REQUEST_METHOD']=="POST") {
			if (empty($_POST)) {
				$request  = json_decode(trim(file_get_contents('php://input')), true);			          
				$usuario->id=$request['id'];				
				$usuario->usuario=$request['usuario'];
			}else{
				$usuario->id=$_POST['id'];
				$usuario->usuario=$_POST['usuario'];
			}			
			$data=$usuario->resetearClave();			
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
	      $data=$usuario->listaUsuarios($filtro,$columna,$valor);
	    }else{      
	      $data['estado']=false;
	      $data['mensaje']="NO EXISTEN DATOS POST";                    
	    }    		
		echo json_encode($data,JSON_PRETTY_PRINT);
  break;  

  default:
    $msj['estado']='ERROR';
    $msj['mensaje']='API NO CONTIENE DATA';
    echo json_encode($msj,JSON_PRETTY_PRINT);
  break;
}
?>

 