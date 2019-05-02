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
      //Obtengo el Saldo para insertar en la Tabla.
      $sql = "SELECT SALDO FROM tacaja WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->caja_id));
      $saldo = $q->fetch(PDO::FETCH_ASSOC);   
      $pdo->beginTransaction();
      if(intval($this->tipo_id==1)){
        $sql = "INSERT INTO tamovimiento VALUES(default,?,?,?,now(),?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($saldo['SALDO'],$this->monto,$this->descripcion,$this->operacion,$this->caja_id,$this->tipo_id,$this->recibo_id,$this->estado_id));
        $mensaje['ingreso_id']=$pdo->lastInsertId();
        //Actualizamos el Saldo 
        $sql = "UPDATE tacaja SET SALDO=SALDO+? WHERE ID=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->monto,$this->caja_id));
        //Actualizamos el Recibo 
        $sql = "UPDATE tarecibo SET ESTADO_ID=2 WHERE ID=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->recibo_id));
        $mensaje['status']=true;
        $mensaje['mensaje']='COBRANZA REGISTRADA CON EXITO'; 
      }else{
        if(floatval($saldo['SALDO'])>=floatval($this->monto)){
          $sql = "INSERT INTO tamovimiento VALUES(default,?,?,?,now(),?,?,?,?,?)";
          $q = $pdo->prepare($sql);
          $q->execute(array($saldo['SALDO'],$this->monto,$this->descripcion,$this->operacion,$this->caja_id,$this->tipo_id,$this->recibo_id,$this->estado_id));
          $mensaje['ingreso_id']=$pdo->lastInsertId();
          //Actualizamos el Saldo 
          $sql = "UPDATE tacaja SET SALDO=SALDO-? WHERE ID=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($this->monto,$this->caja_id));        
          $mensaje['status']=true;
          $mensaje['mensaje']='SALIDA REGISTRADA CON EXITO'; 
        }else{
          $mensaje['status']=false;
          $mensaje['mensaje']='NO EXISTE SALDO EN LA CAJA SELECCIONADA'; 
        }        
      }
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
      //INGRESOS
      if(intval($this->tipo_id)==1){
        //Editar solo campos no necesarios
        if($this->estado_id==1){
          $sql = "UPDATE tamovimiento 
          SET DESCRIPCION=?,            
            OPERACION=?,
            ESTADO_ID=?          
            WHERE ID=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($this->descripcion,$this->operacion,$this->estado_id,$this->id));
          $mensaje['status']=true;            
          $mensaje['mensaje']='MOVIMIENTO EDITADO CON EXITO'; 
        }else{        
          //Obtenemos los Datos del Movimiento
          $sql = "SELECT MONTO,CAJA_ID,RECIBO_ID FROM tamovimiento WHERE ID=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($this->id));
          $data = $q->fetch(PDO::FETCH_ASSOC); 
          //Actualizamos el Saldo y restamos el monto abonado.
          $sql = "UPDATE tacaja SET SALDO=SALDO-? WHERE ID=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($data['MONTO'],$data['CAJA_ID']));
          //Actualizamos el recibo a Pendiente ya que estaba cancelado.
          $sql = "UPDATE tarecibo SET ESTADO_ID=1 WHERE ID=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($data['RECIBO_ID']));
          //Anular el movimiento.
          $sql = "UPDATE tamovimiento SET ESTADO_ID=2 WHERE ID=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($this->id));
          //Actualizamos el Saldo de los Movimientos.
          $sql = "UPDATE tamovimiento SET SALDO=SALDO-? WHERE ID>?";
          $q = $pdo->prepare($sql);
          $q->execute(array($data['MONTO'],$this->id));
          $mensaje['status']=true;
          $mensaje['mensaje']='MOVIMIENTO ELIMINADO CON EXITO'; 
        }
      }
      //SALIDAS
      else{
        //Editar solo campos no necesarios
        if($this->estado_id==1){
          $sql = "UPDATE tamovimiento 
          SET DESCRIPCION=?,            
            OPERACION=?,
            ESTADO_ID=?          
            WHERE ID=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($this->descripcion,$this->operacion,$this->estado_id,$this->id));
          $mensaje['status']=true;            
          $mensaje['mensaje']='MOVIMIENTO EDITADO CON EXITO'; 
        }else{        
          //Obtenemos los Datos del Movimiento
          $sql = "SELECT MONTO,CAJA_ID,RECIBO_ID FROM tamovimiento WHERE ID=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($this->id));
          $data = $q->fetch(PDO::FETCH_ASSOC); 
          //Actualizamos el Saldo y Agregamos el monto abonado.
          $sql = "UPDATE tacaja SET SALDO=SALDO+? WHERE ID=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($data['MONTO'],$data['CAJA_ID']));          
          //Anular el movimiento.
          $sql = "UPDATE tamovimiento SET ESTADO_ID=2 WHERE ID=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($this->id));

          //Actualizamos el Saldo de los Movimientos.
          $sql = "UPDATE tamovimiento SET SALDO=SALDO+? WHERE ID>?";
          $q = $pdo->prepare($sql);
          $q->execute(array($data['MONTO'],$this->id));

          $mensaje['status']=true;
          $mensaje['mensaje']='MOVIMIENTO ELIMINADO CON EXITO'; 
        }
      }
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

    $sql = "SELECT ID,NOMBRE FROM tabloque order by 1";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['bloques']=$data;

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
    $cadena="WHERE  a.FECHA BETWEEN STR_TO_DATE('".$inicio." 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('".$final." 23:59:59', '%d/%m/%Y %H:%i:%s')";
    $cadena.=" AND a.ESTADO_ID IN (".$estados.") AND a.TIPO_ID IN(".$tipo.")"; 
    if(!empty($caja_id)){
      $cadena.=" AND a.CAJA_ID=".$caja_id;
    }
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,b.NOMBRE CAJA,DATE_FORMAT(a.FECHA,'%d/%m/%Y %H:%i') FECHA,
      a.OPERACION,a.MONTO,a.RECIBO_ID,a.DESCRIPCION,a.ESTADO_ID,c.NOMBRE ESTADO,a.TIPO_ID,ROUND(a.SALDO,2) SALDO,
      a.TIPO_ID,d.NOMBRE TIPO
      FROM tamovimiento a
      JOIN tacaja b on b.ID=a.CAJA_ID
      JOIN gnestados c on c.ID=a.ESTADO_ID
      JOIN gnmovimientotipo d on d.ID=a.TIPO_ID ".$cadena ." ORDER BY 1";
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