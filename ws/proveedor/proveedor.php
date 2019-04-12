<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class proveedor 
{
  var $id;  	
	var $ruc;	
  var $nombre;      
  var $direccion;
  var $telefono;
  var $email;
  var $estado_id;

  function crearProveedor(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO taproveedor VALUES(default,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->ruc,$this->nombre,$this->direccion,$this->telefono,$this->email,$this->estado_id));
      $mensaje['status']=true;
      $mensaje['mensaje']='PROVEEDOR REGISTRADO CON EXITO'; 
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

  function editarProveedor(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE taproveedor 
        SET RUC=?,
        NOMBRE=?,
        DIRECCION=?,
        TELEFONO=?,
        EMAIL=?,
        ESTADO_ID=?
        WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->ruc,$this->nombre,$this->direccion,$this->telefono,$this->email,$this->estado_id,$this->id));
      $mensaje['status']=true;
      $mensaje['mensaje']='PROVEEDOR EDITADO CON EXITO';       
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function eliminarProveedor(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "UPDATE taproveedor SET ESTADO_ID=2 WHERE ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));     
    $mensaje['status']=true;
    $mensaje['mensaje']='PROVEEDOR ELIMINADO CON EXITO';     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }
 
  function buscarProveedores($columna,$valor,$estados){
    if(empty($valor)){
      $cadena="WHERE a.ESTADO_ID IN (".$estados.")";
    }else{
      $cadena="WHERE ".$columna." like '%".strtoupper($valor)."%' AND a.ESTADO_ID IN (".$estados.")";
    } 
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.*,b.NOMBRE ESTADO FROM taproveedor a 
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