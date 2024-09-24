<?php
// evaluar el fichero que contiene las constantes y variables de la web
include_once('../data/datos/constsvars.php');
// evaluar el fichero que contiene las funciones
include_once('../lib/funciones.php');

// conexion al servidor
$conexion = conexionBBDD($servidor, $usrservidor, $pwdservidor, $bbdd);

// Número de resultados por página
$resultados_por_pagina = 10;

// Página actual
$pagina_actual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;

// Calcular el índice inicial para la consulta
$indice_inicial = max(0, ($pagina_actual - 1) * $resultados_por_pagina);

?>

<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Servifinder - Categorías</title>
    <!-- listacategorias.php CSS -->
    <link rel="stylesheet" href="../data/css/listacategorias.css?v=<?php echo (rand()); ?>" />
</head>

<body>
    <!--- header de la página --->
    <nav class="navbar bg-body-tertiary border-0">
        <a class="navbar-brand" href="../index.php">
            <img class="img-fluid" src="../data/images/logo.png" width="auto" height="200">
        </a>
        <a href="login.php" class="btn btn-outline-success" type="submit">Añade tu negocio</a>
    </nav>

    <!--- selección de categoria por inicial --->
    <div class="card text-center border-0">
        <h1 class="card-title">ACTIVIDADES A-Z</h1>
        <nav aria-label="Paginaci&oacute;n de categor&iacute;as">
            <ul class="pagination justify-content-center">
                <?php
                // Verificar si hay una letra seleccionada en la URL
                $letraSeleccionada = isset($_GET['letra']) ? $_GET['letra'] : 'a';

                // bucle que genera las cards de las categorías destacadas
                foreach ($diccionario as $letra) {
                    // Convertir la letra a minúscula para la comparación
                    $letraMinuscula = strtolower($letra);
                    // Agregar la clase 'active' si la letra es igual a la letra seleccionada
                    $activeClass = ($letraMinuscula == $letraSeleccionada) ? ' active' : '';
                ?>
                    <li class="page-item<?php echo $activeClass; ?>">
                        <!-- Agrega un enlace dinámico que incluya la letra como parámetro -->
                        <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?letra=<?php echo $letraMinuscula; ?>"><?php echo strtoupper($letra); ?></a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </nav>
    </div>

    <?php
    if (isset($_GET['letra'])) {
        // Obtén la letra de la URL
        $letra = mysqli_real_escape_string($conexion, $_GET['letra']);

        // Número de resultados por página
        $resultados_por_pagina = 12;

        // Obtén el número de página actual
        $pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

        // Calcula el índice inicial para la paginación
        $indice_inicial = max(0, ($pagina_actual - 1) * $resultados_por_pagina);

        // conexión al servidor
        $conexion = conexionBBDD($servidor, $usrservidor, $pwdservidor, $bbdd);

        // Realiza la consulta SQL para obtener las categorías que comienzan con la letra seleccionada
        $consulta_total_resultados = "SELECT COUNT(*) AS total_resultados
											  FROM categorias
											  WHERE nombre LIKE '$letra%'";

        // Ejecuta la consulta para obtener el número total de resultados
        $resultado_total_resultados = mysqli_query($conexion, $consulta_total_resultados);

        // Obtiene el número total de resultados
        $total_resultados_row = mysqli_fetch_assoc($resultado_total_resultados);
        $total_resultados = $total_resultados_row['total_resultados'];

        // Calcula el número total de páginas
        $total_paginas = ceil($total_resultados / $resultados_por_pagina);

        // Realiza la consulta SQL para obtener las categorías que comienzan con la letra seleccionada
        $consulta_categorias_letra = "SELECT *
											  FROM categorias
											  WHERE nombre LIKE '$letra%'
											  LIMIT $indice_inicial, $resultados_por_pagina";

        // Ejecuta la consulta
        $resultado_categorias_letra = mysqli_query($conexion, $consulta_categorias_letra);

        // Verifica si la consulta se realizó correctamente y si hay resultados
        if ($resultado_categorias_letra && mysqli_num_rows($resultado_categorias_letra) > 0) {

            echo "<div class='resultados-container'>";
            $count = 0;
            // Itera sobre los resultados de la consulta
            while ($categoria_letra = mysqli_fetch_assoc($resultado_categorias_letra)) {
                // Si el contador es múltiplo de 3 iniciamos una nueva fila
                if ($count % 3 === 0) {
                    echo "<div class='fila'>";
                }
                echo "<div class='resultado'>";
                //Mostramos el nombre de las categorias
                echo "<p class='nombre-categoria'><a href='busqueda.php?localidades=&categorias=" . urlencode($categoria_letra['nombre']) . "&pagina=$pagina_actual'>" . $categoria_letra['nombre'] . "</a></p>";
                echo "</div>";
                //Si es el tercer elemento de la fila o el ultimo resultado cerramos la fila
                if (($count + 1) % 3 === 0 || ($count + 1) === mysqli_num_rows($resultado_categorias_letra)) {
                    echo "</div>"; // Cierra la fila después de cada tres resultados o al final del bucle
                }
                $count++;
            }
            echo "</div>";

            // Paginación
            echo "<nav aria-label='Paginación de resultados'>";
            echo "<ul class='paginacion'>";

            // Verifica si hay más resultados que el número de resultados por página
            $num_resultados_pagina = mysqli_num_rows($resultado_categorias_letra);

            // Calcula si hay páginas anteriores o posteriores para habilitar/deshabilitar los enlaces
            $habilitar_anterior = ($pagina_actual > 1) ? '' : 'disabled';
            $habilitar_siguiente = ($num_resultados_pagina == $resultados_por_pagina && $pagina_actual < $total_paginas) ? '' : 'disabled';

            // Enlace para ir a la página anterior
            echo "<li class='pagina-item $habilitar_anterior'><a class='pagina-link' href='listacategorias.php?letra=$letra&pagina=" . ($pagina_actual - 1) . "'>&laquo; Anterior</a></li>";

            // Calcular el rango de páginas a mostrar (por ejemplo, las primeras 4 páginas)
            $inicio_paginacion = max(1, $pagina_actual - 2);
            $fin_paginacion = min($inicio_paginacion + 3, $total_paginas);

            // Mostrar enlaces de paginación
            for ($i = $inicio_paginacion; $i <= $fin_paginacion; $i++) {
                echo "<li class='pagina-item " . ($pagina_actual == $i ? 'active' : '') . "'><a class='pagina-link' href='listacategorias.php?letra=$letra&pagina=$i'>$i</a></li>";
            }

            // Mostrar puntos suspensivos si hay más páginas disponibles
            if ($total_paginas > $fin_paginacion) {
                echo "<li class='pagina-item'><span class='pagina-link'>...</span></li>";
            }

            // Mostrar la última página si hay más páginas disponibles
            if ($total_paginas > $fin_paginacion) {
                echo "<li class='pagina-item'><a class='pagina-link' href='listacategorias.php?letra=$letra&pagina=$total_paginas'>$total_paginas</a></li>";
            }

            // Enlace para ir a la página siguiente
            echo "<li class='pagina-item $habilitar_siguiente'><a class='pagina-link' href='listacategorias.php?letra=$letra&pagina=" . ($pagina_actual + 1) . "'>Siguiente &raquo;</a></li>";

            echo "</ul>";
            echo "</nav>";
        } else {
            // Si no hay resultados, muestra un mensaje indicando que no se encontraron categorías
            echo "<div class='mensaje-else'>";
            echo "No se encontraron categorías que comiencen con la letra " . strtoupper($letra) . ".";
            echo "</div>";
        }
    }
    ?>

<div class="container-footer">
		<!-- Footer de la página -->
		<div class="card text-center border-0">
			<div class="card-header">
				<div class="card footer-card position-relative">
					<img src="../data/images/footer_image.jpeg" class="card-img footer-image" alt="FONDO">
					<div class="text-container">
						<!-- Título -->
						<h1 class="card-title mb-3" style="font-size: 40px; color: white;">¿Tienes un Negocio?</h1>
						<!-- Texto -->
						<p class="card-text">Te ayudamos a dar de alta tu empresa en Servifinder</p>
						<p class="card-text">Consigue más clientes, visibilidad y reconocimiento de tu marca.</p>
						<p class="card-text">Deja que te ayudemos a conseguir tus objetivos y a hacer crecer tu negocio.</p>
						<!-- Enlace -->
						<a href="login.php" class="btn btn-primary">Añade tu negocio</a>
					</div>
				</div>
			</div>
		</div>		
		
		<div class="card-footer">
			<h5 class="card-title">Informaci&oacute;n legal</h5>
			<p><a class="link-opacity-100" href="../pages/contacto.php">Contacto</a></p>
			<p><a class="link-opacity-100" href="../pages/login.php">A&ntilde;adir negocio</a></p>
			<p><a class="link-opacity-100" href="../pages/aviso-legal.php">Aviso legal</a></p>
			<p><a class="link-opacity-100" href="../pages/politica-privacidad.php">Pol&iacute;tica de privacidad</a></p>
			<p><a class="link-opacity-100" href="../pages/politica-cookies.php">Pol&iacute;tica de Cookies</a></p>
		</div>						

		<div class="card">
			<img class="ciudad" src="..\data\images\ciudad.png" style="width: 700px; height: 400px;">
		</div>
	</div>


	<div class="container-wide">
		<div class="pinkstone">
			<p>Diseño web por: Agustín Zaragoza Pérez</p>
		</div>
	</div>

</body>

</html>