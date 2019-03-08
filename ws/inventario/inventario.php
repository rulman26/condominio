<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class inventario 
{
  var $id;  	
	var $item_id;
  var $codigobarra;
  var $ubicacion;
	var $cantidad;
  var $saldo;
  var $fechaingreso;
  var $fechafabricacion;
  var $fechavencimiento;
  var $estado;

  function getInventario()
  {
    $array=[];
    $array['id']=$this->id;
    $array['item_id']=$this->item_id;
    $array['codigobarra']=$this->codigobarra;
    $array['ubicacion']=$this->ubicacion;
    $array['cantidad']=$this->cantidad;
    $array['saldo']=$this->saldo;
    $array['fechaingreso']=$this->fechaingreso;
    $array['fechafabricacion']=$this->fechafabricacion;
    $array['fechavencimiento']=$this->fechavencimiento;
    $array['estado']=$this->estado;
    return $array;
  }

  function crearInventario(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO INVENTARIO 
        VALUES(default,?,?,?,?,?,STR_TO_DATE(?,'%d/%m/%Y %H:%i'),STR_TO_DATE(?, '%d/%m/%Y'),STR_TO_DATE(?, '%d/%m/%Y'),?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->item_id,$this->codigobarra,$this->ubicacion,$this->cantidad,$this->saldo,$this->fechaingreso,$this->fechafabricacion,$this->fechavencimiento,$this->estado));                  
      $mensaje['intentario_id']=$pdo->lastInsertId(); 
      $mensaje['estado']=true;
      $mensaje['mensaje']='INVENTARIO REGISTRADO CON EXITO'; 
      $pdo->commit(); 
      
      
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function leerInventario(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT INV.INVENTARIO_ID,INV.INVENTARIO_CODIGO_BARRA,INV.INVENTARIO_UBICACION,INV.ITEM_ID,ITM.ITEM_CODIGO,ITM.ITEM_NOMBRE,INV.INVENTARIO_CANTIDAD,INV.INVENTARIO_SALDO,
      INV.INVENTARIO_FECHA_INGRESO,INV.INVENTARIO_FECHA_FABRICACION,
      INV.INVENTARIO_FECHA_VENCIMIENTO,INV.INVENTARIO_ESTADO 
      FROM INVENTARIO INV
      JOIN ITEM ITM ON ITM.ITEM_ID=INV.ITEM_ID 
      WHERE INV.INVENTARIO_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id)); 
    $data = $q->fetch(PDO::FETCH_ASSOC);                     
    $mensaje['estado']=true;
    $mensaje['data']=$data;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function EditarInventario(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);     
    $sql = "CALL sp_inventario_editar(?,?,?,?,STR_TO_DATE(?,'%d/%m/%Y %H:%i'),STR_TO_DATE(?,'%d/%m/%Y'), STR_TO_DATE(?, '%d/%m/%Y'),?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->item_id,$this->codigobarra,$this->ubicacion,$this->cantidad,$this->fechaingreso,$this->fechafabricacion,$this->fechavencimiento,$this->estado,$this->id));    
    $data = $q->fetch(PDO::FETCH_ASSOC);       
    if($data["ESTADO"])  
    $status = ($data["ESTADO"]=="TRUE") ? true:false;                  
    $mensaje['estado']=$status;
    $mensaje['mensaje']=$data["MENSAJE"];           
    $pdo = baseDatos::desconectar();
    $mensaje['inventario']=$this->leerInventario();
    return $mensaje;  
  }

  function eliminarInventario(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
    $sql = "CALL sp_inventario_eliminar(?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));
    $data = $q->fetch(PDO::FETCH_ASSOC); 
    if($data["ESTADO"])  
    $status = ($data["ESTADO"]=="TRUE") ? true:false;                  
    $mensaje['estado']=$status;
    $mensaje['mensaje']=$data["MENSAJE"]; 
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function listaInventarios($filtro,$fecha,$inicio,$final){
    $filtrosAceptados=['activos','anulados','todos'];
    if (in_array($filtro, $filtrosAceptados)) {
      if ($filtro=="activos") {
        $cadena="WHERE INV.".$fecha." BETWEEN STR_TO_DATE('".$inicio." 00:00:00','%d/%m/%Y %H:%i:%s') "; 
        $cadena.="AND STR_TO_DATE('".$final." 23:59:59','%d/%m/%Y %H:%i:%s') ";
        $cadena.="AND INV.INVENTARIO_ESTADO='ACTIVO'";
      }elseif($filtro=="anulados"){
        $cadena="WHERE INV.".$fecha." BETWEEN STR_TO_DATE('".$inicio." 00:00:00','%d/%m/%Y %H:%i:%s') "; 
        $cadena.="AND STR_TO_DATE('".$final." 23:59:59','%d/%m/%Y %H:%i:%s') ";
        $cadena="WHERE INV.INVENTARIO_ESTADO='ANULADO'";
      }else{
        $cadena="WHERE INV.".$fecha." BETWEEN STR_TO_DATE('".$inicio." 00:00:00','%d/%m/%Y %H:%i:%s') "; 
        $cadena.="AND STR_TO_DATE('".$final." 23:59:59','%d/%m/%Y %H:%i:%s') ";
      }
      $pdo = baseDatos::conectar();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT INV.INVENTARIO_ID,INV.ITEM_ID,ITM.ITEM_CODIGO,ITM.ITEM_NOMBRE,
        INV.INVENTARIO_CANTIDAD,INV.INVENTARIO_SALDO,
        INV.INVENTARIO_FECHA_INGRESO,INV.INVENTARIO_FECHA_FABRICACION,
        INV.INVENTARIO_FECHA_VENCIMIENTO,INV.INVENTARIO_ESTADO 
        FROM INVENTARIO INV
        JOIN ITEM ITM ON ITM.ITEM_ID=INV.ITEM_ID ".$cadena;
      $q = $pdo->prepare($sql);
      $q->execute();
      $data = $q->fetchAll(PDO::FETCH_ASSOC);      
      $mensaje['estado']=true;
      $mensaje['data']=$data ;  
    }else{
      $mensaje['estado']=false;
      $mensaje['mensaje']="SOLO ACEPTA (activos,anulados y todos)";
    }
    baseDatos::desconectar();
    return $mensaje; 
  } 

  function formItemInventarioSalida(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT INV.INVENTARIO_ID,ITM.ITEM_NOMBRE,INV.INVENTARIO_SALDO,
      INV.INVENTARIO_FECHA_VENCIMIENTO,ITMT.ITEM_TIPO_NOMBRE,ITMC.ITEM_CATEGORIA_NOMBRE,
      PRO.PROVEEDOR_NOMBRE,ITM.ITEM_PRECIO_VENTA,
      DATE_FORMAT(INV.INVENTARIO_FECHA_VENCIMIENTO, '%d/%m/%Y')FECHA_VENCIMIENTO
      FROM INVENTARIO INV
      JOIN ITEM ITM ON ITM.ITEM_ID=INV.ITEM_ID 
      JOIN PROVEEDOR PRO ON PRO.PROVEEDOR_ID=ITM.PROVEEDOR_ID
      JOIN ITEM_TIPO ITMT ON ITMT.ITEM_TIPO_ID=ITM.ITEM_TIPO_ID
      JOIN ITEM_CATEGORIA ITMC ON ITMC.ITEM_CATEGORIA_ID=ITM.ITEM_CATEGORIA_ID
      where INV.INVENTARIO_SALDO>0
      order by 3 asc";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['inventario']=$data;
    $mensaje['estado']=true;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

}
?>