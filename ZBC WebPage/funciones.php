<?php 
function quitaFormularios(){
	//Elimina de sesion el formulario de login
	if(isset($_SESSION['formularioLogin'])){
		unset($_SESSION['formularioLogin']);
	}
	if(isset($_SESSION[''])){
				unset($_SESSION['formularioLogin']);
		
	}
}
function inicializaCarrito(){
	//Para inicializar el carrito
	if(!isset($_SESSION['carrito'])){
		

	$_SESSION["carrito"]=array();
	}
}

function inicializaTamPag(){
	//para paginacion
	if(!isset($_SESSION['tamPag'])){
	$_SESSION["tamPag"]=array(4);
	}
}



function cargaCabecera(){
	//Carga la cabecera correspondiente dependiendo de si el usuario esta logeado o no, lo mira en sesion - usuariologeado
	if(!isset($_SESSION['usuarioLogeado'])){
				 include_once("headers/header_nolog.php");
	}else{
		include_once("headers/header_log.php");
	}
	
}


?>
	















