<?php 
 		require_once("funciones.php");
		//INICIALIZAMOS SESION POR SI NO ESTA CARGADA Y EL CARRITO POR SI TAMPOCO

	if(!isset($_SESSIONS)){
		session_start();	
	}
		inicializaCarrito();
		
		
		
		//metemos en el carrito en la key oid el value cantidad que viene de un formulario get con ajax
		
		$_SESSION['carrito'][$_GET['OID']]=$_GET['CANTIDAD'];	

		
		
?>