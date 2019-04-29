<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class recibo 
{
  var $id;  	
	var $fecha;
	var $numero;
  var $descripcion;
  var $monto;    
  var $cuota_id;          
  var $departamento_id;
  var $estado_id;

  function crearRecibo(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "SELECT ifnull(max(numero),10000000)+1 MAX from tarecibo";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->bloque_id));
      $correlativo = $q->fetch(PDO::FETCH_ASSOC); 
      $sql = "INSERT INTO tarecibo VALUES(default,STR_TO_DATE(?,'%d/%m/%Y'),?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->fecha,$correlativo['MAX'],$this->descripcion,$this->monto,$this->cuota_id,$this->departamento_id,$this->estado_id));
      $mensaje['status']=true;
      $mensaje['mensaje']='RECIBO REGISTRADO CON EXITO'; 
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
    $sql = "SELECT ID,NOMBRE FROM gnreciboestado  order by 1";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['estados'] = $q->fetchAll(PDO::FETCH_ASSOC);    
    
    $sql = "SELECT ID,NOMBRE FROM tabloque WHERE ESTADO_ID=1 order by 2";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data['bloques'] = $q->fetchAll(PDO::FETCH_ASSOC);  

    $sql = "SELECT a.ID,CONCAT(b.NOMBRE,' - ',a.NUMERO) NOMBRE 
      FROM tadepartamento a
      JOIN tabloque b on b.ID=a.BLOQUE_ID
      where a.BLOQUE_ID=? AND a.ESTADO_ID=1 ORDER BY 2";
    $q = $pdo->prepare($sql);
    $q->execute(array(1));
    $data['departamentos']= $q->fetchAll(PDO::FETCH_ASSOC);  

    $data['status']=true;
    BaseDatos::desconectar();
    return $data; 
  }

  function editarRecibo(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT ESTADO_ID FROM tarecibo WHERE ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));
    $data = $q->fetch(PDO::FETCH_ASSOC); 
    if(intval($data['ESTADO_ID'])>1){
      $mensaje['status']=false;
      $mensaje['mensaje']="El Recibo ya fue Cancelado";
    }else{
      try {  
        $pdo->beginTransaction();
        $sql = "UPDATE tarecibo 
          SET FECHA=STR_TO_DATE(?, '%d/%m/%Y'),
          DESCRIPCION=?,
          MONTO=?,
          ESTADO_ID=?
          WHERE ID=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->fecha,$this->descripcion,$this->monto,$this->estado_id,$this->id)); 
        $mensaje['status']=true;
        $mensaje['mensaje']='RECIBO EDITADO CON EXITO';       
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

  function buscarRecibos($inicio,$final,$numero,$estados){
    if(empty($numero)){
      $cadena="WHERE  a.FECHA BETWEEN STR_TO_DATE('".$inicio."', '%d/%m/%Y') AND STR_TO_DATE('".$final."', '%d/%m/%Y') AND a.ESTADO_ID IN (".$estados.")";
    }else{
      $cadena="WHERE a.NUMERO=".$numero;
    }   
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,a.FECHA,DATE_FORMAT(a.FECHA,'%d/%m/%Y')FECHAEMISION,
      CONCAT(c.NOMBRE,'-',b.NUMERO) DEPARTAMENTO,
      a.NUMERO,a.DESCRIPCION,a.MONTO,a.DEPARTAMENTO_ID,b.BLOQUE_ID,a.ESTADO_ID,d.NOMBRE ESTADO,
      CONCAT(e.NOMBRES,' ',e.APATERNO,' ',e.AMATERNO) PROPIETARIO
      FROM tarecibo a
      JOIN tadepartamento b on b.ID=a.DEPARTAMENTO_ID
      JOIN tabloque c on c.ID=b.BLOQUE_ID
      JOIN gnreciboestado d on d.ID=a.ESTADO_ID
      JOIN tapropietario e on e.ID=b.PROPIETARIO_ID ".$cadena ." ORDER BY 4";
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