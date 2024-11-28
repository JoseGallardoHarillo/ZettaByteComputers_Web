<?php 

//Miramos si esta la sesion iniciada

	if(!isset($_SESSIONS)){
		session_start();	
	}
	
//Comprobamos si está logeado ya para si no mandarlo directamente a la página principal logueado ya

		if(isset($_SESSION['usuarioLogeado'])){
		header("Location: index.php");
	}
		
//Creamos si no esta creada una sesión que contenga el formulario commpuesto por los distintos atributos pedidos (vacíos inicialmente)
 
	if(!isset($_SESSION["formularioLogin"])){
		$formularioLogin = array();
		$formularioLogin["email"] = "";
		$formularioLogin["contrasenya"] = "";
		
		$_SESSION["formularioLogin"] = $formularioLogin;
		

	}
	
//Comprobamos si está definido el formulario y si está logueado el usuario para si no borrar el formulario y mandar al usuario a la página principal logueado ya 

			if(isset($_SESSION['usuarioLogeado'])){
			if(isset($_SESSION["formularioLogin"])){
				unset($_SESSION["formularioLogin"]);
			}
		header("Location: index.php");
				
	}


?>

<!-- Html del formulario -->

<!DOCTYPE html>
<html lang="es">
	<title>Accede a tu cuenta</title>
	
	<head>
        <meta charset="UTF-8" >
        	        <link rel="stylesheet" href="css\login.css" />	


    </head>


	<body background="images\webb.png">
		
	<?php include_once("headers/header_nolog.php");  //Añadimos la cabecera  ?>


		
		<br>
		<div class="contenedorPrincipalLogin">
		<div class="transboxLogin">
			<form id="formularioLogin" name="formularioLogin" method="post" action="accionLogin.php">
				
				<fieldset class="login">
					<legend class="leyenda"><h2>Identifícate</h2></legend>
					
					<!-- Zona introducción Email -->
					<label>*Email</label>
					<input class="lineatexto" id="email" name="email" type="email" placeholder="Introduce tu E-mail"  pattern="[A-Za-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="<?php if(isset($_SESSION['formularioLogin']['email']))echo $_SESSION['formularioLogin']['email']; ?>" required/></br>
										
										<?php
					if(!empty($_SESSION["errorLogin"]['ERROREMAIL'])){
					$temp0=	$_SESSION["errorLogin"]['ERROREMAIL'];
					echo "$temp0";
					
					}
					//Si no es válido el email impedirá la aceptación del formulario y se le hará saber al sujeto
					?>
						
					<br>
					<!-- Zona introducción Contraseña -->
					<label>*Contraseña</label>
					<input class="lineatexto" id="contrasenya" name="contrasenya" type="password" minlength="8" maxlength="24" pattern="[a-zA-Z0-9-]+" placeholder="Introduce tu contraseña"  required/></br>		
										<?php
					if(!empty($_SESSION["errorLogin"]['ERRORCONTRASENYA'])){
					$temp1=	$_SESSION["errorLogin"]['ERRORCONTRASENYA'];
					echo "$temp1";
					}
					//Si la contraseña es incorrecta impedirá la aceptación del formulario y se le hará saber al sujeto
					?>				
				</fieldset>
				
				<!-- Botón para enviar el formulario de logueo del usuario -->
				
				<div id="envioFormulario">
				<br><br>
					<input class="enviar" style="color:#00f; background-color:#ddd; margin: right;" type="submit" value="Iniciar Sesión" />
				</div>
			</form>
		</div>		
		</div>
		<br>		
		
		
									<?php include_once("footer/pie.php"); //Añadimos el pie de página
									
									unset($_SESSION['errorLogin']); //Eliminamos los errores dados por logueo al refrescar la página
									?>



	</body>


</html>