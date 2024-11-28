 <?php 
 
 
 
 
 //Hacemos require_once a los archivos que vamos a necesitar para la ejecucion de la pagina
 		require_once("gestionBD.php");
 		require_once("funciones.php");
		
//Si no esta iniciada la sesion la iniciamos		
	if(!isset($_SESSIONS)){
		session_start();	
	}
//Inicializamos el carrito, viene de funciones.php
inicializaCarrito();
	
	//Primera parte de la paginacion
	
		if (isset($_SESSION["paginacion"]))
		$paginacion = $_SESSION["paginacion"];
	
	$pagina_seleccionada = isset($_GET["PAG_NUM"]) ? (int)$_GET["PAG_NUM"] : (isset($paginacion) ? (int)$paginacion["PAG_NUM"] : 1);
	$pag_tam = isset($_GET["PAG_TAM"]) ? (int)$_GET["PAG_TAM"] : (isset($paginacion) ? (int)$paginacion["PAG_TAM"] : 5);

	if ($pagina_seleccionada < 1) 		$pagina_seleccionada = 1;
	if ($pag_tam < 1) 		$pag_tam = 5;

	unset($_SESSION["paginacion"]);
	
//Creo una conexion a la base de datos	
$conexion=crearConexionBD();
?>

<!DOCTYPE html>
<html lang="es">
	<title>ZettaByteComputers</title>
	<head>
        <meta charset="UTF-8" >
        	<link rel="stylesheet" href="css\pagPrincipal.css" />

        	</head>


	<body background="images\webb.png">
	

				<!--include header-->

				<?php
				//Cargo la cabecera que corresponda, es una funcion en funciones.php
				 cargaCabecera();?>
				
				<?php include_once("nav/nav.php");
				?>
				
			

		<main>
				<p id="p1">
				Algunos de nuestro productos
				</p>		
				
				
<?php 			
					//Consulta a la base de datos que saca los 8 primeros productos de la misma y lo muestra por pantalla
					//Con su nombre, descripcion, precio y una foto
					try{
							$aux="SELECT NOMBRE,DESCRIPCION,PRECIOBASE,IVA,OID_PRODUCTO FROM PRODUCTOS WHERE OID_PRODUCTO<8";
						$stmt=$conexion->prepare($aux);
						$stmt->execute();
						$resultado=$stmt->fetchAll();
						
						$i=0;
						foreach ($resultado as $key => $value) {
						
							$aux1="SELECT URLFOTO FROM FOTOS WHERE OID_PRODUCTO='".$i."'";
							$stmt1=$conexion->prepare($aux1);
							$stmt1->execute();
						$resultado2=$stmt1->fetchAll();	
						$badprecio=	$value[2];
						$buenprecio=str_replace(',', '.', $badprecio);
						$precioSinIva=(double)$buenprecio;
						$nombre=$value[0];
						$badiva= $value[3];
						$ivabienMostrar= str_replace(',', '', $badiva);						
						$ivabienCalcular=str_replace(',', '1.', $badiva);						
						$ivaDouble=(double)$ivabienCalcular;		
						$precioConIva=round($precioSinIva*$ivaDouble,2);

						$oid=$value[4];

						echo "<article class=\"Producto\">";
						echo "<p class=\"nombre\">".$nombre."</p>";
						echo "<p class=\"descripcion\">".$value[1]."</p>";
						
						echo "<div class=\"imagenypr\">";
						echo "<img class=\"imagen\" src=\"".$resultado2[0][0]."\" style=\"width:150px;height:150px\" alt=\"Imagen del producto no disponible en este momento\"/>";
						echo "<div class=\"pr\">";
						echo "<p class=\"precio\">Precio:".$precioConIva."€</p>";
						

						echo "<p class=\"iva\">IVA:".$ivabienMostrar."%</p>";
						echo "<div class=\"textoyuni\">";
						echo "<p class=\"TextoNumUnidades\">Unidades: </p>";
						echo "<input id=\"NumUnidades$oid\" name=\"NumUnidades\" class=\"NumUnidades\" type=\"number\" min=\"1\" max=\"20\" value=\"1\" autofocus=\"autofocus\" />";
						echo "</div>";			
						echo "<input type=\"button\" class=\"button\" value=\"Añadir al carrito\" onclick = \"funcionCarritojs($oid)\"/>";
						echo "</div>";
						echo "</div>";
						echo "<input type=\"hidden\" id=\"Nombre$oid\" value=\"$nombre\" />";
						
						echo "</article >";
				$i++;
						}

										
					}catch(PDOException $e){
		$_SESSION['errorConexionBD'] = $e->GetMessage();
		header("Location: error_conexionBD.php");
	}
					
?>
				
			
		</main>
		<?php 
		//Hemos terminado de usar la conexion a la base de datos, asi que la cerramos
		cerrarConexionBD($conexion);
?>
		
		
		
		
		
		
		<?php
		//incluimos el footer
		 include_once("footer/pie.php");        ?>
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
