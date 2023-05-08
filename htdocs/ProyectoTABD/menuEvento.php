<?php

// Incluimos conexion.php, que se encarga de la conexion a la base de datos
include 'conexion.php';

// Iniciar sesión
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorando...</title>

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

    <h3>Mientras exploras...</h3>

    <!-- Mostrar eventos -->
    <div class="mi-div">
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST")
        {
            if (isset($_POST["opcion"]))
            {
                // $opcion es el valor del formulario anterior, es decir, contiene el nombre de un escenario
                $opcion = $_POST["opcion"];

                // Obtener el valor IdJugador de la sesión:
                $ID = $_SESSION['IdJugador'];

                // Llamar a una función de la base de datos que recibe el nombre del escenario, el IdJugador donde actualizar
                // su salud y añadir items a su inventario y genere eventos:
                $query = "BEGIN :resultado := PaqueteProyecto.FuncionEvento(:nombreEscenario, :IdJugador); END;";
                $statement = oci_parse($conn, $query);
                oci_bind_by_name($statement, ":resultado", $resultado, 800);
                oci_bind_by_name($statement, ":nombreEscenario", $opcion);
                oci_bind_by_name($statement, ":IdJugador", $ID);

                for ($i = 0; $i < 5; $i++)
                {
                    // Ejecutar el bloque PL/SQL
                    oci_execute($statement);
    
                    // Mostrar el resultado
                    echo "<br>" . $resultado . "<br>";
                }

                // Liberar los recursos
                oci_free_statement($statement);
            }
            elseif (isset($_POST["volver"]))
            {
                // Redirigir a otra página después de completar la llamada
                header("Location: menuAventura.php");
                exit;
            }
        }
        ?>
    </div>

    <?php
    // Mostrar salud actual del jugador:
    // Preparar la consulta SQL:
    $query2 = "SELECT Salud
    FROM TablaJugador
    WHERE IdJugador IN (select MAX(IdJugador) from TablaJugador)";
    $statement2 = oci_parse($conn, $query2);
    
    // Ejecutar el bloque PL/SQL
    oci_execute($statement2);

    if ($row = oci_fetch_assoc($statement2))
    {
        $salud = $row['SALUD'];
    }

    // Liberar los recursos
    oci_free_statement($statement2);

    // Poner salud = 0 si es menor, para no mostrar Salud: -2
    if ($salud < 0)
    {
        $salud = 0;
    }

    // Mostrar salud actual del jugador, con colores:
    if ($salud >= 10)
    {
        ?><p style="color: green;"> <?php echo "<br>Salud: " . $salud . "<br>"; ?></p> <?php
    }
    elseif ($salud < 5 && $salud > 0)
    {
        ?><p style="color: yellow;"> <?php echo "<br>Salud: " . $salud . "<br>"; ?></p> <?php
    }
    elseif ($salud >= 5 && $salud < 10)
    {
        ?><p style="color: orange;"> <?php echo "<br>Salud: " . $salud . "<br>"; ?></p> <?php
    }
    else
    {
        ?><p style="color: red;"> <?php echo "<br>Salud: " . $salud . "<br>Has sido derrotado!"; ?></p> <?php
    }
    ?>

    <!-- Boton para generar mas eventos -->
    <form method="post" action="menuEvento.php">
        <input type="hidden" name="opcion" value="<?php echo htmlspecialchars($opcion); ?>">
        <button type="submit" <?php if ($salud <= 0) echo 'disabled'; ?>>Seguir explorando</button>
        <!-- Cuando la salud es 0 o menos, este botón se desactiva -->
    </form> 
    
    <!-- Boton de volver -->
    <form method="post" action="">
    <button type="submit" name="volver" value="volver">Volver al campamento</button>
    </form>
</body>
</html>