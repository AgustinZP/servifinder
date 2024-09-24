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
	<title>Servifinder - Ficha</title>
	<!-- ficha.php CSS -->
	<link rel="stylesheet" href="../data/css/ficha.css?v=<?php echo (rand()); ?>" />
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
		// Autocompletado categorias
        document.addEventListener('DOMContentLoaded', () => {
            const categoriasInput = document.getElementById('categorias');
            const sugerenciasCategorias = document.getElementById('sugerencias1');
			//Cogemos el valor del input y lo asignamos a la variable
            async function mostrarSugerencias() {
                const textoIngresado = categoriasInput.value.toLowerCase().trim();
				//Si no se ha escrito texto no se muestran sugerencias
                if (textoIngresado === "") {
                    sugerenciasCategorias.style.display = 'none';
                    return;
                }

                try {
					//Llamada al archivo autocompleteCategorias.php
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

		// Automcompletado localidades y provincias
        document.addEventListener('DOMContentLoaded', () => {
            const localidadInput = document.getElementById('localidades');
            const sugerenciasLocalidad = document.getElementById('sugerencias2');

            async function mostrarSugerencias() {
				//Cogemos el valor del input y lo asignamos a la variable
                const textoIngresado = localidadInput.value.toLowerCase().trim();
				//Si no se ha escrito texto no se muestran sugerencias
                if (textoIngresado === "") {
                    sugerenciasLocalidad.style.display = 'none';
                    return;
                }

                try {
					//Llamada al archivo autocomplete.php
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
                localidadInput.value = sugerenciasLocalidad.value;
                sugerenciasLocalidad.style.display = 'none';
            });
        });
    </script>

	<!-- columnas -->
	<div class="container-resultados">
		<div class="column-izquierda">
			<!-- Mapa -->
			<div class="map-container" style="margin-top: 20px;">
				<?php
				//Guardamos nuestra apikey en una variable
				$apiKey = 'apikeycorrespondiente';
				// Inicializamos la variable del mapa
				$mapSrc = "https://www.google.com/maps/embed/v1/view?key=$apiKey&center=40.463667,-3.74922&zoom=5"; // Centro de España

				// Verificamos si se ha pasado el parámetro empresa y lo guardamos en la variable empresa
				if (isset($_GET['empresa'])) {
					// Obtén el ID de la empresa de la URL
					$id_empresa = mysqli_real_escape_string($conexion, $_GET['empresa']);
					
					// Realizamos la consulta para obtener los detalles de la empresa
					$consulta_empresa = "SELECT direccion FROM empresas WHERE idempresa = '$id_empresa'";
					$resultado_empresa = mysqli_query($conexion, $consulta_empresa);

					if ($resultado_empresa && mysqli_num_rows($resultado_empresa) > 0) {
						$empresa = mysqli_fetch_assoc($resultado_empresa);
						$direccion_empresa = $empresa['direccion'];

						// Generamos la URL del mapa con la dirección de la empresa
						$mapSrc = "https://www.google.com/maps/embed/v1/place?key=$apiKey&q=" . urlencode($direccion_empresa);
					}
				}
				?>
				<iframe width="450" height="400" style="border:0" loading="lazy" allowfullscreen src="<?php echo $mapSrc; ?>"></iframe>
			</div>
		</div>
		
		<div class="column-derecha">
			<?php
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
			

						// Información izquierda
						echo "<div class='info'>";

						// Almacenar los parámetros GET actuales
						$params = $_GET;
						// Agregar el parámetro 'telefono' al array de parámetros
						$params['telefono'] = $empresa['telefono'];
						// Construir la URL con los nuevos parámetros GET
						$telefono_url = http_build_query($params);

						echo "<p class='telefono'><a href='#' id='telefono-enlace'><i class='bi bi-telephone telefono-icono'></i></a></p>";
						echo "<h3 class='titulo-empresa'>" . $empresa['nombre'] . "</h3>";

						// Verificar si se ha proporcionado el parámetro de categoría
						if (isset($_GET['categoria'])) {
							// Obtener y mostrar el nombre de la categoría
							$categoria = htmlspecialchars($_GET['categoria']);
							echo "<p class='categoria-seleccionada'>" . ucwords($categoria) . "</p>";
						}
						
						//Código para no duplicar protocolo (http:// o https://)
						$website = $empresa['website'];
						//Comprobamos si disponemos de la website en BBDD
						if(!empty($website)){
							// Funcion propia de php para buscar coincidencias dentro de una cadena de texto
							if (!preg_match("~^(?:f|ht)tps?://~i", $website)) {
								$website = "http://$website";
							}
							echo "<p class='website'><a href='{$website}' target='_blank'>website</a></p>";
						} else {
							//Si no dispopnemos de la web mostramos mensaje
							echo "<p class='texto'>No disponemos de la website de esta empresa.</p>";
						}
						


						// Contenedor para el teléfono oculto inicialmente
						echo "<div class='info'>";
						echo "<div id='telefono-wrapper'>";
						echo "<div id='telefono-info' style='display: none;'>";
						echo "<p>Recuerda que lo has encontrado en Servifinder</p>";
						echo "<p class='telefono-texto'>" . htmlspecialchars($empresa['telefono']) . "</p>";
						echo "</div>";
						echo "</div>";
						echo "</div>";
						?>

						<!-- SCRIPT PARA MOSTRAR Y OCULTAR EL TELEFONO -->
						<script>
						document.getElementById('telefono-enlace').addEventListener('click', function(event) {
							event.preventDefault(); // Prevenir el comportamiento por defecto del enlace
							var telefonoInfo = document.getElementById('telefono-info');
							if (telefonoInfo.style.display === 'none' || telefonoInfo.style.display === '') {
								telefonoInfo.style.display = 'block';
							} else {
								telefonoInfo.style.display = 'none';
							}
						});
						</script>
                        
                        <?php
                        echo "<div class='info'>";
						echo "<p class='direccion'><strong>Dirección</strong> </p>";
						echo "<p class='texto'>{$empresa['direccion']}</p>";
						echo "<p class='google'><a href='https://www.{$empresa['google']}' target='_blank'>Ver mapa</a></p>";
						echo "</div>";
						?>
						

						<div class='info'>
						<p class='conocer'><strong>Que deberías conocer de <?php echo $empresa['nombre']; ?></strong></p>
						<p class='texto'>No disponemos de información adicional de esta empresa.
						</div>

						<?php
						// Redes Sociales
						echo "<div class='info'>";
						echo "<div class='redes-sociales'>";
						echo "<p class='redes titulo'><strong>Redes Sociales</strong></p>";
						echo "<ul>";
						// Variable para verificar si hay redes sociales
						$hasSocialMedia = false;
						if (!empty($empresa['facebook'])) {
							echo "<li><a href='{$empresa['facebook']}' target='_blank'>Facebook</a></li>";
							$hasSocialMedia = true;
						}
						if (!empty($empresa['twitter'])) {
							echo "<li><a href='{$empresa['twitter']}' target='_blank'>Twitter</a></li>";
							$hasSocialMedia = true;
						}
						if (!empty($empresa['instagram'])) {
							echo "<li><a href='{$empresa['instagram']}' target='_blank'>Instagram</a></li>";
							$hasSocialMedia = true;
						}
						if (!empty($empresa['linkedin'])) {
							echo "<li><a href='{$empresa['linkedin']}' target='_blank'>LinkedIn</a></li>";
							$hasSocialMedia = true;
						}
						if (!empty($empresa['yelp'])) {
							echo "<li><a href='{$empresa['yelp']}' target='_blank'>Yelp</a></li>";
							$hasSocialMedia = true;
						}
						if (!empty($empresa['youtube'])) {
							echo "<li><a href='{$empresa['youtube']}' target='_blank'>YouTube</a></li>";
							$hasSocialMedia = true;
						}
						if (!empty($empresa['pinterest'])) {
							echo "<li><a href='{$empresa['pinterest']}' target='_blank'>Pinterest</a></li>";
							$hasSocialMedia = true;
						}
						echo "</ul>";
						// Si no hay redes sociales, mostrar mensaje
						if (!$hasSocialMedia) {
							echo "<p class='texto'>No disponemos de las redes sociales de esta empresa.</p>";
						}
						echo "</div>";
						echo "</div>";						

						// Horarios
						
						echo "<div class='horarios'>";
						echo "<p class='informacion adicional'><strong>Información adicional</strong></p>";
						echo "<table class='horarios-tabla'>";
						echo "<thead>";
						echo "<tr>";
						echo "<th>Día de la semana</th>";
						echo "<th>Horarios</th>";
						echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
						if (!empty($empresa['lunes'])) {
							echo "<tr><td>Lunes</td><td>{$empresa['lunes']}</td></tr>";
						}
						if (!empty($empresa['martes'])) {
							echo "<tr><td>Martes</td><td>{$empresa['martes']}</td></tr>";
						}
						if (!empty($empresa['miercoles'])) {
							echo "<tr><td>Miércoles</td><td>{$empresa['miercoles']}</td></tr>";
						}
						if (!empty($empresa['jueves'])) {
							echo "<tr><td>Jueves</td><td>{$empresa['jueves']}</td></tr>";
						}
						if (!empty($empresa['viernes'])) {
							echo "<tr><td>Viernes</td><td>{$empresa['viernes']}</td></tr>";
						}
						if (!empty($empresa['sabado'])) {
							echo "<tr><td>Sábado</td><td>{$empresa['sabado']}</td></tr>";
						}
						if (!empty($empresa['domingo'])) {
							echo "<tr><td>Domingo</td><td>{$empresa['domingo']}</td></tr>";
						}
						echo "</tbody>";
						echo "</table>";
						echo "</div>";
						echo "</div>"; // Cierre info
						
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