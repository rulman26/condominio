<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class movimiento 
{
  var $id;  		
  var $monto;  
  var $descripcion;
  var $fecha;
  var $operacion;
  var $caja_id;
  var $tipo_id;
  var $recibo_id;    
  var $estado_id;

  function getMovimiento(){
    $data['id']=$this->id;
    $data['monto']=$this->monto;
    $data['descripcion']=$this->descripcion;
    $data['fecha']=$this->fecha;
    $data['operacion']=$this->operacion;
    $data['caja_id']=$this->caja_id;
    $data['tipo_id']=$this->tipo_id;
    $data['recibo_id']=$this->recibo_id;
    $data['estado_id']=$this->estado_id;
    return $data;
  }

  function crearMovimiento(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO tamovimiento VALUES(default,?,?,STR_TO_DATE(?,'%d/%m/%Y'),?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->monto,$this->descripcion,$this->fecha,$this->operacion,$this->caja_id,$this->tipo_id,$this->recibo_id,$this->estado_id));
      $mensaje['ingreso_id']=$pdo->lastInsertId();
      //Actualizamos el Saldo 
      $sql = "UPDATE tacaja SET SALDO=SALDO+? WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->monto,$this->caja_id));

      $sql = "UPDATE tacaja SET SALDO=SALDO+? WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->monto,$this->caja_id));

      $sql = "UPDATE tarecibo SET ESTADO_ID=2 WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->recibo_id));

      $mensaje['status']=true;
      $mensaje['mensaje']='MOVIMIENTO REGISTRADO CON EXITO'; 
      $pdo->commit(); 
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function EditarMovimiento(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE tamovimiento 
        SET DESCRIPCION=?,
          FECHA=STR_TO_DATE(?,'%d/%m/%Y'),
          OPERACION=?,
          ESTADO_ID=?          
          WHERE ID=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->descripcion,$this->fecha,$this->operacion,$this->estado_id,$this->id));
        $mensaje['status']=true;            
        $mensaje['mensaje']='MOVIMIENTO EDITADO CON EXITO'; 
            
     
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function eliminarIngreso(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $sql = "SELECT IFNULL(SUM(CANTIDAD),0) SALIO FROM tasalida WHERE ESTADO_ID=1 AND INGRESO_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));
    $data = $q->fetch(PDO::FETCH_ASSOC);      
    if(intval($data['SALIO'])>0){
      $mensaje['status']=false;
      $mensaje['mensaje']='DICHO INGRESO YA REGISTRO SALIDAS ELIMINAR SALIDAS PRIMERO'; 
    }else{
      $sql = "UPDATE taingreso SET ESTADO_ID=2 WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->id));
      $mensaje['status']=true;
      $mensaje['mensaje']='INGRESO ELIMINADO CORRECTAMENTE'; 
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function formEditar(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    
    $sql = "SELECT a.ID,a.NOMBRE,a.BLOQUE_ID,CONCAT(b.NOMBRE,' - ',a.NOMBRE) CAJA
      FROM tacaja a 
      JOIN tabloque b on b.ID=a.BLOQUE_ID
      WHERE a.ESTADO_ID=1 
      ORDER BY 2";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['cajas']=$data;

    $sql = "SELECT ID,NOMBRE FROM gnestados  order by 1";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['estados']=$data;

    $mensaje['status']=true;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function buscarMovimientos($inicio,$final,$caja_id,$tipo,$estados){
    $cadena="WHERE  a.FECHA BETWEEN STR_TO_DATE('".$inicio."', '%d/%m/%Y') AND STR_TO_DATE('".$final."', '%d/%m/%Y')";
    $cadena.=" AND a.ESTADO_ID IN (".$estados.") AND a.TIPO_ID IN(".$tipo.")"; 
    if(!empty($caja_id)){
      $cadena.=" AND a.CAJA_ID=".$caja_id;
    }
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,b.NOMBRE CAJA,a.FECHA,DATE_FORMAT(a.FECHA,'%d/%m/%Y') FECHAINGRESO,
      a.OPERACION,a.MONTO,a.RECIBO_ID,a.DESCRIPCION,a.ESTADO_ID,c.NOMBRE ESTADO,a.TIPO_ID
      from tamovimiento a
      join tacaja b on b.ID=a.CAJA_ID
      join gnestados c on c.ID=a.ESTADO_ID ".$cadena ." ORDER BY 3";
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