<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class cliente 
{
  var $id;  	
	var $nombre;
	var $direccion;
  var $dni;
  var $ruc;    
  var $telefono;
  var $estado;

  function crearCliente(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO CLIENTE VALUES(default,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->nombre,$this->direccion,$this->dni,$this->ruc,$this->telefono,$this->estado));                  
      $mensaje['estado']=true;
      $mensaje['mensaje']='CLIENTE REGISTRADO CON EXITO'; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function editarCliente(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE CLIENTE 
        SET CLIENTE_NOMBRE=?,
        CLIENTE_DIRECCION=?,
        CLIENTE_DNI=?,
        CLIENTE_RUC=?,
        CLIENTE_TELEFONO=?,
        CLIENTE_ESTADO=?
        WHERE CLIENTE_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->nombre,$this->direccion,$this->dni,$this->ruc,$this->telefono,$this->estado,$this->id)); 
      //Retornamoe el dato actualizado                  
      //$data=$this->leerCliente();
      $mensaje['estado']=true;
      $mensaje['mensaje']='CLIENTE EDITADO CON EXITO'; 
      //$mensaje['cliente']=$data; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
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
    $mensaje['estado']=true;
    $mensaje['data']=$data;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }
  
  function eliminarCliente(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "UPDATE CLIENTE SET CLIENTE_ESTADO='INACTIVO' WHERE  CLIENTE_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));     
    $mensaje['estado']=true;
    $mensaje['mensaje']='CLIENTE ELIMINADO CON EXITO';     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function listaClientes($filtro,$columna,$valor){
    $filtrosAceptados=['activos','inactivos','todos'];
    if (in_array($filtro, $filtrosAceptados)) {
      if ($filtro=="activos") {
        $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%' AND CLIENTE_ESTADO='ACTIVO'";
      }elseif($filtro=="inactivos"){        
        $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%' AND CLIENTE_ESTADO='INACTIVO'";
      }else{
        $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%' ";
      }
      $pdo = baseDatos::conectar();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT *FROM CLIENTE ".$cadena;
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