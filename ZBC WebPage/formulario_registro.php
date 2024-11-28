<?php 
//requires necesarios para la carga de la pagina
		require_once("funciones.php");

//Miramos si esta la sesion iniciada
	if(!isset($_SESSIONS)){
		session_start();	
	}
	
//Creamos si no esta creada una sesión que contenga el formulario commpuesto por los distintos atributos pedidos (vacíos inicialmente)

	if(!isset($_SESSION["formularioRegistro"])){
		$formularioRegistro = array();
		$formularioRegistro["nick"] = "";
		$formularioRegistro["email"] = "";
		$formularioRegistro["contrasenya"] = "";
		$formularioRegistro["contrasenya2"] = "";
		$formularioRegistro["nombre"] = "";
		$formularioRegistro["apellidos"] = "";
		$formularioRegistro["fechaNacimiento"] = "";
		$formularioRegistro["telefono1"] = "";
		$formularioRegistro["telefono2"] = "";
		$formularioRegistro["telefono3"] = "";
		
		$_SESSION["formularioRegistro"] = $formularioRegistro;
		

	}
	
//Comprobamos si está definido el formulario y si está logueado el usuario para si no borrar el formulario y mandar al usuario a la página principal logueado ya 


			if(isset($_SESSION['usuarioLogeado'])){
			if(isset($_SESSION["formularioRegistro"])){
				unset($_SESSION["formularioRegistro"]);
			}
		header("Location: index.php");
				
	}



?>

<!-- Html del formulario -->

<!DOCTYPE html>
<html lang="es">
		<title>Registrate</title>

	<head>
        <meta charset="UTF-8" >
        	
        	<link rel="stylesheet" href="css\registroCSS.css" />

        	</head>


	<body background="images\webb.png">


								<?php cargaCabecera(); //Añadimos la cabecera ?>

		
		
			
		<br>
		<div class="contenedorPrincipal">
		<div class="transbox">
			<form id="formularioAltaUsuario" name="formularioAltaUsuario" method="post" action="accionRegistro.php" onsubmit="return checkFecha();">
				
				<fieldset class="registro">
					<legend width: 1px; height: 5px; overflow: hidden; style="color:#FFFFFF;"><h2>Información de usuario</h2></legend>
					
					<!-- Zona introducción Nick -->
					<label>*Nick</label>
					<input class="lineatexto"	 id="nick" name="nick" type="text" placeholder="A-Z a-z 0-9" maxlength="32" pattern="[A-Za-z0-9]+" value="<?php echo $_SESSION["formularioRegistro"]["nick"]; ?>" required/></br>
					<?php

					if(!empty($_SESSION["errorRegistro"]['ERRORNICK'])){
					$temp0=	$_SESSION["errorRegistro"]['ERRORNICK'];
					echo "$temp0";
					}
					//Si no rellenas el apartado de Nick impedirá la aceptación del formulario y se lo hará saber al sujeto
					?>
					<br>
					
					<!-- Zona introducción Contraseña -->
					<label>*Contraseña</label>
					<input class="lineatexto" id="contrasenya" name="contrasenya" type="password" placeholder="a-z A-Z 0-9" minlength="8" maxlength="24" pattern="[a-zA-Z0-9-]+" value="<?php echo $_SESSION["formularioRegistro"]["contrasenya"]; ?>" required/></br>
					<?php
					if(!empty($_SESSION["errorRegistro"]['ERRORCONTRASENYA'])){
					$temp6=	$_SESSION["errorRegistro"]['ERRORCONTRASENYA'];
					echo "$temp6";
					}
					
					//Si no tiene entre 8 y 24 carácteres impedirá la aceptación del formulario y se lo hará saber al sujeto
					?>	
					<br>
					
					<!-- Zona introducción Confirmación de la contraseña -->
					<label>*Confirmar contraseña</label>
					<input class="lineatexto" id="contrasenya2" name="contrasenya2" type="password" placeholder="Repite la contraseña" minlength="8" maxlength="24" pattern="[a-zA-Z0-9-]+" value="<?php echo $_SESSION["formularioRegistro"]["contrasenya2"]; ?>" required/></br>
					<?php
					
					if(!empty($_SESSION["errorRegistro"]['ERRORCONTRASENYA2'])){
					$temp5=	$_SESSION["errorRegistro"]['ERRORCONTRASENYA2'];
					echo "$temp5";
					}
					
					//Si no es igual a la contraseña puesta anteriormente impedirá la aceptación del formulario y se lo hará saber al sujeto
					?>	
					<br>
					
					<!-- Zona introducción Nombre -->
					<label>*Nombre </label>
					<input class="lineatexto" id="nombre" name="nombre" type="text" placeholder="Escriba su nombre" pattern="[a-zA-Z][a-zA-Z0-9\s]*" maxlength="32" value="<?php echo $_SESSION["formularioRegistro"]["nombre"]; ?>" required/></br>
					<?php
					if(!empty($_SESSION["errorRegistro"]['ERRORNOMBRE'])){
					$temp4=	$_SESSION["errorRegistro"]['ERRORNOMBRE'];
					echo "$temp4";
					}
					
					//Si no rellenas el apartado de Nombre impedirá la aceptación del formulario y se lo hará saber al sujeto
					?>	
					<br>
					
					<!-- Zona introducción Apellidos -->
					<label>*Apellidos </label>
					<input class="lineatexto" id="apellidos" name="apellidos" type="text" placeholder="Escriba sus apellidos" pattern="[a-zA-Z][a-zA-Z0-9\s]*" maxlength="64" value="<?php echo $_SESSION["formularioRegistro"]["apellidos"]; ?>" required/></br>
					<?php
					
					if(!empty($_SESSION["errorRegistro"]['ERRORAPELLIDOS'])){
					$temp3=	$_SESSION["errorRegistro"]['ERRORAPELLIDOS'];
					echo "$temp3";
					}
					
					//Si no rellenas el apartado de Apellidos impedirá la aceptación del formulario y se lo hará saber al sujeto
					?>	
					<br>
					
					<!-- Zona introducción Fecha de nacimiento -->
					<label>Fecha de nacimiento </label>
					<input class="lineatexto" id="fechaNacimiento" name="fechaNacimiento" type="date"  value="<?php echo $_SESSION["formularioRegistro"]["fechaNacimiento"]; ?>" ></br>
					<?php
					
					if(!empty($_SESSION["errorRegistro"]['ERRORFECHA'])){
					$temp2=	$_SESSION["errorRegistro"]['ERRORFECHA'];
					echo "$temp2";
					}
					
					//Si no eres mayor de edad o dejas vacio el apartado de Fecha de nacimiento impedirá la aceptación del formulario y se lo hará saber al sujeto
					?>	
					<br>
					
					<!-- Zona introducción email -->
					<label>*Email</label>
					<input class="lineatexto" id="email" name="email" type="email"  pattern="[A-Za-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" placeholder="Introduce tu E-mail" maxlength="64" value="<?php echo $_SESSION["formularioRegistro"]["email"]; ?>" required/></br>
					<?php
					if(!empty($_SESSION["errorRegistro"]['ERROREMAIL'])){
					$temp1=	$_SESSION["errorRegistro"]['ERROREMAIL'];
					echo "$temp1";
					}
					
					//Si no es válido el email impedirá la aceptación del formulario y se lo hará saber al sujeto
					?>	
					<br>
					
					<!-- Zona introducción Teléfono 1 -->
					<label>*Telefono 1</label>
					<input class="lineatexto" id="telefono1" name="telefono1" type="tel" min="0" max="999999999" pattern="^\d{3}\d{3}\d{3}$" placeholder="Introduzca su telefono" value="<?php echo $_SESSION["formularioRegistro"]["telefono1"]; ?>" required/></br>
					<?php?>	
					<br>
					<label>Telefono 2</label> 
					<input class="lineatexto" id="telefono2" name="telefono2" type="tel" min="0" max="999999999" pattern="^\d{3}\d{3}\d{3}$" placeholder="Introduzca su telefono" value="<?php echo $_SESSION["formularioRegistro"]["telefono2"]; ?>" ></br>
					<?php?>	
					<br>
					<label>Telefono 3</label>
					<input class="lineatexto" id="telefono3" name="telefono3" type="tel" min="0" max="999999999" pattern="^\d{3}\d{3}\d{3}$" placeholder="Introduzca su telefono" value="<?php echo $_SESSION["formularioRegistro"]["telefono3"]; ?>" ></br>
					<?php?>	

				</fieldset>
				
				<!-- Botón para enviar el formulario para registrar al nuevo usuario -->
				<div id="envioFormulario">
				<br><br>
					<input class="envio" style="color:#00f; background-color:#ddd; margin: right;" type="submit" value="Registrarse" />
				</div>
			</form>
		</div>		
		</div>
		<br>

									<?php include_once("footer/pie.php"); //Añadimos el pie de página
									
									unset($_SESSION['errorRegistro']); //Eliminamos los errores dados por registro al refrescar la página
									?>


				
	</body>


</html>

<script>
function checkFecha(){
	var res=true;
	
	
	
	var fecha = document.getElementById("fechaNacimiento").value;
	var fechabien= new Date(fecha);
	var today = new Date();
	var yold = fechabien.getFullYear();
	var ynew = today.getFullYear();
	var mold = fechabien.getMonth();
	var mnew = today.getMonth();
	var dold = fechabien.getDate();
	var dnew = today.getDate();


	var diferenciaEnAnos=ynew-yold;
	if(diferenciaEnAnos==18){


		if((mnew>=mold)){
			if(dnew>=dold){
			}else{
						res=false;
			alert("Debes de ser mayor de edad para registrarte en la página");

			}
		}else{
					res=false;
			alert("Debes de ser mayor de edad para registrarte en la página");

			
			
		}
		
		
	}



	if(diferenciaEnAnos<18){
		res=false;
		
			alert("Debes de ser mayor de edad para registrarte en la página");
	}
	if(diferenciaEnAnos>100){
		res=false;
		
			alert("Pon una fecha válida");
	}
	
	return res;
}


</script>
