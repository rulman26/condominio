<?php
/**
 * Rulman Ferro
 */
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
	  			self::$cont = new PDO("mysql:dbname=192.168.1.120;dbname=bdbotica;charset=utf8mb4","root","root");
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
}
  
?>