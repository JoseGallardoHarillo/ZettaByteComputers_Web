 <?php 
 
 
 
 //Incluimos los archivos necesarios para la ejecucion de la pagina
 		require_once("gestionBD.php");
 		require_once("funciones.php");
		
//Si no esta iniciada la sesion la iniciamos		
	if(!isset($_SESSIONS)){
		session_start();	
	}
//Inicializamos el carrito, es una funcion en funciones.php
	inicializaCarrito();
	

	
//Creamos una conexion a la base de datos	
$conexion=crearConexionBD();

?>


<!doctype html>
<html lang="es">
	<title>Carrito</title>

	<head>
        <meta charset="UTF-8" >
        	<link rel="stylesheet" href="css\carrito.css" />

        	</head>


	<body background="images\webb.png">
		
		
				<?php
//Cargamos la cabecera que corresponda, es una funcion en funciones.php y tambien cargamos el nav
				 cargaCabecera();?>
				<?php include_once("nav/nav.php");?>
			
			

		<main>
			
			
				<?php 
				//Esto es un mensaje de error que viene de Intentar acceder al procesamiento de la compra con el carrito vacio o sin estar logeado
				//Te muestra por pantalla que necesitas cosas en el carrito, y si no estas logeado que necesitas estarlo
				
				if(isset($_SESSION['errorCompraNoLog'])){
									$mensaje=$_SESSION['errorCompraNoLog']['error'];
					
					echo "<p id=\"p2\">$mensaje</p>";
				}
				unset($_SESSION['errorCompraNoLog']);
				
				?>
			
			
			
			
				<p id="p1">
									

				 <?php if(empty($_SESSION['carrito'])){
				 	//Si el carrito está vacio te muestra esto
					echo "¡Tu carrito está vacio, añade algunos productos desde nuestra web!";
					
				}else{
					//Sino esto
					echo "Tu carrito";
				}
				
				
				?>
				</p>
				
				<?php 			
					try{
						//Consulta a la base de datos para mostrar todos los productos del carrito
						$productos= array_keys($_SESSION['carrito']);		
						$preciototal=0;										
						foreach ($productos as $key => $value) {
							$aux="SELECT NOMBRE,DESCRIPCION,PRECIOBASE,IVA,OID_PRODUCTO FROM PRODUCTOS WHERE OID_PRODUCTO='".$value."'";
							$stmt=$conexion->prepare($aux);
							$stmt->execute();
							$resultado=$stmt->fetchAll();

							$aux1="SELECT URLFOTO FROM FOTOS WHERE OID_PRODUCTO='".$resultado[0][4]."'";
							$stmt1=$conexion->prepare($aux1);
							$stmt1->execute();
							$resultado2=$stmt1->fetchAll();	
							$nombre=$resultado[0][0];
								
							$badprecio=	$resultado[0][2];
							$buenprecio=str_replace(',', '.', $badprecio);
							$precioSinIva=(double)$buenprecio;	
						
							$badiva= $resultado[0][3];
							
							$ivabienMostrar= str_replace(',', '', $badiva);						
							$ivabienCalcular=str_replace(',', '1.', $badiva);						
							$ivaDouble=(double)$ivabienCalcular;									
							$precioConIva=round($precioSinIva*$ivaDouble,2);
							$oid=$resultado[0][4];
							$preciototal=$preciototal+($precioConIva*$_SESSION['carrito'][$value]);
							
							
							//Mostarmos por pantalla todos los productos del carrito
							
												echo "<article id=\"artic$oid\" class=\"Producto\">";
												echo "<div class=\"imagen\">";
												echo "<img class=\"imagen\" src=\"".$resultado2[0][0]."\" style=\"width:150px;height:150px\" alt=\"Imagen del producto no disponible en este momento\"/>";
												echo "</div>";
												echo "<p class=\"nombre\">".$resultado[0][0]."</p>";
												echo "<p class=\"descripcion\">".$resultado[0][1]."</p>";
												echo "<div class=\"pr\">";
												echo "<p class=\"precio\">Precio:".$precioConIva."€</p>";
												echo "<p class=\"iva\">IVA:".$ivabienMostrar."%</p>";			
												echo "<p id=\"unitext$oid\" class=\"Unidades\">Unidades:".$_SESSION['carrito'][$value]."</p>";
												
												
												echo "<div class=\"textoyuni\">";
												echo "<p class=\"TextoNumUnidades\">Cambiar a: </p>";
												echo "<input id=\"NumUnidades$oid\" name=\"NumUnidades\" class=\"NumUnidades\" type=\"number\" min=\"0\" max=\"20\" value=\"1\" autofocus=\"autofocus\" />";
												echo "</div>";									
												echo "<input type=\"button\" class=\"button\" value=\"Cambiar cantidad\" onclick = \"funcionCambiaCarro($oid)\"/>";
																	
																	//Variable creadas para usar javascript
																		echo "<input type=\"hidden\" id=\"Nombre$oid\" value=\"$nombre\" />";
																		echo "<input type=\"hidden\" id=\"OIDJAVA\" value=\"$oid\" />";
																		echo "<input type=\"hidden\" id=\"PrecioConIVA\" value=\"$precioConIva\" />";
																		$cantidad=$_SESSION['carrito'][$value];
																		echo "<input type=\"hidden\" id=\"Cantidad\" value=\"$cantidad\" />";
																		
												echo "</div>";
												
												echo "</article >";
						
						
						
						
						
						
						
						
						
						
}
							}catch(PDOException $e){
		$_SESSION['errorConexionBD'] = $e->GetMessage();
		header("Location: error_conexionBD.php");
	}


	if(!empty($_SESSION['carrito'])){
		
	//Si el carrito no esta vacio mostramos el precio total




echo"<div id=\"TotalyPrecio\" class=\"TotalyPrecio\">";
		if($preciototal>50.0){
echo"<p id=\"textoTotal\" class=\"textoTotal\">Total: $preciototal</p>";
		}else{
echo"<p id=\"textoTotal\" class=\"textoTotal\">Total: $preciototal + 2.50€ de gastos de envío, envío gratis en pedidos mayores a 50€</p>";
			
		}
echo"<input class=\"botonProcesarCompra\" type=\"button\" onclick=\"window.location.href='formularioProcesarCompra.php'\" value=\"Procesar compra\" />";
echo"</div>";


	}





		//cerramos conexion a la base de datos, ya no la usamos
		cerrarConexionBD($conexion);
		//incluimos el footer
		include_once("footer/pie.php");
?>

</body>




</html>





<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
function funcionCambiaCarro(oid){
	var NumU="NumUnidades";
	var textoAbuscar=NumU.concat(oid.toString());
	var numunitemp=document.getElementById(textoAbuscar).value;
	var numUnidades=parseInt(numunitemp);
	var enlaceAatacar="funcionCambiaCantidadCarro.php"+"?OID="+oid+"&CANTIDAD="+numUnidades;
	var nom="Nombre";
	var textoAbuscar2=nom.concat(oid.toString());
	var nombre=document.getElementById(textoAbuscar2).value;
	if(isNaN(numUnidades)){
				alert('Unidades no validas');
	}else if(numUnidades>20){
		alert('El número de unidades debe ser menor o igual a 20');
		
	}else if(numUnidades<0){
			alert('No puedes añadir un menor a 0 de unidades');

	}else{
	$.ajax({
		url:enlaceAatacar,
		method:"GET",
		success:function(){
	

			if(numUnidades==0){
			var auxART="artic";	
			var aux2ART=auxART.concat(oid.toString());
			var myobj = document.getElementById(aux2ART);
			myobj.remove();
			
			alert('Se ha eliminado del carrito el producto: ' + nombre);
			var precioFinal=ActualizaPrecioTotal(oid,numUnidades);
			if(precioFinal>50.0){
				
			document.getElementById("textoTotal").innerHTML = "Total: "+precioFinal ;
			}else{
						document.getElementById("textoTotal").innerHTML = "Total: "+precioFinal + " + 2.50€ de gastos de envío, envío gratis en pedidos mayores a 50€";
	
			}
			if(document.getElementsByClassName("Producto").length==0){
					var myobj2 = document.getElementById("TotalyPrecio");

					myobj2.remove();
					document.getElementById("p1").innerHTML ="¡Tu carrito está vacio, añade algunos productos desde nuestra web!";
					var myobj3 = document.getElementById("p2");
					myobj3.remove();

	
			}
			
				
			}else{
			
				
			var aux="unitext";	
			var aux2=aux.concat(oid.toString());
			document.getElementById(aux2).innerHTML = "Unidades: " + numUnidades;
			
			var precioFinal=ActualizaPrecioTotal(oid,numUnidades);
	if(precioFinal>50.0){
				
			document.getElementById("textoTotal").innerHTML = "Total: "+precioFinal ;
			}else{
						document.getElementById("textoTotal").innerHTML = "Total: "+precioFinal + " + 2.50€ de gastos de envío, envío gratis en pedidos mayores a 50€";
	
			}
			alert('Ahora hay '+ numUnidades + ' unidad/es del producto: ' + nombre+ ' en el carrito');
			
			}
			

		}
	});	
	}
}


function ActualizaPrecioTotal(oid,quantity){

var objoid=document.querySelectorAll("[id='OIDJAVA']");
var obj1=document.querySelectorAll("[id='Cantidad']");
var obj2=document.querySelectorAll("[id='PrecioConIVA']");
var i=0;
var precioFinal=0;
while(i<obj1.length){

	var precio=parseFloat(obj2[i].value);
	var cantidad=parseInt(obj1[i].value);
	if(objoid[i].value==oid){
		precioFinal=precioFinal+(precio*quantity);	
	}else{
	precioFinal=precioFinal+(precio*cantidad);
	}
	
	
	i=i+1;
}
precioFinal=precioFinal.toFixed(2);
return precioFinal;



}



</script>


