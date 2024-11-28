<?php
   	require_once("gestionBD.php");
	require_once("funciones.php");

//iniciamos la sesion

	if(!isset($_SESSIONS)){
		session_start();	
	}
	
	//si en sesion no esta el usuario logeado volvemos a la pagina principal
	if(!isset($_SESSION['usuarioLogeado'])){
		header("Location: index.php");
	}
	
	$oid_usuario=$_SESSION['usuarioLogeado']['OID_USUARIO'];
	
	//procedemos a crear la conexion con la BBDD y a borrar el usuario
	$conexion=crearConexionBD();
	try{
			$aux="DELETE FROM USUARIOS WHERE OID_USUARIO='$oid_usuario'";
			$eliminar=$conexion->prepare($aux);
			$eliminar->execute();
		     	 
			}catch(PDOException $e) {
							$_SESSION['errorConexionBD'] = $e->GetMessage();
			header("Location: error_conexionBD.php");
					}
			
			
	unset($_SESSION['usuarioLogeado']);
	header("Location: index.php");
?>