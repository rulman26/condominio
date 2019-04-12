<?php
require_once 'usuario.php'; 
$usuario=new usuario();
function tokenValido(){
  $header=apache_request_headers();
  $token=base64_decode($header['Authorization']);
  $datos=explode(",",$token);
  $valido=BaseDatos::Valido($datos[0],$datos[1]);  
  return $valido;
}
switch ($_GET['solicitud']){
	case 'login':	
	if ($_SERVER['REQUEST_METHOD']=="POST") {
		if (empty($_POST)) {
			$request  = json_decode(trim(file_get_contents('php://input')), true);			          
			$usuario->usuario=ltrim($request['usuario']);
			$usuario->password=base64_decode($request['password']);
		}else{
			$usuario->usuario=ltrim($_POST['usuario']);
			$usuario->password=base64_decode($_POST['password']);
		}			
		$data=$usuario->loginUsuario();			
	}else{			
		$data['status']="ERROR";
		$data['mensaje']="NO EXISTEN DATOS POST";    			           
	}
    echo json_encode($data,JSON_PRETTY_PRINT);
	break;
	
	case 'formcrear':    
    $data=$usuario->formCrear();
    echo json_encode($data,JSON_PRETTY_PRINT);  
  break;

	case 'crear': 
		if(tokenValido()){ 		
			if ($_SERVER['REQUEST_METHOD']=="POST") {
				if (empty($_POST)) {
					$request  = json_decode(trim(file_get_contents('php://input')), true);			          
					$usuario->usuario=$request['usuario'];
					$usuario->password=$request['usuario'];
					$usuario->token="";
					$usuario->tipo_id=$request['tipo_id'];	
					$usuario->empleado_id=$request['empleado_id'];
					$usuario->estado_id=1;
				}else{
					$usuario->usuario=$_POST['usuario'];
					$usuario->password=$_POST['usuario'];
					$usuario->token="";
					$usuario->tipo_id=$_POST['tipo_id'];
					$usuario->empleado_id=$_POST['empleado_id'];
					$usuario->estado_id=1;      				 	
				}			
				$data=$usuario->crearUsuario();			
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
    $data=$usuario->formEditar();
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
			$data=$usuario->BuscarUsuarios($columna,$valor,$status);  			
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
					$usuario->id=$request['id'];				
					$usuario->usuario=$request['usuario'];				
					$usuario->tipo_id=$request['tipo_id'];				
					$usuario->empleado_id=$request['empleado_id'];	
					$usuario->estado_id=$request['estado_id'];	
				}else{
					$usuario->id=$_POST['id']; 
					$usuario->usuario=$_POST['usuario'];            
					$usuario->tipo_id=$_POST['tipo_id'];
					$usuario->empleado_id=$_POST['empleado_id'];
					$usuario->estado_id=$_POST['estado_id'];  		
				}			
				$data=$usuario->editarUsuario();			
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

	case 'cambiar': 	
	 if ($_SERVER['REQUEST_METHOD']=="POST") {
		 if (empty($_POST)) {
			 $request  = json_decode(trim(file_get_contents('php://input')), true);			          
			 $usuario->id=$request['id'];				
			 $usuario->password=$request['password'];
		 }else{
			 $usuario->id=$_POST['id'];
			 $usuario->password=$_POST['password'];
		 }			
		 $data=$usuario->cambiarClave();			
	 }else{
		 $data['status']=false;
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

 