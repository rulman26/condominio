<?php
require '../conn.php';

class cliente 
{
  var $id;  	
	var $nombre;
	var $direccion;
  var $dni;
  var $ruc;    
  var $telefono;
  var $estado_id;

  function crearCliente(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO tacliente VALUES(default,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->nombre,$this->direccion,$this->dni,$this->ruc,$this->telefono,$this->estado_id));
      $mensaje['status']=true;
      $mensaje['mensaje']='CLIENTE REGISTRADO CON EXITO'; 
      $pdo->commit();  
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
    $sql = "SELECT ID,NOMBRE FROM gnestados  order by 1";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['estados'] = $q->fetchAll(PDO::FETCH_ASSOC);    
  
    $data['status']=true;
    BaseDatos::desconectar();
    return $data; 
  }

  function editarCliente(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE tacliente 
        SET NOMBRE=?,
        DIRECCION=?,
        DNI=?,
        RUC=?,
        TELEFONO=?,
        ESTADO_ID=?
        WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->nombre,$this->direccion,$this->dni,$this->ruc,$this->telefono,$this->estado_id,$this->id)); 
      $mensaje['status']=true;
      $mensaje['mensaje']='CLIENTE EDITADO CON EXITO';       
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function leerCliente(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT *FROM CLIENTE WHERE CLIENTE_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id)); 
    $data = $q->fetch(PDO::FETCH_ASSOC);                     
    $mensaje['status']=true;
    $mensaje['data']=$data;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }
  
  function eliminarCliente(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "UPDATE tacliente SET ESTADO_ID=2 WHERE  ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));     
    $mensaje['status']=true;
    $mensaje['mensaje']='CLIENTE ELIMINADO CON EXITO';     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  
  function buscarClientes($columna,$valor,$estados){
    if(empty($valor)){
      $cadena="WHERE a.ESTADO_ID IN (".$estados.")";
    }else{
      $cadena="WHERE ".$columna." like '%".strtoupper($valor)."%' AND a.ESTADO_ID IN (".$estados.")";
    }      
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.*,b.NOMBRE ESTADO FROM tacliente a
      JOIN gnestados b on b.ID=a.ESTADO_ID ".$cadena;
    $q = $pdo->prepare($sql);
    $q->execute();
    $data = $q->fetchAll(PDO::FETCH_ASSOC);      
    $mensaje['status']=true;
    $mensaje['data']=$data ;  
    baseDatos::desconectar();
    return $mensaje; 
  } 
}
?>