<?php include 'session_check.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Registro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body data-page="eliminar">
    <div class="container">
        <div class="box">
            <?php
            // Conectar a la base de datos MySQL
            include 'config.php';

            $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

            // Comprobar la conexión
            if (!$conn) {
                die("<p>Error de conexión: " . mysqli_connect_error() . "</p>");
            }

            if (isset($_GET['nomina'])) {
                $nomina = mysqli_real_escape_string($conn, $_GET['nomina']);
                $sql = "DELETE FROM trabajador WHERE nomina = '$nomina'";
                if (mysqli_query($conn, $sql)) {
                    echo "<p>Trabajador eliminado exitosamente</p>";
                } else {
                    echo "<p>Error al eliminar el registro: " . mysqli_error($conn) . "</p>";
                }
            } elseif (isset($_GET['numero_serie'])) {
                $numero_serie = mysqli_real_escape_string($conn, $_GET['numero_serie']);
                $sql = "DELETE FROM equipo WHERE numero_serie = '$numero_serie'";
                if (mysqli_query($conn, $sql)) {
                    echo "<p>Equipo eliminado exitosamente</p>";
                } else {
                    echo "<p>Error al eliminar el registro: " . mysqli_error($conn) . "</p>";
                }
            } elseif (isset($_GET['hardware_id'])) {
                $hardware_id = mysqli_real_escape_string($conn, $_GET['hardware_id']);
                $sql = "DELETE FROM hardware WHERE id = '$hardware_id'";
                if (mysqli_query($conn, $sql)) {
                    echo "<p>Hardware eliminado exitosamente</p>";
                } else {
                    echo "<p>Error al eliminar el registro: " . mysqli_error($conn) . "</p>";
                }
            } elseif (isset($_GET['software_id'])) {
                $software_id = mysqli_real_escape_string($conn, $_GET['software_id']);
                $sql = "DELETE FROM software WHERE id = '$software_id'";
                if (mysqli_query($conn, $sql)) {
                    echo "<p>Software eliminado exitosamente</p>";
                } else {
                    echo "<p>Error al eliminar el registro: " . mysqli_error($conn) . "</p>";
                }
            } else {
                echo "<p>No se ha proporcionado un identificador válido para eliminar.</p>";
            }

            // Cerrar la conexión a la base de datos
            mysqli_close($conn);

            // Botones adicionales
            echo '<br><button onclick="window.location.href=\'index.php\'">Regresar</button>';
            echo '<button onclick="window.history.back()">Eliminar Otro</button>';
            echo '<button onclick="window.location.href=\'inicio.html\'">Salir</button>';
            ?>
        </div>
    </div>
</body>
</html>
