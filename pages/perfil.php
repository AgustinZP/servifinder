<!DOCTYPE html>
<html lang="es-ES">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Servifinder - Perfil</title>
	<link rel="stylesheet" href="../data/css/perfil.css?v=<?php echo (rand()); ?>" />
</head>

<body>
	<?php
	session_start();
	// evalua el fichero que contiene las constantes y variables de la web
	include_once('../data/datos/constsvars.php');
	// evalua el fichero que contiene las funciones
	include_once('../lib/funciones.php');
	// conexion al servidor
	$conexion = conexionBBDD($servidor, $usrservidor, $pwdservidor, $bbdd);
	// Recuperar el id_usuario de la sesión
	$id_usuario = $_SESSION['id_usuario'] ?? null;
	// Obtener el nombre del usuario logueado
	$nombre_usuario = "";
	if ($id_usuario) {
		$sql_nombre_usuario = "SELECT nombre FROM usuarios WHERE idusuario = ?";
		$stmt_nombre_usuario = $conexion->prepare($sql_nombre_usuario);
		$stmt_nombre_usuario->bind_param("i", $id_usuario);
		$stmt_nombre_usuario->execute();
		$stmt_nombre_usuario->bind_result($nombre_usuario);
		$stmt_nombre_usuario->fetch();
		$stmt_nombre_usuario->close();
	}
	?>
	<!--- header de la página --->
	<nav class="navbar bg-body-tertiary">
		<div class="container-fluid">
			<!--- imagen de Servifinder con enlace a la página principal --->
			<a class="navbar-brand" href="../index.php">
				<img class="img-fluid" src="../data/images/logo.png" width="auto" height="200">
			</a>
			<span class="navbar-text">
				<?php echo "Hola " . ucwords($nombre_usuario) . "!"; ?>
				<a href="cierre-sesion.php" class="cerrar">Cerrar sesión</a>
			</span>
		</div>
	</nav>

	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const categoriasInput = document.getElementById('categorias');
			const sugerenciasCategorias = document.getElementById('sugerencias1');

			async function mostrarSugerencias() {
				const textoIngresado = categoriasInput.value.toLowerCase().trim();

				if (textoIngresado === "") {
					sugerenciasCategorias.innerHTML = '';
					return;
				}

				try {
					const response = await fetch(`autocompleteCategorias.php?term=${encodeURIComponent(textoIngresado)}`);
					const sugerencias = await response.json();

					sugerenciasCategorias.innerHTML = '';

					sugerencias.forEach(sugerencia => {
						const sugerenciaElemento = document.createElement('li');
						sugerenciaElemento.textContent = sugerencia.nombre;
						sugerenciaElemento.classList.add('list-group-item');
						sugerenciaElemento.addEventListener('click', () => {
							categoriasInput.value = sugerencia.nombre;
							sugerenciasCategorias.innerHTML = '';
							// Aquí podrías agregar lógica adicional, como seleccionar la categoría y realizar alguna acción
						});
						sugerenciasCategorias.appendChild(sugerenciaElemento);
					});
				} catch (error) {
					console.error('Error fetching suggestions:', error);
				}
			}

			categoriasInput.addEventListener('input', () => {
				mostrarSugerencias();
			});
		});
	</script>

	<!-- Autocomplete Localidades/Provincias -->
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const localidad = document.getElementById('localidades');
			const sugerenciasLocalidad = document.getElementById('sugerencias2');

			async function mostrarSugerencias() {
				const textoIngresado = localidad.value.toLowerCase().trim();

				console.log('Término de búsqueda:', textoIngresado); // Agrega esta línea para imprimir el término de búsqueda

				if (textoIngresado === "") {
					sugerenciasLocalidad.innerHTML = '';
					return;
				}

				try {
					const response = await fetch(`autocomplete.php?term=${encodeURIComponent(textoIngresado)}`);
					const sugerencias = await response.json();

					console.log('Sugerencias recibidas:', sugerencias); // Agrega esta línea para imprimir las sugerencias recibidas

					sugerenciasLocalidad.innerHTML = '';

					sugerencias.forEach(sugerencia => {
						const sugerenciaElemento = document.createElement('li');
						sugerenciaElemento.textContent = sugerencia.tipo === 'provincia' ? `${sugerencia.nombre} - Provincia` : sugerencia.nombre;
						sugerenciaElemento.classList.add('list-group-item');
						sugerenciaElemento.addEventListener('click', () => {
							localidad.value = sugerenciaElemento.textContent;
							sugerenciasLocalidad.innerHTML = '';
							// Aquí podrías agregar lógica para filtrar las categorías basadas en la sugerencia elegida
						});
						sugerenciasLocalidad.appendChild(sugerenciaElemento);
					});
				} catch (error) {
					console.error('Error fetching suggestions:', error);
				}
			}

			localidad.addEventListener('input', () => {
				mostrarSugerencias();
			});
		});
	</script>

	<div class="container-body">
		<div class="container-pasos">
			<h2>YA CASI HAS TERMINADO</h2>
			<p>Da de alta tu empresa con el formulario de la derecha <i class="bi bi-arrow-right"></i></p>
			<h2>CÓMO?</h2>
			<h3>MUY FÁCIL! SIGUE ESTOS PASOS:</h3>
			<p>1. Añade los datos de la empresa.</p>
			<p>2. Añade el horario comercial de la empresa.</p>
			<p>3. Añade las redes sociales de la empresa.</p>
		</div>

		<div class="form-container">
			<h1 class="form-title">Registra tu empresa gratis en Servifinder</h1>

			<!-- Pestañas -->
			<div class="tabs">
				<button class="tablink active" onclick="openTab('datosEmpresa')">Datos Empresa</button>
				<button class="tablink" onclick="openTab('horarios')">Horarios</button>
				<button class="tablink" onclick="openTab('redesSociales')">Redes Sociales</button>
			</div>

			<!-- Contenido de las pestañas -->
			<div id="datosEmpresa" class="tabcontent">
				<form class="alta-empresa" action="perfil.php" method="post">
					<label for="nombre">Nombre</label>
					<input type="text" id="nombre" name="nombre" required>

					<label for="direccion">Dirección</label>
					<input type="text" id="direccion" name="direccion" required>

					<label for="codigopostal">Código Postal</label>
					<input type="text" id="codigopostal" name="codigopostal">

					<label for="telefono">Teléfono</label>
					<input type="text" id="telefono" name="telefono">

					<input type="submit" name="submit_datos_empresa" value="Guardar">
				</form>
			</div>
			<div id="horarios" class="tabcontent">
				<form class="alta-empresa" action="perfil.php" method="post">

					<label for="lunes">Lunes:</label>
					<input type="text" id="lunes" name="lunes">

					<label for="martes">Martes:</label>
					<input type="text" id="martes" name="martes">

					<label for="miercoles">Miércoles:</label>
					<input type="text" id="miercoles" name="miercoles">

					<label for="jueves">Jueves:</label>
					<input type="text" id="jueves" name="jueves">

					<label for="viernes">Viernes:</label>
					<input type="text" id="viernes" name="viernes">

					<label for="sabado">Sábado:</label>
					<input type="text" id="sabado" name="sabado">

					<label for="domingo">Domingo:</label>
					<input type="text" id="domingo" name="domingo">

					<input type="submit" name="submit_horarios" value="Guardar">
				</form>
			</div>
			<div id="redesSociales" class="tabcontent">
				<form class="alta-empresa" action="perfil.php" method="post">

					<label for="website">Sitio web:</label>
					<input type="text" id="website" name="website">

					<label for="facebook">Facebook:</label>
					<input type="text" id="facebook" name="facebook">

					<label for="twitter">Twitter:</label>
					<input type="text" id="twitter" name="twitter">

					<label for="instagram">Instagram:</label>
					<input type="text" id="instagram" name="instagram">

					<label for="linkedin">LinkedIn:</label>
					<input type="text" id="linkedin" name="linkedin">

					<label for="yelp">Yelp:</label>
					<input type="text" id="yelp" name="yelp">

					<label for="youtube">YouTube:</label>
					<input type="text" id="youtube" name="youtube">

					<label for="pinterest">Pinterest:</label>
					<input type="text" id="pinterest" name="pinterest">

					<label for="google">Google:</label>
					<input type="text" id="google" name="google">

					<input type="submit" name="submit_redes_sociales" value="Guardar">
				</form>
			</div>
		</div>

		<?php
		// Verificar si se ha enviado un formulario POST
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			// Verificar si la conexión ha fallado
			if ($conexion->connect_error) {
				die("La conexión ha fallado: " . $conexion->connect_error);
			}

			// Recuperar el id_usuario de la sesión
			$id_usuario = $_SESSION['id_usuario'] ?? null;

			// Verificar si el usuario está autenticado
			if ($id_usuario) {
				if (isset($_POST['submit_datos_empresa'])) {
					// Obtener datos del formulario
					$nombre = $_POST['nombre'] ?? '';
					$direccion = $_POST['direccion'] ?? '';
					$codigopostal = $_POST['codigopostal'] ?? '';
					$telefono = $_POST['telefono'] ?? '';

					// Insertar datos de la empresa en la base de datos
					$sql = "INSERT INTO empresas (nombre, direccion, codigopostal, telefono, idusuario) 
				VALUES (?, ?, ?, ?, ?)";
					$stmt = $conexion->prepare($sql);
					$stmt->bind_param("ssisi", $nombre, $direccion, $codigopostal, $telefono, $id_usuario);

					if ($stmt->execute()) {
						// Obtenemos el idempresa recién creado
						$empresa_id = $conexion->insert_id;

						// Guardar el idempresa en la sesión
						$_SESSION['empresa_id'] = $empresa_id;

						echo "<div class='mensaje'>Empresa registrada correctamente!</div>";
					} else {
						echo "Error al guardar los datos de la empresa: " . $conexion->error;
					}
					$stmt->close();
				} elseif (isset($_POST['submit_horarios'])) {
					// Obtener el ID de la empresa desde el campo oculto
					$empresa_id = $_POST['empresa_id'] ?? '';

					$empresa_id = $_SESSION['empresa_id'] ?? '';

					// Verificar si el ID de la empresa es válido
					if (!empty($empresa_id)) {
						// Obtener datos de horarios del formulario
						$lunes = $_POST['lunes'] ?? '';
						$martes = $_POST['martes'] ?? '';
						$miercoles = $_POST['miercoles'] ?? '';
						$jueves = $_POST['jueves'] ?? '';
						$viernes = $_POST['viernes'] ?? '';
						$sabado = $_POST['sabado'] ?? '';
						$domingo = $_POST['domingo'] ?? '';

						// Actualizar los datos de horarios en la base de datos
						$sql = "UPDATE empresas SET lunes=?, martes=?, miercoles=?, jueves=?, viernes=?, sabado=?, domingo=? WHERE idempresa=?";
						$stmt = $conexion->prepare($sql);
						$stmt->bind_param("sssssssi", $lunes, $martes, $miercoles, $jueves, $viernes, $sabado, $domingo, $empresa_id);

						if ($stmt->execute()) {
							echo "<div class='mensaje'>Horarios actualizados correctamente!</div>";
						} else {
							echo "Error al actualizar los horarios: " . $conexion->error;
						}
						$stmt->close();
					} else {
						echo "<div class='mensaje-error'>Error: ID de empresa no válido.</div>";
					}
				} elseif (isset($_POST['submit_redes_sociales'])) {
					// Obtener el ID de la empresa desde el campo oculto
					$empresa_id = $_POST['empresa_id'] ?? '';

					$empresa_id = $_SESSION['empresa_id'] ?? '';

					// Verificar si el ID de la empresa es válido
					if (!empty($empresa_id)) {
						// Obtener datos de redes sociales del formulario
						$website = $_POST['website'] ?? '';
						$facebook = $_POST['facebook'] ?? '';
						$twitter = $_POST['twitter'] ?? '';
						$instagram = $_POST['instagram'] ?? '';
						$linkedin = $_POST['linkedin'] ?? '';
						$yelp = $_POST['yelp'] ?? '';
						$youtube = $_POST['youtube'] ?? '';
						$pinterest = $_POST['pinterest'] ?? '';
						$google = $_POST['google'] ?? '';

						// Actualizar los datos de redes sociales en la base de datos
						$sql = "UPDATE empresas SET website=?, facebook=?, twitter=?, instagram=?, linkedin=?, yelp=?, youtube=?, pinterest=?, google=? WHERE idempresa=?";
						$stmt = $conexion->prepare($sql);
						$stmt->bind_param("sssssssssi", $website, $facebook, $twitter, $instagram, $linkedin, $yelp, $youtube, $pinterest, $google, $empresa_id);

						if ($stmt->execute()) {
							echo "<div class='mensaje'>Redes sociales actualizadas correctamente!</div>";
						} else {
							echo "<div class='mensaje-error'>Error al actualizar las redes sociales: </div>" . $conexion->error;
						}
						$stmt->close();
					} else {
						echo "<div class='mensaje-error'>Error: ID de empresa no válido.</div>";
					}
				}
			} else {
				echo "<div class='mensaje-error'>Error: Usuario no autenticado.</div>";
			}
		}
		?>
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
	<script>
		// Función para cambiar de pestaña
		function openTab(tabName) {
			var i, tabcontent, tablinks;
			tabcontent = document.getElementsByClassName("tabcontent");
			for (i = 0; i < tabcontent.length; i++) {
				tabcontent[i].style.display = "none";
			}
			document.getElementById(tabName).style.display = "block";
		}
		// Por defecto, mostrar la primera pestaña al cargar la página
		openTab('datosEmpresa');
	</script>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			const tabs = document.querySelectorAll(".tablink");
			tabs.forEach(tab => {
				tab.addEventListener("click", function() {
					tabs.forEach(t => t.classList.remove("active"));
					this.classList.add("active");
				});
			});
		});
	</script>

</body>

</html>