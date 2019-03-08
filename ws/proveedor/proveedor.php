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
  var $estado;

  function crearProveedor(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO PROVEEDOR VALUES(default,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->ruc,$this->nombre,$this->direccion,$this->telefono,$this->email,$this->estado));
      $mensaje['estado']=true;
      $mensaje['mensaje']='PROVEEDOR REGISTRADO CON EXITO'; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function editarProveedor(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE PROVEEDOR 
        SET PROVEEDOR_RUC=?,
        PROVEEDOR_NOMBRE=?,
        PROVEEDOR_DIRECCION=?,
        PROVEEDOR_TELEFONO=?,
        PROVEEDOR_EMAIL=?,
        PROVEEDOR_ESTADO=?
        WHERE PROVEEDOR_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->ruc,$this->nombre,$this->direccion,$this->telefono,$this->email,$this->estado,$this->id));
      //Retornamoe el dato actualizado                  
      $data=$this->leerProveedor();
      $mensaje['estado']=true;
      $mensaje['mensaje']='PROVEEDOR EDITADO CON EXITO'; 
      $mensaje['proveedor']=$data; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function leerProveedor(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT *FROM PROVEEDOR WHERE PROVEEDOR_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id)); 
    $data = $q->fetch(PDO::FETCH_ASSOC);                     
    $mensaje['estado']=true;
    $mensaje['data']=$data;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function eliminarProveedor(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "UPDATE PROVEEDOR SET PROVEEDOR_ESTADO='INACTIVO' WHERE  PROVEEDOR_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));     
    $mensaje['estado']=true;
    $mensaje['mensaje']='PROVEEDOR ELIMINADO CON EXITO';     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }
 
  function listaProveedores($filtro,$columna,$valor){
    $filtrosAceptados=['activos','inactivos','todos'];
    if (in_array($filtro, $filtrosAceptados)) {
      if ($filtro=="activos") {
        $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%' AND PROVEEDOR_ESTADO='ACTIVO'";
      }elseif($filtro=="inactivos"){
        $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%' AND PROVEEDOR_ESTADO='INACTIVO'";
      }else{
        $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%' ";
      }
      $pdo = baseDatos::conectar();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT * FROM PROVEEDOR ".$cadena;
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