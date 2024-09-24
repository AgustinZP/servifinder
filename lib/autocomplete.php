<?php
include_once('../data/datos/constsvars.php');
include_once('../lib/funciones.php');

$conexion = conexionBBDD($servidor, $usrservidor, $pwdservidor, $bbdd);

if ($conexion) {
    $term = mysqli_real_escape_string($conexion, $_GET['term']);
    $sugerencias = [];

    // Buscar en localidades
    $consulta_localidades = "SELECT nombre, 'localidad' AS tipo FROM localidades WHERE nombre LIKE '$term%' LIMIT 5";
    $resultado_localidades = mysqli_query($conexion, $consulta_localidades);
    while ($row = mysqli_fetch_assoc($resultado_localidades)) {
        $sugerencias[] = $row;
    }

    // Buscar en provincias
    $consulta_provincias = "SELECT nombre, 'provincia' AS tipo FROM provincias WHERE nombre LIKE '$term%' LIMIT 5";
    $resultado_provincias = mysqli_query($conexion, $consulta_provincias);
    while ($row = mysqli_fetch_assoc($resultado_provincias)) {
        $sugerencias[] = $row;
    }

    echo json_encode($sugerencias);
} else {
    echo json_encode([]);
}
?>