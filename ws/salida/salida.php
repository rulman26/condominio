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

  function editarSalida(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "SELECT INV.INVENTARIO_CANTIDAD CANTIDAD,IFNULL(SUM(SAL.SALIDA_CANTIDAD),0) SALIO 
      FROM INVENTARIO INV 
      LEFT JOIN SALIDA SAL ON INV.INVENTARIO_ID=SAL.INVENTARIO_ID AND INV.INVENTARIO_ESTADO='ACTIVO' AND  SAL.SALIDA_ID!=? 
      WHERE INV.INVENTARIO_ID=? 
      GROUP BY INV.INVENTARIO_CANTIDAD";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->id,$this->inventario_id));
      $data = $q->fetch(PDO::FETCH_ASSOC);       
      $mensaje['data']=$data;
      $total=intval($data['SALIO'])+intval($this->cantidad);
      if($total<=intval($data['CANTIDAD'])){
        $sql = "UPDATE SALIDA 
        SET INVENTARIO_ID=?,
          SALIDA_PRECIO=?,
          SALIDA_CANTIDAD=?,          
          SALIDA_FECHA=STR_TO_DATE(?,'%d/%m/%Y %H:%i'),
          SALIDA_TIPO=?,          
          USUARIO_ID=?, 
          SALIDA_ESTADO=?
          WHERE SALIDA_ID=?";          
        $q = $pdo->prepare($sql);
        $q->execute(array($this->inventario_id,$this->precio,$this->cantidad,$this->fecha,$this->tipo,$this->usuario_id,$this->estado,$this->id));            

        $sql = "SELECT INV.INVENTARIO_CANTIDAD-IFNULL(SUM(SAL.SALIDA_CANTIDAD),0) SALDO 
        FROM INVENTARIO INV 
        LEFT JOIN SALIDA SAL ON SAL.INVENTARIO_ID=INV.INVENTARIO_ID AND SAL.SALIDA_ESTADO='ACTIVO'
        WHERE INV.INVENTARIO_ID=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->inventario_id));
        $data = $q->fetch(PDO::FETCH_ASSOC); 
        $mensaje['saldo']=$data['SALDO'];
        $mensaje['estado']=true;
        $mensaje['mensaje']='SALIDA EDITADO CON EXITO';       
      }else{
        $mensaje['estado']=false;
        $mensaje['mensaje']='ERROR LA CANTIDAD NO PUEDE SER MAYOR QUE EL SALDO';       
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

  function eliminarSalida(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "UPDATE SALIDA SET SALIDA_ESTADO='INACTIVO' WHERE  SALIDA_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));     
    $mensaje['estado']=true;
    $mensaje['mensaje']='SALIDA ELIMINADO CON EXITO';     
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