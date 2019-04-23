<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class ingreso 
{
  var $id;  		
  var $codigobarra;  
  var $preciocompra;
  var $precioventa;
  var $lote;
  var $cantidad;
  var $factura;
  var $fechaingreso;  
  var $fechavencimiento;
  var $fechacreacion;
  var $item_id;
  var $usuario_id;
  var $estado_id;

  function getIngreso(){
    $data['id']=$this->id;
    $data['codigobarra']=$this->codigobarra;
    $data['preciocompra']=$this->preciocompra;
    $data['precioventa']=$this->precioventa;
    $data['lote']=$this->lote;
    $data['cantidad']=$this->cantidad;
    $data['factura']=$this->factura;
    $data['fechaingreso']=$this->fechaingreso;
    $data['fechavencimiento']=$this->fechavencimiento;
    $data['fechacreacion']=$this->fechacreacion;
    $data['item_id']=$this->item_id;
    $data['usuario_id']=$this->usuario_id;
    $data['estado_id']=$this->estado_id;
    return $data;
  }

  function crearIngreso(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO taingreso VALUES(default,?,?,?,?,?,?,STR_TO_DATE(?,'%d/%m/%Y %H:%i'),STR_TO_DATE(?, '%d/%m/%Y'),now(),?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->codigobarra,$this->preciocompra,$this->precioventa,$this->lote,$this->cantidad,$this->factura,$this->fechaingreso,$this->fechavencimiento,$this->item_id,$this->usuario_id,$this->estado_id));
      $mensaje['ingreso_id']=$pdo->lastInsertId(); 
      $mensaje['status']=true;
      $mensaje['mensaje']='INGRESO REGISTRADO CON EXITO'; 
      $pdo->commit(); 
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function EditarIngreso(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "SELECT IFNULL(SUM(CANTIDAD),0) SALIO FROM tasalida WHERE ESTADO_ID=1 AND INGRESO_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->id));
      $data = $q->fetch(PDO::FETCH_ASSOC); 
      if($this->cantidad>=$data['SALIO']){
        if(intval($this->estado_id)==2){
          if(intval($data['SALIO'])>0){
            $mensaje['estado']=false;
            $mensaje['mensaje']='DICHO INGRESO YA REGISTRO SALIDAS ELIMINAR SALIDAS PRIMERO'; 
          }else{
            $sql = "UPDATE taingreso 
            SET 
              CODIGOBARRA=?,              
              PRECIOCOMPRA=?,
              PRECIOVENTA=?,
              LOTE=?,
              CANTIDAD=?,
              FACTURA=?,
              FECHAINGRESO=STR_TO_DATE(?,'%d/%m/%Y %H:%i'),              
              FECHAVENCIMIENTO=STR_TO_DATE(?,'%d/%m/%Y'),
              ITEM_ID=?,
              USUARIO_ID=?,
              ESTADO_ID=?
              WHERE ID=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($this->codigobarra,$this->preciocompra,$this->precioventa,$this->lote,$this->cantidad,$this->factura,$this->fechaingreso,$this->fechavencimiento,$this->item_id,$this->usuario_id,$this->estado_id,$this->id));
            $mensaje['status']=true;
            $mensaje['mensaje']='INGRESO EDITADO CON EXITO'; 
          }
        }else{
          $sql = "UPDATE taingreso 
            SET 
              CODIGOBARRA=?,              
              PRECIOCOMPRA=?,
              PRECIOVENTA=?,
              LOTE=?,
              CANTIDAD=?,
              FACTURA=?,
              FECHAINGRESO=STR_TO_DATE(?,'%d/%m/%Y %H:%i'),              
              FECHAVENCIMIENTO=STR_TO_DATE(?,'%d/%m/%Y'),
              ITEM_ID=?,
              USUARIO_ID=?,
              ESTADO_ID=?
              WHERE ID=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($this->codigobarra,$this->preciocompra,$this->precioventa,$this->lote,$this->cantidad,$this->factura,$this->fechaingreso,$this->fechavencimiento,$this->item_id,$this->usuario_id,$this->estado_id,$this->id));
            $mensaje['status']=true;            
            $mensaje['mensaje']='INGRESO EDITADO CON EXITO'; 
        }
              
      }else{
        $mensaje['status']=false;
        $mensaje['mensaje']='ERROR LA CANTIDAD NO PUEDE SER MENOR QUE LAS SALIDAS REGISTRADAS';       
      }
            
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function eliminarIngreso(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $sql = "SELECT IFNULL(SUM(CANTIDAD),0) SALIO FROM tasalida WHERE ESTADO_ID=1 AND INGRESO_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));
    $data = $q->fetch(PDO::FETCH_ASSOC);      
    if(intval($data['SALIO'])>0){
      $mensaje['status']=false;
      $mensaje['mensaje']='DICHO INGRESO YA REGISTRO SALIDAS ELIMINAR SALIDAS PRIMERO'; 
    }else{
      $sql = "UPDATE taingreso SET ESTADO_ID=2 WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->id));
      $mensaje['status']=true;
      $mensaje['mensaje']='INGRESO ELIMINADO CORRECTAMENTE'; 
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function formEditar(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT a.ID,a.NOMBRE,b.NOMBRE LABORATORIO,c.NOMBRE PROVEEDOR,d.NOMBRE PRESENTACION,e.NOMBRE CATEGORIA
      FROM taitem a 
      JOIN talaboratorio b on b.ID=a.LABORATORIO_ID
      JOIN taproveedor c on c.ID=a.PROVEEDOR_ID
      JOIN gnitempresentacion d on d.ID=a.PRESENTACION_ID
      JOIN gnitemcategoria e on e.ID=a.CATEGORIA_ID
      WHERE a.ESTADO_ID=1";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['items']=$data;

    $sql = "SELECT ID,NOMBRE FROM gnestados  order by 1";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['estados']=$data;

    $mensaje['status']=true;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function buscarIngresos($fecha,$inicio,$final,$item_id,$estados){       
    $cadena="WHERE ".$fecha." BETWEEN STR_TO_DATE('".$inicio." 00:00:00','%d/%m/%Y %H:%i:%s') "; 
    $cadena.="AND STR_TO_DATE('".$final." 23:59:59','%d/%m/%Y %H:%i:%s') ";    
    if(!empty($item_id)){
      $cadena.=" AND a.ITEM_ID=".$item_id;
    }
    $cadena.=" AND a.ESTADO_ID IN (".$estados.")";

    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,a.ITEM_ID,b.NOMBRE ITEM,a.LOTE,a.CANTIDAD,a.PRECIOCOMPRA,a.PRECIOVENTA,a.FACTURA,
      DATE_FORMAT(a.FECHAINGRESO,'%d/%m/%Y %H:%i') FECHAINGRESO,        
      DATE_FORMAT(a.FECHAVENCIMIENTO,'%d/%m/%Y') FECHAVENCIMIENTO,
      a.ESTADO_ID,c.NOMBRE ESTADO
      FROM taingreso a 
      JOIN taitem b on b.id=a.ITEM_ID
      JOIN gnestados c on c.ID=a.ESTADO_ID  ".$cadena;
    $mensaje['sql']=$item_id;  
    $q = $pdo->prepare($sql);
    $q->execute();
    $data = $q->fetchAll(PDO::FETCH_ASSOC);      
    $mensaje['status']=true;    
    $mensaje['data']=$data ;  
   
    baseDatos::desconectar();
    return $mensaje; 
  } 

  function ingresoSalida(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT ID,FECHA,PRECIOVENTA,CANTIDAD,
      ROUND(PRECIOVENTA*PRECIOVENTA,2) TOTAL, 
      DATE_FORMAT(FECHA,'%d/%m/%Y %H:%i') FECHASALIDA
      FROM tasalida
      WHERE INGRESO_ID=? AND ESTADO_ID=1
      ORDER BY 2";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id)); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                     
    $mensaje['status']=true;
    $mensaje['data']=$data;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function formItemInventarioSalida(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT a.ID,b.NOMBRE ITEM,a.FECHAVENCIMIENTO FECHA,c.NOMBRE LABORATORIO,d.NOMBRE PROVEEDOR,
     e.NOMBRE PRESENTACION,f.NOMBRE CATEGORIA,a.PRECIOVENTA,
     DATE_FORMAT(a.FECHAVENCIMIENTO, '%d/%m/%Y')FECHAVENCIMIENTO,
     (a.CANTIDAD-IFNULL(sum(g.CANTIDAD),0)) as SALDO
     FROM taingreso a 
     JOIN taitem b on b.ID=a.ITEM_ID
     JOIN talaboratorio c on c.ID=b.LABORATORIO_ID
     JOIN taproveedor d on d.ID=b.PROVEEDOR_ID
     JOIN gnitempresentacion e on e.ID=b.PRESENTACION_ID
     JOIN gnitemcategoria f on f.ID=b.CATEGORIA_ID
     LEFT JOIN tasalida g on g.INGRESO_ID=a.ID AND g.ESTADO_ID=1
     GROUP BY a.ID,b.NOMBRE,a.FECHAVENCIMIENTO,c.NOMBRE,d.NOMBRE,
     e.NOMBRE,f.NOMBRE,a.PRECIOVENTA,DATE_FORMAT(a.FECHAVENCIMIENTO, '%d/%m/%Y')
     HAVING SALDO>0
     ORDER BY 3 asc";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['ingresos']=$data;
    $mensaje['status']=true;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function inventarioStock($item_id,$proveedor_id,$presentacion_id,$categoria_id){    
    $cadena="";
    if(!empty($item_id)){
      $cadena.=" AND a.ITEM_ID=".$item_id;
    }
    if(!empty($proveedor_id)){
      $cadena.=" AND b.PROVEEDOR_ID=".$proveedor_id;
    }
    if(!empty($presentacion_id)){
      $cadena.=" AND b.PRESENTACION_ID=".$presentacion_id;
    }
    if(!empty($categoria_id)){
      $cadena.=" AND b.CATEGORIA_ID=".$categoria_id;
    }

    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT a.ID,b.NOMBRE ITEM,a.FECHAVENCIMIENTO FECHA,
      DATE_FORMAT(a.FECHAINGRESO,'%d/%m/%Y %H:%i') FECHAINGRESO,   
      DATE_FORMAT(a.FECHAVENCIMIENTO,'%d/%m/%Y')FECHAVENCIMIENTO,
      a.CANTIDAD ,
      (a.CANTIDAD-IFNULL(sum(c.CANTIDAD),0)) AS SALDO 
      from taingreso a 
      JOIN taitem b on b.ID=a.ITEM_ID
      LEFT JOIN tasalida c on c.INGRESO_ID=a.ID AND c.ESTADO_ID=1  
      WHERE a.ESTADO_ID=1 ".$cadena."
      GROUP BY a.ID,b.NOMBRE,a.FECHAVENCIMIENTO ,
      DATE_FORMAT(a.FECHAINGRESO,'%d/%m/%Y %H:%i') ,   
      DATE_FORMAT(a.FECHAVENCIMIENTO,'%d/%m/%Y'),
      a.CANTIDAD 
      HAVING SALDO>0
      ORDER BY 3 asc";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['stock']=$data;
    $mensaje['status']=true;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

}
?>