<?php

// Incluimos conexion.php, que se encarga de la conexion a la base de datos
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    // Ir de aventura:
    if (isset($_POST["irAventura"]))
    {
        // Redirigir a otra página después de completar la llamada
        header("Location: menuEvento.php");
        exit;
    }
    // Ver inventario:
    elseif (isset($_POST["verInventario"]))
    {
        // Redirigir a otra página después de completar la llamada
        header("Location: menuInventario.php");
        exit;
    }
    // Manual de objetos y enemigos:
    elseif (isset($_POST["verManual"]))
    {
        // Redirigir a otra página después de completar la llamada
        header("Location: menuManual.php");
        exit;
    }
    elseif (isset($_POST["derrota"]))
    {
        // Redirigir a otra página después de completar la llamada usando javascript, porque sino no deja cargar index.php, por algún motivo extraño
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    }
}
    // Mostrar salud actual del jugador:
    // Preparar la consulta SQL:
    $query = "SELECT Salud
    FROM TablaJugador
    WHERE IdJugador IN (select MAX(IdJugador) from TablaJugador)";
    $statement = oci_parse($conn, $query);
    
    // Ejecutar el bloque PL/SQL
    oci_execute($statement);

    // obtener salud actual del jugador:
    if ($row = oci_fetch_assoc($statement))
    {
        $salud = $row['SALUD'];
    }

    // Liberar los recursos
    oci_free_statement($statement);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descansando en el campamento...</title>
</head>
<body>
    <h2>Descansando en el campamento...</h2>

    <!-- Botón/Formulario de ir de aventura -->
    <form method="post" action="menuEvento.php">
        <label for="opcion">Elige la zona a explorar</label>
        <select id="opcion" name="opcion">
            <option value="Bosque encantado">Bosque encantado</option>
            <option value="Catacumbas olvidadas">Catacumbas olvidadas</option>
            <option value="Portal al Infierno">Portal al Infierno</option>
            <option value="Cueva de la muerte">Cueva de la Muerte</option>
        </select><br>
        
        <button type="submit" name="irAventura" <?php if ($salud <= 0) echo 'disabled'; ?>>Ir de aventura</button>
        <!-- Cuando la salud es 0 o menos, este botón se desactiva -->
    </form>

    <!-- Botón oculto excepto cuando se pierde la partida -->
    <form method="post" action="" style="<?php if ($salud > 0) echo 'display: none;'; ?>">
        <button type="submit" name="derrota" value="derrota">Comenzar de nuevo</button>
    </form>

    <?php echo "<br>"; ?>

    <!-- Botón de ver inventario -->
    <form method="post" action="">
        <button type="submit" name="verInventario" value="verInventario">Ver tu inventario</button>
    </form>

    <!-- Botón de ver manual de objetos y enemigos -->
    <form method="post" action="">
        <button type="submit" name="verManual" value="verManual">Ver el manual</button>
    </form>

    <?php
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
        ?><p style="color: red;"> <?php echo "<br>Salud: " . $salud . "<br>"; ?></p> <?php
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
</body>
</html>