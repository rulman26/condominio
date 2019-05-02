<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class cuota 
{
  var $id;  	
  var $periodo;      
  var $total;
  var $cantidad;
  var $cuota;
  var $descripcion;
  var $bloque_id;
  var $estado_id;

  function crearCuota(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO tacuota VALUES(default,?,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->periodo,$this->total,$this->cantidad,$this->cuota,$this->descripcion,$this->bloque_id,$this->estado_id));
      $idCuota=$pdo->lastInsertId(); 
      $sql = "SELECT a.ID,CONCAT(b.nombre,'-',a.NUMERO) NOMBRE
        FROM tadepartamento a 
        JOIN tabloque b on b.ID=a.BLOQUE_ID
        WHERE a.BLOQUE_ID=? AND a.ESTADO_ID=1";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->bloque_id));
      $data = $q->fetchAll(PDO::FETCH_ASSOC); 

      foreach($data as $row){
        $sql = "SELECT ifnull(max(numero),10000000)+1 MAX from tarecibo";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->bloque_id));
        $correlativo = $q->fetch(PDO::FETCH_ASSOC); 
        $sql = "INSERT INTO tarecibo VALUES(default,now(),?,?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($correlativo['MAX'],'MANTENIMIENTO '.$this->periodo.' '.$row['NOMBRE'],$this->cuota,$idCuota,$row['ID'],1));
      }

      $sql = "UPDATE tagasto set ESTADO_ID=2 WHERE BLOQUE_ID=? AND PERIODO=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->bloque_id,$this->periodo));
      $mensaje['status']=true;
      $mensaje['mensaje']='CUOTAS REGISTRADO CON EXITO'; 
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
  
    $data['status']=true;
    BaseDatos::desconectar();
    return $data; 
  }

  function eliminarCuota(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT ID from tarecibo where CUOTA_ID=? AND ESTADO_ID=2";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));
    $data = $q->fetchAll(PDO::FETCH_ASSOC); 
    if(count($data)>1){
      $mensaje['status']=false;
      $mensaje['mensaje']="Extornar los recibos Cancelados de la Cuota Nro : ".$this->id;
    }else{
      try {  
        $pdo->beginTransaction();
        //Cancelamos la Cuota.
        $sql = "UPDATE tacuota 
          SET ESTADO_ID=2
          WHERE ID=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->id));
        //Cancelamos todos los recibos de esa Cuota.
        $sql = "UPDATE tarecibo 
          SET ESTADO_ID=3
          WHERE CUOTA_ID=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->id));
        //Obtener BLOQUE_ID y PERIODO
        $sql = "SELECT ID,BLOQUE_ID,PERIODO from tacuota where ID=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($this->id));
        $data = $q->fetch(PDO::FETCH_ASSOC); 
        //Cambiamos EL pago a Pendiente de lo que estaba Programado
        $sql = "UPDATE tagasto 
          SET ESTADO_ID=1
          WHERE BLOQUE_ID=? AND PERIODO=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($data['BLOQUE_ID'],$data['PERIODO']));
        //Retornamoe el dato actualizado.                        
        $mensaje['status']=true;
        $mensaje['mensaje']='CUOTA ELIMINADA CON EXITO';       
        $pdo->commit();  
      }catch(PDOException $e) { 
        $mensaje['status']=false;
        $mensaje['mensaje']=$e->getMessage();
        $pdo->rollBack();
      }; 
    }      
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function buscarCuotas($bloque_id,$periodo,$estados){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,a.PERIODO,ROUND(a.TOTAL,2) TOTAL,a.CANTIDAD,
      ROUND(a.CUOTA,2) CUOTA,a.DESCRIPCION,a.BLOQUE_ID,
      a.ESTADO_ID,b.NOMBRE BLOQUE,a.ESTADO_ID,c.NOMBRE ESTADO
      FROM tacuota a
      JOIN tabloque b ON b.ID=a.BLOQUE_ID
      JOIN gnestados c ON c.ID=a.ESTADO_ID
      WHERE a.BLOQUE_ID=? AND a.PERIODO=? ORDER BY 4";
    $q = $pdo->prepare($sql);    
    $q->execute(array($bloque_id,$periodo));
    $data = $q->fetchAll(PDO::FETCH_ASSOC);      
    $mensaje['status']=true;    
    $mensaje['data']=$data ;  
    baseDatos::desconectar();
    return $mensaje; 
  }
  
  function cuotaRecibos(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT a.ID,DATE_FORMAT(a.FECHA,'%d/%m/%Y') FECHA ,a.NUMERO,a.DESCRIPCION,
      ROUND(a.MONTO,2) MONTO,CONCAT(c.NOMBRE,'-',b.NUMERO) DEPARTAMENTO,a.ESTADO_ID,d.NOMBRE ESTADO
      FROM tarecibo a
      JOIN tadepartamento b ON b.ID=a.DEPARTAMENTO_ID
      JOIN tabloque c ON c.ID=b.BLOQUE_ID
      JOIN gnreciboestado d ON d.ID=a.ESTADO_ID
      WHERE CUOTA_ID=? ORDER BY 3";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id)); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['recibos']=$data;
    $mensaje['status']=true;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }
}
?>