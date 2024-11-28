<?php
	if(!isset($_SESSIONS)){
		
		session_start();
	}
	require_once("funciones.php");
 ?>

<!DOCTYPE html>
<html lang="es">
	
	<head>
		
        <meta charset="UTF-8" >
        	<link rel="stylesheet" href="css\error_conexionBD.css" />

        	</head>
        	<body background="images/webb.png">
        	        	<?php cargaCabecera();?>									

        		<main>
        			<article class="MensajeError">
        			<p id="mensaje"> Ha habido un error en la conexión con la base de datos.</p>

        						        			<p id="mensaje">Para volver a la página, por favor, haz click <a href="index.php">aquí</a>.</p>

								</article>
        			
        		</main>
        		
        		
        		
        		
        		
        		
        	<?php include_once("footer/pie.php");?>	
        	</body>
        	

			</html>
			<?php unset($_SESSION['errorConexionBD']); ?>