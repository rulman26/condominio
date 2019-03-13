<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class salida 
{
  var $id;  	
  var $inventario_id;	      
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
      $sql = "INSERT INTO SALIDA VALUES(default,?,?,?,STR_TO_DATE(?,'%d/%m/%Y %H:%i'),?,?,?,now())";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->inventario_id,$this->precio,$this->cantidad,$this->fecha,$this->tipo,$this->usuario_id,$this->estado));
      $mensaje['estado']=true;
      $mensaje['salida_id']=$pdo->lastInsertId(); 
      $mensaje['mensaje']='SALIDA REGISTRADA CON EXITO';       
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function EditarSalida(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "SELECT IFNULL(SUM(SALIDA_CANTIDAD),0) SALIO FROM SALIDA WHERE INVENTARIO_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->inventario_id));
      $data = $q->fetch(PDO::FETCH_ASSOC); 
      if($this->cantidad>=$data['SALIO']){
        $sql = "UPDATE INVENTARIO 
        SET ITEM_ID=?,
          INVENTARIO_CODIGO_BARRA=?,
          INVENTARIO_UBICACION=?,
          INVENTARIO_CANTIDAD=?,        
          INVENTARIO_FECHA_INGRESO=STR_TO_DATE(?,'%d/%m/%Y %H:%i'),
          INVENTARIO_FECHA_FABRICACION=STR_TO_DATE(?,'%d/%m/%Y'),
          INVENTARIO_FECHA_VENCIMIENTO=STR_TO_DATE(?,'%d/%m/%Y'),
          INVENTARIO_ESTADO=?
          WHERE INVENTARIO_ID=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->item_id,$this->codigobarra,$this->ubicacion,$this->cantidad,$this->fechaingreso,$this->fechafabricacion,$this->fechavencimiento,$this->estado,$this->id));            
        $mensaje['estado']=true;
        $mensaje['mensaje']='INVENTARIO EDITADO CON EXITO';       
      }else{
        $mensaje['estado']=false;
        $mensaje['mensaje']='ERROR LA CANTIDAD NO PUEDE SER MENOR';       
      }
      //Retornamoe el dato actualizado                        
      
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