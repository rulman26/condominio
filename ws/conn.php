<?php
/**
 * Rulman Ferro
 */
header('Access-Control-Allow-Origin: *');  
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Lima');

class baseDatos 
{
	
	private static $cont  = null;
	public function __construct() {
		exit('Init function is not allowed');
	}	
	public static function conectar()
	{
	   // One connection through whole application
       if ( null == self::$cont )
       {      
	        try 
	        {
	  			self::$cont = new PDO("mysql:dbname=condominio;host=localhost;port=3306;charset=utf8mb4","root","root");
	        }
	        catch(PDOException $e) 
	        {
	          die($e->getMessage());  
	        }
       }  
       return self::$cont;
	}

	public static function desconectar()
	{
		self::$cont = null;
	}

	public static function  Valido($codigo,$token)
  {
    $pdo = BaseDatos::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM tausuario WHERE ID=? AND TOKEN=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($codigo,$token));
    $data= $q->fetchAll(PDO::FETCH_ASSOC);   
    BaseDatos::desconectar();
    if (!empty($data)) {
      return true;
    }else{
      return false;
    }
  }
}
  
?>