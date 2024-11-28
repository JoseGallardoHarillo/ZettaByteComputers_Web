<?php

//requires necesarios para la carga de la pagina
		require_once("gestionBD.php");
 		require_once("funciones.php");
		require_once("paginacion_consulta.php");
//se crea una conexion a la base de datos		
		$conexion=crearConexionBD();
//Miramos si esta la sesion iniciada		
	if(!isset($_SESSIONS)){
		session_start();	
	}
//inicializamos el carrito por si no lo estaba	
inicializaCarrito();

	//Comienzo de la paginacion
	if (isset($_SESSION["paginacion"]))
		$paginacion = $_SESSION["paginacion"];
	$pagina_seleccionada = isset($_GET["PAG_NUM"]) ? (int)$_GET["PAG_NUM"] : (isset($paginacion) ? (int)$paginacion["PAG_NUM"] : 1);
	$pag_tam = isset($_GET["PAG_TAM"]) ? (int)$_GET["PAG_TAM"] : (isset($paginacion) ? (int)$paginacion["PAG_TAM"] : 5);

	if ($pagina_seleccionada < 1) 		$pagina_seleccionada = 1;
	if ($pag_tam < 1) 		$pag_tam = 5;

	// Antes de seguir, borramos las variables de sección para no confundirnos más adelante
	unset($_SESSION["paginacion"]);

	
		
		

?>

<!DOCTYPE html>
<html lang="es">
		<title>Componentes y ordenadores</title>

	<head>
        <meta charset="UTF-8" >
                	<link rel="stylesheet" href="css\menuNavegacionPags.css" />

        	<link rel="stylesheet" href="css\pagPrincipal.css" />

        	</head>


	<body background="images\webb.png">
		
		
	
				<?php cargaCabecera();?>
				<?php include_once("nav/nav.php");?>

			
			

		<main>
				<p id="p1">
				Componentes y ordenadores
				</p>		
				
				<?php
					//Consultas a la base de datos por los productos paginados
				
				try{	
						
					
				$query="SELECT NOMBRE,DESCRIPCION,PRECIOBASE,IVA,OID_PRODUCTO FROM PRODUCTOS WHERE CATEGORIA = 'COMPONENTES Y ORDENADORES'";
				 	$total_registros = total_consulta($conexion, $query);
				 	$total_paginas = (int)($total_registros / $pag_tam);

	if ($total_registros % $pag_tam > 0)		$total_paginas++;
	if ($pagina_seleccionada > $total_paginas)		$pagina_seleccionada = $total_paginas;

	$paginacion["PAG_NUM"] = $pagina_seleccionada;
	$paginacion["PAG_TAM"] = $pag_tam;
	$_SESSION["paginacion"] = $paginacion;
	
				 $resultado= consulta_paginada($conexion,$query,$pagina_seleccionada, $pag_tam);

						foreach ($resultado as $key => $value) {
							$oid=$value['OID_PRODUCTO'];
							$aux1="SELECT URLFOTO FROM FOTOS WHERE OID_PRODUCTO='".$oid."'";
							$stmt1=$conexion->prepare($aux1);
							$stmt1->execute();
						$resultado2=$stmt1->fetchAll();	
						$badprecio=	$value['PRECIOBASE'];
						$buenprecio=str_replace(',', '.', $badprecio);
						$precioSinIva=(double)$buenprecio;
						$nombre=$value['NOMBRE'];
						$badiva= $value['IVA'];
						$ivabienMostrar= str_replace(',', '', $badiva);						
						$ivabienCalcular=str_replace(',', '1.', $badiva);						
						$ivaDouble=(double)$ivabienCalcular;		
						$precioConIva=round($precioSinIva*$ivaDouble,2);


						echo "<article class=\"Producto\">";
						echo "<p class=\"nombre\">".$nombre."</p>";
						echo "<p class=\"descripcion\">".$value['DESCRIPCION']."</p>";
						
						echo "<div class=\"imagenypr\">";
						echo "<img class=\"imagen\" src=\"".$resultado2[0][0]."\" style=\"width:150px;height:150px\" alt=\"Imagen del producto no disponible en este momento\"/>";
						echo "<div class=\"pr\">";
						echo "<p class=\"precio\">Precio:".$precioConIva."€</p>";
						echo "<div class=\"textoyuni\">";
						echo "<p class=\"TextoNumUnidades\">Unidades: </p>";
						echo "<input id=\"NumUnidades$oid\" name=\"NumUnidades\" class=\"NumUnidades\" type=\"number\" min=\"1\" max=\"20\" value=\"1\" autofocus=\"autofocus\" />";
						echo "</div>";			
						echo "<input type=\"button\" class=\"button\" value=\"Añadir al carrito\" onclick = \"funcionCarritojs($oid)\"/>";
						echo "</div>";
						echo "</div>";
						echo "<input type=\"hidden\" id=\"Nombre$oid\" value=\"$nombre\" />";
						
						echo "</article >";
						}

										
					}catch(PDOException $e){
		$_SESSION['errorConexionBD'] = $e->GetMessage();
		header("Location: error_conexionBD.php");
	}
								
				
				
				?>
				
				
				<menu class="menuNavegacionPaginas">
					<p class="textoPags">	Menú de páginas </p>
					
							<div class="enlaces">
					<p class="parrafoPE">Páginas encontradas:</p>
			<?php 

				for( $pagina = 1; $pagina <= $total_paginas; $pagina++ )

					if ( $pagina == $pagina_seleccionada) { 	?>

						<span class="current"><?php echo $pagina; ?></span>

			<?php }	else { ?>

						<a href="busquedaComponentesOrdenadores.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a>

			<?php } ?>

		</div>
		
		<form method="get" action="busquedaComponentesOrdenadores.php" class="formularioCambiarPagina">
		
			<input id="PAG_NUM" name="PAG_NUM" type="hidden" value="<?php echo $pagina_seleccionada?>"/>

			Mostrando

			<input id="PAG_TAM" name="PAG_TAM" type="number"

				min="1" max="<?php echo $total_registros; ?>"

				value="<?php echo $pag_tam?>" autofocus="autofocus" />

			entradas de <?php echo $total_registros?>

			<input type="submit" value="Cambiar">

		</form>
					
					
				</menu>
				
				
				
				
				

				
			
		</main>

		
		
		
		
		
		<!--include footer-->
		<?php include_once("footer/pie.php");        ?>
	</body>


</html>



<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script> 
function funcionCarritojs(oid){
	var NumU="NumUnidades";
	var textoAbuscar=NumU.concat(oid.toString());
	var numUnidades=document.getElementById(textoAbuscar).value;
	var enlaceAatacar="funcionAnadeCarro.php"+"?OID="+oid+"&CANTIDAD="+parseInt(numUnidades);
	var nom="Nombre";
	var textoAbuscar2=nom.concat(oid.toString());
	var nombre=document.getElementById(textoAbuscar2).value;

	if(parseInt(numUnidades)<=20&&parseInt(numUnidades)>0){
	$.ajax({
		url:enlaceAatacar,
		method:"GET",
		success:function(){
		

			
			
			alert('Ahora hay en el carrito '+ parseInt(numUnidades) + ' unidad/es del producto: ' + nombre);
		}
	});

}else{
				alert('El número de unidades del producto debe ser menor o igual que 20 y mayor que 0');

}

}
</script>
		<?php 
		unset($_SESSION['paginacion']);
		cerrarConexionBD($conexion);
?>