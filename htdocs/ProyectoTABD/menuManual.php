<?php

// Incluimos conexion.php, que se encarga de la conexion a la base de datos
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    // Ver manual de enemigos:
    if (isset($_POST["verEnemigos"]))
    {
        // Crear la consulta SQL, recompensa es un VARRAY en la base de datos, se obtiene de forma un poco distinta
        $query = "SELECT e.Nombre NE, LISTAGG(r.COLUMN_VALUE, ', ') WITHIN GROUP (ORDER BY r.COLUMN_VALUE) AS Recompensas, es.Nombre
        FROM TablaEnemigo e, TABLE(e.Recompensa) r, TablaEscenario es
        WHERE e.IdEscenario = es.IdEscenario
        GROUP BY e.Nombre, es.Nombre, es.Descripcion";
        $statement = oci_parse($conn, $query);

        // Ejecutar el bloque PL/SQL
        oci_execute($statement);
    }
    // Ver manual de objetos:
    elseif (isset($_POST["verObjetos"]))
    {
        $query2 = "SELECT IdObjeto, Nombre, Descripcion
        FROM TablaObjeto ORDER BY IdObjeto";
        $statement2 = oci_parse($conn, $query2);

        // Ejecutar el bloque PL/SQL
        oci_execute($statement2);
    }
    // Ver manual de escenarios:
    elseif (isset($_POST["verEscenarios"]))
    {
        $query3 = "SELECT Nombre, Descripcion
        FROM TablaEscenario";
        $statement3 = oci_parse($conn, $query3);

        // Ejecutar el bloque PL/SQL
        oci_execute($statement3);
    }
    // Volver a la página anterior:
    elseif (isset($_POST["volver"]))
    {
        // Redirigir a otra página después de completar la llamada
        header("Location: menuAventura.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leyendo el manual...</title>

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
    <h2>Leyendo el manual...</h2>

    <!-- Botón de ver enemigos -->
    <form method="post" action="">
    <button type="submit" name="verEnemigos" value="verEnemigos">Ver manual de enemigos</button>
    </form>

    <!-- Botón de ver objetos -->
    <form method="post" action="">
    <button type="submit" name="verObjetos" value="verObjetos">Ver manual de objetos</button>
    </form>

    <!-- Botón de ver escenarios -->
    <form method="post" action="">
    <button type="submit" name="verEscenarios" value="verEscenarios">Ver manual de escenarios</button>
    </form>

    <!-- Botón de volver -->
    <form method="post" action="">
    <button type="submit" name="volver" value="volver">Volver</button>
    </form>

<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST")
    {
        ?><div class="mi-div"><?php
        // Ver manual de enemigos:
        if (isset($_POST["verEnemigos"]))
        {
            echo "Información sobre los enemigos: <br><br>";
            while (($row = oci_fetch_assoc($statement)) !== false) 
            {
                echo "Nombre del enemigo: " . $row['NE'] . "<br>";
                echo "ID del objeto dado como recompensa: " . $row['RECOMPENSAS'] . "<br>";
                echo "Nombre del escenario donde aparece: " . $row['NOMBRE'] . "<br>";
                echo "<br>";
            }
        }
        // Ver manual de objetos:
        elseif (isset($_POST["verObjetos"]))
        {
            echo "Información sobre los objetos: <br><br>";
            while (($row = oci_fetch_assoc($statement2)) !== false) 
            {
                echo "ID: " . $row['IDOBJETO'] . "<br>";
                echo "Nombre: " . $row['NOMBRE'] . "<br>";
                echo "Descripción: " . $row['DESCRIPCION'] . "<br>";
                echo "<br>";
            }
        }
        // Ver manual de escenarios:
        elseif (isset($_POST["verEscenarios"]))
        {
            echo "Información sobre los escenarios: <br><br>";
            while (($row = oci_fetch_assoc($statement3)) !== false) 
            {
                echo "Nombre: " . $row['NOMBRE'] . "<br>";
                echo "Descripción: " . $row['DESCRIPCION'] . "<br>";
                echo "<br>";
            }
        }
        ?><div><?php
    }
?>
</body>
</html>