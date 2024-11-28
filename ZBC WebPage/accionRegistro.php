<?php 
	require_once("gestionBD.php");
	require_once("funciones.php");
	//Inicializa sesion
	if(!isset($_SESSION)){
		session_start();
	}
	//Si no existe el formulario registro para el registro
	
if(!isset($_SESSION["formularioRegistro"])){
header("Location: formulario_registro.php");        
}	

if(!isset($_SESSION['errorRegistro'])){
		$errorarr = array();
		$errorarr["ERROREMAIL"] = "";
		$errorarr["ERRORCONTRASENYA"] = "";
		$errorarr["ERRORCONTRASENYA2"] = "";
		$errorarr["ERRORNOMBRE"] = "";
		$errorarr["ERRORAPELLIDOS"] = "";	
		$errorarr["ERRORNICK"] = "";	
		$errorarr["ERRORFECHA"] = "";	
$_SESSION["errorRegistro"] = $errorarr;
}	
	

	
	
	
	//guardado de los datos del post en variables
	$nuevoUsuario=$_POST;
	$nick=$_POST['nick'];
	$contrasenya=$_POST['contrasenya'];
	$contrasenya2=$_POST['contrasenya2'];
	$nombre=$_POST['nombre'];
	$apellidos=$_POST['apellidos'];
	$fechaNacimiento=$_POST['fechaNacimiento'];
	$emailtemp=$_POST['email'];
	$email=strtolower($emailtemp);
	$telefono1=$_POST['telefono1'];
	$telefono2=$_POST['telefono2'];
	$telefono3=$_POST['telefono3'];
	//guardado de los datos en sesion
	
	$_SESSION['formularioRegistro']['nick']=$nick;
	$_SESSION['formularioRegistro']['contrasenya']=$contrasenya;
	$_SESSION['formularioRegistro']['contrasenya2']=$contrasenya2;
	$_SESSION['formularioRegistro']['nombre']=$nombre;
	$_SESSION['formularioRegistro']['apellidos']=$apellidos;
	$_SESSION['formularioRegistro']['fechaNacimiento']=$fechaNacimiento;
	$_SESSION['formularioRegistro']['email']=$email;
	$_SESSION['formularioRegistro']['telefono1']=$telefono1;
	$_SESSION['formularioRegistro']['telefono2']=$telefono2;
	$_SESSION['formularioRegistro']['telefono3']=$telefono3;
	
	
	
	

			//Comprobar que los datos comprueban las restricciones por php
			


	//Hacer inserción	
		
		
	validacionNuevoUsuario($nuevoUsuario);
			
	$check=true;
foreach ($_SESSION["errorRegistro"] as $key => $value) {
	if($value!=""){
		$check=false;
	}
	
}




		if($check==true){
			//Se crea el usuario 
			$conexion=crearConexionBD();
			
			
	try{
		
			$aux="SELECT * FROM USUARIOS WHERE EMAIL='$email'";
			$stmt=$conexion->prepare($aux);
			$stmt->execute();
			$resultado=$stmt->fetchAll();
			
			$select2="SELECT * FROM CLIENTES WHERE NICK='$nick'";
			$consultaaux=$conexion->prepare($select2);
			$consultaaux->execute();
			$resultado2=$consultaaux->fetchAll();
			
			if(empty($resultado)&&empty($resultado2)){
				if($fechaNacimiento=""){
					$fechaInsert='null';
				}else{
									$fechaInsert = date("d-m-Y", strtotime($_POST['fechaNacimiento']));
					
				}
		
				
				
				$aux2="INSERT INTO USUARIOS (email,contrasenya,nombre,apellidos,fechaNacimiento,activo) VALUES ('$email','$contrasenya','$nombre','$apellidos',to_date('$fechaInsert','DD-MM-YYYY'),'S')";
				$insert = $conexion -> prepare($aux2);
				$insert -> execute();	
				
				
				
				
				$aux3="SELECT OID_USUARIO FROM USUARIOS WHERE EMAIL='$email'";	
				$selectUsuario = $conexion -> prepare($aux3);
				$selectUsuario-> execute();
				$resultadoUsuario=$selectUsuario->fetchAll();
				$oidUsuario=$resultadoUsuario[0]['OID_USUARIO'];
				
				

				$aux4="INSERT INTO CLIENTES (OID_USUARIO,nick) VALUES ('$oidUsuario','$nick')";
				$insertCliente = $conexion -> prepare($aux4);
				$insertCliente-> execute();
			
				$aux5="SELECT OID_CLIENTE FROM CLIENTES WHERE NICK='$nick'";	
				$selectCliente= $conexion -> prepare($aux5);
				$selectCliente-> execute();
				$resultadoCliente=$selectCliente->fetchAll();
				$oidCliente=$resultadoCliente[0]['OID_CLIENTE'];

				
			if(!empty($telefono1)){
				$auxtel1="INSERT INTO TELEFONOS (OID_PROVEEDOR,OID_CLIENTE,PREFIJO,NUMERO) VALUES (null,$oidCliente,34,'$telefono1')";
				$insertTel1=$conexion -> prepare($auxtel1);	
				$insertTel1->execute();
			}
			if(!empty($telefono2)){	
				$auxtel2="INSERT INTO TELEFONOS (OID_PROVEEDOR,OID_CLIENTE,PREFIJO,NUMERO) VALUES (null,$oidCliente,34,'$telefono2')";
				$insertTel2=$conexion -> prepare($auxtel2);	
				$insertTel2->execute();		
			}
			if(!empty($telefono3)){	
				$auxtel3="INSERT INTO TELEFONOS (OID_PROVEEDOR,OID_CLIENTE,PREFIJO,NUMERO) VALUES (null,$oidCliente,34,'$telefono3')";
				$insertTel3=$conexion -> prepare($auxtel3);	
				$insertTel3->execute();
			}

				
				
				
				if(!isset($_SESSION['usuarioLogeado'])){
						$usuarioLogeado = array();
		$usuarioLogeado["nick"] = $nick;
		$usuarioLogeado["nombre"] = $nombre;
		$usuarioLogeado["OID_USUARIO"] = $oidUsuario;
		$usuarioLogeado["OID_CLIENTE"] = $oidCliente;		
		$_SESSION["usuarioLogeado"] = $usuarioLogeado;
					
					
				unset($_SESSION['formularioRegistro']);
				unset($_SESSION['errorRegistro']);
				
				if(isset($_SESSION["formularioLogin"])){
				unset($_SESSION["formularioLogin"]);
				}					
					
	}
				
				
				
				
			}else{
				if(!empty($resultado2)&&!empty($resultado)){
					$_SESSION["errorRegistro"]['ERRORNICK'] = "<p class=\"error\">Ese nick ya está en uso</p>";
					$_SESSION["errorRegistro"]['ERROREMAIL'] = "<p class=\"error\">Ese email ya está en uso</p>";
					
					
				}else if(!empty($resultado2)){
							$_SESSION["errorRegistro"]['ERRORNICK'] = "<p class=\"error\">Ese nick ya está en uso</p>";
					
				}else if(!empty($resultado)){
									$_SESSION["errorRegistro"]['ERROREMAIL'] = "<p class=\"error\">Ese email ya está en uso</p>";
					
				}
				cerrarConexionBD($conexion);	
				header("Location: formulario_registro.php");     				
						
			}

		}catch(PDOException $e){
		$_SESSION['errorConexionBD'] = $e->GetMessage();
		header("Location: error_conexionBD.php");
	}
			
			
			
			
			
				
			
			
			
				cerrarConexionBD($conexion);	
		}else{
//Si hay errores en los datos volvemos al formulario

header("Location: formulario_registro.php");        
}
		
		

		
		
		















			//funciones de validación del registro

			
function validacionNuevoUsuario($nuevoUsuario){

		
			if(!filter_var($nuevoUsuario['email'], FILTER_VALIDATE_EMAIL)){
				$_SESSION["errorRegistro"]['ERROREMAIL'] = "<p class=\"error\">El email es incorrecto</p>";
				
			}
		
		
		if(empty($nuevoUsuario['contrasenya'])) {
			$_SESSION["errorRegistro"]['ERRORCONTRASENYA'] = "<p class=\"error\">La contraseña debe tener entre 8 y 24 caracteres</p>";
		} else if ( strlen($nuevoUsuario['contrasenya']) < 8  or (strlen($nuevoUsuario['contrasenya']) > 24 )){
			$_SESSION["errorRegistro"]['ERRORCONTRASENYA'] = "<p class=\"error\">La contraseña debe tener entre 8y 24 caracteres</p>";
		} 
			
		if($nuevoUsuario['nombre'] == ""){
			$_SESSION["errorRegistro"]['ERRORNOMBRE']  = "<p class=\"error\">El nombre no puede estar vacío</p>";
		}else if(1 == preg_match('~[0-9]~', $nuevoUsuario['nombre'])){
			$_SESSION["errorRegistro"]['ERRORNOMBRE']  = "<p class=\"error\">El nombre no puede contener números</p>";
		}
		
		if(strcmp($nuevoUsuario['contrasenya'], $nuevoUsuario['contrasenya2'])!== 0){
				$_SESSION["errorRegistro"]['ERRORCONTRASENYA2'] = "<p class=\"error\" >Las contraseñas no son iguales</p>";
		}
		
		
		
		
		if($nuevoUsuario['apellidos'] == "") {
			$_SESSION["errorRegistro"]['ERRORAPELLIDOS'] = "<p class=\"error\">El apellido no puede estar vacío</p>";
		}else if(1 == preg_match('~[0-9]~', $nuevoUsuario['apellidos'])){
			$_SESSION["errorRegistro"]['ERRORAPELLIDOS'] = "<p class=\"error\">El apellido no puede contener números</p>";
		}
		
		validarFechaNacimiento($nuevoUsuario['fechaNacimiento']);
		
		
		if($nuevoUsuario['nickname'] = ""){
			$_SESSION["errorRegistro"]['ERRORNICK'] = "<p class=\"error\">El nickname no puede estar vacío</p>";
		}

	}
											
	
function validarFechaNacimiento($fechaNacimiento) {
	if($fechaNacimiento==""){
					$_SESSION["errorRegistro"]['ERRORFECHA'] = "<p class=\"error\">Debes introducir tu fecha de nacimiento</p>";
	}else{
		$fechaNacimientoFormat = strtotime($fechaNacimiento);
			if ($fechaNacimientoFormat > $fecha=date(strtotime("-18 years"))){
			$_SESSION["errorRegistro"]['ERRORFECHA'] = "<p class=\"error\">Debes ser mayor de edad para registrate</p>";

		}
						if ($fechaNacimientoFormat < $fecha=date(strtotime("-100 years"))){
			$_SESSION["errorRegistro"]['ERRORFECHA'] = "<p class=\"error\">Introduce una fecha válida</p>";

		}
			
			
	}}
?>




<!doctype html>

<html lang="es">
		<title>¡Bienvenido!</title>

<head>
  <meta charset="utf-8">
        	<link rel="stylesheet" href="css\accionRegistro.css" />



</head>

<body background="images\webb.png">
				<?php cargaCabecera();?>
				<?php include_once("nav/nav.php");?>

	<p class="Bienvenido">¡Bienvenido a ZettaByteComputers <?php echo"$nick"; ?>!</p>
	<main class="contenidoPrincipal">
	<p class="texto1">Estos son los datos con los que te has unido a nuestra web:</p>
	
	<?php
	$contador=0;						
	foreach ($nuevoUsuario as $key => $value) {
	if(empty($nuevoUsuario[$key])){
		
	}else{

		if($key=="contrasenya2"){
			
		}else{
					echo "<hr/>";
								echo"<div class=\"conjuntoClaveValor\">";								
					
			echo"<p class=\"clave\">$key: </p>";										
			echo"<p class=\"valor\">$value</p>";	
						echo"</div>";								
										
		}	
		}

	}
							echo "<hr/>";
	
	?>

	
		<button class="boton" onclick="window.location='index.php';">Para volver a la página principal pulsa aquí</button>

	</main>
	
		<?php include_once"footer/pie.php";?>

	
	
</body>
</html>

















