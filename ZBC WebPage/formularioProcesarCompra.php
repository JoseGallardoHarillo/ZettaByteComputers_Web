<?php
//requires necesarios para la carga de la pagina
	require_once("funciones.php");
	require_once("gestionBD.php");
	
//Miramos si esta la sesion iniciada
		if(!isset($_SESSIONS)){
		session_start();	
	}
	
//Comprobamos si el usuario está logeado para si no impedir la compra
 
	if(!isset($_SESSION['usuarioLogeado'])){
		$_SESSION['errorCompraNoLog']['error']="Para procesar la compra, por favor, inicie sesión";
		header("Location: carrito.php");
	}
	
//Comprobamos si el carrito está vacío

	if(empty($_SESSION['carrito'])){
		$_SESSION['errorCompraNoLog']['error']="¡No puedes procesar tu compra con el carrito vacío!";
		header("Location: carrito.php");
	}
	
//Creamos si no esta creada una sesión que contenga el formulario commpuesto por los distintos atributos pedidos (vacíos inicialmente)
	
		if(!isset($_SESSION["formularioCompra"])){
		$formularioCompra = array();
		$formularioCompra["direccion"] = "";
		$formularioCompra["cp"] = "";
		$formularioCompra["provincia"] = "";
		$formularioCompra["Nombre"]="";

		
		
		$_SESSION["formularioCompra"] = $formularioCompra;
		

	}	
		
		
		
		
?>

<!-- Html del formulario -->

<!DOCTYPE html>
<html>
	<title>Procesa tu compra</title>
	<head>
		        <meta charset="UTF-8" >
				        	<link rel="stylesheet" href="css\formularioProcesarCompra.css" />

	</head>
<body background="images\webb.png">
				<?php cargaCabecera();  //Añadimos la cabecera?> 
				<main class="main">
					
							<div class="contenedorPrincipalLogin">
		<div class="transboxLogin">
	<form id="formularioCompra" name="formularioCompra" method="post"  action="accionCompra.php"  onsubmit="return validarFormulario();">
				
				<fieldset class="login">
					<legend class="leyenda"><h2>Datos envio</h2></legend>
					
					<!-- Zona de introducción Dirección-->
					<label>*Dirección</label>
					<input class="lineatexto" id="direccion" maxlength="64"
					
					<?php 
					if(!empty($_SESSION['formularioCompra']["direccion"])){
						$valor=$_SESSION['formularioCompra']["direccion"];
						echo "value=\"$valor\"";
					}
					
					//Si está relleno el apartado de dirección imprimimos la dirección
					?>
					
					
					 name="direccion" type="text" placeholder="Introduce tu Dirección" pattern="[a-zA-Z0-9- ]+" required/></br>

					<br>
										<label class="texto">*Provincia</label>
							<select class="selectCompra"  name="provincia" id="provincia"  required>
								
								<?php
								
								//Accedemos a la base de datos
									try{
											$conexion=crearConexionBD();
										
							$aux="SELECT NOMBRE FROM PROVINCIAS";
							$stmt=$conexion->prepare($aux);
							$stmt->execute();
							$resultado=$stmt->fetchAll();	
							foreach ($resultado as $key => $value) {
							$texto=$value[0];
							$paramostrar= substr($texto,2);
							echo "<option value=\"$texto\" ";
							if($_SESSION['formularioCompra']['provincia']=="$texto") echo "selected";
							echo " >$paramostrar</option>";			
							}
								
								
							cerrarConexionBD($conexion); //Fin del acceso
							}
							catch(PDOException $e){
							$_SESSION['errorConexionBD'] = $e->GetMessage();
							header("Location: error_conexionBD.php");
							} //Si hay un error en la base de datos la página llevará al usuario a una página que indique que un error de BD ha ocurrido
							?>
							</select>
							
							<br />
							<br />
					
					<!-- Zona de introducción Correo Postal -->
					<label>*Código Postal</label>
					<input class="lineatexto" id="cp" name="cp"
										<?php 
					if(!empty($_SESSION['formularioCompra']["cp"])){
						$valor=$_SESSION['formularioCompra']["cp"];
						echo "value=\"$valor\"";
					}
					
					//Si has usado ya un Código postal anteriormente podrás seleccionarlo sin escribirlo
					?>
					
					
					 type="text" placeholder="Introduce tu código postal"  minlength=5 maxlength=5 pattern="[0-9]+"  required/></br>
					<?php
					if(!empty($_SESSION['errorCompra']['ERRORCP'])){
					$temp6=	$_SESSION['errorCompra']['ERRORCP'];
					echo "$temp6";
					
					}
					
					//Si el código postal no concuerda con la localidad impedirá la aceptación del formulario y se lo hará saber al sujeto
					?>			
					



				</fieldset>
				
					<fieldset class="login">
						
					<legend class="leyenda"><h2>Datos bancarios</h2></legend>
					
					
					<!-- Zona de introducción de Nombre en la targeta -->
					<label>*Nombre en la tarjeta</label>
					<input class="lineatexto" id="nombre" name="nombre" maxlength="32"
															<?php 
					if(!empty($_SESSION['formularioCompra']["Nombre"])){
						$valor=$_SESSION['formularioCompra']["Nombre"];
						echo "value=\"$valor\"";
					}
					
					//Si has usado ya un Nombre de tarjeta anteriormente podrás seleccionarlo sin escribirlo
					?>
					
					 type="text" placeholder="Introduce tu nombre" pattern="[a-zA-Z ]+" required/></br>
					
					<!-- Zona de introducción de Número de tarjeta -->
					<label>*Número de tarjeta</label>
					<input class="lineatexto" id="NumTarjeta" name="NumTarjeta" type="text" placeholder="Introduce tu tarjeta" maxlength="16" required/></br>
															<?php
					if(!empty($_SESSION['errorCompra']['ERRORTARJETA'])){
					$temp8=	$_SESSION['errorCompra']['ERRORTARJETA'];
					echo "$temp8";
					
					}
					
					// Si el Número de tarjeta es incorrecto impedirá la aceptación del formulario y se lo hará saber al sujeto
					?>	
					
					<!-- Zona de introducción de CVV -->
					<label>*CVV</label>
					<input class="lineatexto" id="cvvNumber" name="cvvNumber" type="text" placeholder="Introduce el CVV" pattern="[0-9]+" minlength="3" maxlength="4" required/></br>
					<label>*Fecha de caducidad de la tarjeta</label>
					<input class="lineatexto" id="fechaCad" name="fechaCad" type="date" required/></br>
										<?php
					if(!empty($_SESSION['errorCompra']['ERRORFECHA'])){
					$temp7=	$_SESSION['errorCompra']['ERRORFECHA'];
					echo "$temp7";
					
					}
					
					//Si la tarjeta de crédito está caducada impedirá la aceptación del formulario y se lo hará saber al sujeto
					?>	
				</fieldset>
					
					<input class="enviar" style="color:#00f; background-color:#ddd; margin: right;" type="submit" value="Procesar Compra" />
					<!-- Botón para enviar el formulario para realizar la compra -->
					
</form>
</div>
</div>
							

							

					
					
				</main>
				<?php include_once("footer/pie.php"); //Añadimos el pie de página?>




</body>
</html>
<script>

//Función de validación por Java script

function validarFormulario(){
	 var num=document.getElementById("NumTarjeta").value;
	 var fecha=document.getElementById("fechaCad").value;

  if(valid_credit_card(num)==false){ 
    alert("El número de tarjeta introducido no es válido");
    return false;
  }
  
	if(validaFechaCaducidad(fecha)==false){
	    alert("La tarjeta está caducada");
	    return false;

	}
	

  return true;
}



function valid_credit_card(value) {
	if (/[^0-9-\s]+/.test(value)) return false;

	let nCheck = 0, bEven = false;
	value = value.replace(/\D/g, "");

	for (var n = value.length - 1; n >= 0; n--) {
		var cDigit = value.charAt(n),
			  nDigit = parseInt(cDigit, 10);

		if (bEven && (nDigit *= 2) > 9) nDigit -= 9;

		nCheck += nDigit;
		bEven = !bEven;
	}

	return (nCheck % 10) == 0;

}
function validaFechaCaducidad(fecha){
	var result=true;
	var fechaParseada= Date.parse(fecha);
	if(fechaParseada<Date.now()){
		result=false;
	}

return result;
}	
	
</script>

<?php if(isset($_SESSION['errorCompra'])){
unset($_SESSION['errorCompra']);	
}	////Eliminamos los errores dados por el formulario de compra al refrescar la página?>