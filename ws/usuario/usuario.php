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
    $sql = "SELECT USUARIO,PASSWORD FROM tausuario WHERE USUARIO=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->usuario));
    $data= $q->fetch(PDO::FETCH_ASSOC);
    if(password_verify($this->password,$data['PASSWORD'])){
      $sql = "SELECT a.ID,a.USUARIO,a.TIPO_ID,a.EMPLEADO_ID,
        CONCAT(b.NOMBRES,' ',b.APATERNO,' ',b.AMATERNO) COLABORADOR,
        LOWER(c.NOMBRE) REDIRECT
        FROM tausuario a
        JOIN taempleado b ON b.ID=a.EMPLEADO_ID
        JOIN gnusuariotipo c ON c.ID=a.TIPO_ID
        WHERE a.USUARIO=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->usuario));
      $data= $q->fetch(PDO::FETCH_ASSOC);   
      if(!empty($data)){
        $token="rulman";
        $sql = "UPDATE tausuario SET TOKEN=? WHERE ID=?";
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
    $sql = "SELECT ID,NOMBRE FROM gnusuariotipo WHERE ESTADO_ID=1 ORDER BY 1";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['tipos'] = $q->fetchAll(PDO::FETCH_ASSOC); 
    //Colaboradores Sin Usuario      
    $sql = "SELECT a.ID,CONCAT(a.NOMBRES,' ',a.APATERNO,' ',a.AMATERNO) NOMBRE 
      FROM taempleado a
      LEFT JOIN tausuario b on b.EMPLEADO_ID=a.ID 
      WHERE b.ID is null AND a.ESTADO_ID=1";
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
      $sql = "SELECT USUARIO FROM tausuario WHERE USUARIO=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->usuario));
      $data= $q->fetch(PDO::FETCH_ASSOC);
      if(empty($data)){
        $password = password_hash($this->password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tausuario VALUES(default,?,?,?,?,?,?)";
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
    $sql = "SELECT ID,NOMBRE FROM gnusuariotipo WHERE ESTADO_ID=1 ORDER BY 1";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['tipos'] = $q->fetchAll(PDO::FETCH_ASSOC); 
    //Colaboradores Sin Usuario      
    $sql = "SELECT a.ID,CONCAT(a.NOMBRES,' ',a.APATERNO,' ',a.AMATERNO) NOMBRE 
      FROM taempleado a
      LEFT JOIN tausuario b on b.EMPLEADO_ID=a.ID 
      WHERE b.ID is null AND a.ESTADO_ID=1";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['empleados'] = $q->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT ID,NOMBRE FROM gnestados  order by 1";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['estados'] = $q->fetchAll(PDO::FETCH_ASSOC);    
  
    $data['status']=true;
    BaseDatos::desconectar();
    return $data; 
  }

  function BuscarUsuarios($columna,$valor,$estados){
    if(empty($valor)){
      $cadena="WHERE a.ESTADO_ID IN (".$estados.")";
    }else{
      $cadena="WHERE ".$columna." like '%".strtoupper($valor)."%' AND a.ESTADO_ID IN (".$estados.")";
    }    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,a.TIPO_ID,c.NOMBRE PERFIL,a.USUARIO,a.EMPLEADO_ID,
      CONCAT(b.NOMBRES,' ',b.APATERNO,' ',b.AMATERNO) COLABORADOR,
      a.ESTADO_ID,d.NOMBRE ESTADO
      FROM tausuario a
      JOIN taempleado b on b.ID=a.EMPLEADO_ID
      JOIN gnusuariotipo c on c.ID=a.TIPO_ID
      JOIN gnestados d on d.ID=a.ESTADO_ID ".$cadena;    
    $q = $pdo->prepare($sql);
    $q->execute();
    $data = $q->fetchAll(PDO::FETCH_ASSOC);      
    $mensaje['status']=true;
    $mensaje['data']=$data ;  
    baseDatos::desconectar();
    return $mensaje; 
  } 

  function editarUsuario(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "SELECT USUARIO FROM tausuario WHERE USUARIO=? AND ID!=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->usuario,$this->id));
      $data= $q->fetch(PDO::FETCH_ASSOC);       
      if(empty($data)){
        $sql = "UPDATE tausuario SET TIPO_ID=?,USUARIO=?,EMPLEADO_ID=?,ESTADO_ID=? WHERE ID=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->tipo_id,$this->usuario,$this->empleado_id,$this->estado_id,$this->id));                   
        $mensaje['status']=true;
        $mensaje['mensaje']='USUARIO EDITADO CORRECTAMENTE'; 
        $pdo->commit();  
      }
      else{
        $mensaje['status']=false;
        $mensaje['mensaje']="EL USUARIO YA EXISTE";
      }  
      
    }catch(PDOException $e) { 
      $mensaje['status']=false;
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
      $sql = "UPDATE tausuario SET PASSWORD=? WHERE ID=?";
      $q = $pdo->prepare($sql);
      $password = password_hash($this->usuario, PASSWORD_DEFAULT);
      $q->execute(array($password,$this->id));                   
      $mensaje['status']=true;
      $mensaje['mensaje']='CONTRASEÑA ACTUALIZADA CORRECTAMENTE'; 
      $pdo->commit();
    }catch(PDOException $e) { 
      $mensaje['status']=false;
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
      $sql = "UPDATE tausuario SET PASSWORD=? WHERE ID=?";
      $q = $pdo->prepare($sql);
      $password = password_hash($this->password, PASSWORD_DEFAULT);
      $q->execute(array($password,$this->id));                   
      $mensaje['status']=true;
      $mensaje['mensaje']='SE CAMBIO LA CLAVE CORRECTAMENTE'; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }
  
}
?>