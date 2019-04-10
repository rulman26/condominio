<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class usuario 
{
  var $id;
  var $usuario;
  var $password;
  var $token;
  var $tipo_id;
  var $empleado_id;
  var $estado_id;  

  function loginUsuario(){
    $pdo = BaseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT USUARIO,PASSWORD FROM USUARIO WHERE USUARIO=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->usuario));
    $data= $q->fetch(PDO::FETCH_ASSOC); 
    if(password_verify($this->password,$data['PASSWORD'])){
      $sql = "SELECT a.ID,a.USUARIO,b.EMPLEADO_DNI,TIPO_ID,a.EMPLEADO_ID,
        CONCAT(b.EMPLEADO_NOMBRES,' ',b.EMPLEADO_APATERNO,' ',b.EMPLEADO_AMATERNO) COLABORADOR,
        LOWER(c.USUARIO_TIPO_NOMBRE) REDIRECT
        FROM USUARIO a
        JOIN EMPLEADO b ON b.EMPLEADO_ID=a.EMPLEADO_ID
        JOIN USUARIO_TIPO c ON c.USUARIO_TIPO_ID=a.TIPO_ID
        WHERE a.USUARIO=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->usuario));
      $data= $q->fetch(PDO::FETCH_ASSOC);   
      if(!empty($data)){
        $token="rulman";
        $sql = "UPDATE USUARIO SET TOKEN=? WHERE ID=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($token,$data['ID']));
        $response["status"]=true;
        $response["data"]=$data;
        $secret=$data['ID'].",".$token.",".$data['EMPLEADO_ID'];
        $response["token"]=base64_encode($secret);      
        $response["redirect"]=$data['REDIRECT'];  
      }else{
        $response["status"]=false;
        $response["mensaje"]="Datos No encontrado";
      }
    }else{
      $response["status"]=false;
      $response["mensaje"]="Usuario o Contraseña Incorrecta";
    }
    BaseDatos::desconectar();
    return $response; 
  }

  function formCrear(){
    $pdo = BaseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT USUARIO_TIPO_ID ID,USUARIO_TIPO_NOMBRE NOMBRE FROM USUARIO_TIPO";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['tipos'] = $q->fetchAll(PDO::FETCH_ASSOC); 
    //Colaboradores Sin Usuario      
    $sql = "SELECT a.EMPLEADO_ID ID,CONCAT(a.EMPLEADO_NOMBRES,' ',a.EMPLEADO_APATERNO,' ',a.EMPLEADO_AMATERNO) NOMBRE 
      FROM EMPLEADO a
      LEFT JOIN USUARIO b on b.EMPLEADO_ID=a.EMPLEADO_ID 
      WHERE b.ID is null";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['empleados'] = $q->fetchAll(PDO::FETCH_ASSOC);   
    $data['status']=true;
    BaseDatos::desconectar();
    return $data; 
  }

  function crearUsuario(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "SELECT USUARIO FROM USUARIO WHERE USUARIO=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->usuario));
      $data= $q->fetch(PDO::FETCH_ASSOC);
      if(empty($data)){
        $password = password_hash($this->password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO USUARIO VALUES(default,?,?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->usuario,$password,$this->token,$this->tipo_id,$this->empleado_id,$this->estado_id));                  
        $mensaje['status']=true;
        $mensaje['mensaje']='USUARIO REGISTRADO CORRECTAMENTE'; 
        $pdo->commit();  
      }else{
        $mensaje['status']=false;
        $mensaje['mensaje']='EL USUARIO YA EXISTE'; 
      }
      
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function formEditar(){
    $pdo = BaseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT USUARIO_TIPO_ID ID,USUARIO_TIPO_NOMBRE NOMBRE FROM USUARIO_TIPO";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['tipos'] = $q->fetchAll(PDO::FETCH_ASSOC); 
    //Colaboradores Sin Usuario      
    $sql = "SELECT a.EMPLEADO_ID ID,CONCAT(a.EMPLEADO_NOMBRES,' ',a.EMPLEADO_APATERNO,' ',a.EMPLEADO_AMATERNO) NOMBRE 
      FROM EMPLEADO a
      LEFT JOIN USUARIO b on b.EMPLEADO_ID=a.EMPLEADO_ID 
      WHERE b.ID is null";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['empleados'] = $q->fetchAll(PDO::FETCH_ASSOC);   
  
    $data['status']=true;
    BaseDatos::desconectar();
    return $data; 
  }

  function BuscarUsuarios($columna,$valor){
    if(empty($cadena)){
      $cadena="";
    }else{
      $cadena="WHERE ".$columna." like '%".strtoupper($valor)."%' ";
    }    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,a.TIPO_ID,c.USUARIO_TIPO_NOMBRE PERFIL,a.USUARIO,a.EMPLEADO_ID,
      CONCAT(b.EMPLEADO_NOMBRES,' ',b.EMPLEADO_APATERNO,' ',b.EMPLEADO_AMATERNO) COLABORADOR,a.ESTADO
      FROM USUARIO a
      JOIN EMPLEADO b on b.EMPLEADO_ID=a.EMPLEADO_ID
      JOIN USUARIO_TIPO c on c.USUARIO_TIPO_ID=a.TIPO_ID ".$cadena;
    $q = $pdo->prepare($sql);
    $q->execute();
    $data = $q->fetchAll(PDO::FETCH_ASSOC);      
    $mensaje['estado']=true;
    $mensaje['data']=$data ;  
    baseDatos::desconectar();
    return $mensaje; 
  } 

  function editarUsuario(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "SELECT USUARIO FROM USUARIO WHERE USUARIO=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->usuario));
      $data= $q->fetch(PDO::FETCH_ASSOC);
      if(empty($data)){
        $sql = "UPDATE USUARIO SET TIPO_ID=?,USUARIO=?,EMPLEADO_ID=?,ESTADO=? WHERE ID=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->tipo_id,$this->usuario,$this->empleado_id,$this->estado,$this->id));                   
        $mensaje['estado']=true;
        $mensaje['mensaje']='USUARIO EDITADO CORRECTAMENTE'; 
        $pdo->commit();  
      }
      else{
        $mensaje['estado']=false;
        $mensaje['mensaje']="EL USUARIO YA EXISTE";
      }  
      
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
      $sql = "UPDATE USUARIO SET PASSWORD=? WHERE ID=?";
      $q = $pdo->prepare($sql);
      $password = password_hash($this->usuario, PASSWORD_DEFAULT);
      $q->execute(array($password,$this->id));                   
      $mensaje['estado']=true;
      $mensaje['mensaje']='CONTRASEÑA ACTUALIZADA CORRECTAMENTE'; 
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
      $sql = "UPDATE USUARIO SET PASSWORD=? WHERE ID=?";
      $q = $pdo->prepare($sql);
      $password = password_hash($this->password, PASSWORD_DEFAULT);
      $q->execute(array($password,$this->id));                   
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