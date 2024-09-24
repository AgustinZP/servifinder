<!DOCTYPE html>
<html lang="es-ES">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Servifinder - Cookies</title>
	<!-- politica-cookies.php CSS -->
	<link rel="stylesheet" href="../data/css/politica-cookies.css?v=<?php echo (rand()); ?>">
</head>

<body>
	<?php
	// evalua el fichero que contiene las constantes y variables de la web
	include_once('../data/datos/constsvars.php');
	// evalua el fichero que contiene las funciones
	include_once('../lib/funciones.php');
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

	<!--- Página de la política de cookies de la web Servifinder --->
	<div class="card text-left border-0">
		<h1 class="card-title titulo-grande">POLÍTICA DE COOKIES</h1>
		<!--- aqui insertar la política de cookies -->
		<div class="card-body">
			<p class="card-text">En esta web se utilizan cookies de terceros y propias para conseguir que tengas una mejor experiencia de navegación, puedas compartir contenido en redes sociales y para que podamos obtener estadísticas de los usuarios.</p>
			<p class="card-text">Puedes evitar la descarga de cookies a través de la configuración de tu navegador, evitando que las cookies se almacenen en su dispositivo.</p>
			<p class="card-text">Como propietario de este sitio web, te comunico que no utilizamos ninguna información personal procedente de cookies, tan sólo realizamos estadísticas generales de visitas que no suponen ninguna información personal.</p>
			<p class="card-text">Es muy importante que leas la presente política de cookies y comprendas que, si continúas navegando, consideraremos que aceptas su uso.</p>
			<p class="card-text">Según los términos incluidos en el artículo 22.2 de la Ley 34/2002 de Servicios de la Sociedad de la Información y Comercio Electrónico, si continúas navegando, estarás prestando tu consentimiento para el empleo de los referidos mecanismos.</p>
			<p class="card-text h3">Entidad Responsable</p>
			<p class="card-text">La entidad responsable de la recogida, procesamiento y utilización de tus datos personales, en el sentido establecido por la Ley de Protección de Datos Personales es la página https://servifinder.com/, propiedad de Servifinder – .</p>
			<p class="card-text h3">¿Qué son las cookies?</p>
			<p class="card-text">Las cookies son un conjunto de datos que un servidor deposita en el navegador del usuario para recoger la información de registro estándar de Internet y la información del comportamiento de los visitantes en un sitio web. Es decir, se trata de pequeños archivos de texto que quedan almacenados en el disco duro del ordenador y que sirven para identificar al usuario cuando se conecta nuevamente al sitio web. Su objetivo es registrar la visita del usuario y guardar cierta información. Su uso es común y frecuente en la web ya que permite a las páginas funcionar de manera más eficiente y conseguir una mayor personalización y análisis sobre el comportamiento del usuario.</p>
			<p class="card-text h3">¿Qué tipos de cookies existen?</p>
			<p class="card-text">Las cookies utilizadas en nuestro sitio web, son de sesión y de terceros, y nos permiten almacenar y acceder a información relativa al idioma, el tipo de navegador utilizado, y otras características generales predefinidas por el usuario, así como, seguir y analizar la actividad que lleva a cabo, con el objeto de introducir mejoras y prestar nuestros servicios de una manera más eficiente y personalizada.</p>
			<p class="card-text">Las cookies, en función de su permanencia, pueden dividirse en cookies de sesión o permanentes. Las que expiran cuando el usuario cierra el navegador. Las que expiran en función de cuando se cumpla el objetivo para el que sirven (por ejemplo, para que el usuario se mantenga identificado en los servicios de Servifinder) o bien cuando se borran manualmente.</p>
			<table class="table">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Tipo</th>
						<th>Caducidad</th>
						<th>Finalidad</th>
						<th>Clase</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>__utma</td>
						<td>De Terceros (Google Analytics)</td>
						<td>2 años</td>
						<td>Se usa para distinguir usuarios y sesiones</td>
						<td>No Exenta</td>
					</tr>
					<tr>
						<td>__utmb</td>
						<td>De Terceros (Google Analytics)</td>
						<td>30 minutos</td>
						<td>Se usa para determinar nuevas sesiones o visitas</td>
						<td>No Exenta</td>
					</tr>
					<tr>
						<td>__utmc</td>
						<td>De Terceros (Google Analytics)</td>
						<td>Al finalizar la sesión Se configura para su uso con Urchin</td>
						<td>No Exenta</td>
					</tr>
					<tr>
						<td>__utmz</td>
						<td>De Terceros (Google Analytics)</td>
						<td>6 meses</td>
						<td>Almacena el origen o la campaña que explica cómo el usuario ha llegado hasta la página web</td>
						<td>No Exenta</td>
					</tr>
				</tbody>
			</table>
			<p class="card-text">Adicionalmente, en función de su objetivo, las cookies pueden clasificarse de la siguiente forma:</p>
			<p class="card-text h3">Cookies de rendimiento</p>
			<p class="card-text">Este tipo de Cookie recuerda sus preferencias para las herramientas que se encuentran en los servicios, por lo que no tiene que volver a configurar el servicio cada vez que usted visita. A modo de ejemplo, en esta tipología se incluyen: Ajustes de volumen de reproductores de vídeo o sonido. Las velocidades de transmisión de vídeo que sean compatibles con su navegador. Los objetos guardados en el “carrito de la compra” en los servicios de e-commerce tales como tiendas.</p>
			<p class="card-text">Cookies de geo-localización</p>
			<p class="card-text">Estas cookies son utilizadas para averiguar en qué país se encuentra cuando se solicita un servicio. Esta cookie es totalmente anónima, y sólo se utiliza para ayudar a orientar el contenido a su ubicación.</p>
			<p class="card-text h3">Cookies de registro</p>
			<p class="card-text">Las cookies de registro se generan una vez que el usuario se ha registrado o posteriormente ha abierto su sesión, y se utilizan para identificarle en los servicios con los siguientes objetivos:</p>
			<p class="card-text">Mantener al usuario identificado de forma que, si cierra un servicio, el navegador o el ordenador y en otro momento u otro día vuelve a entrar en dicho servicio, seguirá identificado, facilitando así su navegación sin tener que volver a identificarse. Esta funcionalidad se puede suprimir si el usuario pulsa la funcionalidad [cerrar sesión], de forma que esta cookie se elimina y la próxima vez que entre en el servicio el usuario tendrá que iniciar sesión para estar identificado.</p>

			<p class="card-text">Comprobar si el usuario está autorizado para acceder a ciertos servicios, por ejemplo, para participar en un concurso.</p>
			<p class="card-text">Adicionalmente, algunos servicios pueden utilizar conectores con redes sociales tales como Facebook o Twitter. Cuando el usuario se registra en un servicio con credenciales de una red social, autoriza a la red social a guardar una Cookie persistente que recuerda su identidad y le garantiza acceso a los servicios hasta que expira. El usuario puede borrar esta Cookie y revocar el acceso a los servicios mediante redes sociales actualizando sus preferencias en la red social específica.</p>
			<p class="card-text h3">Cookies de analíticas</p>
			<p class="card-text">Cada vez que un usuario visita un servicio, una herramienta de un proveedor externo genera una cookie analítica en el ordenador del usuario. Esta cookie que sólo se genera en la visita, servirá en próximas visitas a los servicios de Servifinder para identificar de forma anónima al visitante. Los objetivos principales que se persiguen son:</p>
			<ul>
				<li>Permitir la identificación anónima de los usuarios navegantes a través de la cookie (identifica navegadores y dispositivos, no personas) y por lo tanto la contabilización aproximada del número de visitantes y su tendencia en el tiempo.</li>
				<li>Identificar de forma anónima los contenidos más visitados y por lo tanto más atractivos para los usuarios. Saber si el usuario que está accediendo es nuevo o repite visita.</li>
			</ul>
			<p class="card-text">Importante: Salvo que el usuario decida registrarse en un servicio de Servifinder, la cookie nunca irá asociada a ningún dato de carácter personal que pueda identificarle. Dichas cookies sólo serán utilizadas con propósitos estadísticos que ayuden a la optimización de la experiencia de los usuarios en el sitio.</p>
			<p class="card-text h3">Cookies de publicidad</p>
			<p class="card-text">Este tipo de cookies permiten ampliar la información de los anuncios mostrados a cada usuario anónimo en los servicios de Servifinder. Entre otros, se almacena la duración o frecuencia de visualización de posiciones publicitarias, la interacción con las mismas, o los patrones de navegación y/o comportamientos del usuario ya que ayudan a conformar un perfil de interés publicitario. De este modo, permiten ofrecer publicidad afín a los intereses del usuario.</p>
			<p class="card-text h3">Cookies publicitarias de terceros</p>
			<p class="card-text">Además de la publicidad gestionada por las webs de Servifinder en sus servicios, las webs de Servifinder ofrecen a sus anunciantes la opción de servir anuncios a través de terceros (“Ad-Servers”). De este modo, estos terceros pueden almacenar cookies enviadas desde los servicios de Servifinder procedentes de los navegadores de los usuarios, así como acceder a los datos que en ellas se guardan.</p>
			<p class="card-text">Las empresas que generan estas cookies tienen sus propias políticas de privacidad. En la actualidad, las webs de Servifinder utilizan la plataforma Doubleclick (Google) para gestionar estos servicios. Para más información, acuda a</p>
			<p class="card-text"><a href="http://www.google.es/policies/privacy/ads/#toc-doubleclick y a http://www.google.es/policies/privacy/ads/">http://www.google.es/policies/privacy/ads/#toc-doubleclick y a http://www.google.es/policies/privacy/ads/</a>.</p>
			<p class="card-text h3">¿Cómo puedo deshabilitar las cookies en mi navegador?</p>
			<p class="card-text">Se pueden configurar los diferentes navegadores para avisar al usuario de la recepción de cookies y, si se desea, impedir su instalación en el equipo. Asimismo, el usuario puede revisar en su navegador qué cookies tiene instaladas y cuál es el plazo de caducidad de las mismas, pudiendo eliminarlas.</p>
			<p class="card-text">Para ampliar esta información consulte las instrucciones y manuales de su navegador:</p>
			<p class="card-text">Para más información sobre la administración de las cookies en Google Chrome: <a href="https://support.google.com/chrome/answer/95647?hl=es">https://support.google.com/chrome/answer/95647?hl=es</a></p>
			<p class="card-text">Para más información sobre la administración de las cookies en Internet Explorer: <a href="http://windows.microsoft.com/es-es/windows-vista/cookies-frequently-asked-questions">http://windows.microsoft.com/es-es/windows-vista/cookies-frequently-asked-questions</a></p>
			<p class="card-text">Para más información sobre la administración de las cookies en Mozilla Firefox: <a href="http://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-que-los-sitios-we">http://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-que-los-sitios-we</a></p>
			<p class="card-text">Para más información sobre la administración de las cookies en Safari: <a href="http://www.apple.com/es/privacy/use-of-cookies/">http://www.apple.com/es/privacy/use-of-cookies/</a></p>
			<p class="card-text">Para más información sobre la administración de las cookies en Opera: <a href="http://help.opera.com/Windows/11.50/es-ES/cookies.html">http://help.opera.com/Windows/11.50/es-ES/cookies.html</a></p>
			<p class="card-text">Si desea dejar de ser seguido por Google Analytics visite: <a href="http://tools.google.com/dlpage/gaoptout">http://tools.google.com/dlpage/gaoptout</a></p>
			<p class="card-text h3">Para saber más sobre las cookies</p>
			<p class="card-text">Puede obtener más información sobre la publicidad online basada en el comportamiento y la privacidad online en el siguiente enlace: <a href="http://www.youronlinechoices.com/es/">http://www.youronlinechoices.com/es/</a>.</p>
			<p class="card-text">Protección de datos de Google Analytics: <a href="http://www.google.com/analytics/learn/privacy.html">http://www.google.com/analytics/learn/privacy.html</a></p>

			<p class="card-text">Cómo usa Google Analytics las cookies: <a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/cookie-usage?hl=es#analyticsjs">https://developers.google.com/analytics/devguides/collection/analyticsjs/cookie-usage?hl=es#analyticsjs</a></p>
			<p class="card-text h3">Actualizaciones y cambios en la política de privacidad/cookies</p>
			<p class="card-text">Las webs de Servifinder pueden modificar esta Política de Cookies en función de exigencias legislativas, reglamentarias, o con la finalidad de adaptar dicha política a las instrucciones dictadas por la Agencia Española de Protección de Datos, por ello se aconseja a los usuarios que la visiten periódicamente.</p>
			<p class="card-text">Cuando se produzcan cambios significativos en esta Política de Cookies, estos se comunicarán a los usuarios bien mediante la web o a través de correo electrónico a los usuarios registrados.</p>
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