<?php
require_once('funciones.php');
	if(!isset($_SESSION)){
		session_start();
	}
	if(isset($_SESSION['usuarioLogeado'])){
				unset($_SESSION['usuarioLogeado']);
		}
	if(isset($_SESSION['carrito'])){
				unset($_SESSION['carrito']);
			inicializaCarrito();
	}
	if(isset($_SESSION['formularioCompra'])){
			unset($_SESSION['formularioCompra']);
	}
	if(isset($_SESSION['formularioEditarUsuario'])){
		unset($_SESSION['formularioEditarUsuario']);
	}

		if(isset($_SESSION['formularioLogin'])){
			unset($_SESSION['formularioLogin']);
	}
			if(isset($_SESSION['formularioRegistro'])){
			unset($_SESSION['formularioRegistro']);
	}
	
	
	
header('Location: index.php')


//Al hacer logout lo que se hace es vaciar todos los formularios que puedan estar cargados, de forma que si te metes en ellos de nuevo no hay ningún dato de la
//persona anterior, tambien se hace unset al carrito y se inicializa, y se hace unset a usuario logeado.

?>