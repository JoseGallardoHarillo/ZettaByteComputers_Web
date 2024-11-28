<?php
require_once("gestionBD.php");
require_once("funciones.php");

	if(!isset($_SESSIONS)){
		session_start();	
	}
	
	
	
	if(!isset($_SESSION['errorEditarUsuario'])){
		$_SESSION["errorEditarUsuario"] = array();
		
	}
		//guarda en variables los datos del post o get depende del formulario
	$nuevosDatos=$_POST;	
	$correotemp=$_POST['Correo'];
	$correo=strtolower($correotemp);
	$Telefono1=$_POST['Telefono1'];
	$Telefono2=$_POST['Telefono2'];
	$Telefono3=$_POST['Telefono3'];
	
	
	$oid_usario=$_SESSION['usuarioLogeado']['OID_USUARIO'];
	$oid_cliente=$_SESSION['usuarioLogeado']['OID_CLIENTE'];
	
	
	
	
		//guarda todos los datos del formulario en sesion
	

	$_SESSION['formulario']['CorreoElectronico']=$correo;
	$_SESSION['formulario']['Telefono1']=$Telefono1;
	$_SESSION['formulario']['Telefono2']=$Telefono2;
	$_SESSION['formulario']['Telefono3']=$Telefono3;
	

	
	
	
		//Comprobar que los datos comprueban las restricciones por php

		
		if(empty($Telefono1) and empty($Telefono2) and empty($Telefono3) and empty($correo)){
			cerrarConexionBD($conexion);
			header("Location: editar_usuario.php");
		}
		
		

		if(!empty($correo)){
			ValidarEmail($correo);
			ValidarEmail2($correo);
		}
		if(!empty($Telefono1)){
			ValidarTlf($Telefono1);
			ValidarTlf2($Telefono1);
		}
		if(!empty($Telefono2)){
			ValidarTlf($Telefono2);
			ValidarTlf2($Telefono2);
		}
		if(!empty($Telefono3)){
			ValidarTlf($Telefono3);
			ValidarTlf2($Telefono3);
		}
		if(!empty($Telefono1) and !empty($Telefono2)){
			if($Telefono1==$Telefono2){
				cerrarConexionBD($conexion);
				$_SESSION["errorEditarUsuario"]['ERRORTLFIGUAL'] = "<p class=\"error\">No puedes incluir telefonos iguales</p>";
			}
		}
		
		if(!empty($Telefono2) and !empty($Telefono3)){
			if($Telefono2==$Telefono3){
				cerrarConexionBD($conexion);
				$_SESSION["errorEditarUsuario"]['ERRORTLFIGUAL'] = "<p class=\"error\">No puedes incluir telefonos iguales</p>";
				
			}
		}
		
		if(!empty($Telefono1) and !empty($Telefono3)){
			if($Telefono1==$Telefono3){
				cerrarConexionBD($conexion);
				$_SESSION["errorEditarUsuario"]['ERRORTLFIGUAL'] = "<p class=\"error\">No puedes incluir telefonos iguales</p>";
				
			}
		}
		
		if(!empty($Telefono1) and !empty($Telefono2) and !empty($Telefono3)){
			if($Telefono1==$Telefono2 and $Telefono1==$Telefono3 and $Telefono2==$Telefono3){
				cerrarConexionBD($conexion);
				$_SESSION["errorEditarUsuario"]['ERRORTLFIGUAL'] = "<p class=\"error\">No puedes incluir telefonos iguales</p>";
				
			}
		}
		
		
		
			if(empty ($_SESSION["errorEditarUsuario"])){
				$conexion=crearConexionBD();
					try{
						if (!empty($correo)){						
							$aux="UPDATE USUARIOS SET EMAIL='$correo' WHERE OID_USUARIO='$oid_usario'";
							$stmt=$conexion->prepare($aux);
							$stmt-> execute();
						}else{	
						}
					}catch(PDOException $e){
							$_SESSION['errorConexionBD'] = $e->GetMessage();
			header("Location: error_conexionBD.php");	
					}
				cerrarConexionBD($conexion);
			}else{
						header("Location: editar_usuario.php");	
			}
			 		
	
		
		
		
		if(empty ($_SESSION["errorEditarUsuario"])){
			$conexion=crearConexionBD();
			try{
				
				$vald2="SELECT * FROM TELEFONOS WHERE OID_CLIENTE='$oid_cliente'";
				$stmt3=$conexion->prepare($vald2);
				$stmt3->execute();
				$resultado2=$stmt3->fetchAll();
				$cunt=count($resultado2);
				
				
				$num="SELECT OID_T FROM TELEFONOS WHERE OID_CLIENTE='$oid_cliente'";
				$stmt4=$conexion->prepare($num);
				$stmt4->execute();
				$resultadonum=$stmt4->fetchAll();
				
				
				//se actualiza el telefono 1 y se inserta los numeros de telefonos 2 y 3
				
				
				if (count($resultado2)==1){
							
						if(!empty($Telefono1)){
							$telefonoaux=$resultadonum[0][0];
							$auxup1="UPDATE TELEFONOS SET NUMERO='$Telefono1' WHERE OID_T='$telefonoaux'";
							$stmtup1=$conexion->prepare($auxup1);
							$stmtup1->execute();	
						}
									
						if(!empty($Telefono2)){
							
							$auxtel1="INSERT INTO TELEFONOS (OID_PROVEEDOR,OID_CLIENTE,PREFIJO,NUMERO) VALUES (null,$oid_cliente,34,'$Telefono2')";
							$insertTel1=$conexion -> prepare($auxtel1);	
							$insertTel1->execute();
						
						}
						
						if(!empty($Telefono3)){
	
							$auxtel23="INSERT INTO TELEFONOS (OID_PROVEEDOR,OID_CLIENTE,PREFIJO,NUMERO) VALUES (null,$oid_cliente,34,'$Telefono3')";
							$insertTel23=$conexion -> prepare($auxtel23);	
							$insertTel23->execute();
							
					    }
					    
					    }
							
						
					
					//se actualizan los telefonos 1 y 2 y se inserta el numero de telefono 3
					
				if (count($resultado2)==2){
					
					
					
					
						if(!empty($Telefono1)){
							$telefonoaux=$resultadonum[0][0];
							$auxup1="UPDATE TELEFONOS SET NUMERO='$Telefono1' WHERE OID_T='$telefonoaux'";
							$stmtup1=$conexion->prepare($auxup1);
							$stmtup1->execute();
								
						}
						
						if(!empty($Telefono2)){
							$telefonoaux2=$resultadonum[1][0];
							$auxup1="UPDATE TELEFONOS SET NUMERO='$Telefono2' WHERE OID_T='$telefonoaux2'";
							$stmtup1=$conexion->prepare($auxup1);
							$stmtup1->execute();
								
						}
						
						
						if(!empty($Telefono3)){
							
							$auxtel2="INSERT INTO TELEFONOS (OID_PROVEEDOR,OID_CLIENTE,PREFIJO,NUMERO) VALUES (null,$oid_cliente,34,'$Telefono3')";
							$insertTel2=$conexion -> prepare($auxtel2);	
							$insertTel2->execute();
						}
					}
				
				
				
				//se actualizan los telefonos 1 y 2 y 3
				
				
				if (count($resultado2)==3){
					if(!empty($Telefono1)){
							$telefonoaux=$resultadonum[0][0];
							$auxup1="UPDATE TELEFONOS SET NUMERO='$Telefono1' WHERE OID_T='$telefonoaux'";
							$stmtup1=$conexion->prepare($auxup1);
							$stmtup1->execute();
								
						}
						
						if(!empty($Telefono2)){
							$telefonoaux2=$resultadonum[1][0];
							$auxup1="UPDATE TELEFONOS SET NUMERO='$Telefono2' WHERE OID_T='$telefonoaux2'";
							$stmtup1=$conexion->prepare($auxup1);
							$stmtup1->execute();
								
						}
						
						
						if(!empty($Telefono3)){
							$telefonoaux3=$resultadonum[2][0];
							$auxup1="UPDATE TELEFONOS SET NUMERO='$Telefono3' WHERE OID_T='$telefonoaux3'";
							$stmtup1=$conexion->prepare($auxup1);
							$stmtup1->execute();
								
						}
				}
		     	 
			}catch(PDOException $e) {
							$_SESSION['errorConexionBD'] = $e->GetMessage();
			header("Location: error_conexionBD.php");
					}
				cerrarConexionBD($conexion);
					
			}else{
						header("Location: editar_usuario.php");
			}		
			
	//Funciones de validación
		
		

		function ValidarEmail($correo){
		if(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
				$_SESSION["errorEditarUsuario"]['ERROREMAIL'] = "<p class=\"error\">El email es incorrecto</p>";
				
				
			}
		}

		function ValidarTlf($tlf){
			if(!preg_match("/^\d{3}\d{3}\d{3}$/", $tlf)) {
  				$_SESSION["errorEditarUsuario"]['ERRORTLF'] = "<p class=\"error\">El teléfono es incorrecto</p>";
			}
				
		}
		
			
		
		function ValidarEmail2($correo){
			try{
				$conexion=crearConexionBD();
				$vald="SELECT * FROM USUARIOS WHERE EMAIL='$correo'";
				$stmt2=$conexion->prepare($vald);
				$stmt2->execute();
				$resultado=$stmt2->fetchAll();
				cerrarConexionBD($conexion);
				if(!empty($resultado)){
					$_SESSION["errorEditarUsuario"]['ERROREMAIL'] = "<p class=\"error\">El email ya está en uso</p>";
					
				}
				
		}catch(PDOException $e) {
							$_SESSION['errorConexionBD'] = $e->GetMessage();
			header("Location: error_conexionBD.php");
			cerrarConexionBD($conexion);
					}
			

		}
		
		function ValidarTlf2($tlf) {
			try{
				$conexion=crearConexionBD();
				$vald2="SELECT * FROM TELEFONOS WHERE NUMERO='$tlf'";
				$stmt3=$conexion->prepare($vald2);
				$stmt3->execute();
				$resultado4=$stmt3->fetchAll();
				cerrarConexionBD($conexion);
				if(!empty($resultado4)){
					$_SESSION["errorEditarUsuario"]['ERRORTLFUSO'] = "<p class=\"error\">El telefono ya está en uso</p>";
				}
				
				
			}catch(PDOException $e) {
							$_SESSION['errorConexionBD'] = $e->GetMessage();
			header("Location: error_conexionBD.php");
			cerrarConexionBD($conexion);
					}
			

		}
		
		
				
			
			
		

		

?>

<!DOCTYPE html>
<html lang="es">
		<title>¡Tus datos han sido actualizados!</title>

<head>
	<link rel="stylesheet" href="css\accionEditarUsuario.css"/>
</head>
<body background="images\webb.png">
	<?php cargaCabecera();?>
	<?php include_once("nav/nav.php");?>
	
	<p class="Bienvenido">¡Tus datos han sido actualizados!</p>
	<main class="contenidoPrincipal">
	<p class="texto1">Estos son tus nuevos datos:</p>
	
	<?php				
	foreach ($nuevosDatos as $key => $value) {
	if(!empty($nuevosDatos[$key])){
	
			echo "<hr/>";
			echo"<div class=\"conjuntoClaveValor\">";										
			echo"<p class\"clave\">$key: </p>";										
			echo"<p class\"valor\">$value</p>";	
						echo"</div>";								
										
		}	
		}

	
	?>
	<p class="texto2">Para volver a la página principal pulsa 
		<a class="boton" href="index.php" type="button">aquí</a>
		</p>
		

	</main>
	
		<?php include_once"footer/pie.php";?>
	
</body>
