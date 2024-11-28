<?php
require_once("gestionBD.php");
 		require_once("funciones.php");
						require_once("paginacion_consulta.php");
		
	if(!isset($_SESSIONS)){
		session_start();	
	}
	if(!isset($_SESSION['usuarioLogeado'])){
		
				header("Location: index.php");
		
	}
	
		$oidC= $_SESSION['usuarioLogeado']['OID_CLIENTE'];
	
	
	
		if (isset($_SESSION["paginacion"]))
		$paginacion = $_SESSION["paginacion"];
	
	$pagina_seleccionada = isset($_GET["PAG_NUM"]) ? (int)$_GET["PAG_NUM"] : (isset($paginacion) ? (int)$paginacion["PAG_NUM"] : 1);
	$pag_tam = isset($_GET["PAG_TAM"]) ? (int)$_GET["PAG_TAM"] : (isset($paginacion) ? (int)$paginacion["PAG_TAM"] : 5);

	if ($pagina_seleccionada < 1) 		$pagina_seleccionada = 1;
	if ($pag_tam < 1) 		$pag_tam = 5;

	// Antes de seguir, borramos las variables de sección para no confundirnos más adelante
	unset($_SESSION["paginacion"]);

	
?>


<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
        	<link rel="stylesheet" href="css/historialCompra.css" />
        	<link rel="stylesheet" href="css/menuNavegacionPags.css" />

  <title>Historial de compras</title>

	

</head>

<body background="images\webb.png">
							<?php cargaCabecera();?>
							<main class="cuerpo">
								<?php
				try{	
			$conexion=crearConexionBD();


	$query="SELECT * FROM VENTAS WHERE OID_CLIENTE = '$oidC'";
				$total_registros = total_consulta($conexion, $query);
				 	$total_paginas = (int)($total_registros / $pag_tam);

	if ($total_registros % $pag_tam > 0)		$total_paginas++;
	if ($pagina_seleccionada > $total_paginas)		$pagina_seleccionada = $total_paginas;

	$paginacion["PAG_NUM"] = $pagina_seleccionada;
	$paginacion["PAG_TAM"] = $pag_tam;
	$_SESSION["paginacion"] = $paginacion;
	
				 $resultado= consulta_paginada($conexion,$query,$pagina_seleccionada, $pag_tam);	
					
					
					
					
					
							
						foreach ($resultado as $key => $value) {
							echo "<Article class=\"elemento\">";
							
							
							
							
							$Direccion=$value['DIRECCIONENVIO'];
							$poblacion=$value['POBLACION'];
							$cp=$value['CODIGOPOSTAL'];
							
							$importeTotal=$value['IMPORTE'];
							
							echo "<p class=\"titulo\">Datos de envío:</p>";
														echo "<hr>";
							
							echo "<p class=\"dato\">Dirección: $Direccion</p>";
							echo "<p class=\"dato\">Población: $poblacion.</p>";
							echo "<p class=\"dato\">Código postal: $cp.</p>";
							
							
							$fechaCompra=$value['FECHAVENTA'];
							$newstr = substr_replace($fechaCompra, "20", 6, 0);
							
							echo "<p class=\"dato\">Fecha de la Compra: $newstr</p>";		
							
							
							
														echo "<hr>";
							
							
							$oidV=$value['OID_V'];
								$query2="SELECT * FROM DATOS_PAGO_VENTA WHERE OID_V = '$oidV'";
								$stmt2=$conexion->prepare($query2);
								$stmt2->execute();
								$resultado2=$stmt2->fetchAll();
							
							echo "<p class=\"titulo\">Datos de pago:</p>";
													echo "<hr>";
							
							$Tarjeta=$resultado2[0]['TARJETACREDITO'];
							$Nombre=$resultado2[0]['NOMBRE'];
							
							
							echo "<p class=\"dato\">Nombre del titular de la tarjeta: $Nombre.</p>";
							echo "<p class=\"dato\">Número de tarjeta: $Tarjeta</p>";
							

							echo "<hr>";
							
							echo "<p class=\"titulo\">Productos:</p>";
														echo "<hr>";
							
							$query3="SELECT * FROM ASOC_V_P WHERE OID_V = '$oidV'";
							$stmt3=$conexion->prepare($query3);
								$stmt3->execute();
								$resultado3=$stmt3->fetchAll();
							
							
							foreach ($resultado3 as $key => $value) {
								$oidProducto= $value['OID_PRODUCTO'];
							$query4="SELECT * FROM PRODUCTOS WHERE OID_PRODUCTO = '$oidProducto'";
							$stmt4=$conexion->prepare($query4);
								$stmt4->execute();
								$resultado4=$stmt4->fetchAll();	
																$nombres= $resultado4[0]['NOMBRE'];
								
															$query5="SELECT * FROM FOTOS WHERE OID_PRODUCTO = '$oidProducto'";
															$stmt5=$conexion->prepare($query5);
																$stmt5->execute();
																$resultado5=$stmt5->fetchAll();	
								$urlfoto=$resultado5[0]['URLFOTO'];								
								
								$precioVenta= $value['PRECIOVENTA'];
								$cantidad= $value['CANTIDAD'];
								
								
								
								
								
								echo "<img class=\"imagen\" src=\"$urlfoto\" style=\"width:150px;height:150px\" alt=\"Imagen del producto no disponible en este momento\"/>";
								echo "<div class=\"divChico\">";
								echo "<p class=\"dato\">Producto: $nombres</p>";
								echo "<p class=\"dato\">Precio de compra del producto: $precioVenta</p>";
								echo "<p class=\"dato\">Cantidad: $cantidad</p>";
								echo "</div>";
																
								echo "<hr>";
								
							}
							
							
							
							
							
							
							
							
							
							
							
							
							echo "<p class=\"Importe\">Importe total: $importeTotal</p>";
							echo "</Article>";
						}					












		cerrarConexionBD($conexion);

				}catch(PDOException $e){
		$_SESSION['errorConexionBD'] = $e->GetMessage();
		header("Location: error_conexionBD.php");
	}


?>

															
							</main>
					<menu class="menuNavegacionPaginas">
					<p class="textoPags">	Menú de páginas </p>
					
							<div class="enlaces">
					<p class="parrafoPE">Páginas encontradas:</p>
			<?php

				for( $pagina = 1; $pagina <= $total_paginas; $pagina++ )

					if ( $pagina == $pagina_seleccionada) { 	?>

						<span class="current"><?php echo $pagina; ?></span>

			<?php }	else { ?>

						<a href="historialCompras.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a>

			<?php } ?>

		</div>
		
		<form method="get" action="historialCompras.php" class="formularioCambiarPagina">
		
			<input id="PAG_NUM" name="PAG_NUM" type="hidden" value="<?php echo $pagina_seleccionada?>"/>

			Mostrando

			<input id="PAG_TAM" name="PAG_TAM" type="number"

				min="1" max="<?php echo $total_registros; ?>"

				value="<?php echo $pag_tam?>" autofocus="autofocus" />

			entradas de <?php echo $total_registros?>

			<input type="submit" value="Cambiar">

		</form>
					
					
				</menu>
														<?php include_once 'footer/pie.php';  ?>

			</body>


</html>
		
		
		
		
									<?php 
				unset($_SESSION['paginacion']);
		
?>