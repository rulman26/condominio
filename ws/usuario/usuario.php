<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class usuario 
{
  var $id;  	
  var $perfil;
	var $usuario;
	var $password;
  var $nombres;
  var $token;    
  var $estado;

  function loginUsuario(){
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT *FROM USUARIO WHERE USUARIO=? AND PASSWORD=?";
    $q = $pdo->prepare($sql);    
    $q->execute(array($this->usuario,$this->password));
    $data = $q->fetch(PDO::FETCH_ASSOC);       
    
    if(empty($data)){      
      $mensaje['estado']=false;
      $mensaje['mensaje']='USUARIO O CLAVE INCORRECTA'; 
    }else{
      $mensaje['estado']=true;
      $mensaje['data']=$data; 
      try {
      $fecha = date_create();
      $texto="Random".date_timestamp_get($fecha); 
      $this->token=md5($texto);  
      $pdo->beginTransaction();
      $sql = "UPDATE USUARIO SET TOKEN=? WHERE USUARIO=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->token,$this->usuario));                         
      $pdo->commit();  
      }catch(PDOException $e) { 
        $mensaje['estado']=false;
        $mensaje['mensaje']=$e->getMessage();
        $pdo->rollBack();
      }       
    }
    baseDatos::desconectar();
    return $mensaje; 
  }

  function crearUsuario(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO USUARIO VALUES(default,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->perfil,$this->usuario,$this->password,$this->nombres,'token','ACTIVO'));                   
      $mensaje['estado']=true;
      $mensaje['mensaje']='USUARIO REGISTRADO CON EXITO'; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }
  function editarUsuario(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE USUARIO SET PERFIL=?,USUARIO=?,NOMBRES=?,ESTADO=? WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->perfil,$this->usuario,$this->nombres,$this->estado,$this->id));                   
      $mensaje['estado']=true;
      $mensaje['mensaje']='USUARIO EDITADO CORRECTAMENTE'; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function cambiarClave(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE USUARIO SET PASSWORD=? WHERE USUARIO=? AND ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->password,$this->usuario,$this->id));                   
      $mensaje['estado']=true;
      $mensaje['mensaje']='SE CAMBIO LA CLAVE CORRECTAMENTE'; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function resetearClave(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE USUARIO SET PASSWORD=? WHERE USUARIO=? AND ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->usuario,$this->usuario,$this->id));                   
      $mensaje['estado']=true;
      $mensaje['mensaje']='SE RESETEO LA CLAVE CORRECTAMENTE'; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }
  
  function listaUsuarios($filtro,$columna,$valor){
    $filtrosAceptados=['activos','inactivos','todos'];
    if (in_array($filtro, $filtrosAceptados)) {
      if ($filtro=="activos") {        
        $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%' AND ESTADO='ACTIVO'";
      }elseif($filtro=="inactivos"){        
        $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%' AND ESTADO='INACTIVO'";
      }else{
        $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%' ";
      }
      $pdo = baseDatos::conectar();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT ID,PERFIL,USUARIO,NOMBRES,ESTADO FROM USUARIO ".$cadena;
      $q = $pdo->prepare($sql);
      $q->execute();
      $data = $q->fetchAll(PDO::FETCH_ASSOC);      
      $mensaje['estado']=true;
      $mensaje['data']=$data ;  
    }else{
      $mensaje['estado']=false;
      $mensaje['mensaje']="SOLO ACEPTA (activos,inactivos y todos)";
    }
    baseDatos::desconectar();
    return $mensaje; 
  } 
}
?>