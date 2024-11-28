<?php

//Miramos si esta la sesion iniciada
	if(!isset($_SESSIONS)){
		session_start();	
	}

//Comprobamos si está logeado por si no lo está mandarlo a la página principal sin loguear
	if(!isset($_SESSION['usuarioLogeado'])){
		header("Location: index.php");
	}
	 
//requires necesarios para la carga de la pagina
require_once("gestionBD.php");
require_once("funciones.php");

//Creamos si no esta creada una sesión que contenga el formulario commpuesto por los distintos atributos pedidos (vacíos inicialmente)
	if(!isset($_SESSION["formularioCC"])){
		$formularioCC = array();
		$formularioCC["Contrasenya"] = "";
		$formularioCC["ContrasenyaNueva"] = "";
		$_SESSION["formularioCC"] = $formularioCC;
	}
					

?>



<!-- Html del formulario -->

<!DOCTYPE html>
<html lang="es">
		<title>Cambiar contraseña</title>

	<head>
		<link rel="stylesheet" href="css/CambiarContrasenya.css"/>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&amp;display=swap" rel="stylesheet">
	</head>
	
	<body background="images\webb.png">
		
		<?php 
		$oid_usario=$_SESSION['usuarioLogeado']['OID_USUARIO'];//Contenemos en una variable la sesión que contenga el oid del usuario
		cargaCabecera();//Añadimos la cabecera
		?>
		
		<div class="cajaC">
			<div class="caja_cambiosC">
				<form id="cambiarContrasenya" method="post" action="accionCambiarContrasenya.php">
					<fieldset class="camposC">
						<legend width: 1px; height: 5px; overflow: hidden; style="color:#FFFFFF;"><h2 class="tituloC">Cambiar contraseña</h2></legend>
						
						<!-- Zona de introducción de Contraseña actual -->
						<label>Contraseña Actual:</label><br/>
						<input class="Contr" type="password" id="contrasenya" name="contrasenya" minlength="8" maxlength="24" pattern="[a-zA-Z0-9-]+" placeholder="Introduce tu contraseña"  required/><br/>
						<?php
						if(!empty($_SESSION['errorCambiarContrasenia']['errorContra'])){
							$temp=$_SESSION['errorCambiarContrasenia']['errorContra'];
							echo "$temp";
						}
						
						//Si no es tu contraseña la puesta impedirá la aceptación del formulario y se le hará saber al sujeto
						?>	
						
						<!-- Zona de introducción de Contraseña nueva -->
						<label>Nueva Contraseña:</label><br/>
						<input class="Contr" type="password" id="contrasenyaNueva"  name="contrasenyaNueva" minlength="8" maxlength="24" pattern="[a-zA-Z0-9-]+" placeholder="Introduce tu nueva contraseña" required/><br/>
					<?php 
											if(!empty($_SESSION['errorCambiarContrasenia']['errorNuevaContra'])){
							$temp2=$_SESSION['errorCambiarContrasenia']['errorNuevaContra'];
							echo "$temp2";
						}
						
					//Si la nueva contraseña es igual a la anterior impedirá la aceptación del formulario y se le hará saber al sujeto
					?>
					</fieldset>
					
					<div>
						<input type="submit" value="cambiar contraseña" />
					</div>
				</form>
			</div>	
		</div>
		
		<?php include_once("footer/pie.php"); //Añadimos el pie de página
		unset($_SESSION["errorCambiarContrasenia"]); //Eliminamos los errores dados por el cambio de contraseña al refrescar la página
		?>
		
	</body>
	








	
	
	
