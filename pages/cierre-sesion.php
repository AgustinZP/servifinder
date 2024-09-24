<!DOCTYPE html>
<html lang="es-ES">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Servifinder - Cierre de Sesión</title>
	<!-- cierre-sesion.php CSS -->
	<link rel="stylesheet" href="../data/css/login.css?v=<?php echo (rand()); ?>" />
</head>

<body>

	<?php
	// Inicia la sesión si no está iniciada
    session_start();

    // Cierra la sesión actual
    session_destroy();

    // Redirige al usuario a la página de login
    header("Location: login.php");
    exit;
	?>
    	
</body>

</html>