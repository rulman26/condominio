<?php
require '../conn.php';

/**
 * Clase Usuario
 */

class item 
{
  var $id;  	
  var $codigo;
	var $nombre;
	var $preciocompra;
  var $precioventa;
  var $unidades;
  var $proveedor_id;    
  var $item_tipo_id;
  var $item_categoria_id;
  var $estado;

  function getItem()
  {
    $arrayItem=[];
    $arrayItem["id"]=$this->id;
    $arrayItem["codigo"]=$this->codigo;
    $arrayItem["nombre"]=$this->nombre;
    $arrayItem["preciocompra"]=$this->preciocompra;
    $arrayItem["precioventa"]=$this->precioventa;
    $arrayItem["unidades"]=$this->unidades;
    $arrayItem["proveedor_id"]=$this->proveedor_id;
    $arrayItem["item_tipo_id"]=$this->item_tipo_id;
    $arrayItem["item_categoria_id"]=$this->item_categoria_id;
    $arrayItem["estado"]=$this->estado;
    return $arrayItem;
  }

  function crearItem(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO ITEM VALUES(default,?,?,?,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->codigo,$this->nombre,$this->preciocompra,$this->precioventa,$this->unidades,$this->proveedor_id,$this->item_tipo_id,$this->item_categoria_id,$this->estado));                  
      $mensaje['estado']=true;
      $mensaje['mensaje']='ITEM REGISTRADO CON EXITO'; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function editarItem(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE ITEM 
        SET ITEM_CODIGO=?,
        ITEM_NOMBRE=?,
        ITEM_PRECIO_COMPRA=?,
        ITEM_PRECIO_VENTA=?,
        ITEM_UNIDADES=?,
        PROVEEDOR_ID=?,
        ITEM_TIPO_ID=?,
        ITEM_CATEGORIA_ID=?,
        ITEM_ESTADO=?
        WHERE ITEM_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->codigo,$this->nombre,$this->preciocompra,$this->precioventa,$this->unidades,$this->proveedor_id,$this->item_tipo_id,$this->item_categoria_id,$this->estado,$this->id)); 
      //Retornamoe el dato actualizado                  
      $mensaje['estado']=true;
      $mensaje['mensaje']='ITEM EDITADO CON EXITO'; 
      
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function leerItem(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT IT.ITEM_ID,IT.ITEM_CODIGO,IT.ITEM_NOMBRE,IT.ITEM_PRECIO_COMPRA,IT.ITEM_PRECIO_VENTA,
      IT.ITEM_GRAMOS,IT.ITEM_TIPO_ID,ITP.ITEM_TIPO_NOMBRE,IT.ITEM_CATEGORIA_ID,
      ITC.ITEM_CATEGORIA_NOMBRE,IT.ITEM_ESTADO
      FROM ITEM IT 
      JOIN PROVEEDOR PRO ON PRO.PROVEEDOR_ID=IT.PROVEEDOR_ID
      JOIN ITEM_TIPO ITP ON ITP.ITEM_TIPO_ID=IT.ITEM_TIPO_ID
      JOIN ITEM_CATEGORIA ITC ON ITC.ITEM_CATEGORIA_ID=IT.ITEM_CATEGORIA_ID 
      WHERE IT.ITEM_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id)); 
    $data = $q->fetch(PDO::FETCH_ASSOC);                     
    $mensaje['estado']=true;
    $mensaje['data']=$data;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function formItemRegistrar(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT PROVEEDOR_ID,PROVEEDOR_RUC,PROVEEDOR_NOMBRE FROM PROVEEDOR WHERE PROVEEDOR_ESTADO='ACTIVO'";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['proveedores']=$data;

    $sql = "SELECT ITEM_TIPO_ID,ITEM_TIPO_NOMBRE FROM ITEM_TIPO WHERE ITEM_TIPO_ESTADO='ACTIVO'";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['itemtipos']=$data;

    $sql = "SELECT ITEM_CATEGORIA_ID,ITEM_CATEGORIA_NOMBRE FROM ITEM_CATEGORIA WHERE ITEM_CATEGORIA_ESTADO='ACTIVO'";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['itemcategorias']=$data;

    $mensaje['estado']=true;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function formItemInventario(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT ITEM_ID,ITEM_NOMBRE FROM ITEM WHERE ITEM_ESTADO='ACTIVO'";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['items']=$data;
    $mensaje['estado']=true;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function eliminarItem(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "UPDATE ITEM SET ITEM_ESTADO='INACTIVO' WHERE  ITEM_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));     
    $mensaje['estado']=true;
    $mensaje['mensaje']='ITEM ELIMINADO CON EXITO';     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }
 
  function listaItems($filtro,$columna,$valor){
    $filtrosAceptados=['activos','inactivos','todos'];    
    if (in_array($filtro, $filtrosAceptados)) {
      
      if(empty($valor)){
        $cadena="";
      }else{
        if($columna=="ITEM_CODIGO" or $columna=="ITEM_NOMBRE"){          
          $cadena="WHERE UPPER(".$columna.") like '%".strtoupper($valor)."%'";
        }else{
          $cadena="WHERE ".$columna."=".$valor;
        }
      }
      if ($filtro=="activos") {        
        $cadena.=" AND IT.ITEM_ESTADO='ACTIVO'";
      }elseif($filtro=="inactivos"){
        $cadena.=" AND IT.ITEM_ESTADO='INACTIVO'";
      }else{
        $cadena.="";
      }
      $pdo = baseDatos::conectar();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT IT.ITEM_ID,IT.ITEM_CODIGO,IT.ITEM_NOMBRE,IT.ITEM_PRECIO_COMPRA,IT.ITEM_PRECIO_VENTA,
        IT.ITEM_UNIDADES,IT.PROVEEDOR_ID,PRO.PROVEEDOR_NOMBRE,
        IT.ITEM_TIPO_ID,ITP.ITEM_TIPO_NOMBRE,IT.ITEM_CATEGORIA_ID,
        ITC.ITEM_CATEGORIA_NOMBRE,IT.ITEM_ESTADO
        FROM ITEM IT 
        JOIN PROVEEDOR PRO ON PRO.PROVEEDOR_ID=IT.PROVEEDOR_ID
        JOIN ITEM_TIPO ITP ON ITP.ITEM_TIPO_ID=IT.ITEM_TIPO_ID
        JOIN ITEM_CATEGORIA ITC ON ITC.ITEM_CATEGORIA_ID=IT.ITEM_CATEGORIA_ID ".$cadena;        
      $q = $pdo->prepare($sql);
      $q->execute();
      $data = $q->fetchAll(PDO::FETCH_ASSOC);      
      $mensaje['estado']=true;
      $mensaje['data']=$data;
      $mensaje['sql']=$sql;   
    }else{
      $mensaje['estado']=false;
      $mensaje['mensaje']="SOLO ACEPTA (activos,inactivos y todos)";
    }
    baseDatos::desconectar();
    return $mensaje; 
  }

  /*TABLAS GENERICAS QUE PERMITEN CARGAR DATAS*/ 
  function crearItemTipo($nombre,$estado){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO ITEM_TIPO VALUES(default,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($nombre,$estado));                  
      $mensaje['estado']=true;
      $mensaje['mensaje']='ITEM TIPO REGISTRADO CON EXITO'; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function leerItemTipo($id){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT * FROM ITEM_TIPO WHERE ITEM_TIPO_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id)); 
    $data = $q->fetch(PDO::FETCH_ASSOC);                     
    $mensaje['estado']=true;
    $mensaje['data']=$data;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function editarItemTipo($nombre,$estado,$id){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE ITEM_TIPO 
        SET ITEM_TIPO_NOMBRE=?,        
        ITEM_TIPO_ESTADO=?
        WHERE ITEM_TIPO_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($nombre,$estado,$id)); 
      //Retornamoe el dato actualizado                  
      $data=$this->leerItemTipo($id);
      $mensaje['estado']=true;
      $mensaje['mensaje']='ITEM TIPO EDITADO CON EXITO'; 
      $mensaje['item']=$data; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function eliminarItemTipo($id){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "UPDATE ITEM_TIPO SET ITEM_TIPO_ESTADO='INACTIVO' WHERE  ITEM_TIPO_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));     
    $mensaje['estado']=true;
    $mensaje['mensaje']='ITEM TIPO ELIMINADO CON EXITO';     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function listaItemTipos($filtro){
    $filtrosAceptados=['activos','inactivos','todos'];
    if (in_array($filtro, $filtrosAceptados)) {
      if ($filtro=="activos") {
        $cadena="WHERE ITEM_TIPO_ESTADO='ACTIVO'";
      }elseif($filtro=="inactivos"){
        $cadena="WHERE ITEM_TIPO_ESTADO='INACTIVO'";
      }else{
        $cadena="";
      }
      $pdo = baseDatos::conectar();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT *FROM ITEM_TIPO ".$cadena;        
      $q = $pdo->prepare($sql);
      $q->execute();
      $data = $q->fetchAll(PDO::FETCH_ASSOC);      
      $mensaje['estado']=true;
      $mensaje['data']=$data;  
    }else{
      $mensaje['estado']=false;
      $mensaje['mensaje']="SOLO ACEPTA (activos,inactivos y todos)";
    }
    baseDatos::desconectar();
    return $mensaje; 
  }

  /*CATEGORIA DE ITEMS*/
  function crearItemCategoria($nombre,$estado){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO ITEM_CATEGORIA VALUES(default,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($nombre,$estado));                  
      $mensaje['estado']=true;
      $mensaje['mensaje']='ITEM CATEGORIA REGISTRADO CON EXITO'; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function leerItemCategoria($id){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "SELECT * FROM ITEM_CATEGORIA WHERE ITEM_CATEGORIA_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id)); 
    $data = $q->fetch(PDO::FETCH_ASSOC);                     
    $mensaje['estado']=true;
    $mensaje['data']=$data;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function editarItemCategoria($nombre,$estado,$id){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE ITEM_CATEGORIA 
        SET ITEM_CATEGORIA_NOMBRE=?,        
        ITEM_CATEGORIA_ESTADO=?
        WHERE ITEM_CATEGORIA_ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($nombre,$estado,$id)); 
      //Retornamoe el dato actualizado                  
      $data=$this->leerItemCategoria($id);
      $mensaje['estado']=true;
      $mensaje['mensaje']='ITEM CATEGORIA EDITADO CON EXITO'; 
      $mensaje['item']=$data; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['estado']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function eliminarItemCategoria($id){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "UPDATE ITEM_CATEGORIA SET ITEM_CATEGORIA_ESTADO='INACTIVO' WHERE  ITEM_CATEGORIA_ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));     
    $mensaje['estado']=true;
    $mensaje['mensaje']='ITEM CATEGORIA ELIMINADO CON EXITO';     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function listaItemCategorias($filtro){
    $filtrosAceptados=['activos','inactivos','todos'];
    if (in_array($filtro, $filtrosAceptados)) {
      if ($filtro=="activos") {
        $cadena="WHERE ITEM_CATEGORIA_ESTADO='ACTIVO'";
      }elseif($filtro=="inactivos"){
        $cadena="WHERE ITEM_CATEGORIA_ESTADO='INACTIVO'";
      }else{
        $cadena="";
      }
      $pdo = baseDatos::conectar();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT * FROM ITEM_CATEGORIA ".$cadena;        
      $q = $pdo->prepare($sql);
      $q->execute();
      $data = $q->fetchAll(PDO::FETCH_ASSOC);      
      $mensaje['estado']=true;
      $mensaje['data']=$data;  
    }else{
      $mensaje['estado']=false;
      $mensaje['mensaje']="SOLO ACEPTA (activos,inactivos y todos)";
    }
    baseDatos::desconectar();
    return $mensaje; 
  }
}
?>