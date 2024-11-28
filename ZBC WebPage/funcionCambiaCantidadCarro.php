<?php 
 		require_once("funciones.php");
	//Inicializamos sesion por si no lo esta

	if(!isset($_SESSIONS)){
		session_start();	
	}
	//Inicializamos carrito por si no lo esta
		inicializaCarrito();
		
		
		
		
//Si la cantidad del producto es 0 lo eliminamos del carrito		
		if($_GET['CANTIDAD']==0){
		unset($_SESSION['carrito'][$_GET['OID']]);	

		
			
		}else{
//En otro caso cambiamos su valor

		$_SESSION['carrito'][$_GET['OID']]=$_GET['CANTIDAD'];	

		}
		
		
?>