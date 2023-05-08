<?php

// Incluimos conexion.php, que se encarga de la conexion a la base de datos
include 'conexion.php';

// Iniciamos sesión, para poder guardar de forma 'global' la variable IdJugador
session_start();

    if ($_SERVER["REQUEST_METHOD"] === "POST")
    {
        if (isset($_POST["empezar"]))
        {
            // Obtener los datos enviados desde el formulario
            $dato = $_POST['dato'];
            $opcion = $_POST['opcion'];

            // Validar que el campo "dato" no esté vacío o supere 50 caracteres
            if (empty($dato))
            {
                $error = "Debes ingresar un nombre";
            }
            elseif (strlen($dato) > 50)
            {
                $error = "El nombre no puede tener más de 50 caracteres";
            }
            else
            {
                // Llamar a la función con los datos recibidos
                $query3 = "BEGIN :result := PaqueteProyecto.FuncionNombreRol(:nombre, :rol); END;";
                $stmt3 = oci_parse($conn, $query3);
                oci_bind_by_name($stmt3, ":result", $result, 5);   // result tendrá el Id del jugador que se haya cargado
                oci_bind_by_name($stmt3, ":nombre", $dato, 50);
                oci_bind_by_name($stmt3, ":rol", $opcion, 50);
                oci_execute($stmt3);

                // Si el IdJugador recibido es 0, quiere decir que existe pero su salud es menor o igual que 0
                if ($result == 0)
                {
                    $error = $dato . " fué derrotado en la partida que intentas cargar";

                    // Liberar los recursos
                    oci_free_statement($stmt3);
                }
                else
                {
                    // Almacenar el valor de result en una variable de sesión
                    $_SESSION['IdJugador'] = $result;
    
                    // Liberar los recursos
                    oci_free_statement($stmt3);
    
                    // Redirigir a otra página después de completar la llamada
                    header("Location: menuAventura.php");
                    exit;
                }

            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creando tu personaje...</title>
</head>
<body>
    <h2>Crea tu personaje</h2>
    <h2>Para cargar una partida anterior, escribe el nombre del personaje de la partida</h2>
    
    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="dato">Introduce el nombre:</label>
        <input type="text" id="dato" name="dato"><br>

        <label for="opcion">Elige la clase:</label>
        <select id="opcion" name="opcion">
            <option value="Caballero">Caballero</option>
            <option value="Arquero">Arquero</option>
            <option value="Mago">Mago</option>
        </select><br>

        <button type="submit" name = "empezar" value= "empezar">Empezar aventura</button>
    </form>
</body>
</html>