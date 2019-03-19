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
  var $lote;
	var $cantidad;
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
    $array['lote']=$this->lote;
    $array['ubicacion']=$this->ubicacion;
    $array['cantidad']=$this->cantidad;    
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
      $q->execute(array($this->item_id,$this->codigobarra,$this->ubicacion,$this->lote,$this->cantidad,$this->fechaingreso,$this->fechafabricacion,$this->fechavencimiento,$this->estado));                  
      $mensaje['intentario_id']=$pdo->lastInsertId(); 
      $mensaje['estado']=true;
      $mensaje['mensaje']='INGRESO REGISTRADO CON EXITO'; 
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
    try {  
      $pdo->beginTransaction();
      $sql = "SELECT IFNULL(SUM(SALIDA_CANTIDAD),0) SALIO FROM SALIDA WHERE SALIDA_ESTADO='ACTIVO' AND INVENTARIO_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->id));
      $data = $q->fetch(PDO::FETCH_ASSOC); 
      if($this->cantidad>=$data['SALIO']){
        if($this->estado=="INACTIVO"){
          if(intval($data['SALIO'])>0){
            $mensaje['estado']=false;
            $mensaje['mensaje']='DICHO INGRESO YA REGISTRO SALIDAS ELIMINAR SALIDAS PRIMERO'; 
          }else{
            $sql = "UPDATE INVENTARIO 
            SET ITEM_ID=?,
              INVENTARIO_CODIGO_BARRA=?,
              INVENTARIO_UBICACION=?,
              INVENTARIO_LOTE=?,
              INVENTARIO_CANTIDAD=?,        
              INVENTARIO_FECHA_INGRESO=STR_TO_DATE(?,'%d/%m/%Y %H:%i'),
              INVENTARIO_FECHA_FABRICACION=STR_TO_DATE(?,'%d/%m/%Y'),
              INVENTARIO_FECHA_VENCIMIENTO=STR_TO_DATE(?,'%d/%m/%Y'),
              INVENTARIO_ESTADO=?
              WHERE INVENTARIO_ID=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($this->item_id,$this->codigobarra,$this->ubicacion,$this->lote,$this->cantidad,$this->fechaingreso,$this->fechafabricacion,$this->fechavencimiento,$this->estado,$this->id));            
            $mensaje['estado']=true;
            $mensaje['mensaje']='INVENTARIO EDITADO CON EXITO'; 
          }
        }else{
          $sql = "UPDATE INVENTARIO 
          SET ITEM_ID=?,
            INVENTARIO_CODIGO_BARRA=?,
            INVENTARIO_UBICACION=?,
            INVENTARIO_LOTE=?,
            INVENTARIO_CANTIDAD=?,        
            INVENTARIO_FECHA_INGRESO=STR_TO_DATE(?,'%d/%m/%Y %H:%i'),
            INVENTARIO_FECHA_FABRICACION=STR_TO_DATE(?,'%d/%m/%Y'),
            INVENTARIO_FECHA_VENCIMIENTO=STR_TO_DATE(?,'%d/%m/%Y'),
            INVENTARIO_ESTADO=?
            WHERE INVENTARIO_ID=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($this->item_id,$this->codigobarra,$this->ubicacion,$this->lote,$this->cantidad,$this->fechaingreso,$this->fechafabricacion,$this->fechavencimiento,$this->estado,$this->id));            
          $mensaje['estado']=true;
          $mensaje['mensaje']='INVENTARIO EDITADO CON EXITO'; 
        }
              
      }else{
        $mensaje['estado']=false;
        $mensaje['mensaje']='ERROR LA CANTIDAD NO PUEDE SER MENOR QUE LAS SALIDAS REGISTRADAS';       
      }
      //Retornamoe el dato actualizado                        
      
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function eliminarInventario(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $sql = "SELECT IFNULL(SUM(SALIDA_CANTIDAD),0) SALIO FROM SALIDA WHERE SALIDA_ESTADO='ACTIVO' AND INVENTARIO_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));
    $data = $q->fetch(PDO::FETCH_ASSOC);      
    if(intval($data['SALIO'])>0){
      $mensaje['estado']=false;
      $mensaje['mensaje']='DICHO INGRESO YA REGISTRO SALIDAS ELIMINAR SALIDAS PRIMERO'; 
    }else{
      $sql = "UPDATE INVENTARIO SET INVENTARIO_ESTADO='INACTIVO' WHERE INVENTARIO_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->id));
      $mensaje['estado']=true;
      $mensaje['mensaje']='INGRESO ELIMINADO CORRECTAMENTE'; 
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function listaInventarios($filtro,$fecha,$inicio,$final,$item_id){
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
      if(!empty($item_id)){
        $cadena.="AND INV.ITEM_ID=".$item_id;
      }
      $pdo = baseDatos::conectar();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT INV.INVENTARIO_ID,INV.ITEM_ID,ITM.ITEM_NOMBRE,
        INV.INVENTARIO_CANTIDAD,INV.INVENTARIO_UBICACION,
        DATE_FORMAT(INV.INVENTARIO_FECHA_INGRESO,'%d/%m/%Y %H:%i') INVENTARIO_FECHA_INGRESO,
        DATE_FORMAT(INV.INVENTARIO_FECHA_FABRICACION,'%d/%m/%Y') INVENTARIO_FECHA_FABRICACION,
        DATE_FORMAT(INV.INVENTARIO_FECHA_VENCIMIENTO,'%d/%m/%Y') INVENTARIO_FECHA_VENCIMIENTO,
        INV.INVENTARIO_ESTADO 
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

  function inventarioSalida(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT SALIDA_ID,SALIDA_FECHA,SALIDA_PRECIO,SALIDA_CANTIDAD,
      ROUND(SALIDA_PRECIO*SALIDA_CANTIDAD,2) TOTAL, 
      DATE_FORMAT(SALIDA_FECHA,'%d/%m/%Y %H:%i') FECHA
      FROM SALIDA
      WHERE INVENTARIO_ID=? AND SALIDA_ESTADO='ACTIVO' 
      ORDER BY 2";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id)); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                     
    $mensaje['estado']=true;
    $mensaje['data']=$data;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function formItemInventarioSalida(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT INV.INVENTARIO_ID,ITM.ITEM_NOMBRE,
      INV.INVENTARIO_FECHA_VENCIMIENTO,ITMT.ITEM_TIPO_NOMBRE,ITMC.ITEM_CATEGORIA_NOMBRE,
      LAB.LABORATORIO_NOMBRE,PRO.PROVEEDOR_NOMBRE,ITM.ITEM_PRECIO_VENTA,
      DATE_FORMAT(INV.INVENTARIO_FECHA_VENCIMIENTO, '%d/%m/%Y')FECHA_VENCIMIENTO,
      (INV.INVENTARIO_CANTIDAD-IFNULL(sum(SAL.SALIDA_CANTIDAD),0)) AS INVENTARIO_SALDO
      FROM INVENTARIO INV
      JOIN ITEM ITM ON ITM.ITEM_ID=INV.ITEM_ID 
      JOIN LABORATORIO LAB ON LAB.LABORATORIO_ID=ITM.LABORATORIO_ID
      JOIN PROVEEDOR PRO ON PRO.PROVEEDOR_ID=ITM.PROVEEDOR_ID
      JOIN ITEM_TIPO ITMT ON ITMT.ITEM_TIPO_ID=ITM.ITEM_TIPO_ID
      JOIN ITEM_CATEGORIA ITMC ON ITMC.ITEM_CATEGORIA_ID=ITM.ITEM_CATEGORIA_ID      
      LEFT JOIN SALIDA SAL ON SAL.INVENTARIO_ID=INV.INVENTARIO_ID AND SAL.SALIDA_ESTADO='ACTIVO'
      WHERE INV.INVENTARIO_ESTADO='ACTIVO'
      GROUP BY INV.INVENTARIO_ID,ITM.ITEM_NOMBRE,
      INV.INVENTARIO_FECHA_VENCIMIENTO,ITMT.ITEM_TIPO_NOMBRE,ITMC.ITEM_CATEGORIA_NOMBRE,
      PRO.PROVEEDOR_NOMBRE,ITM.ITEM_PRECIO_VENTA,
      DATE_FORMAT(INV.INVENTARIO_FECHA_VENCIMIENTO, '%d/%m/%Y')            
      HAVING INVENTARIO_SALDO>0
      ORDER BY 3 asc";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['inventario']=$data;
    $mensaje['estado']=true;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function inventarioStock($item_id,$proveedor_id,$item_tipo_id,$item_categoria_id){    
    $cadena="";
    if(!empty($item_id)){
      $cadena.=" AND ITM.ITEM_ID=".$item_id;
    }
    if(!empty($proveedor_id)){
      $cadena.=" AND ITM.PROVEEDOR_ID=".$proveedor_id;
    }
    if(!empty($item_tipo_id)){
      $cadena.=" AND ITM.ITEM_TIPO_ID=".$item_tipo_id;
    }
    if(!empty($item_categoria_id)){
      $cadena.=" AND ITM.ITEM_CATEGORIA_ID=".$item_categoria_id;
    }

    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT INV.INVENTARIO_ID,
      ITM.ITEM_NOMBRE,
      INV.INVENTARIO_FECHA_VENCIMIENTO,
      DATE_FORMAT(INV.INVENTARIO_FECHA_INGRESO,'%d/%m/%Y %H:%i') FECHA_INGRESO,
      DATE_FORMAT(INV.INVENTARIO_FECHA_FABRICACION,'%d/%m/%Y')FECHA_FABRICACION,
      DATE_FORMAT(INV.INVENTARIO_FECHA_VENCIMIENTO,'%d/%m/%Y')FECHA_VENCIMIENTO,
      INVENTARIO_CANTIDAD,
      (INV.INVENTARIO_CANTIDAD-IFNULL(sum(SAL.SALIDA_CANTIDAD),0)) AS INVENTARIO_SALDO
      FROM INVENTARIO INV
      JOIN ITEM ITM ON ITM.ITEM_ID=INV.ITEM_ID 
      LEFT JOIN SALIDA SAL ON SAL.INVENTARIO_ID=INV.INVENTARIO_ID AND SAL.SALIDA_ESTADO='ACTIVO'
      WHERE INV.INVENTARIO_ESTADO='ACTIVO' ".$cadena.
      " GROUP BY 
      INV.INVENTARIO_ID,
      ITM.ITEM_NOMBRE,
      INV.INVENTARIO_FECHA_VENCIMIENTO,
      DATE_FORMAT(INV.INVENTARIO_FECHA_INGRESO,'%d/%m/%Y %H:%i'),
      DATE_FORMAT(INV.INVENTARIO_FECHA_FABRICACION,'%d/%m/%Y'),
      DATE_FORMAT(INV.INVENTARIO_FECHA_VENCIMIENTO,'%d/%m/%Y'),
      INVENTARIO_CANTIDAD
      HAVING INVENTARIO_SALDO>0
      ORDER BY 3 asc";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['stock']=$data;
    $mensaje['estado']=true;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

}
?>