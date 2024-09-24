<?php
	 
	// asegura que los acentos y eñes contenidos en los strings son tratados correctamente
	header('Content-Type: text/html; charset=UTF-8');
	
	// URL del servidor de la p�gina
	const ROUTE_SERVER = 'http://localhost/web_infoland/';

	//const ROUTE_SERVER = 'https://infoland.es';
	
	// nombre y extensi�n de la p�gina principal
    const PRINCIPAL_PAGE = ROUTE_SERVER . 'index.php';
			
	// VARIABLES PARA CONEXION DESDE LOCALHOST
	
	// nombre del servidor de la base de datos en local
	$servidor = 'localhost';
	// nombre del usuario de la base de datos
	$usrservidor = 'root';
	// contrase�a de acceso al servidor de la base de datos
	$pwdservidor = '';
	// nombre de la base de datos
	//$bbdd = 'infoland'; //bbdd sin tabla localidades
	// nombre de la base de datos
	$bbdd = 'servifinder'; //bbdd con tabla localidades
	
	// categorias destacadas en index.php. "nombre de categoria destacada" => "ruta de la imagen de la categor�a destacada"))
	//$categoriasdestacadas = array( "cerrajeria" => "data/images/cerrajero-verde.png", "dentistas" => "data/images/dentista-verde.png", "fisioterapeutas" => "data/images/fisioterapia-verde.png", "electricistas" => "data/images/electricidad-verde.png", "fontaneros" => "data/images/fontanero-verde.png", "abogados" => "data/images/abogado-verde.png", "veterinarios" => "data/images/veterinario-verde.png", "agencias de marketing" => "data/images/ordenador-verde.png", "mecanicos" => "data/images/mecanico-verde.png");
	
	// busqueda alfab�tica
	$diccionario = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
	
?>