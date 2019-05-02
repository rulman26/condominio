<?php
require '../conn.php';

class departamento 
{
  var $id;  	
	var $numero;
	var $ocupado;
  var $bloque_id;
  var $propietario_id;
  var $estado_id;

  function crearDeapartamento(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      
      $pdo->beginTransaction();
      $sql = "INSERT INTO tadepartamento VALUES(default,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->numero,$this->ocupado,$this->bloque_id,$this->propietario_id,$this->estado_id));
      $mensaje['status']=true;
      $mensaje['mensaje']='DEPARTAMENTO REGISTRADO CON EXITO'; 
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

    $sql = "SELECT ID,NOMBRE FROM tabloque WHERE ESTADO_ID=1 order by 2";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['bloques'] = $q->fetchAll(PDO::FETCH_ASSOC);      

    $sql = "SELECT ID,CONCAT(APATERNO,' ',AMATERNO,' ',NOMBRES) NOMBRE from tapropietario WHERE ESTADO_ID=1";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['propietarios'] = $q->fetchAll(PDO::FETCH_ASSOC);      
  
    $data['status']=true;
    BaseDatos::desconectar();
    return $data; 
  }

  function editarDepartamento(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE tadepartamento 
        SET NUMERO=?,
        OCUPADO=?,
        BLOQUE_ID=?,
        PROPIETARIO_ID=?,
        ESTADO_ID=?
        WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->numero,$this->ocupado,$this->bloque_id,$this->propietario_id,$this->estado_id,$this->id)); 
      $mensaje['status']=true;
      $mensaje['mensaje']='DEPARTAENTO EDITADO CON EXITO';       
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function leerCliente(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT *FROM CLIENTE WHERE CLIENTE_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id)); 
    $data = $q->fetch(PDO::FETCH_ASSOC);                     
    $mensaje['status']=true;
    $mensaje['data']=$data;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }
  
  function eliminarCliente(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "UPDATE tacliente SET ESTADO_ID=2 WHERE  ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));     
    $mensaje['status']=true;
    $mensaje['mensaje']='CLIENTE ELIMINADO CON EXITO';     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function buscardepartamentos($columna,$valor,$estados){
    if(empty($valor)){
      $cadena="WHERE a.ESTADO_ID IN (".$estados.")";
    }else{
      $cadena="WHERE ".$columna." like '%".strtoupper($valor)."%' AND a.ESTADO_ID IN (".$estados.")";
    }   
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,a.NUMERO,a.BLOQUE_ID,b.NOMBRE BLOQUE,a.OCUPADO,a.PROPIETARIO_ID,CONCAT(b.NOMBRE,'-',a.NUMERO) DEPARTAMENTO,
    CONCAT(c.APATERNO,' ',c.AMATERNO,' ',c.NOMBRES) PROPIETARIO,a.ESTADO_ID,d.NOMBRE ESTADO
    FROM tadepartamento a
    JOIN tabloque b ON b.ID=a.BLOQUE_ID
    JOIN tapropietario c ON c.ID=a.PROPIETARIO_ID
    JOIN gnestados d ON d.ID=a.ESTADO_ID ".$cadena."  ORDER BY 2";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data = $q->fetchAll(PDO::FETCH_ASSOC);      
    $mensaje['status']=true;
    $mensaje['data']=$data ;  
    baseDatos::desconectar();
    return $mensaje; 
  } 

  function departamentosByBloqueId($bloque_id){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,CONCAT(b.NOMBRE,' - ',a.NUMERO) NOMBRE 
      FROM tadepartamento a
      JOIN tabloque b on b.ID=a.BLOQUE_ID
      where a.BLOQUE_ID=? AND a.ESTADO_ID=1 ORDER BY 2";
    $q = $pdo->prepare($sql);
    $q->execute(array($bloque_id));
    $data = $q->fetchAll(PDO::FETCH_ASSOC);      
    $mensaje['status']=true;
    $mensaje['data']=$data ;  
    baseDatos::desconectar();
    return $mensaje; 
  } 
}
?>