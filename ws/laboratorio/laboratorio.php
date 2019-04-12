<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class laboratorio 
{
  var $id;  	
  var $nombre;      
  var $estado_id;

  function crearLaboratorio(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO talaboratorio VALUES(default,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->nombre,$this->estado_id));
      $mensaje['status']=true;
      $mensaje['mensaje']='LABORATORIO REGISTRADO CON EXITO'; 
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
    $sql = "SELECT ID,NOMBRE FROM gnestados  order by 1";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['estados'] = $q->fetchAll(PDO::FETCH_ASSOC);    
  
    $data['status']=true;
    BaseDatos::desconectar();
    return $data; 
  }

  function editarLaboratorio(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE talaboratorio 
        SET NOMBRE=?,  
        ESTADO_ID=?
        WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->nombre,$this->estado_id,$this->id));
      //Retornamoe el dato actualizado                        
      $mensaje['status']=true;
      $mensaje['mensaje']='LABORATORIO EDITADO CON EXITO';       
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function eliminarLaboratorio(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "UPDATE talaboratorio SET ESTADO_ID=2 WHERE ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));     
    $mensaje['status']=true;
    $mensaje['mensaje']='LABORATORIO ELIMINADO CON EXITO';     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function buscarLaboratorios($columna,$valor,$estados){
    if(empty($valor)){
      $cadena="WHERE a.ESTADO_ID IN (".$estados.")";
    }else{
      $cadena="WHERE ".$columna." like '%".strtoupper($valor)."%' AND a.ESTADO_ID IN (".$estados.")";
    }   
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.*,b.NOMBRE ESTADO FROM talaboratorio a 
      JOIN gnestados b on b.ID=a.ESTADO_ID ".$cadena;
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