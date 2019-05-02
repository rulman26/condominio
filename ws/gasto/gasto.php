<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class gasto 
{
  var $id;  	
	var $periodo;	
  var $descripcion;      
  var $monto;  
  var $servicio_id;
  var $bloque_id;
  var $estado_id;

  function crearGasto(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO tagasto VALUES(default,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->periodo,$this->descripcion,$this->monto,$this->servicio_id,$this->bloque_id,$this->estado_id));
      $mensaje['status']=true;
      $mensaje['mensaje']='GASTO REGISTRADO CON EXITO'; 
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
    $sql = "SELECT ID,NOMBRE FROM gngastoestado  order by 1";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['estados'] = $q->fetchAll(PDO::FETCH_ASSOC); 
    
    $sql = "SELECT ID,NOMBRE FROM tabloque WHERE ESTADO_ID=1 order by 2";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['bloques'] = $q->fetchAll(PDO::FETCH_ASSOC);   

    $sql = "SELECT ID,NOMBRE FROM taservicio  order by 1";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['servicios'] = $q->fetchAll(PDO::FETCH_ASSOC); 
  
    $data['status']=true;
    BaseDatos::desconectar();
    return $data; 
  }

  function editarGasto(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
    
    $sql = "SELECT ESTADO_ID FROM tagasto WHERE ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));
    $data = $q->fetch(PDO::FETCH_ASSOC); 
    if(intval($data['ESTADO_ID'])>1){
      $mensaje['status']=false;
      $mensaje['mensaje']="El gastos ya fue programado";
    }else{
      try {  
        $pdo->beginTransaction();
        $sql = "UPDATE tagasto 
          SET PERIODO=?,
          DESCRIPCION=?,
          MONTO=?,
          SERVICIO_ID=?,
          BLOQUE_ID=?,
          ESTADO_ID=?
          WHERE ID=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->periodo,$this->descripcion,$this->monto,$this->servicio_id,$this->bloque_id,$this->estado_id,$this->id));
        $mensaje['status']=true;
        $mensaje['mensaje']='GASTO EDITADO CON EXITO';       
        $pdo->commit();  
      }catch(PDOException $e) { 
        $mensaje['status']=false;
        $mensaje['mensaje']=$e->getMessage();
        $pdo->rollBack();
      }
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
 
  function buscarGastos($bloque_id,$periodo,$estados){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,c.NOMBRE BLOQUE,a.PERIODO,b.NOMBRE SERVICIO,a.DESCRIPCION,ROUND(a.MONTO,2) MONTO,
    a.ESTADO_ID,d.NOMBRE ESTADO
    FROM tagasto a
    JOIN taservicio b ON b.ID=a.SERVICIO_ID
    JOIN tabloque c ON c.ID=a.BLOQUE_ID
    JOIN gngastoestado d ON d.ID=a.ESTADO_ID
    WHERE a.BLOQUE_ID=? AND a.PERIODO=?  ORDER BY 4";
    $q = $pdo->prepare($sql);    
    $q->execute(array($bloque_id,$periodo));
    $data = $q->fetchAll(PDO::FETCH_ASSOC);      
    $mensaje['status']=true;    
    $mensaje['data']=$data ;  
    baseDatos::desconectar();
    return $mensaje; 
  } 
  
  function programarGastos($bloque_id,$periodo){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,b.NOMBRE SERVICIO,a.PERIODO,a.MONTO from tagasto a
      JOIN taservicio b on b.ID=a.SERVICIO_ID
      where a.ESTADO_ID=1 AND a.BLOQUE_ID=? AND a.PERIODO=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($bloque_id,$periodo));
    $data = $q->fetchAll(PDO::FETCH_ASSOC);      
    $mensaje['status']=true;
    $mensaje['data']=$data ;  
    baseDatos::desconectar();
    return $mensaje; 
  } 
  
}
?>