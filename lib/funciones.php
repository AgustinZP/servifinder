<?php	
	// funcion de conexi�n a base de datos
	function conexionBBDD ($servidor, $usrservidor, $pwdservidor, $bbdd) {
		// abrir conexion a la base de datos
		$conexion = mysqli_connect($servidor, $usrservidor, $pwdservidor, $bbdd) or die('Error en la conexión a la base de datos, revise la configuración de la misma.');

		// establecer el juego de caracteres
		mysqli_set_charset($conexion, 'utf8mb4');
		
		return $conexion;
	}
	
	// funci�n de cierre de conexi�n a la base de datos
	function cerrarConexion($conexion) {
		mysqli_close($conexion);
	}

?>