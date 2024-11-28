<?php 
	require_once("gestionBD.php");

	//Inicializa sesion
	if(!isset($_SESSION)){
		session_start();
	}
	
	//Si existe el formulario login
if(!isset($_SESSION["formularioLogin"])){
header("Location: formulario_login.php");        
}	

if(!isset($_SESSION['errorLogin'])){
		$errorlog = array();
		$errorlog["ERROREMAIL"] = "";
		$errorlog["ERRORCONTRASENYA"] = "";	
	
	
$_SESSION["errorLogin"] = $errorlog;
}	





//guardado de los datos del post en variables
	$nuevoLogin=$_POST;
	$emailtemp=$_POST['email'];
	$email=strtolower($emailtemp);
	$contrasenya=$_POST['contrasenya'];
	
//guardado de los datos en sesion
	
	$_SESSION['formularioLogin']['email']=$email;
	
	
//Comprobar que los datos cumplen las restricciones por php
			
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
						$_SESSION["errorLogin"]["ERROREMAIL"]="<p class=\"error\">El email introducido en es un email valido</p>";
								header("Location: formulario_login.php");     				
		
		
		
	}else{

	//Si los datos cumplen las restricciones php iniciamos la conexion a la BBDD y procedemos a seleccionar los datos
	
			$conexion=crearConexionBD();
			
			
	try{
		
		//Seleccionamos los datos de usuario donde el email escrito coincide con uno guardado en la BBDD
		
			$logeo="SELECT OID_USUARIO,CONTRASENYA,NOMBRE FROM USUARIOS WHERE EMAIL='$email'";
			$log=$conexion->prepare($logeo);
			$log->execute();
			$resultadoemail=$log->fetchAll();
			if(empty($resultadoemail)){
				$_SESSION["errorLogin"]["ERROREMAIL"]="<p class=\"error\">No hay cuenta vinculada a dicho email</p>";
								cerrarConexionBD($conexion);	
				
								header("Location: formulario_login.php");     				

     	//Si el email anterior no coincide, nos da error, si coincide procedemos a comprobar si la contraseña escrita es la asociada a dicho email en la BBDD

			}else{
				if($contrasenya!==$resultadoemail[0][1]){
									$_SESSION["errorLogin"]["ERRORCONTRASENYA"]="<p class=\"error\">La contraseña es incorrecta</p>";
									cerrarConexionBD($conexion);	
					
								header("Location: formulario_login.php");     
				}else{

				
				$oidUsuario=$resultadoemail[0][0];
				$nombre=$resultadoemail[0][2];
				
				
				$aux="SELECT OID_CLIENTE,NICK FROM CLIENTES WHERE OID_USUARIO='$oidUsuario'";
				$SelectClientes=$conexion->prepare($aux);
				$SelectClientes->execute();
				$datosCLiente=$SelectClientes->fetchAll();
				
				$oidCliente=$datosCLiente[0][0];
				$nick=$datosCLiente[0][1];
				
				$usuarioLogeado = array();
				$usuarioLogeado["nick"] = $nick;
				$usuarioLogeado["nombre"] = $nombre;
				$usuarioLogeado["OID_USUARIO"] = $oidUsuario;
				$usuarioLogeado["OID_CLIENTE"] = $oidCliente;
		
				$_SESSION["usuarioLogeado"] = $usuarioLogeado;
												unset($_SESSION["formularioLogin"]);
													unset($_SESSION["errorLogin"]);
													if(isset($_SESSION["formularioRegistro"])){
														unset($_SESSION["formularioRegistro"]);
													}
																cerrarConexionBD($conexion);	
								
								header("Location: index.php");     
				
								
				

								
				
				}
			}

			
			
			
			
	}catch(PDOException $e){
		$_SESSION['errorConexionBD'] = $e->GetMessage();
		header("Location: error_conexionBD.php");
	}

}		
			

			
			
?>
