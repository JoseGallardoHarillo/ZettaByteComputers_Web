<?php

//Usamos la conexion de la gestionBD y las funciones

require_once("gestionBD.php");
require_once("funciones.php");
	if(!isset($_SESSIONS)){
		session_start();	
	}
	if(!isset($_SESSION['formularioCC'])){
		header("Location: index.php");
	}
	if(!isset($_SESSION['usuarioLogeado'])){
				header("Location: index.php");
	}

	
	//Variable en sesion para guardar los errores del formulario
	if(!isset($_SESSION['errorCambiarContrasenia'])){
		$errorCambiarContrasenia = array();
		
	}	
	
		//guarda en variables los datos del post o get depende del formulario
	
	$contrasenya=$_POST['contrasenya'];
	$contrasenyaN=$_POST['contrasenyaNueva'];
	$oid_usuario=$_SESSION['usuarioLogeado']['OID_USUARIO'];
	
		//guarda todos los datos del formulario en sesion
		
		$_SESSION['formularioCC']['contrasenyaNueva']=$contrasenyaN;
		$_SESSION['formularioCC']['contrasenya']=$contrasenya;
		
			
			$contrasenyaReal;

		try{
			
			
			//creamos la conexion a la BBDD y seleccionamos el dato a actualizar en este caso la contraseña que coincida con el OID_usuario 
			
			$conexion=crearConexionBD();
			
			$aux="SELECT CONTRASENYA FROM USUARIOS WHERE OID_USUARIO = '$oid_usuario'";
			$stmt=$conexion->prepare($aux);
			$stmt->execute();
			$resultado=$stmt->fetchAll();
			$contrasenyaReal=$resultado[0][0];
			
			
			cerrarConexionBD($conexion);
			
				}catch(PDOException $e){
		$_SESSION['errorConexionBD'] = $e->GetMessage();
		header("Location: error_conexionBD.php");
	}	

								
				
		//Funciones validación
		function validaNuevaContra($contrasenyaN,$contrasenyaReal){
		if($contrasenyaN==$contrasenyaReal){
			$_SESSION['errorCambiarContrasenia']['errorNuevaContra']="<p class=\"error\">La nueva contraseña no puede ser igual a la anterior</p>";
		}	
		}
		function validaContra($contrasenya,$contrasenyaReal){
				if($contrasenya!=$contrasenyaReal){
								$_SESSION['errorCambiarContrasenia']['errorContra']="<p class=\"error\">Tu contraseña no es esa, inténtalo otra vez.</p>";
					
				}	
		}


			validaNuevaContra($contrasenyaN,$contrasenyaReal);
			validaContra($contrasenya,$contrasenyaReal);
		if(empty($_SESSION['errorCambiarContrasenia'])){
			
			try{
						$conexion=crearConexionBD();
						$aux="UPDATE USUARIOS SET CONTRASENYA='$contrasenyaN' WHERE OID_USUARIO = '$oid_usuario'";
						$stmt=$conexion->prepare($aux);
						$stmt->execute();
			
			
			
			
			
				}catch(PDOException $e){
		$_SESSION['errorConexionBD'] = $e->GetMessage();
		header("Location: error_conexionBD.php");
	}	
			
			
	unset($_SESSION['errorCambiarContrasenia']);	
				unset($_SESSION['formularioCC']);	

		}else{
					header("Location: CambiarContrasenya.php");
			
		}



?>

<!doctype html>

<html lang="es">
<head>
  <meta charset="utf-8">

  <title>¡Contraseña cambiada!</title>


  <link rel="stylesheet" href="css/accionCambiarContrasenya.css">

</head>

<body background="images\webb.png">
					<?php cargaCabecera();?>
<main>
	<div class="contenedor">
	<p class="texto">¡Tu contraseña ha sido cambiada! Tu nueva contraseña es: <?php echo "$contrasenyaN";?></p>
	<p class="texto">Para volver a la página principal pulsa 
		<a class="boton" href="index.php" type="button">aquí</a>
		</p>
		</div>
	
					
</main>
	
	<?php include_once("footer/pie.php");?>
	
	

</body>
</html>
