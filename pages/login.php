<!DOCTYPE html>
<html lang="es-ES">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Servifinder - Login</title>
	<!-- login.php CSS -->
	<link rel="stylesheet" href="../data/css/login.css?v=<?php echo (rand()); ?>" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
</head>

<body>
	<?php
	// evalua el fichero que contiene las constantes y variables de la web
	include_once('../data/datos/constsvars.php');
	// evalua el fichero que contiene las funciones
	include_once('../lib/funciones.php');
	// conexion al servidor
	$conexion = conexionBBDD($servidor, $usrservidor, $pwdservidor, $bbdd);
	session_start();

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if ($conexion->connect_error) {
			die("La conexión ha fallado: " . $conexion->connect_error);
		}

		$email = $_POST['email'] ?? '';
		$password = $_POST['password'] ?? '';

		if (!empty($email) && !empty($password)) {
			// Escapar los datos para evitar la inyección SQL
			$email = $conexion->real_escape_string($email);
			$password = $conexion->real_escape_string($password);

			// Consulta preparada para evitar la inyección SQL
			$stmt = $conexion->prepare("SELECT idusuario, contrasena FROM usuarios WHERE email = ?");
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$stmt->store_result();

			if ($stmt->num_rows > 0) {
				$stmt->bind_result($id_usuario, $stored_password);
				$stmt->fetch();

				// Verificar la contraseña
				if ($password === $stored_password) {
					// Usuario autenticado, almacenar el id_usuario en la sesión
					$_SESSION['id_usuario'] = $id_usuario;

					// Redirigir al usuario a la página 'anadir.php'
					header("Location: perfil.php");
					exit;
				} else {
					// Contraseña incorrecta
					header("Location: login.php?error=invalid_credentials");
					exit;
				}
			} else {
				// Usuario no encontrado
				header("Location: registro.php?error=user_not_found");
				exit;
			}
		}
	}
	?>
	<!--- header de la página --->
    <nav class="navbar bg-body-tertiary border-0">
        <a class="navbar-brand" href="../index.php">
            <img class="img-fluid" src="../data/images/logo.png" width="auto" height="200">
        </a>
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

	<div class="container-body">
		<div class="container-pasos">
			<h2>YA ESTÁS REGISTRADO?</h2>
			<p>Accede a tu perfil con el formulario de la derecha <i class="bi bi-arrow-right"></i></p>
			<h2>NO LO ESTÁS?</h2>
			<h3>SIGUE ESTOS PASOS:</h3>
			<p>Regístrate haciendo click <a href="registro.php">AQUI</a></p>
			<p>Accede a tu perfil</p>
		</div>
		<!-- Formulario de login -->
		<div class="form-container">
			<h1 class="form-title">Accede a tu perfil de usuario</h1>
			<div class="form-body">
				<form class="alta-usuario" action="login.php" method="post">
					<label for="email">Email</label>
					<input type="email" id="email" name="email" required>

					<label for="password">Contraseña</label>
					<input type="password" id="password" name="password" required>
					<button type="button" id="toggle" onclick="togglePassword()">Mostrar</button>

					<label for="submit"></label>
					<input type="submit" value="Enviar">

					<p class='parrafo-login'>¿No estás registrado? Haz click <a href='registro.php' class="enlace">aquí</a></p>
				</form>
			</div>
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

	<!-- script para mostrar y ocultar contraseña en el formulario -->
	<script>
	function togglePassword() {
    var passwordInput = document.getElementById("password");
    var toggleButton = document.getElementById("toggle");    
	if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleButton.textContent = "Ocultar";
    } else {
        passwordInput.type = "password";
        toggleButton.textContent = "Mostrar";
    }
}
</script>

</body>

</html>