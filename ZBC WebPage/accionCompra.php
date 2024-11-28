<?php 

	require_once("gestionBD.php");
	require_once("funciones.php");
		if(!isset($_SESSIONS)){
		session_start();	
	}
		
		
		
		
if(!isset($_SESSION["usuarioLogeado"])){
header("Location: index.php");        
}		
		
if(!isset($_SESSION["formularioCompra"])){
header("Location: index.php");        
}			


//variable en session que guarda los errores en la compra

if(!isset($_SESSION['errorCompra'])){
		$errorarr = array();
	
		$errorarr["ERRORCP"] = "";
		$errorarr["ERRORTARJETA"] = "";
		$errorarr["CVV"] = "";
		$errorarr["ERRORFECHA"] = "";	
		$_SESSION["errorCompra"] = $errorarr;
}

	
$oidCliente=$_SESSION['usuarioLogeado']['OID_CLIENTE'];
$direccion= $_POST['direccion'];
$provincia=$_POST['provincia'];
$cp=$_POST['cp'];
$name=$_POST['nombre'];
$NumTarjeta=$_POST['NumTarjeta'];
$cvv=$_POST['cvvNumber'];
$caducidad=$_POST['fechaCad'];


					//Los uso despues para guardar datos de los productos que voy a mostrar
					$nombres=array();
					$preciosTotales=array();
					$precioTotalParaLuego="";
					
$_SESSION["formularioCompra"]["direccion"]=$direccion;
$_SESSION["formularioCompra"]["cp"]=$cp;
$_SESSION["formularioCompra"]["provincia"]=$provincia;
$_SESSION["formularioCompra"]["Nombre"]=$name;


checkCP($provincia,$cp);
checkFecha($caducidad);
luhn_check($NumTarjeta);

$numErrores=0;
foreach ($_SESSION['errorCompra'] as $key => $value) {
	if($value!=""){
		$numErrores=$numErrores+1;
	}
}


if($numErrores!=0){
				header("Location: formularioProcesarCompra.php");     				
}else{
	

						
							$productos= $_SESSION['carrito'];		
							
		try{
									
																			$preciototal=0.0;										
												
								
								//Aqui cogemos los productos en el carro para ver su precio 
								
								
		foreach ($productos as $key => $value) {
				$conexion=crearConexionBD();
			
					$aux="SELECT PRECIOBASE,IVA FROM PRODUCTOS WHERE OID_PRODUCTO='$key'";
							$stmt=$conexion->prepare($aux);
							$stmt->execute();
							$resultado=$stmt->fetchAll();
							$badiva= $resultado[0][1];
							$ivabienCalcular=str_replace(',', '1.', $badiva);						
							$ivaDouble=(double)$ivabienCalcular;	
							$badprecio=	$resultado[0][0];
							$buenprecio=str_replace(',', '.', $badprecio);
							$precioSinIva=(double)$buenprecio;	
							$precioConIva=round($precioSinIva*$ivaDouble,2);
							$preciototal=$preciototal+($precioConIva*$value);
							
							
																
			//aqui comprobamos si supera el minimo para no tener gastos de envio				
						
		}	
		if($preciototal<50.0){
			$preciototal=$preciototal+2.5;
		}
		
		$precioTotalParaLuego=$preciototal;
		$now = date("d-m-Y");
		
$fechaInsert = date("d-m-Y", strtotime($now));

//Se inserta en la tabla ventas los datos del pedido realizado

$poblacionBien=substr($provincia,2);
$aux="INSERT INTO VENTAS (OID_CLIENTE,importe, ventaOnline,tipoEnvio,fechaEnvio,fechaVenta,direccionEnvio,poblacion,codigoPostal,fechaEntrega) VALUES ('$oidCliente',$preciototal,'S','ECONOMICO',to_date('$fechaInsert','DD-MM-YYYY'),to_date('$fechaInsert','DD-MM-YYYY'),'$direccion','$poblacionBien','$cp',to_date('$fechaInsert','DD-MM-YYYY'))";
$insert=$conexion->prepare($aux);
$insert->execute();					
					
$aux2="SELECT MAX(OID_V) FROM VENTAS WHERE OID_CLIENTE=$oidCliente";					
$select=$conexion->prepare($aux2);				
$select->execute();					
$resultadoSelect=$select->fetchAll();
$oid_venta=$resultadoSelect[0][0];
					

					$segundoForEach=$_SESSION['carrito'];
		foreach ($segundoForEach as $key => $value) {
					
					$aux="SELECT PRECIOBASE,IVA,NOMBRE FROM PRODUCTOS WHERE OID_PRODUCTO='$key'";
							$stmt=$conexion->prepare($aux);
							$stmt->execute();
							$resultado=$stmt->fetchAll();
							$badiva= $resultado[0][1];
							$ivabienCalcular=str_replace(',', '1.', $badiva);						
							$ivaDouble=(double)$ivabienCalcular;	
							$badprecio=	$resultado[0][0];
							$buenprecio=str_replace(',', '.', $badprecio);
							$precioSinIva=(double)$buenprecio;	
							$precioConIva=round($precioSinIva*$ivaDouble,2);
							$preciosTotales[$key]=	$precioConIva;
							$nombres[$key]	=$resultado[0][2];

							
						$insert= "INSERT INTO ASOC_V_P(OID_V, OID_PRODUCTO, cantidad, precioVenta) VALUES ($oid_venta,$key,$value,$precioConIva)";	
				$stmt3=$conexion->prepare($insert);
									$stmt3->execute();
			
			
			
			
			//Insertamos los datos del pago del pedido.
			
			
		}
					
					$fechaInsert2 = date("d-m-Y", strtotime($caducidad));
					
		$intTarjeta=(int)$NumTarjeta;			
		$intcvv=(int)$cvv;
		$insert2="INSERT INTO DATOS_PAGO_VENTA(OID_V,TARJETACREDITO,NOMBRE,CVV,FECHACADUCIDAD) VALUES ($oid_venta,$intTarjeta,'$name',$intcvv,to_date('$fechaInsert2','DD-MM-YYYY'))";
	
		$stmt4=$conexion->prepare($insert2);
		$stmt4->execute();
		
		






				cerrarConexionBD($conexion);
		
		
		}catch(PDOException $e){
		$_SESSION['errorConexionBD'] = $e->GetMessage();
		header("Location: error_conexionBD.php");
	}
		
		
}





//funciones de validacion de la compra



function checkCP($provincia,$cp){
	$provincias=array();
	$comprobar=substr($cp, 0, 2);
	$comprobar2=substr($provincia, 0, 2);
	
if($comprobar!=$comprobar2){
	$_SESSION['errorCompra']['ERRORCP']="<p class=\"error\">El código postal no concuerda con la localidad</p>";
}

						
}	
function checkFecha($fecha){
if( strtotime($fecha) < strtotime('now') ) {
		$_SESSION['errorCompra']['ERRORFECHA']="<p class=\"error\">La tarjeta de credito está caducada</p>";
	
}
}
	
function luhn_check($number) {

  // Quita todos carateres que no sean texto
  $number=preg_replace('/\D/', '', $number);

  // Coge la longitud del string y la paridad
  $number_length=strlen($number);
  $parity=$number_length % 2;

  // Hacemos un loop por los numeros y comprobamos si son correctos
  $total=0;
  for ($i=0; $i<$number_length; $i++) {
    $digit=$number[$i];
    // Multiplica dígitos alternos por dos
    if ($i % 2 == $parity) {
      $digit*=2;
      // Si la suma son dos digitos los pone juntos 
      if ($digit > 9) {
        $digit-=9;
      }
    }
    // Total de los numeros
    $total+=$digit;
  }

  // Si el total modulo 10 es 0 es valido
  if ($total % 10 != 0) {
  			$_SESSION['errorCompra']['ERRORTARJETA']="<p class=\"error\">La tarjeta de credito no es correcta</p>";
	
  }

}	




?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
        	<link rel="stylesheet" href="css\accionCompra.css" />

  <title>¡Compra realizada!</title>

	

</head>

<body background="images\webb.png">
							<?php cargaCabecera();?>
							<main class="cuerpo">
								



								<p class="textos">¡Tu compra ha sido realizada! Estos han sido los datos de tu compra:</p>
								<fieldset class="campo">
								<legend class="leyenda"><h2>Dirección de envío:</h2></legend>
								<p>Dirección: <?php echo $direccion;?></p>
								<hr>
								<p class="nombres">Provincia: <?php 
								
								$provinciaMostrar=substr($provincia, 2);
								
								
								echo $provinciaMostrar;?></p>
								<hr>
								<p class="nombres">Código postal: <?php echo $cp;?></p>	
								</fieldset>
								
								<fieldset class="campo">
								<legend class="leyenda"><h2>Datos de pago:</h2></legend>

								<p class="nombres">Nombre en la tarjeta: <?php echo $name;?></p>
								<hr>
								<p class="nombres">Número de tarjeta: <?php echo $NumTarjeta;?></p>
								</fieldset>
								<fieldset class="campo">
								<legend class="leyenda"><h2>Productos comprados:</h2></legend>
								<?php
								$i=0;
								foreach ($nombres as $key => $value) {
									$nombreProducto =$nombres[$key];
									$preciodeeste=$preciosTotales[$key];
									if($i!=0){
									echo "<hr>";
									}
									echo '<p class="nombres"> Producto: '.$nombreProducto.', Cantidad: '.$_SESSION['carrito'][$key].'.</p>';
									echo '<p class="nombres">Precio: '.$preciodeeste.', por unidad.</p>';
									
									$i=1;
								}
								
											
								
								
								?>
																<hr>

								<p class="preciototal">Precio Total: <?php echo $precioTotalParaLuego;?></p>
								</fieldset>
								
								
								<p class="textos">Puedes consultar tus compras en tu perfil o pulsando <a href="historialCompras.php">aquí</a>.</p>								
							</main>
		
		
		
		
		
							<?php include_once 'footer/pie.php';  ?>
</body>
</html>
<?php		

if($numErrores==0){

	unset($_SESSION["formularioCompra"]);
	unset($_SESSION["errorCompra"]);
	unset($_SESSION['carrito']);
}
	
	?>
