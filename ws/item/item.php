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
  var $unidades;
  var $laboratorio_id;
  var $proveedor_id;    
  var $presentacion_id;
  var $categoria_id;
  var $fecha_creacion;
  var $usuario_id;
  var $estado_id;

  function formEditar(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
    $sql = "SELECT ID,NOMBRE FROM talaboratorio WHERE ESTADO_ID=1";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['laboratorios']=$data;

    $sql = "SELECT ID,NOMBRE FROM taproveedor WHERE ESTADO_ID=1";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['proveedores']=$data;

    $sql = "SELECT ID,NOMBRE FROM gnitempresentacion WHERE ESTADO_ID=1";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['presentaciones']=$data;

    $sql = "SELECT ID,NOMBRE FROM gnitemcategoria WHERE ESTADO_ID=1";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['categorias']=$data;

    $sql = "SELECT ID,NOMBRE FROM gnestados  order by 1";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['estados']=$data;

    $mensaje['status']=true;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function crearItem(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO taitem VALUES(default,?,?,?,?,?,?,?,now(),?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->codigo,$this->nombre,$this->unidades,$this->laboratorio_id,$this->proveedor_id,$this->presentacion_id,$this->categoria_id,$this->usuario_id,$this->estado_id));                  
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
      $sql = "UPDATE taitem 
        SET CODIGO=?,
        NOMBRE=?,        
        UNIDADES=?,
        LABORATORIO_ID=?,
        PROVEEDOR_ID=?,
        PRESENTACION_ID=?,
        CATEGORIA_ID=?,
        ESTADO_ID=?
        WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($this->codigo,$this->nombre,$this->unidades,$this->laboratorio_id,$this->proveedor_id,$this->presentacion_id,$this->categoria_id,$this->estado_id,$this->id)); 
      $mensaje['status']=true;
      $mensaje['mensaje']='ITEM EDITADO CON EXITO'; 
      
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function eliminarItem(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $sql = "UPDATE taitem SET ESTADO_ID=2 WHERE  ID=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($this->id));     
    $mensaje['status']=true;
    $mensaje['mensaje']='ITEM ELIMINADO CON EXITO';     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function buscarItems($columna,$valor,$estados){
    if(empty($valor)){
      $cadena="WHERE a.ESTADO_ID IN (".$estados.")";
    }else{
      $cadena="WHERE ".$columna." like '%".strtoupper($valor)."%' AND a.ESTADO_ID IN (".$estados.")";
    } 
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,a.CODIGO,a.NOMBRE,a.UNIDADES,a.PRESENTACION_ID,b.NOMBRE PRESENTACION,
      a.CATEGORIA_ID,c.NOMBRE CATEGORIA,a.LABORATORIO_ID,d.NOMBRE LABORATORIO,
      a.PROVEEDOR_ID,e.NOMBRE PROVEEDOR,a.ESTADO_ID,f.NOMBRE ESTADO
      FROM taitem a
      JOIN gnitempresentacion b on b.ID=a.PRESENTACION_ID        
      JOIN gnitemcategoria c on c.ID=a.CATEGORIA_ID
      JOIN talaboratorio d on d.ID=a.LABORATORIO_ID
      JOIN taproveedor e on e.ID=a.PROVEEDOR_ID
      JOIN gnestados f ON f.ID=a.ESTADO_ID ".$cadena;
    $q = $pdo->prepare($sql);
    $q->execute();
    $data = $q->fetchAll(PDO::FETCH_ASSOC);      
    $mensaje['status']=true;
    $mensaje['data']=$data ;  
    baseDatos::desconectar();
    return $mensaje; 
  } 

  /*TABLAS GENERICAS QUE PERMITEN CARGAR DATAS*/ 
  function formEditarPresentacion(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
    $sql = "SELECT ID,NOMBRE FROM gnestados  order by 1";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['estados']=$data;

    $mensaje['status']=true;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function crearItemPresentacion($nombre){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO gnitempresentacion VALUES(default,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($nombre,1));                  
      $mensaje['status']=true;
      $mensaje['mensaje']='ITEM TIPO REGISTRADO CON EXITO'; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function editaItemPresentacion($nombre,$estado_id,$id){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE gnitempresentacion 
        SET NOMBRE=?,        
        ESTADO_ID=?
        WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($nombre,$estado_id,$id));      
      $mensaje['status']=true;
      $mensaje['mensaje']='PRESENTACION EDITADA CON EXITO';       
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function buscarItemPresentaciones($valor,$estados){
    if(empty($valor)){
      $cadena="WHERE a.ESTADO_ID IN (".$estados.")";
    }else{
      $cadena="WHERE a.NOMBRE like '%".strtoupper($valor)."%' AND a.ESTADO_ID IN (".$estados.")";
    } 
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,a.NOMBRE,a.ESTADO_ID,b.NOMBRE ESTADO
      FROM gnitempresentacion a
      JOIN gnestados b on b.ID=a.ESTADO_ID ".$cadena;
    $q = $pdo->prepare($sql);
    $q->execute();
    $data = $q->fetchAll(PDO::FETCH_ASSOC);      
    $mensaje['status']=true;
    $mensaje['data']=$data ;  
    baseDatos::desconectar();
    return $mensaje; 
  } 

  /*CATEGORIA DE ITEMS*/
  function formEditarCategoria(){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
    $sql = "SELECT ID,NOMBRE FROM gnestados  order by 1";
    $q = $pdo->prepare($sql);
    $q->execute(); 
    $data = $q->fetchAll(PDO::FETCH_ASSOC);                         
    $mensaje['estados']=$data;

    $mensaje['status']=true;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function crearItemCategoria($nombre){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "INSERT INTO gnitemcategoria VALUES(default,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($nombre,1));                  
      $mensaje['status']=true;
      $mensaje['mensaje']='ITEM CATEGORIA REGISTRADO CON EXITO'; 
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function editarItemCategoria($nombre,$estado,$id){    
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {  
      $pdo->beginTransaction();
      $sql = "UPDATE gnitemcategoria 
        SET NOMBRE=?,        
        ESTADO_ID=?
        WHERE ID=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($nombre,$estado,$id));             
      $mensaje['status']=true;
      $mensaje['mensaje']='ITEM CATEGORIA EDITADO CON EXITO';       
      $pdo->commit();  
    }catch(PDOException $e) { 
      $mensaje['status']=false;
      $mensaje['mensaje']=$e->getMessage();
      $pdo->rollBack();
    }
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }

  function buscarItemCategorias($valor,$estados){
    if(empty($valor)){
      $cadena="WHERE a.ESTADO_ID IN (".$estados.")";
    }else{
      $cadena="WHERE a.NOMBRE like '%".strtoupper($valor)."%' AND a.ESTADO_ID IN (".$estados.")";
    } 
    $pdo = baseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.ID,a.NOMBRE,a.ESTADO_ID,b.NOMBRE ESTADO
      FROM gnitemcategoria a
      JOIN gnestados b on b.ID=a.ESTADO_ID ".$cadena;
    $q = $pdo->prepare($sql);
    $q->execute();
    $data = $q->fetchAll(PDO::FETCH_ASSOC);      
    $mensaje['status']=true;
    $mensaje['data']=$data ;  
    baseDatos::desconectar();
    return $mensaje; 
  } 
  /*PROVEENDO SERVICIOS*/
  function formItemIngreso(){    
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
    $mensaje['status']=true;     
    $pdo = baseDatos::desconectar();
    return $mensaje;  
  }
}
?>