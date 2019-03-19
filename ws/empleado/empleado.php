<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class empleado 
{
  var $id;  	
	var $dni;
	var $nombres;
  var $apaterno;
  var $amaterno;    
  var $correo;    
  var $imagen;    
  var $telefono;
  var $estado;

  function crearColaborador(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO EMPLEADO VALUES(default,?,?,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->dni,$this->nombres,$this->apaterno,$this->amaterno,$this->correo,$this->imagen,$this->telefono,$this->estado));                  
      $mensaje['estado']=true;
      $mensaje['mensaje']='COLABORADOR REGISTRADO CON EXITO'; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function editarColaborador(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE EMPLEADO 
        SET EMPLEADO_DNI=?,
        EMPLEADO_NOMBRES=?,
        EMPLEADO_APATERNO=?,
        EMPLEADO_AMATERNO=?,
        EMPLEADO_CORREO=?,
        EMPLEADO_TELEFONO=?,
        EMPLEADO_ESTADO=?
        WHERE EMPLEADO_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->dni,$this->nombres,$this->apaterno,$this->amaterno,$this->correo,$this->telefono,$this->estado,$this->id)); 
      $mensaje['estado']=true;
      $mensaje['mensaje']='COLABORADOR EDITADO CON EXITO';       
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function listaColaboradores($filtro,$columna,$valor){
    $filtrosAceptados=['activos','inactivos','todos'];
    if (in_array($filtro, $filtrosAceptados)) {
      if ($filtro=="activos") {
        $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%' AND EMPLEADO_ESTADO='ACTIVO'";
      }elseif($filtro=="inactivos"){        
        $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%' AND EMPLEADO_ESTADO='INACTIVO'";
      }else{
        $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%' ";
      }
      $pdo = baseDatos::conectar();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT *FROM EMPLEADO ".$cadena;
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