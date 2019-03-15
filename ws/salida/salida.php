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
 
  function listaSalidas($filtro,$fecha,$inicio,$final,$item_id){
    $filtrosAceptados=['activos','anulados','todos'];
    if (in_array($filtro, $filtrosAceptados)) {
      if ($filtro=="activos") {
        $cadena="WHERE SAL.".$fecha." BETWEEN STR_TO_DATE('".$inicio." 00:00:00','%d/%m/%Y %H:%i:%s') "; 
        $cadena.="AND STR_TO_DATE('".$final." 23:59:59','%d/%m/%Y %H:%i:%s') ";
        $cadena.="AND SAL.SALIDA_ESTADO='ACTIVO'";
      }elseif($filtro=="anulados"){
        $cadena="WHERE SAL.".$fecha." BETWEEN STR_TO_DATE('".$inicio." 00:00:00','%d/%m/%Y %H:%i:%s') "; 
        $cadena.="AND STR_TO_DATE('".$final." 23:59:59','%d/%m/%Y %H:%i:%s') ";
        $cadena="WHERE SAL.SALIDA_ESTADO='ANULADO'";
      }else{
        $cadena="WHERE SAL.".$fecha." BETWEEN STR_TO_DATE('".$inicio." 00:00:00','%d/%m/%Y %H:%i:%s') "; 
        $cadena.="AND STR_TO_DATE('".$final." 23:59:59','%d/%m/%Y %H:%i:%s') ";
      }
      if(!empty($item_id)){
        $cadena.="AND INV.ITEM_ID=".$item_id;
      }
      $pdo = baseDatos::conectar();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT SAL.SALIDA_ID,SAL.SALIDA_FECHA,INV.INVENTARIO_ID,
        ITM.ITEM_NOMBRE,SAL.SALIDA_PRECIO,SAL.SALIDA_CANTIDAD,SAL.SALIDA_TIPO,
        ROUND(SAL.SALIDA_PRECIO*SAL.SALIDA_CANTIDAD,2) TOTAL,
        DATE_FORMAT(SAL.SALIDA_FECHA,'%d/%m/%Y %H:%i') FECHA,SAL.SALIDA_ESTADO
        FROM SALIDA SAL
        JOIN INVENTARIO INV ON INV.INVENTARIO_ID=SAL.INVENTARIO_ID       
        JOIN ITEM ITM ON ITM.ITEM_ID=INV.ITEM_ID ".$cadena." ORDER BY 2";
      $q = $pdo->prepare($sql);
      $q->execute();
      $data = $q->fetchAll(PDO::FETCH_ASSOC);      
      $mensaje['estado']=true;
      $mensaje['data']=$data ;  
    }else{
      $mensaje['estado']=false;
      $mensaje['mensaje']="SOLO ACEPTA (activos,anulados y todos)";
    }
    baseDatos::desconectar();
    return $mensaje; 
  }
}
?>