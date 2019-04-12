<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class salida 
{
  var $id;  	  
  var $preciocompra;
  var $precioventa;
  var $cantidad;
  var $fecha;  
  var $fechacreacion;
  var $usuario_id;
  var $estado;
  var $fecha_creacion;

  function crearSalida(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT  PRECIOCOMPRA FROM taingreso WHERE ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->ingreso_id));
    $data = $q->fetch(PDO::FETCH_ASSOC);  
    $preciocompra=$data['PRECIOCOMPRA'];
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO tasalida VALUES(default,?,?,?,STR_TO_DATE(?,'%d/%m/%Y %H:%i'),now(),?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($preciocompra,$this->precioventa,$this->cantidad,$this->fecha,$this->ingreso_id,$this->usuario_id,$this->estado_id));
      $mensaje['status']=true;
      $mensaje['salida_id']=$pdo->lastInsertId(); 
      $mensaje['mensaje']='SALIDA REGISTRADA CON EXITO';       
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
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
      $sql = "SELECT a.CANTIDAD,IFNULL(SUM(b.CANTIDAD),0) SALIO 
        FROM taingreso a
        LEFT JOIN tasalida b on b.INGRESO_ID=a.ID AND a.ESTADO_ID=1 AND b.ID!=?
        WHERE a.ID=?
        GROUP BY a.CANTIDAD";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->id,$this->ingreso_id));
      $data = $q->fetch(PDO::FETCH_ASSOC);       
      $mensaje['data']=$data;
      $total=intval($data['SALIO'])+intval($this->cantidad);
      if($total<=intval($data['CANTIDAD'])){

        $sql = "SELECT  PRECIOCOMPRA FROM taingreso WHERE ID=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->ingreso_id));
        $data = $q->fetch(PDO::FETCH_ASSOC);  
        $preciocompra=$data['PRECIOCOMPRA'];

        $sql = "UPDATE tasalida 
        SET PRECIOCOMPRA=?,
          PRECIOVENTA=?,
          CANTIDAD=?,          
          FECHA=STR_TO_DATE(?,'%d/%m/%Y %H:%i'),          
          INGRESO_ID=?,          
          USUARIO_ID=?, 
          ESTADO_ID=?
          WHERE ID=?";          
        $q = $pdo->prepare($sql);
        $q->execute(array($preciocompra,$this->precioventa,$this->cantidad,$this->fecha,$this->ingreso_id,$this->usuario_id,$this->estado_id,$this->id));            
        $sql = "SELECT a.CANTIDAD-IFNULL(SUM(b.CANTIDAD),0) SALDO 
          FROM taingreso a 
          LEFT JOIN tasalida b on b.INGRESO_ID=a.ID
          WHERE a.ID=1";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->ingreso_id));
        $data = $q->fetch(PDO::FETCH_ASSOC); 
        $mensaje['saldo']=$data['SALDO'];
        $mensaje['status']=true;
        $mensaje['mensaje']='SALIDA EDITADO CON EXITO';       
      }else{
        $mensaje['status']=false;
        $mensaje['mensaje']='ERROR LA CANTIDAD NO PUEDE SER MAYOR QUE EL SALDO';       
      }
      //Retornamoe el dato actualizado                        
      
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function eliminarSalida(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "UPDATE tasalida SET ESTADO_ID=2 WHERE  ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));     
    $mensaje['status']=true;
    $mensaje['mensaje']='SALIDA ELIMINADO CON EXITO';     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function buscarSalidas($fecha,$inicio,$final,$item_id,$estados){       
    $cadena="WHERE ".$fecha." BETWEEN STR_TO_DATE('".$inicio." 00:00:00','%d/%m/%Y %H:%i:%s') "; 
    $cadena.="AND STR_TO_DATE('".$final." 23:59:59','%d/%m/%Y %H:%i:%s') ";    
    if(!empty($item_id)){
      $cadena.="AND b.ITEM_ID=".$item_id;
    }
    $cadena="AND a.ESTADO_ID IN (".$estados.")";

    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,a.FECHA,a.INGRESO_ID,c.NOMBRE ITEM,a.PRECIOVENTA,a.CANTIDAD,
      ROUND(a.PRECIOVENTA*a.CANTIDAD,2) TOTAL,
      DATE_FORMAT(a.FECHA,'%d/%m/%Y %H:%i') FECHASALIDA,a.ESTADO_ID,d.NOMBRE ESTADO
      FROM tasalida a
      JOIN taingreso b on b.ID=a.INGRESO_ID
      JOIN taitem c on c.ID=b.ITEM_ID
      JOIN gnestados d on d.ID=a.ESTADO_ID ".$cadena;
    $q = $pdo->prepare($sql);
    $q->execute();
    $data = $q->fetchAll(PDO::FETCH_ASSOC);      
    $mensaje['status']=true;
    $mensaje['data']=$data ;  
   
    baseDatos::desconectar();
    return $mensaje; 
  } 

  function ResumenVenta($fecha){
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,a.FECHA,DATE_FORMAT(a.FECHA,'%d/%m/%Y %H:%i') FECHASALIDA,
      c.NOMBRE ITEM,d.NOMBRE LABORATORIO,e.NOMBRE PRESENTACION,a.PRECIOCOMPRA,
      a.PRECIOVENTA,a.CANTIDAD,
      ROUND(a.PRECIOVENTA*a.CANTIDAD,2) TOTAL,
      ROUND((a.PRECIOVENTA-a.PRECIOCOMPRA)*a.CANTIDAD,2) GANANCIA 
      FROM tasalida a 
      JOIN taingreso b on b.ID=a.INGRESO_ID
      JOIN taitem c on c.ID=b.ITEM_ID
      JOIN talaboratorio d on d.ID=c.LABORATORIO_ID
      JOIN gnitempresentacion e on e.ID=c.PRESENTACION_ID
      WHERE DATE_FORMAT(a.FECHA,'%d/%m/%Y')=? ORDER BY 2";
    $q = $pdo->prepare($sql);
    $q->execute(array($fecha));
    $data = $q->fetchAll(PDO::FETCH_ASSOC);      
    $mensaje['status']=true;
    $mensaje['data']=$data ;      
    baseDatos::desconectar();
    return $mensaje; 
  }
}
?>