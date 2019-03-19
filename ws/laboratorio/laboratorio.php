<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class laboratorio 
{
  var $id;  	
  var $nombre;      
  var $estado;

  function crearLaboratorio(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO LABORATORIO VALUES(default,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->nombre,$this->estado));
      $mensaje['estado']=true;
      $mensaje['mensaje']='LABORATORIO REGISTRADO CON EXITO'; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function editarLaboratorio(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE LABORATORIO 
        SET LABORATORIO_NOMBRE=?,  
        LABORATORIO_ESTADO=?
        WHERE LABORATORIO_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->nombre,$this->estado,$this->id));
      //Retornamoe el dato actualizado                        
      $mensaje['estado']=true;
      $mensaje['mensaje']='LABORATORIO EDITADO CON EXITO';       
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function eliminarLaboratorio(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "UPDATE LABORATORIO SET LABORATORIO_ESTADO='INACTIVO' WHERE  LABORATORIO_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));     
    $mensaje['estado']=true;
    $mensaje['mensaje']='LABORATORIO ELIMINADO CON EXITO';     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function listaLaboratorio($filtro,$columna,$valor){
    $filtrosAceptados=['activos','inactivos','todos'];
    if (in_array($filtro, $filtrosAceptados)) {
      if ($filtro=="activos") {
        $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%' AND LABORATORIO_ESTADO='ACTIVO'";
      }elseif($filtro=="inactivos"){
        $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%' AND LABORATORIO_ESTADO='INACTIVO'";
      }else{
        $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%' ";
      }
      $pdo = baseDatos::conectar();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT * FROM LABORATORIO ".$cadena;
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