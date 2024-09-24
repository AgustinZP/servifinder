<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es-ES">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Servifinder - Índice</title>	
	<!-- index.php CSS -->
	<link rel="stylesheet" href="data/css/index.css?v=<?php echo (rand()); ?>">
</head>

<body>
	<?php
	// evalua el fichero que contiene las constantes y variables de la web
	include 'data/datos/constsvars.php';
	// evalua el fichero que contiene las funciones
	include 'lib/funciones.php';
	?>
	<!--- header de la página --->
    <nav class="navbar bg-body-tertiary border-0">
        <a class="navbar-brand" href="index.php">
            <img class="img-fluid" src="data/images/logo.png" width="auto" height="200">
        </a>
        <a href="pages/login.php" class="btn btn-outline-success" type="submit">Añade tu negocio</a>
    </nav>

    <!-- Búsqueda por actividad y localidad -->
    <div class="card border-0">
        <div class="card-body">
            <h1 class="card-title" style="margin-top: 50px;">Buscamos los Servicios y Negocios que USTED NECESITA</h1>
            <div class="form-container">
                <form class="d-flex align-items-center" id="searchForm" role="search" action="pages/busqueda.php" method="get">
                    <div class="form-group">
                        <input class="form-control" type="search" id="categorias" name="categorias" placeholder="&iquest;Qu&eacute; buscas? ej: cerrajeros, abogados, fontaneros..." aria-label="Search">
                        <select class="form-control" id="sugerencias1" size="8"></select>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="search" id="localidades" name="localidades" placeholder="&iquest;D&oacute;nde? (localidad o provincia)" aria-label="Search">
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
                    const response = await fetch(`lib/autocompleteCategorias.php?term=${encodeURIComponent(textoIngresado)}`);
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
					const response = await fetch(`lib/autocomplete.php?term=${encodeURIComponent(textoIngresado)}`);
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
	
	<div class="container">
		<img class="ciudad" src="data\images\ciudad.png" style="width: 600px; height: 300px">
	</div>

	<div class="container">
		<!--- menú de una selección de categorías--->
		<div class="card text-center border-0">
			<h1 class="card-title" style="text-align: center; margin-top: 60px;">Todas las categorias que imaginas</h1>
			<h3 class="card-title" style="text-align: center; margin-bottom: 50px;">Encuentra las mejores TAPAS en tu ciudad, Hoteles, Disfraces...<br>
				hemos realizado una selección con todo lo que imaginas</h3>
			<!--- aqui insertar con php una seleccion de categorias -->
			<div class="card-columns">

			<?php
				// Array de categorías destacadas con nombres mostrados al usuario y rutas de imágenes
				$categoriasdestacadas = array(
					"Cerrajeros" => array("nombre_bd" => "cerrajeria", "imagen" => "data/images/cerrajero-verde.png"),
					"Dentistas" => array("nombre_bd" => "dentista", "imagen" => "data/images/dentista-verde.png"),
					"Fisioterapeutas" => array("nombre_bd" => "fisioterapeuta", "imagen" => "data/images/fisioterapia-verde.png"),
					"Electricistas" => array("nombre_bd" => "electricista", "imagen" => "data/images/electricidad-verde.png"),
					"Fontaneros" => array("nombre_bd" => "fontaneria", "imagen" => "data/images/fontanero-verde.png"),
					"Abogados" => array("nombre_bd" => "abogado", "imagen" => "data/images/abogado-verde.png"),
					"Veterinarios" => array("nombre_bd" => "veterinario", "imagen" => "data/images/veterinario-verde.png"),
					"Agencias de Marketing" => array("nombre_bd" => "agencia marketing", "imagen" => "data/images/ordenador-verde.png"),
					"Mecánicos" => array("nombre_bd" => "mecanico", "imagen" => "data/images/mecanico-verde.png")
				);

				// Generar las cards de las categorías destacadas
				foreach ($categoriasdestacadas as $nombre_mostrado => $categoria) {
					$nombre_bd = $categoria['nombre_bd'];
					$imagen = $categoria['imagen'];
				?>

					<div class="col">
						<div class="card border-0">
							<a href="pages/busqueda.php?localidades=&categorias=<?php echo ucwords($nombre_bd); ?>">
								<img class="img-fluid" src="<?php echo $imagen; ?>" class="card-img-top" style="width: 32px; height: 32px;" alt="IMAGEN" />
								<div class="card-body">
									<h5 class="card-title"><?php echo mb_strtoupper($nombre_mostrado, 'UTF8'); ?></h5>
								</div>
							</a>
						</div>
					</div>

				<?php
				}
			?>

			</div>

			<!--- todas las categorias --->
			<div class="card-body">
				<p style="text-align: center;">
					<a class="custom-hover btn btn-primary" href="pages/listacategorias.php?letra=a" role="button">Ver todas las categor&iacute;as</a>
				</p>
			</div>
		</div>

		<!--- menú de una selección de provincias --->
		<div class="card text-center border-0">
			<h3 class="card-title" style="text-align: center; font-weight: bold;">Selección con las ciudades más buscadas</h3>
			<div class="prov-columns">
				<!--- insertamos con php una seleccion de las primeras provincas -->
				<?php
				// Array de provincias con su nombre y enlace
				$localidades = array(
					"Madrid" => "madrid",
					"Barcelona" => "barcelona",
					"Valencia" => "valencia",
					"Sevilla" => "sevilla",
					"Zaragoza" => "zaragoza",
					"Málaga" => "malaga",
					"Bilbao" => "bilbao",
					"Palma de Mallorca" => "palma de mallorca",
					"Murcia" => "murcia",
					"Marbella" => "marbella",
					"Alicante" => "alicante",
					"Las Palmas de Gran Canaria" => "las palmas de gran canaria",
					"Vigo" => "vigo",
					"A Coruña" => "a coruña",
					"Valladolid" => "valladolid",
					"Córdoba" => "cordoba",
					"Granada" => "granada",
					"Elche" => "elche",
					"Donostia - San Sebastián" => "donostia - san sebastian",
					"Gijón" => "gijon",
					"Santa Cruz de Tenerife" => "santa cruz de tenerife",
					"Sabadell" => "sabadell",
					"Oviedo" => "oviedo",
					"Terrassa" => "terrassa",
					"Castellón de la Plana" => "castellon de la plana",
					"Badalona" => "badalona",
					"L' Hospitalet de Llobregat" => "hospitalet de llobregat, l",
					"Almería" => "almeria"
				);				

				// Itera sobre el array de localidades y muestra los enlaces
				foreach ($localidades as $nombre1 => $enlace1) {
					?>
						<div class='col'>
							<div class='card border-0'>
								<a href="pages/busqueda.php?categorias=&localidades=<?php echo $enlace1 ?>" class='badge badge-light'>
									<div class='card-list'>
										<h5 class='card-title'><?php echo $nombre1; ?> </h5>
									</div>
								</a>
							</div>
						</div>
					<?php
					}

				// Array de provincias con su nombre y enlace
				$provincias = array(
					">> Madrid" => "madrid",
					">> Barcelona" => "barcelona",
					">> Valencia" => "valencia",
					">> Alicante" => "alicante",
					">> Malaga" => "malaga",
					">> Sevilla" => "sevilla",
					">> Vizcaya" => "vizcaya",
					">> Murcia" => "murcia",
					">> Illes Balears" => "islas baleares",
					">> A Coruna" => "a coruña",
					">> Girona" => "gerona",
					">> Zaragoza" => "zaragoza",
					">> Pontevedra" => "pontevedra",
					">> Las Palmas" => "las palmas",
					">> Asturias" => "asturias",
					">> Guipuzcoa" => "guipuzcoa",
					">> Cadiz" => "cadiz",
					">> Santa Cruz de Tenerife" => "tenerife",
					">> Granada" => "granada",
					">> Tarragona" => "tarragona",
					">> Cordoba" => "cordoba",
					">> Toledo" => "toledo",
					">> Almeria" => "almeria",
					">> Castellon" => "castellon",
					">> Badajoz" => "badajoz",
					">> Cantabria" => "cantabria",
					">> Lleida" => "lerida",
					">> Valladolid" => "valladolid",
					">> Jaen" => "jaen",
					">> Leon" => "leon",
					">> Navarra" => "navarra",
					">> Ciudad Real" => "ciudad real",
					">> Huelva" => "huelva",
					">> Albacete" => "albacete",
					">> Caceres" => "caceres",
					">> Burgos" => "burgos",
					">> La Rioja" => "la rioja",
					">> Salamanca" => "salamanca",
					">> Lugo" => "lugo",
					">> Ourense" => "orense",
					">> Huesca" => "huesca",
					">> Alava" => "alava",
					">> Cuenca" => "cuenca",
					">> Guadalajara" => "guadalajara",
					">> Zamora" => "zamora",
					">> Palencia" => "palencia",
					">> Segovia" => "segovia",
					">> Avila" => "avila",
					">> Teruel" => "teruel",
					">> Soria" => "soria",
					">> Ceuta" => "ceuta",
					">> Melilla" => "melilla"
				);								

				// Itera sobre el array de provincias y muestra los enlaces
				foreach ($provincias as $nombre2 => $enlace2) {
					?>
						<div class='col'>
							<div class='card border-0'>
								<a href="pages/busqueda.php?categorias=&localidades=<?php echo ucwords($enlace2) . ' - Provincia' ?>" class='badge badge-light'>
									<div class='card-list'>
										<h5 class='card-title'><?php echo $nombre2; ?> </h5>
										<p style="margin-top: -10px; font-size: 12px;">Provincia</p> <!-- Subtítulo -->
									</div>
								</a>
							</div>
						</div>
					<?php
					}
					?>
				</div>
		</div>
	</div>

	<div class="container">
		<!-- Footer de la página -->
		<div class="card text-center border-0">
			<div class="card-header">
				<div class="card footer-card position-relative">
					<img src="data/images/footer_image.jpeg" class="card-img footer-image" alt="FONDO">
					<div class="text-container">
						<!-- Título -->
						<h1 class="card-title mb-3" style="font-size: 40px; color: white;">¿Tienes un Negocio?</h1>
						<!-- Texto -->
						<p class="card-text">Te ayudamos a dar de alta tu empresa en Servifinder</p>
						<p class="card-text">Consigue más clientes, visibilidad y reconocimiento de tu marca.</p>
						<p class="card-text">Deja que te ayudemos a conseguir tus objetivos y a hacer crecer tu negocio.</p>
						<!-- Enlace -->
						<a href="pages/login.php" class="btn btn-primary">Añade tu negocio</a>
					</div>
				</div>
			</div>
		</div>
		
		<div class="card-footer">
			<h5 class="card-title">Informaci&oacute;n legal</h5>
			<p><a class="link-opacity-100" href="pages/contacto.php">Contacto</a></p>
			<p><a class="link-opacity-100" href="pages/perfil.php">A&ntilde;adir negocio</a></p>
			<p><a class="link-opacity-100" href="pages/aviso-legal.php">Aviso legal</a></p>
			<p><a class="link-opacity-100" href="pages/politica-privacidad.php">Pol&iacute;tica de privacidad</a></p>
			<p><a class="link-opacity-100" href="pages/politica-cookies.php">Pol&iacute;tica de Cookies</a></p>
		</div>

		<div class="card">
			<img class="ciudad" src="data\images\ciudad.png" style="width: 500px; height: auto; margin-bottom: 30px;">
		</div>
	</div>
	
	<div class="container-wide">
		<div class="pinkstone">
          <a href="https://agusdev.es/index.html"><p>Diseño web por: Agustín Zaragoza Pérez</p></a>
		</div>
	</div>

</body>

</html>