<?php 
      require_once("gestionBD.php");
 	  require_once("funciones.php");
	if(!isset($_SESSIONS)){
		session_start();	
	}
	if(!isset($_SESSION['usuarioLogeado'])){
		header("Location: index.php");
	}
	
	
?>

<!DOCTYPE html>
<html lang="es">
		<title>Tu perfil</title>

	<head>
        <meta charset="UTF-8" >
        	<link rel="stylesheet" href="css\perfil.css" />

        	</head>


	<body background="images\webb.png">
	

				<!--include header-->

				<?php cargaCabecera();?>
				
				
			

		<main>
		<div class="cajaperfil">
		
		<div class="cajaperfil2">
		<br><br><br><br>
			<fieldset class="perfil">
				<br><br><br><br>
				<a class="botonperfil" href="editar_usuario.php">
				
				Actualiza datos de tu perfil
				
				</a>	
				<br><br><br><br>
				<a class="botonperfil" href="CambiarContrasenya.php">
				
				Cambia tu contraseña
				
				</a>	
				<br><br><br><br>
				<a class="botonperfil" href="historialCompras.php">
				
				Historial de compras
				
				</a>
				<br><br><br><br>	
				
					
			</fieldset>	
			<br><br><br><br>
			<form action="accionEliminarCuenta.php" onsubmit="return pregunta();">
			
			
				<input class="botondesactivar" type="submit" value="Desactiva permanentemente tu cuenta" >
				
				</form>
				<br><br>
		</div>
		
		</div>		
				

				
			
		</main>

		
		
		
		
		
	
		<?php include_once("footer/pie.php");        ?>
	</body>


</html>

<script>
	
	function pregunta(){

		if (window.confirm('¿Estás seguro? Esta acción no se puede deshacer')){
		return true;
    alert('¡Muchas gracias por haber formado parte de nuestra web!');
		}else{
	return false;
		}
}
	
	
	
	
</script>
