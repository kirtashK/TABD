<?php

// Incluimos conexion.php, que se encarga de la conexion a la base de datos
include 'conexion.php';

// Iniciar sesión:
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    // Volver a la página anterior:
    if (isset($_POST["volver"]))
    {
        // Redirigir a otra página después de completar la llamada
        header("Location: menuAventura.php");
        exit;
    }
}
    // Obtener el valor IdJugador de la sesión:
    $ID = $_SESSION['IdJugador'];

    // Ver inventario:
    // Preparar la consulta SQL con el cursor de salida
    $query = "SELECT o.Nombre, o.Descripcion, i.Cantidad
    FROM TablaObjeto o, TablaInventario i
    WHERE o.IdObjeto = i.IdObjeto AND i.IdJugador = :IdJugador
    GROUP BY o.Nombre, o.Descripcion, i.Cantidad";

    $statement = oci_parse($conn, $query);
    oci_bind_by_name($statement, ":IdJugador", $ID);
    
    // Ejecutar el bloque PL/SQL
    oci_execute($statement);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viendo el inventario...</title>

    <style>
        /* Estilos para div: */
        .mi-div {
            background-color: #f2f2f2;
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Boton de volver -->
<form method="post" action="">
    <button type="submit" name="volver" value="volver">Volver</button>
</form>

    <h2>Revisando el inventario del campamento...</h2>

    <div class="mi-div">
    <?php
    // Mostrar el inventario del jugador
    while (($row = oci_fetch_assoc($statement)) !== false)
    {
        echo "Nombre: " . $row['NOMBRE'] . "<br>";
        echo "Descripción: " . $row['DESCRIPCION'] . "<br>";
        echo "Cantidad: " . $row['CANTIDAD'] . "<br>";
        echo "<br>";
    }
    // Liberar los recursos
    oci_free_statement($statement);
    ?>
    <div>
</body>
</html>