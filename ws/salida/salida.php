<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class salida 
{
  var $id;  	
  var $inventario_id;	
  var $saldo;      
  var $precio;
  var $cantidad;
  var $fecha;
  var $tipo;
  var $usuario_id;
  var $estado;
  var $fecha_creacion;

  function crearSalida(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO SALIDA VALUES(default,?,?,?,?,STR_TO_DATE(?,'%d/%m/%Y %H:%i'),?,?,?,now())";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->inventario_id,$this->saldo,$this->precio,$this->cantidad,$this->fecha,$this->tipo,$this->usuario_id,$this->estado));
      $mensaje['estado']=true;
      $mensaje['salida_id']=$pdo->lastInsertId(); 
      $mensaje['mensaje']='SALIDA REGISTRADA CON EXITO'; 
      $sql = "UPDATE INVENTARIO 
        SET INVANTARIO_SALDO=INVANTARIO_SALDO-?
        WHERE INVENTARIO_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->cantidad,$this->inventario_id));
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function editarSalida(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      //Obtener los datos del Anterior iD INVENTARIO ANTERIOR
      $sql = "SELECT *FROM SALIDA WHERE SALIDA_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->id)); 
      $data = $q->fetch(PDO::FETCH_ASSOC);
      $inventario_id_ant=$data['INVENTARIO_ID'];
      $salida_cantidad_ant=$data['SALIDA_CANTIDAD'];
      //Obtener los datos del Anterior iD SALDO ACTUAL DEL INVENTARIO
      $sql = "SELECT *FROM INVENTARIO WHERE INVENTARIO_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($inventario_id_ant)); 
      $data = $q->fetch(PDO::FETCH_ASSOC);
      $inventario_saldo_actual=$data['INVENTARIO_SALDO'];
      //Actualizamos su Saldo sumando el actual y la vez que salio
      $sql = "UPDATE INVENTARIO 
        SET INVANTARIO_SALDO=?
        WHERE INVENTARIO_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(($salida_cantidad_ant+$inventario_saldo_actual),data['INVENTARIO_ID']));
      //Actualizamos todos los saldos de la salida
      $sql = "UPDATE SALIDA 
        SET SALIDA_SALDO=SALIDA_SALDO+?
        WHERE INVENTARIO_ID=? AND SALIDA_ID>?";
      $q = $pdo->prepare($sql);
      $q->execute(array($salida_cantidad_ant,$inventario_id_ant,$this->id));  
      
      $sql = "UPDATE SALIDA 
        SET INVENTARIO_ID=?,
        SALIDA_SALDO=?,
        SALIDA_PRECIO=?,
        SALIDA_CANTIDAD=?,
        SALIDA_FECHA=?,
        SALIDA_TIPO=?,
        USUARIO_ID=?,
        SALIDA_ESTADO=?
        WHERE SALIDA_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->inventario_id,$this->saldo,$this->precio,$this->cantidad,$this->fecha,$this->tipo,$this->usuario_id,$this->estado,$this->id));

      $sql = "UPDATE INVENTARIO 
        SET INVANTARIO_SALDO=INVANTARIO_SALDO-?
        WHERE INVENTARIO_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->cantidad,$this->inventario_id));
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