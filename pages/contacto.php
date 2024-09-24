<!DOCTYPE html>
<html lang="es-ES">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Servifinder - Contacto</title>
	<!-- contacto.php CSS -->
	<link rel="stylesheet" href="../data/css/contacto.css?v=<?php echo (rand()); ?>" />
	<meta name='linkatomic-verify-code' content='76c41ec57dabf97f13964c9fb32c843e' />
</head>

<body>
	<?php
	// evalua el fichero que contiene las constantes y variables de la web
	include_once('../data/datos/constsvars.php');
	// evalua el fichero que contiene las funciones
	include_once('../lib/funciones.php');
	// conexion al servidor
	$conexion = conexionBBDD($servidor, $usrservidor, $pwdservidor, $bbdd);
	?>
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
            <div class="form-container-buscador">
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

	<!--- Formulario de contacto --->
	<div class="form-container">
		<div class="parrafo">
			<h2 class="form-title">&Uacute;nete a Nosotros</h2>
			<p class="card-parrafo"><span><a href="login.php">Darte de alta</a></span> en Servifinder es la mejor manera para impulsar tu negocio. Es ese directorio los usuarios podrán encontrar tu empresa filtrando por sector de actividad y provincia. En la ficha de empresa podrán encontrar todos los métodos de contacto además de links a su página web para generarles más tráfico.</p>
			<p class="card-parrafo">Además podemos modificar la ficha de empresa a su gusto para darles la visibilidad que ustedes quieran.</p>
			<p class="card-parrafo">Encuentra <a href="../pages/busqueda.php?categorias=<?php echo "Cerrajeria" ?>" class="enlace-parrafo">cerrajerias</a> cerca de ti</p>
			<p class="card-parrafo">Encuentra <a href="../pages/busqueda.php?categorias=<?php echo "Electricista" ?>" class="enlace-parrafo">electricistas a domicilio</a> en tu ciudad</p>
			<p class="card-parrafo"><a href="../pages/busqueda.php?categorias=<?php echo "Fontaneria" ?>" class="enlace-parrafo">Fontaneros de urgencia</a> en tu ciudad</p>
			<p class="card-parrafo"><a href="../pages/busqueda.php?categorias=<?php echo "Dentista" ?>" class="enlace-parrafo">Dentistas</a> cerca de ti</p>
		</div>
		<div class="form-body">
			<form action="contacto.php" method="post">
				<div class="form-group">
					<input type="text" class="form-control" name="nom" id="nom" aria-describedby="" placeholder="Nombre y apellidos">
				</div>
				<div class="form-group">
					<input type="tel" class="form-control" name="tel" id="tel" aria-describedby="" placeholder="Teléfono">
				</div>
				<div class="form-group">
					<input type="email" class="form-control" name="ema" id="ema" aria-describedby="" placeholder="Tu correo electrónico">
				</div>
				<div class="form-group">
					<textarea id="mensaje" name="mensaje" placeholder="¿Qué necesitas?"></textarea>
				</div>
				<div class="form-group form-check">
					<input type="checkbox" class="form-check-input" id="exampleCheck1">
					<label class="form-check-label" for="exampleCheck1">He leído y acepto la <a href="politica-privacidad.php" class="badge badge-light">Política de Privacidad</a></label>
				</div>
				<button type="submit" class="btn-enviar" name="enviar">Enviar</button>
			</form>
		</div>
	</div>

	<?php
	//These must be at the top of your script, not inside a function
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require 'PHPMailer/Exception.php';
	require 'PHPMailer/PHPMailer.php';
	require 'PHPMailer/SMTP.php';

	// Verificar si el formulario ha sido enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recoger los datos del formulario
        $nombre = $_POST['nom'];
        $telefono = $_POST['tel'];
        $email = $_POST['ema'];
        $mensaje = $_POST['mensaje'];

		//Create an instance; passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
			//Server settings
			$mail->SMTPDebug = 0;
			$mail->isSMTP();                             
			$mail->Host       = 'smtp.outlook.com';
			$mail->SMTPAuth   = true;
			$mail->Username   = 'agustin-zaragoza1@eep-igroup.com';
			$mail->Password   = 'Map09748';
			$mail->SMTPSecure = 'tls';
			$mail->Port       = 587;

			//Recipients
			$mail->setFrom('agustin-zaragoza1@eep-igroup.com', 'Servifinder Contacto');
			$mail->addAddress('agustinzarpe@gmail.com');

			//Content
			$mail->isHTML(true);
			$mail->CharSet = 'UTF-8';
			$mail->Subject = 'Email de contacto';
			$mail->Body    = $mail->Body = "
											<p>Nombre: $nombre\n</p>
											<p>Teléfono: $telefono\n</p>
											<p>Correo electrónico: $email\n</p>
											<p>Mensaje:\n</p>
											<p>$mensaje</p>
										";
			$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			$mail->send();
			echo "<div class='mensaje'>El correo electrónico se envió correctamente.</div>";
		} catch (Exception $e) {
			echo "<div class='mensaje-error'>Hubo un error al enviar el correo electrónico:  " . $mail->ErrorInfo . "</div>";
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