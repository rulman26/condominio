<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class caja 
{
  var $id;  	  
	var $nombre;
  var $banco;
  var $cuenta;
  var $saldo;    
  var $bloque_id;  
  var $estado_id;

  function formEditar(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  

    $sql = "SELECT ID,NOMBRE FROM gnestados  order by 1";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['estados'] = $q->fetchAll(PDO::FETCH_ASSOC); 
    
    $sql = "SELECT ID,NOMBRE FROM tabloque WHERE ESTADO_ID=1 order by 2";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['bloques'] = $q->fetchAll(PDO::FETCH_ASSOC);  

    $data['status']=true;
    BaseDatos::desconectar();
    return $data;  
  }

  function crearCaja(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO tacaja VALUES(default,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->nombre,$this->banco,$this->cuenta,$this->saldo,$this->bloque_id,$this->estado_id));                  
      $mensaje['estado']=true;
      $mensaje['mensaje']='CAJA REGISTRADA CON EXITO'; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function editarGasto(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE tacaja 
        SET NOMBRE=?,
        BANCO=?,        
        CUENTA=?,
        SALDO=?,
        BLOQUE_ID=?,
        ESTADO_ID=?
        WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->nombre,$this->banco,$this->cuenta,$this->saldo,$this->bloque_id,$this->estado_id,$this->id)); 
      $mensaje['status']=true;
      $mensaje['mensaje']='CAJA EDITADO CON EXITO'; 
      
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }


  function buscarCajas($bloque_id,$nombre,$estados){
    if(empty($valor)){
      $cadena="WHERE a.ESTADO_ID IN (".$estados.")";
    }else{
      $cadena="WHERE BLOQUE_ID=".$bloque_id." AND a.ESTADO_ID IN (".$estados.")";
    } 
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,a.NOMBRE,a.BANCO,a.CUENTA,a.SALDO,a.BLOQUE_ID,b.NOMBRE BLOQUE,a.ESTADO_ID,c.NOMBRE ESTADO
      FROM tacaja a
      JOIN tabloque b ON b.ID=a.BLOQUE_ID
      JOIN gnestados c ON c.ID=a.ESTADO_ID ".$cadena;
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