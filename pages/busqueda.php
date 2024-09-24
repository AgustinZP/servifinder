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
	<title>Servifinder - Busqueda</title>
	<!-- busqueda.php CSS -->
	<link rel="stylesheet" href="../data/css/busqueda.css?v=<?php echo (rand()); ?>" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
	<!--- header de la página --->
    <nav class="navbar bg-body-tertiary border-0">
        <a class="navbar-brand" href="../index.php">
            <img class="img-fluid" src="../data/images/logo.png" width="auto" height="200">
        </a>
        <a href="login.php" class="btn btn-outline-success" type="submit">Añade tu negocio</a>
    </nav>

    <!-- Búsqueda por actividad y localidad -->
    <div class="card border-0">
        <div class="card-body">
            <h1 class="card-title" style="margin-top: 80px;">Buscamos los Servicios y Negocios que USTED NECESITA</h1>
            <div class="form-container">
                <form class="d-flex align-items-center" id="searchForm" role="search" action="busqueda.php" method="get">
                    <div class="form-group">
                        <input class="form-control" type="search" id="categorias" name="categorias" value="<?php echo isset($_GET['categorias']) ? htmlspecialchars($_GET['categorias']) : ''; ?>" placeholder="&iquest;Qu&eacute; buscas? ej: cerrajeros, abogados, fontaneros..." aria-label="Search">
                        <select class="form-control" id="sugerencias1" size="8"></select>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="search" id="localidades" name="localidades" value="<?php echo isset($_GET['localidades']) ? htmlspecialchars($_GET['localidades']) : ''; ?>" placeholder="&iquest;D&oacute;nde? (localidad o provincia)" aria-label="Search">
                        <select class="form-control" id="sugerencias2" size="8"></select>
                    </div>
                    <button class="btn btn-outline-success-form" type="submit">Buscar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categoriasInput = document.getElementById('categorias');
            const sugerenciasCategorias = document.getElementById('sugerencias1');

            async function mostrarSugerencias() {
                const textoIngresado = categoriasInput.value.toLowerCase().trim();

                if (textoIngresado === "") {
                    sugerenciasCategorias.style.display = 'none';
                    return;
                }

                try {
                    const response = await fetch(`../lib/autocompleteCategorias.php?term=${encodeURIComponent(textoIngresado)}`);
                    const sugerencias = await response.json();

                    sugerenciasCategorias.innerHTML = '';

                    sugerencias.forEach(sugerencia => {
                        const opcion = document.createElement('option');
                        opcion.value = sugerencia.nombre;
                        opcion.textContent = sugerencia.nombre;
                        sugerenciasCategorias.appendChild(opcion);
                    });

                    sugerenciasCategorias.style.display = 'block';
                } catch (error) {
                    console.error('Error fetching suggestions:', error);
                }
            }

            categoriasInput.addEventListener('input', () => {
                mostrarSugerencias();
            });

            sugerenciasCategorias.addEventListener('change', () => {
                categoriasInput.value = sugerenciasCategorias.value;
                sugerenciasCategorias.style.display = 'none';
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
    const localidadInput = document.getElementById('localidades');
    const sugerenciasLocalidad = document.getElementById('sugerencias2');

    async function mostrarSugerencias() {
        const textoIngresado = localidadInput.value.toLowerCase().trim();

        if (textoIngresado === "") {
            sugerenciasLocalidad.style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`../lib/autocomplete.php?term=${encodeURIComponent(textoIngresado)}`);
            const sugerencias = await response.json();

            sugerenciasLocalidad.innerHTML = '';

            sugerencias.forEach(sugerencia => {
                const opcion = document.createElement('option');
                opcion.value = sugerencia.nombre;
                opcion.textContent = sugerencia.tipo === 'provincia' ? `${sugerencia.nombre} - Provincia` : sugerencia.nombre;
                sugerenciasLocalidad.appendChild(opcion);
            });

            sugerenciasLocalidad.style.display = 'block';
        } catch (error) {
            console.error('Error fetching suggestions:', error);
        }
    }

    localidadInput.addEventListener('input', () => {
        mostrarSugerencias();
    });

    sugerenciasLocalidad.addEventListener('change', () => {
        // Obtener el valor de la opción seleccionada
        const seleccion = sugerenciasLocalidad.value;
        // Verificar si la opción seleccionada es una provincia
        const esProvincia = sugerenciasLocalidad.options[sugerenciasLocalidad.selectedIndex].textContent.includes("- Provincia");
        // Concatenar "- Provincia" al valor del campo de entrada si es una provincia
        localidadInput.value = esProvincia ? `${seleccion} - Provincia` : seleccion;
        sugerenciasLocalidad.style.display = 'none';
    });
});
    </script>

	<div class="container-resultados">
		<div class="column-izquierda">
			<!-- Mapa -->
			<div class="map-container" style="margin-top: 20px;">
				<?php
				//Guardamos nuestra apikey en una variable
				$apiKey = 'apikeycorrespondiente';
				//comprobamos si se ha pasado el parametro localidades y lo guardamos en la variable localidad
				$localidad = isset($_GET['localidades']) ? htmlspecialchars($_GET['localidades']) : '';
				$mapSrc = '';
				//Si localidad
				if ($localidad) {
					$mapSrc = "https://www.google.com/maps/embed/v1/place?key=$apiKey&q=" . urlencode($localidad);
				} else {
					$mapSrc = "https://www.google.com/maps/embed/v1/view?key=$apiKey&center=40.463667,-3.74922&zoom=5"; // Centro de España
				}
				?>
				<iframe width="450" height="400" style="border:0" loading="lazy" allowfullscreen src="<?php echo $mapSrc; ?>"></iframe>
			</div>
		</div>

		<div class="column-derecha">
			<?php
			if (isset($_GET['categorias']) && isset($_GET['localidades'])) {
				$categorias = mysqli_real_escape_string($conexion, $_GET['categorias']);
				$localidades = mysqli_real_escape_string($conexion, $_GET['localidades']);

				// Número de resultados por página
				$resultados_por_pagina = 10;

				// Obtén el número de página actual
				$pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

				// Calcula el índice inicial para la paginación
				$indice_inicial = ($pagina_actual - 1) * $resultados_por_pagina;

				// Conexión al servidor
				$conexion = conexionBBDD($servidor, $usrservidor, $pwdservidor, $bbdd);

				if ($conexion) {
					// Verificar si la categoría está presente en la base de datos
					$consulta_existencia_categoria = "SELECT COUNT(*) AS total FROM categorias WHERE nombre LIKE '%$categorias%'";
					$resultado_existencia_categoria = mysqli_query($conexion, $consulta_existencia_categoria);
					$data_existencia_categoria = mysqli_fetch_assoc($resultado_existencia_categoria);
					$existencia_categoria = $data_existencia_categoria['total'];

					if ($existencia_categoria > 0) {
						// Si la categoría está presente, proceder con la consulta de búsqueda

						$categorias_coincidentes = [];
						$provincias_coincidentes = [];
						$localidades_coincidentes = [];

						// Verifica si la entrada tiene el sufijo " - Provincia"
						if (strpos($localidades, ' - Provincia') !== false) {
							$localidades_palabras = [str_replace(' - Provincia', '', $localidades)];
							$es_provincia = true;
						} else {
							$localidades_palabras = [$localidades];
							$es_provincia = false;
						}

						// Busca categorías coincidentes
						$consulta_categorias = "SELECT idcategoria FROM categorias WHERE nombre LIKE '%$categorias%'";
						$resultado_categorias = mysqli_query($conexion, $consulta_categorias);
						while ($row = mysqli_fetch_assoc($resultado_categorias)) {
							$categorias_coincidentes[] = $row['idcategoria'];
						}

						// Busca provincias o localidades coincidentes
						if ($es_provincia) {
							foreach ($localidades_palabras as $palabra) {
								$consulta_provincias = "SELECT idprovincia FROM provincias WHERE nombre LIKE '%$palabra%'";
								$resultado_provincias = mysqli_query($conexion, $consulta_provincias);
								while ($row = mysqli_fetch_assoc($resultado_provincias)) {
									$provincias_coincidentes[] = $row['idprovincia'];
								}
							}
						} else {
							foreach ($localidades_palabras as $palabra) {
								$consulta_localidades = "SELECT idlocalidad FROM localidades WHERE nombre LIKE '%$palabra%'";
								$resultado_localidades = mysqli_query($conexion, $consulta_localidades);
								while ($row = mysqli_fetch_assoc($resultado_localidades)) {
									$localidades_coincidentes[] = $row['idlocalidad'];
								}
							}
						}

						// Construye la consulta para obtener las empresas
						$consulta_empresas = "SELECT e.idempresa, e.nombre, e.direccion, e.telefono, e.codigopostal FROM empresas e";
						$where_clauses = [];

						if (!empty($provincias_coincidentes)) {
							$consulta_empresas .= " INNER JOIN localidades l ON e.idlocalidad = l.idlocalidad";
							$where_clauses[] = "l.idprovincia IN (" . implode(',', $provincias_coincidentes) . ")";
						} else if (!empty($localidades_coincidentes)) {
							$where_clauses[] = "e.idlocalidad IN (" . implode(',', $localidades_coincidentes) . ")";
						}

						if (!empty($categorias_coincidentes)) {
							$where_clauses[] = "e.idcategoria IN (" . implode(',', $categorias_coincidentes) . ")";
						}

						if (!empty($where_clauses)) {
							$consulta_empresas .= " WHERE " . implode(' AND ', $where_clauses);
						}

						$consulta_empresas .= " ORDER BY e.nombre ASC";

						$total_resultados_query = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM ($consulta_empresas) AS subconsulta");
						$total_resultados_data = mysqli_fetch_assoc($total_resultados_query);
						$total_resultados = $total_resultados_data['total'];

						$total_paginas = ceil($total_resultados / $resultados_por_pagina);

						$consulta_empresas .= " LIMIT $indice_inicial, $resultados_por_pagina";
						$resultado_empresas = mysqli_query($conexion, $consulta_empresas);

						if ($resultado_empresas && mysqli_num_rows($resultado_empresas) > 0) {
							// Imprimir el título de los resultados
							echo "<p class='titulo-resultados'>";
							echo ucwords(htmlspecialchars($_GET['categorias']));

							// Verifica si se ha proporcionado el nombre de localidad o provincia
							if (!empty($_GET['localidades'])) {
								echo " en " . ucwords(htmlspecialchars($_GET['localidades']));
							} else if (!empty($_GET['provincias'])) {
								echo " en " . ucwords(htmlspecialchars($_GET['provincias']));
							}
							echo "</p>";

							echo "<p class='subtitulo-resultados'>Resultados de búsqueda</p>";

							while ($empresa = mysqli_fetch_assoc($resultado_empresas)) {
								echo "<a href='ficha.php?empresa=" . urlencode($empresa['idempresa']) . "&categoria=" . urlencode($_GET['categorias']) . "' class='resultado-empresas'>";
								echo "<div class='elements'>";
								echo "<p class='nombre-empresas'>" . $empresa['nombre'] . "</p>";
								echo "<div class='direccion'>";
								echo "<p>{$empresa['direccion']}</p>";
								echo "</div>";
								echo "</div>";
								echo "<p class='telefono'><i class='bi bi-telephone telefono-icono'></i></p>";
								echo "</a>";
							}

							$pagina_anterior = max(1, $pagina_actual - 1);
							$pagina_siguiente = min($total_paginas, $pagina_actual + 1);

							echo "<nav aria-label='Paginación de resultados'>";
							echo "<ul class='paginacion justify-content-center'>";

							echo "<li class='pagina-item'><a class='pagina-link' href='?categorias=" . urlencode($_GET['categorias']) . "&localidades=" . urlencode($_GET['localidades']) . "&pagina=1'>&laquo;&laquo; Primera</a></li>";
							echo "<li class='pagina-item'><a class='pagina-link' href='?categorias=" . urlencode($_GET['categorias']) . "&localidades=" . urlencode($_GET['localidades']) . "&pagina=$pagina_anterior'>&laquo; Anterior</a></li>";

							$inicio_paginacion = max(1, $pagina_actual - 2);
							$fin_paginacion = min($inicio_paginacion + 4, $total_paginas);

							for ($i = $inicio_paginacion; $i <= $fin_paginacion; $i++) {
								if ($i <= $total_paginas) {
									echo "<li class='pagina-item " . ($pagina_actual == $i ? 'active' : '') . "'><a class='pagina-link' href='?categorias=" . urlencode($_GET['categorias']) . "&localidades=" . urlencode($_GET['localidades']) . "&pagina=$i'>$i</a></li>";
								}
							}

							if ($fin_paginacion < $total_paginas) {
								echo "<li class='pagina-item'><span class='pagina-link'>...</span></li>";
							}

							if ($fin_paginacion < $total_paginas) {
								echo "<li class='pagina-item'><a class='pagina-link' href='?categorias=" . urlencode($_GET['categorias']) . "&localidades=" . urlencode($_GET['localidades']) . "&pagina=$total_paginas'>$total_paginas</a></li>";
							}

							echo "<li class='pagina-item'><a class='pagina-link' href='?categorias=" . urlencode($_GET['categorias']) . "&localidades=" . urlencode($_GET['localidades']) . "&pagina=$pagina_siguiente'>Siguiente &raquo;</a></li>";
							echo "<li class='pagina-item'><a class='pagina-link' href='?categorias=" . urlencode($_GET['categorias']) . "&localidades=" . urlencode($_GET['localidades']) . "&pagina=$total_paginas'>Última &raquo;&raquo;</a></li>";

							echo "</ul>";
							echo "</nav>";
						} else {
							echo "<div class='mensaje-else'>";
							echo "No se encontraron resultados.";
							echo "</div>";
						}

						mysqli_close($conexion);
					} else {
						echo "<div class='mensaje-else'>";
						echo "No existen coincidencias.";
						echo "</div>";
					}
				} else {
					echo "<div class='mensaje-else'>";
					echo "Error en la conexión con la base de datos.";
					echo "</div>";
				} 
			}	
			
			if (isset($_GET['letra'])) {
				// Obtén la letra de la URL
				$letra = mysqli_real_escape_string($conexion, $_GET['letra']);

				// Número de resultados por página
				$resultados_por_pagina = 10;

				// Obtén el número de página actual
				$pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

				// Calcula el índice inicial para la paginación
				//$indice_inicial = ($pagina_actual - 1) * $resultados_por_pagina;
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
					while ($categoria_letra = mysqli_fetch_assoc($resultado_categorias_letra)) {
						if ($count % 3 === 0) {
							echo "<div class='fila'>";
						}
						echo "<div class='resultado'>";
						echo "<p class='nombre-categoria'><a href='categorias.php?categoria_id=" . urlencode($categoria_letra['idcategoria']) . "&pagina=$pagina_actual'>" . $categoria_letra['nombre'] . "</a></p>";
						echo "</div>";
						if (($count + 1) % 3 === 0 || ($count + 1) === mysqli_num_rows($resultado_categorias_letra)) {
							echo "</div>"; // Cierra la fila después de cada tres resultados o al final del bucle
						}
						$count++;
					}
					echo "</div>";

					// Paginación
					echo "<nav aria-label='Paginación de resultados'>";
					echo "<ul class='paginacion justify-content-center'>";

					// Verifica si hay más resultados que el número de resultados por página
					$num_resultados_pagina = mysqli_num_rows($resultado_categorias_letra);

					// Calcula si hay páginas anteriores o posteriores para habilitar/deshabilitar los enlaces
					$habilitar_anterior = ($pagina_actual > 1) ? '' : 'disabled';
					$habilitar_siguiente = ($num_resultados_pagina == $resultados_por_pagina && $pagina_actual < $total_paginas) ? '' : 'disabled';

					// Enlace para ir a la página anterior
					echo "<li class='pagina-item $habilitar_anterior'><a class='pagina-link' href='categorias.php?letra=$letra&pagina=" . ($pagina_actual - 1) . "'>&laquo; Anterior</a></li>";

					// Calcular el rango de páginas a mostrar (por ejemplo, las primeras 4 páginas)
					$inicio_paginacion = max(1, $pagina_actual - 2);
					$fin_paginacion = min($inicio_paginacion + 3, $total_paginas);

					// Mostrar enlaces de paginación
					for ($i = $inicio_paginacion; $i <= $fin_paginacion; $i++) {
						echo "<li class='pagina-item " . ($pagina_actual == $i ? 'active' : '') . "'><a class='pagina-link' href='categorias.php?letra=$letra&pagina=$i'>$i</a></li>";
					}

					// Mostrar puntos suspensivos si hay más páginas disponibles
					if ($total_paginas > $fin_paginacion) {
						echo "<li class='pagina-item'><span class='pagina-link'>...</span></li>";
					}

					// Mostrar la última página si hay más páginas disponibles
					if ($total_paginas > $fin_paginacion) {
						echo "<li class='pagina-item'><a class='pagina-link' href='categorias.php?letra=$letra&pagina=$total_paginas'>$total_paginas</a></li>";
					}

					// Enlace para ir a la página siguiente
					echo "<li class='pagina-item $habilitar_siguiente'><a class='pagina-link' href='categorias.php?letra=$letra&pagina=" . ($pagina_actual + 1) . "'>Siguiente &raquo;</a></li>";

					echo "</ul>";
					echo "</nav>";
				} else {
					// Si no hay resultados, muestra un mensaje indicando que no se encontraron categorías
					echo "<div class='mensaje-else'>";
					echo "No se encontraron categorías que comiencen con la letra " . strtoupper($letra) . ".";
					echo "</div";
				}
			}

			// ESTE CODIGO SACA EL DETALLE DE LA EMPRESA CON LA NUEVA ESTRUCTURA DE BBDD
			if (isset($_GET['empresa'])) {
				// Obtén el ID de la empresa de la URL
				$id_empresa = mysqli_real_escape_string($conexion, $_GET['empresa']);

				// Conexión al servidor
				$conexion = conexionBBDD($servidor, $usrservidor, $pwdservidor, $bbdd);

				$consulta_empresa = "SELECT e.idempresa, e.nombre, e.direccion, e.telefono, e.website, e.google,
									e.lunes, e.martes, e.miercoles, e.jueves, e.viernes, e.sabado, e.domingo 
									FROM empresas AS e
									WHERE e.idempresa = '$id_empresa'";

				$resultado_empresa = mysqli_query($conexion, $consulta_empresa);

				if ($resultado_empresa && mysqli_num_rows($resultado_empresa) > 0) {
					while ($empresa = mysqli_fetch_assoc($resultado_empresa)) {
						echo "<div class='detalle-empresa'>";

						// Información izquierda
						echo "<div class='info-izquierda'>";
						echo "<h3 class='titulo-empresa'>" . $empresa['nombre'] . "</h3>";
						echo "<p><strong>Dirección:</strong> {$empresa['direccion']}</p>";
						echo "<p><strong>Teléfono:</strong> {$empresa['telefono']}</p>";

						// Redes sociales
						echo "<div class='redes-sociales'>";
						echo "<p><strong>Website:</strong> {$empresa['website']}</p>";
						echo "<p><strong>Google:</strong> <a href='https://www." . $empresa['google'] . "'>" . $empresa['google'] . "</a></p>";
						echo "</div>";
						echo "</div>";

						// Información derecha
						echo "<div class='info-derecha'>";
						// Horarios
						echo "<div class='horarios'>";
						echo "<p><strong>Horarios:</strong></p>";
						echo "<ul>";
						if (!empty($empresa['lunes'])) {
							echo "<li>Lunes: {$empresa['lunes']}</li>";
						}
						if (!empty($empresa['martes'])) {
							echo "<li>Martes: {$empresa['martes']}</li>";
						}
						if (!empty($empresa['miercoles'])) {
							echo "<li>Miércoles: {$empresa['miercoles']}</li>";
						}
						if (!empty($empresa['jueves'])) {
							echo "<li>Jueves: {$empresa['jueves']}</li>";
						}
						if (!empty($empresa['viernes'])) {
							echo "<li>Viernes: {$empresa['viernes']}</li>";
						}
						if (!empty($empresa['sabado'])) {
							echo "<li>Sábado: {$empresa['sabado']}</li>";
						}
						if (!empty($empresa['domingo'])) {
							echo "<li>Domingo: {$empresa['domingo']}</li>";
						}
						echo "</ul>";
						echo "</div>";
						echo "</div>"; // Cierre de info-derecha
						echo "</div>"; // Cierre de detalle-empresa
					}
				}
			}
			?>

		</div>
	</div>

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