<!DOCTYPE html>
<html lang="es-ES">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Servifinder - Privacidad</title>
	<!-- politica-privacidad.php CSS -->
	<link rel="stylesheet" href="../data/css/politica-privacidad.css?v=<?php echo (rand()); ?>">
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
	
	<!--- Página de la política de privacidad de la web Servifinder ---> 
	<div class="card text-left border-0">
		<h1 class="card-title titulo-grande">PRIVACIDAD</h1>
		<!--- aqui insertar la política de privacidad -->
		<div class="card-body">
    <p class="card-text h3">Responsable – ¿Quién es el responsable del tratamiento de los datos?</p>
    <p class="card-text">Identidad: Servifinder</p>
    <p class="card-text">Domicilio social: C/ Rafael Bergamin, 11 Madrid</p>
    <p class="card-text">Correo Electrónico: info@servifinder.es</p>
    <p class="card-text">Contacto: Servifinder</p>
    <p class="card-text">Nombre del dominio: https://servifinder.es/</p>
    <p class="card-text h3">Finalidades – ¿Con qué finalidades tratamos tus datos?</p>
    <p class="card-text">En cumplimiento de lo dispuesto en el Reglamento Europeo 2016/679 General de Protección de Datos, te informamos de que trataremos los datos que nos facilitas para:</p>
    <ul>            
        <li>Gestionar la contratación de servicios que realice a través de la Plataforma, así como la facturación y entrega correspondiente.</li>
        <li>Remitir periódicamente comunicaciones sobre servicios, eventos y noticias relacionadas con las actividades desarrolladas por Servifinder, por cualquier medio (teléfono, correo postal o email), salvo que se indique lo contrario o el usuario se oponga o revoque su consentimiento.</li>
        <li>Remitir información comercial y/o promocional relacionada con el sector de servicios contratados y valor añadido para usuarios finales, salvo que se indique lo contrario o el usuario se oponga o revoque su consentimiento.</li>
        <li>Dar cumplimiento a las obligaciones legalmente establecidas, así como verificar el cumplimiento de las obligaciones contractuales, incluida la prevención de fraude.</li>
        <li>Cesión de datos a organismos y autoridades, siempre y cuando sean requeridos de conformidad con las disposiciones legales y reglamentarias.</li>
    </ul>
    <p class="card-text h3">Categorías de datos – ¿Qué datos tratamos?</p>
    <p class="card-text">Derivada de las finalidades antes mencionadas, en Servifinder gestionamos las siguientes categorías de datos:</p>
    <ul>
        <li>Datos identificativos</li>
        <li>Metadatos de comunicaciones electrónicas</li>
        <li>Datos de información comercial. En caso de que el usuario facilite datos de terceros, manifiesta contar con el consentimiento de estos y se compromete a trasladarle la información contenida en esta cláusula, eximiendo a Servifinder de cualquier responsabilidad en este sentido.</li>
        <li>No obstante, Servifinder podrá llevar a cabo las verificaciones para constatar este hecho, adoptando las medidas de diligencia debida que correspondan, conforme a la normativa de protección de datos.</li>
    </ul>
    <p class="card-text h3">Legitimación – ¿Cuál es la legitimación para el tratamiento de tus datos?</p>
    <p class="card-text">El tratamiento de datos cuyo fin es el envío de boletines periódicos (newslettering) sobre servicios, eventos y noticias relacionadas con nuestra actividad profesional se basa en el consentimiento del interesado, solicitado expresamente para llevar a cabo dichos tratamientos, de acuerdo con la normativa vigente. Además, la legitimación para el tratamiento de los datos relacionados con ofertas o colaboraciones se basan en el consentimiento del usuario que remite sus datos, que puede retirar en cualquier momento, si bien ello puede afectar a la posible comunicación de forma fluida y obstrucción de procesos que desea realizar. Por último, los datos se podrán utilizar para dar cumplimiento a las obligaciones legales aplicables a Servifinder.</p>
    <p class="card-text h3">Plazo de Conservación de los Datos – ¿Por cuánto tiempo conservaremos tus datos?</p>
    <p class="card-text">Servifinder conservará los datos personales de los usuarios únicamente durante el tiempo necesario para la realización de las finalidades para las que fueron recogidos, mientras no revoque los consentimientos otorgados. Posteriormente, en caso de ser necesario, mantendrá la información bloqueada durante los plazos legalmente establecidos.</p>
    <p class="card-text h3">Destinatarios ¿A qué destinatarios se comunicarán tus datos?</p>
    <p class="card-text">Tus datos podrán ser accedidos por aquellos proveedores que prestan servicios a Servifinder, tales como servicios de alojamiento, herramientas de marketing y sistemas de contenido u otros profesionales, cuando dicha comunicación sea necesaria normativamente, o para la ejecución de los servicios contratados.</p>
    <p class="card-text">Servifinder, ha suscrito los correspondientes contratos de encargo de tratamiento con cada uno de los proveedores que prestan servicios a Servifinder, con el objetivo de garantizar que dichos proveedores tratarán tus datos de conformidad con lo establecido en la legislación vigente.</p>
    <p class="card-text">También podrán ser cedidos a las Fuerzas y Cuerpos de Seguridad del Estado en los casos que exista una obligación legal.</p>
    <p class="card-text">Bancos y entidades financieras, para el cobro de los servicios.</p>
    <p class="card-text">Administraciones públicas con competencia en los sectores de actividad, cuando así lo establezca la normativa vigente.</p>
    <p class="card-text h3">Seguridad de la Información – ¿Qué medidas de seguridad implantamos para cuidar sus datos?</p>
    <p class="card-text">Para proteger las diferentes tipologías de datos reflejados en esta política de privacidad llevará a cabo las medidas de seguridad técnicas necesarias para evitar su pérdida, manipulación, difusión o alteración.</p>
    <ul>
        <li>Encriptación de las comunicaciones entre el dispositivo del usuario y los servidores de Servifinder.</li>
        <li>Encriptación de la información en los propios servidores de Servifinder.</li>
        <li>Otras medidas que eviten el acceso a los datos del usuario por parte de terceros.</li>
        <li>En aquellos casos en los que Servifinder cuente con prestadores de servicio para el mantenimiento de la plataforma que se encuentren fuera de la Unión Europea, estas transferencias internacionales se hayan regularizadas atendiendo al compromiso de Servifinder con la protección, integridad y seguridad de los datos personales de los usuarios.</li>
    </ul>
    <p class="card-text h3">Derechos – ¿Cuáles son tus derechos cuando nos facilitas tus datos y cómo puedes ejercerlos?</p>
    <p class="card-text">Tienes derecho a obtener confirmación sobre si en Servifinder estamos tratando datos personales que te conciernan, o no. Asimismo, tienes derecho a acceder a tus datos personales, así como a solicitar la rectificación de los datos inexactos o, en su caso, solicitar su supresión cuando, entre otros motivos, los datos ya no sean necesarios para los fines que fueron recogidos.</p>
    <p class="card-text">En determinadas circunstancias, podrás solicitar la limitación del tratamiento de tus datos, en cuyo caso únicamente los conservaremos para el ejercicio o la defensa de reclamaciones.</p>
    <p class="card-text">En determinadas circunstancias y por motivos relacionados con tu situación particular, podrás oponerte al tratamiento de tus datos. Servifinder dejará de tratar los datos, salvo por motivos legítimos imperiosos, o el ejercicio o la defensa de posibles reclamaciones.</p>
    <p class="card-text">Asimismo, puedes ejercer el derecho a la portabilidad de los datos, así como retirar los consentimientos facilitados en cualquier momento, sin que ello afecte a la licitud del tratamiento basado en el consentimiento previo a su retirada.</p>
    <p class="card-text">Si deseas hacer uso de cualquiera de tus derechos puedes dirigirte a <a href="mailto:info@servifinder.es">info@servifinder.es</a>.</p>
    <p class="card-text">Por último, te informamos que puedes dirigirte ante la Agencia Española de Protección de Datos y demás organismos públicos competentes para cualquier reclamación derivada del tratamiento de tus datos personales.</p>
    <p class="card-text h3">Modificación de la política de privacidad</p>
    <p class="card-text">Servifinder podrá modificar la presente Política de Privacidad en cualquier momento, siendo publicadas las sucesivas versiones en el Sitio Web. En cualquier caso, Servifinder comunicará con previo aviso las modificaciones de la presente política que afecten a los usuarios a fin de que puedan aceptar las mismas.</p>
    <p class="card-text">La presente Política de Privacidad se encuentra actualizada a fecha 27/12/2018 Servifinder (España). Reservados todos los derechos.</p>
    <p class="card-text">Si lo deseas también puedes consultar nuestra <a href="../pages/politica-cookies.php">Política de Cookies</a>.</p>
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
        <h5 class="card-title">Información legal</h5>
        <p><a class="link-opacity-100" href="../pages/contacto.php">Contacto</a></p>
        <p><a class="link-opacity-100" href="../pages/login.php">Añadir negocio</a></p>
        <p><a class="link-opacity-100" href="../pages/aviso-legal.php">Aviso legal</a></p>
        <p><a class="link-opacity-100" href="../pages/politica-privacidad.php">Política de privacidad</a></p>
        <p><a class="link-opacity-100" href="../pages/politica-cookies.php">Política de Cookies</a></p>
    </div>                      

    <div class="card">
        <img class="ciudad" src="../data/images/ciudad.png" style="width: 700px; height: 400px;">
    </div>
</div>

<div class="container-wide">
    <div class="pinkstone">
        <p>Diseño web por: Agustín Zaragoza Pérez</p>
    </div>
</div>

</body>
</html>