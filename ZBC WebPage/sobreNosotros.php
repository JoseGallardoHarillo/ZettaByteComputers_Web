<!DOCTYPE html>
<html lang="es">
	<?php require_once"funciones.php";?>
		<title>Sobre nosotros</title>

<head>
	<meta charset = "UTF-8">
	<link rel="stylesheet" href="css\SN.css" />
	
</head>

<body background="images\webb.png">
				<?php cargaCabecera();?>
				<?php include_once("nav/nav.php");?>
						<p id="p1">Sobre nosotros</p>

	<div class="imagenes">
	<img class="imagen1" src="images/fotoTienda0.jpg"  alt="Imagen no disponible en este momento"/>
	<img class="imagen1" src="images/fotoTienda1.jpg"  alt="Imagen no disponible en este momento"/>
	<img class="imagen1" src="images/mapa.png" alt="Imagen no disponible en este momento"/>
	</div>
	<div class="texto">
	<p class="aux">¿Quiénes somos?</p>
	<p class="descripcion" >ZettaByte Computers es una empresa fundada en Febrero de 2020, encargada a vender todo tipo de tecnologías como computadores, periféricos, consolas populares, etc,cuyos miembros fundadores son José Guisado Simón, José Gallardo Harillo, José Franciscos Rodriguez Medina y Jesús Delgado Jimenez. Actualmente tenemos más de 100 establecimientos por todo el mundo, estando el principal en Sevilla, e intentaremos a lo largo de los años mejorar su experiencia aquí con nuestros productos. Esperemos que esto último sea así de corazón, y que la tecnología te acompañe.</p>
	
	</div>
	
	
	
</body>




<?php

require_once("footer/pie.php");

?>