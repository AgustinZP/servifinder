<!DOCTYPE html>
<html lang="es-ES">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Servifinder - Aviso Legal</title>
	<!-- aviso-legal.php CSS -->
	<link rel="stylesheet" href="../data/css/aviso-legal.css?v=<?php echo (rand()); ?>">
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
	
	<!--- Página del aviso legal de la web Servifinder ---> 
	<div class="card text-left border-0">
    <h1 class="card-title titulo-grande">AVISO LEGAL Y CONDICIONES GENERALES DE USO</h1>
    <div class="card-body">
        <p class="card-text h3">INFORMACIÓN GENERAL</p>
        <p class="card-text">En cumplimiento con el deber de información dispuesto en la Ley 34/2002 de Servicios de la Sociedad de la Información y el Comercio Electrónico (LSSI-CE) de 11 de julio, se facilitan a continuación los siguientes datos de información general de este sitio web:</p>
        <p class="card-text">La titularidad de este sitio web, www.servifinder.es, (en adelante, Sitio Web) la ostenta: Agustin Zaragoza Perez, con NIF: 53212779-W, y cuyos datos de contacto son:</p>
        <ul>
            <li>Dirección: C/ Rafael Bergamin, 11 Madrid</li>
            <li>Teléfono de contacto: 666112684</li>
            <li>Email de contacto: info@servifinder.es</li>
        </ul>

        <p class="card-text h3">TÉRMINOS Y CONDICIONES GENERALES DE USO</p>
        <p class="card-text">El objeto de las condiciones: El Sitio Web</p>
        <p class="card-text">El objeto de las presentes Condiciones Generales de Uso (en adelante, Condiciones) es regular el acceso y la utilización del Sitio Web. A los efectos de las presentes Condiciones se entenderá como Sitio Web: la apariencia externa de los interfaces de pantalla, tanto de forma estática como de forma dinámica, es decir, el árbol de navegación; y todos los elementos integrados tanto en los interfaces de pantalla como en el árbol de navegación (en adelante, Contenidos) y todos aquellos servicios o recursos en línea que en su caso ofrezca a los Usuarios (en adelante, Servicios).</p>
        <p class="card-text">Servifinder se reserva la facultad de modificar, en cualquier momento, y sin aviso previo, la presentación y configuración del Sitio Web y de los Contenidos y Servicios que en él pudieran estar incorporados. El Usuario reconoce y acepta que en cualquier momento Servifinder pueda interrumpir, desactivar y/o cancelar cualquiera de estos elementos que se integran en el Sitio Web o el acceso a los mismos.</p>
        <p class="card-text">El acceso al Sitio Web por el Usuario tiene carácter libre y, por regla general, es gratuito sin que el Usuario tenga que proporcionar una contraprestación para poder disfrutar de ello, salvo en lo relativo al coste de conexión a través de la red de telecomunicaciones suministrada por el proveedor de acceso que hubiere contratado el Usuario.</p>
        <p class="card-text">La utilización de alguno de los Contenidos o Servicios del Sitio Web podrá hacerse mediante la suscripción o registro previo del Usuario.</p>
        
        <p class="card-text h3">EL USUARIO</p>
        <p class="card-text">El acceso, la navegación y uso del Sitio Web, así como por los espacios habilitados para interactuar entre los Usuarios, y el Usuario y Servifinder, como los comentarios y/o espacios de blogging, confiere la condición de Usuario, por lo que se aceptan, desde que se inicia la navegación por el Sitio Web, todas las Condiciones aquí establecidas, así como sus ulteriores modificaciones, sin perjuicio de la aplicación de la correspondiente normativa legal de obligado cumplimiento según el caso. Dada la relevancia de lo anterior, se recomienda al Usuario leerlas cada vez que visite el Sitio Web.</p>
        <p class="card-text">El Sitio Web de Servifinder proporciona gran diversidad de información, servicios y datos. El Usuario asume su responsabilidad para realizar un uso correcto del Sitio Web. Esta responsabilidad se extenderá a:</p>
        <ul>
            <li>Un uso de la información, Contenidos y/o Servicios y datos ofrecidos por Servifinder sin que sea contrario a lo dispuesto por las presentes Condiciones, la Ley, la moral o el orden público, o que de cualquier otro modo puedan suponer lesión de los derechos de terceros o del mismo funcionamiento del Sitio Web.</li>
            <li>La veracidad y licitud de las informaciones aportadas por el Usuario en los formularios extendidos por Servifinder para el acceso a ciertos Contenidos o Servicios ofrecidos por el Sitio Web. En todo caso, el Usuario notificará de forma inmediata a Servifinder acerca de cualquier hecho que permita el uso indebido de la información registrada en dichos formularios, tales como, pero no solo, el robo, extravío, o el acceso no autorizado a identificadores y/o contraseñas, con el fin de proceder a su inmediata cancelación.</li>
        </ul>
        <p class="card-text">Servifinder se reserva el derecho de retirar todos aquellos comentarios y aportaciones que vulneren la ley, el respeto a la dignidad de la persona, que sean discriminatorios, xenófobos, racistas, pornográficos, spamming, que atenten contra la juventud o la infancia, el orden o la seguridad pública o que, a su juicio, no resultaran adecuados para su publicación.</p>
        <p class="card-text">En cualquier caso, Servifinder no será responsable de las opiniones vertidas por los Usuarios a través de comentarios u otras herramientas de blogging o de participación que pueda haber.</p>
        <p class="card-text">El mero acceso a este Sitio Web no supone entablar ningún tipo de relación de carácter comercial entre Servifinder y el Usuario.</p>
        <p class="card-text">El Usuario declara ser mayor de edad y disponer de la capacidad jurídica suficiente para vincularse por las presentes Condiciones. Por lo tanto, este Sitio Web de Servifinder no se dirige a menores de edad. Servifinder declina cualquier responsabilidad por el incumplimiento de este requisito.</p>
        <p class="card-text">El Sitio Web está dirigido principalmente a Usuarios residentes en España. Servifinder no asegura que el Sitio Web cumpla con legislaciones de otros países, ya sea total o parcialmente. Si el Usuario reside o tiene su domiciliado en otro lugar y decide acceder y/o navegar en el Sitio Web lo hará bajo su propia responsabilidad, deberá asegurarse de que tal acceso y navegación cumple con la legislación local que le es aplicable, no asumiendo Servifinder responsabilidad alguna que se pueda derivar de dicho acceso.</p>

        <p class="card-text h3">ACCESO Y NAVEGACIÓN EN EL SITIO WEB: EXCLUSIÓN DE GARANTÍAS Y RESPONSABILIDAD</p>
        <p class="card-text">Servifinder no garantiza la continuidad, disponibilidad y utilidad del Sitio Web, ni de los Contenidos o Servicios. Servifinder hará todo lo posible por el buen funcionamiento del Sitio Web, sin embargo, no se responsabiliza ni garantiza que el acceso a este Sitio Web no vaya a ser ininterrumpido o que esté libre de error.</p>
        <p class="card-text">Tampoco se responsabiliza o garantiza que el contenido o software al que pueda accederse a través de este Sitio Web, esté libre de error o cause un daño al sistema informático (software y hardware) del Usuario. En ningún caso Servifinder será responsable por las pérdidas, daños o perjuicios de cualquier tipo que surjan por el acceso, navegación y el uso del Sitio Web, incluyéndose, pero no limitándose, a los ocasionados a los sistemas informáticos o los provocados por la introducción de virus.</p>
        <p class="card-text">Servifinder tampoco se hace responsable de los daños que pudiesen ocasionarse a los usuarios por un uso inadecuado de este Sitio Web. En particular, no se hace responsable en modo alguno de las caídas, interrupciones, falta o defecto de las telecomunicaciones que pudieran ocurrir.</p>

        <p class="card-text h3">POLÍTICA DE ENLACES</p>
        <p class="card-text">Se informa que el Sitio Web de Servifinder pone o puede poner a disposición de los Usuarios medios de enlace (como, entre otros, links, banners, botones), directorios y motores de búsqueda que permiten a los Usuarios acceder a sitios web pertenecientes y/o gestionados por terceros.</p>
        <p class="card-text">La instalación de estos enlaces, directorios y motores de búsqueda en el Sitio Web tiene por objeto facilitar a los Usuarios la búsqueda de y acceso a la información disponible en Internet, sin que pueda considerarse una sugerencia, recomendación o invitación para la visita de los mismos.</p>
        <p class="card-text">Servifinder no ofrece ni comercializa por sí ni por medio de terceros los productos y/o servicios disponibles en dichos sitios enlazados.</p>
        <p class="card-text">Asimismo, tampoco garantizará la disponibilidad técnica, exactitud, veracidad, validez o legalidad de sitios ajenos a su propiedad a los que se pueda acceder por medio de los enlaces.</p>
        <p class="card-text">Servifinder en ningún caso revisará o controlará el contenido de otros sitios web, así como tampoco aprueba, examina ni hace propios los productos y servicios, contenidos, archivos y cualquier otro material existente en los referidos sitios enlazados.</p>
        <p class="card-text">Servifinder no asume ninguna responsabilidad por los daños y perjuicios que pudieran producirse por el acceso, uso, calidad o licitud de los contenidos, comunicaciones, opiniones, productos y servicios de los sitios web no gestionados por Servifinder y que sean enlazados en este Sitio Web.</p>
        <p class="card-text">El Usuario o tercero que realice un hipervínculo desde una página web de otro, distinto, sitio web al Sitio Web de Servifinder deberá saber que:</p>
        <ul>
            <li>No se permite la reproducción —total o parcialmente— de ninguno de los Contenidos y/o Servicios del Sitio Web sin autorización expresa de Servifinder.</li>
            <li>No se permite tampoco ninguna manifestación falsa, inexacta o incorrecta sobre el Sitio Web de Servifinder, ni sobre los Contenidos y/o Servicios del mismo.</li>
            <li>A excepción del hipervínculo, el sitio web en el que se establezca dicho hiperenlace no contendrá ningún elemento, de este Sitio Web, protegido como propiedad intelectual por el ordenamiento jurídico español, salvo autorización expresa de Servifinder.</li>
            <li>El establecimiento del hipervínculo no implicará la existencia de relaciones entre Servifinder y el titular del sitio web desde el cual se realice, ni el conocimiento y aceptación de Servifinder de los contenidos, servicios y/o actividades ofrecidas en dicho sitio web, y viceversa.</li>
        </ul>

        <p class="card-text h3">PROPIEDAD INTELECTUAL E INDUSTRIAL</p>
        <p class="card-text">Servifinder por sí o como parte cesionaria, es titular de todos los derechos de propiedad intelectual e industrial del Sitio Web, así como de los elementos contenidos en el mismo (a título enunciativo y no exhaustivo, imágenes, sonido, audio, vídeo, software o textos, marcas o logotipos, combinaciones de colores, estructura y diseño, selección de materiales usados, programas de ordenador necesarios para su funcionamiento, acceso y uso, etc.). Serán, por consiguiente, obras protegidas como propiedad intelectual por el ordenamiento jurídico español, siéndoles aplicables tanto la normativa española y comunitaria en este campo, como los tratados internacionales relativos a la materia y suscritos por España.</p>
        <p class="card-text">Todos los derechos reservados. En virtud de lo dispuesto en la Ley de Propiedad Intelectual, quedan expresamente prohibidas la reproducción, la distribución y la comunicación pública, incluida su modalidad de puesta a disposición, de la totalidad o parte de los contenidos de esta página web, con fines comerciales, en cualquier soporte y por cualquier medio técnico, sin la autorización de Servifinder.</p>
        <p class="card-text">El Usuario se compromete a respetar los derechos de propiedad intelectual e industrial de Servifinder. Podrá visualizar los elementos del Sitio Web o incluso imprimirlos, copiarlos y almacenarlos en el disco duro de su ordenador o en cualquier otro soporte físico siempre y cuando sea, exclusivamente, para su uso personal. El Usuario, sin embargo, no podrá suprimir, alterar, o manipular cualquier dispositivo de protección o sistema de seguridad que estuviera instalado en el Sitio Web.</p>
        <p class="card-text">En caso de que el Usuario o tercero considere que cualquiera de los Contenidos del Sitio Web suponga una violación de los derechos de protección de la propiedad intelectual, deberá comunicarlo inmediatamente a Servifinder a través de los datos de contacto del apartado de INFORMACIÓN GENERAL de este Aviso Legal y Condiciones Generales de Uso.</p>

        <p class="card-text h3">ACCIONES LEGALES, LEGISLACIÓN APLICABLE Y JURISDICCIÓN</p>
        <p class="card-text">Servifinder se reserva la facultad de presentar las acciones civiles o penales que considere necesarias por la utilización indebida del Sitio Web y Contenidos, o por el incumplimiento de las presentes Condiciones.</p>
        <p class="card-text">La relación entre el Usuario y Servifinder se regirá por la normativa vigente y de aplicación en el territorio español. De surgir cualquier controversia en relación con la interpretación y/o a la aplicación de estas Condiciones las partes someterán sus conflictos a la jurisdicción ordinaria sometiéndose a los jueces y tribunales que correspondan conforme a derecho.</p>
        <p class="card-text">Este documento de Aviso Legal y Condiciones Generales de uso del sitio web ha sido creado mediante el generador de plantilla de aviso legal y condiciones de uso online el día 04/05/2024.</p>
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