<?php

//Miramos si esta la sesion iniciada
	if(!isset($_SESSIONS)){
		session_start();	
	}
	if(!isset($_SESSION['usuarioLogeado'])){
		header("Location: index.php");
	}
	
//requires necesarios para la carga de la pagina
require_once("gestionBD.php");
require_once"funciones.php";

//Creamos si no esta creada una sesión que contenga el formulario commpuesto por los distintos atributos pedidos (vacíos inicialmente)
	if(!isset($_SESSION["formularioEditarUsuario"])){
		$formulario = array();
		$formulario["CorreoElectronico"] = "";
		$formulario["Telefono1"] = "";
		$formulario["Telefono2"] = "";
		$formulario["Telefono3"] = "";

		
		$_SESSION["formularioEditarUsuario"] = $formulario;
		

	}
					

//Accedemos a la base de datos
$conexion=crearConexionBD();

	try{
		$oidUsuario=$_SESSION['usuarioLogeado']['OID_USUARIO'];
	$aux="SELECT EMAIL FROM USUARIOS WHERE OID_USUARIO='$oidUsuario'";
						$stmt=$conexion->prepare($aux);
						$stmt->execute();
						$resultado=$stmt->fetchAll();
						$emailMostrar=$resultado[0][0];
		
		$oidCliente=$_SESSION['usuarioLogeado']['OID_CLIENTE'];
			$aux2="SELECT NUMERO FROM TELEFONOS WHERE OID_CLIENTE='$oidCliente'";
		
						$stmt1=$conexion->prepare($aux2);
						$stmt1->execute();
						$resultadoTelefono=$stmt1->fetchAll();
						
						$telefono1Mostrar="";
						$telefono2Mostrar="";
						$telefono3Mostrar="";
						if(empty($resultadoTelefono[0][0])){
						}else{
							$telefono1Mostrar=$resultadoTelefono[0][0];
						}
							
						if(empty($resultadoTelefono[1][0])){
						}else{
							$telefono2Mostrar=$resultadoTelefono[1][0];
						}
						if(empty($resultadoTelefono[2][0])){
						}else{
							$telefono3Mostrar=$resultadoTelefono[2][0];
						}						
	
		
		
	}catch(PDOException $e){
		$_SESSION['errorConexionBD'] = $e->GetMessage();
		header("Location: error_conexionBD.php");
	}//Si hay un error en la base de datos la página llevará al usuario a una página que indique que un error de BD ha ocurrido
							

?>


<!-- Html del formulario -->

<!DOCTYPE html>
<html lang="es">
		<title>Editar tus datos</title>

	<head>
		<link rel="stylesheet" href="css/editar.css"/>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&amp;display=swap" rel="stylesheet">
	</head>
	
	<body background="images\webb.png">
		
		<?php 
		
		cargaCabecera();//Añadimos la cabecera
	
		//print_r($_SESSION);
		
		?>
		
		<div class="caja">
			<div class="caja_cambios">
				<form id="editarUsuario" method="post" action="accionEditarUsuario.php">
					<fieldset class="campos">
						<legend width: 1px; height: 5px; overflow: hidden; style="color:#FFFFFF;"><h2 class="titulo">Datos del usuario</h2></legend>
						
						<!-- Zona de introducción de Correo electrónico -->
						<label for="correo">Correo electrónico:</label><br/>
						<input class="texto" type="text" id="Correo" pattern="[A-Za-z0-9._%+-]+@[a-z0-9.-]+.[a-z]{2,}$" placeholder="<?php echo"$emailMostrar";?>" name="Correo"/><br/>
						<?php
						if(!empty($_SESSION["errorEditarUsuario"]['ERROREMAIL'])){
							$temp=$_SESSION["errorEditarUsuario"]['ERROREMAIL'];
							echo "$temp";
						}
						
						//Si el email ya está en uso o es incorrecto impedirá la aceptación del formulario y se lo hará saber al sujeto
						?>
						
						<!-- Zona de introducción de Teléfono 1 -->
						<label for="tlf1">Teléfono 1:</label><br/>
						<input class="texto" type="tel" min="0"max="999999999" placeholder="<?php echo"$telefono1Mostrar";?>" pattern="^\d{3}\d{3}\d{3}$" id="Telefono1" name="Telefono1"/><br/>
						
						<!-- Zona de introducción de Teléfono 2 -->
						<label for="tlf1">Teléfono 2:</label><br/>
						<input class="texto" type="tel" min="0"max="999999999" placeholder="<?php echo"$telefono2Mostrar";?>" pattern="^\d{3}\d{3}\d{3}$" id="Telefono2" name="Telefono2"/><br/>
						
						<!-- Zona de introducción de Teléfono 3 -->
						<label for="tlf1">Teléfono 3:</label><br/>
						<input class="texto" type="tel" min="0"max="999999999" placeholder="<?php echo"$telefono3Mostrar";?>" pattern="^\d{3}\d{3}\d{3}$" id="Telefono3" name="Telefono3"/><br/>
						<?php
						if(!empty($_SESSION["errorEditarUsuario"]['ERRORTLFUSO'])){
							$temp2=$_SESSION["errorEditarUsuario"]['ERRORTLFUSO'];
							echo "$temp2";
						}
						
						//Si algún teléfono es incorrecto impedirá la aceptación del formulario y se le hará saber al sujeto
						if(!empty($_SESSION["errorEditarUsuario"]['ERRORTLFIGUAL'])){
							$temp2=$_SESSION["errorEditarUsuario"]['ERRORTLFIGUAL'];
							echo "$temp2";
						}
						
						//Si algún teléfono está ya en uso impedirá la aceptación del formulario y se le hará saber al sujeto
						?>
						<input class="enviar" type="submit" value="Guardar cambios"/>
					</fieldset>
				</form >
				
			</div>	
		</div>
		
		<?php include_once("footer/pie.php"); //Añadimos el pie de página
		unset($_SESSION["errorEditarUsuario"]); //Eliminamos los errores dados por el editar usuario al refrescar la página
		?>
		
	</body>
