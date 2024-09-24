<?php
// Aquí debes incluir tus archivos de configuración y conexión a la base de datos
include_once('../data/datos/constsvars.php');
include_once('../lib/funciones.php');

// Realizar la conexión a la base de datos
$conexion = conexionBBDD($servidor, $usrservidor, $pwdservidor, $bbdd);

if ($conexion) {
    // Obtener el término de búsqueda desde la solicitud GET
    $term = mysqli_real_escape_string($conexion, $_GET['term']);
    $sugerencias = [];

    // Realizar la consulta para buscar coincidencias en las actividades
    $consulta_actividades = "SELECT nombre FROM actividades WHERE nombre LIKE '$term%' LIMIT 5";
    $resultado_actividades = mysqli_query($conexion, $consulta_actividades);
    while ($row = mysqli_fetch_assoc($resultado_actividades)) {
        $sugerencias[] = $row;
    }

    // Realizar la consulta para buscar coincidencias en las categorías
    $consulta_categorias = "SELECT nombre FROM categorias WHERE nombre LIKE '$term%' LIMIT 5";
    $resultado_categorias = mysqli_query($conexion, $consulta_categorias);
    while ($row = mysqli_fetch_assoc($resultado_categorias)) {
        $sugerencias[] = $row;
    }

    // Devolver las sugerencias como un array JSON
    echo json_encode($sugerencias);
} else {
    // Si no se puede establecer la conexión a la base de datos, devolver un array vacío
    echo json_encode([]);
}
?>