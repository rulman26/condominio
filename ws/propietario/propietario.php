<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class propietario 
{
  var $id;  	
	var $dni;
	var $nombres;
  var $apaterno;
  var $amaterno;    
  var $correo;          
  var $telefono;
  var $estado_id;

  function crearPropietario(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO tapropietario VALUES(default,?,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->dni,$this->nombres,$this->apaterno,$this->amaterno,$this->telefono,$this->correo,$this->estado_id));                  
      $mensaje['status']=true;
      $mensaje['mensaje']='PROPIETARIO REGISTRADO CON EXITO'; 
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

  function editarColaborador(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE tapropietario 
        SET DNI=?,
        NOMBRES=?,
        APATERNO=?,
        AMATERNO=?,
        CORREO=?,
        TELEFONO=?,
        ESTADO_ID=?
        WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->dni,$this->nombres,$this->apaterno,$this->amaterno,$this->correo,$this->telefono,$this->estado_id,$this->id)); 
      $mensaje['status']=true;
      $mensaje['mensaje']='COLABORADOR EDITADO CON EXITO';       
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function buscarColaboradores($columna,$valor,$estados){
    if(empty($valor)){
      $cadena="WHERE a.ESTADO_ID IN (".$estados.")";
    }else{
      $cadena="WHERE ".$columna." like '%".strtoupper($valor)."%' AND a.ESTADO_ID IN (".$estados.")";
    }   
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.*,b.NOMBRE ESTADO FROM tapropietario a 
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