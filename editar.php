<?php
include 'session_check.php';
include 'config.php';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Comprobar la conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Editar</title>
</head>
<body data-page="editar">
    <div class="container">
        <div class="box">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $nomina = isset($_POST["nomina"]) ? mysqli_real_escape_string($conn, $_POST["nomina"]) : null;

                if (isset($_POST['editar_trabajador'])) {
                    $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
                    $apellidos = mysqli_real_escape_string($conn, $_POST["apellidos"]);
                    $correo = mysqli_real_escape_string($conn, $_POST["correo"]);
                    $empresa = mysqli_real_escape_string($conn, $_POST["empresa"]);
                    $departamento = mysqli_real_escape_string($conn, $_POST["departamento"]);
                    $puesto = mysqli_real_escape_string($conn, $_POST["puesto"]);

                    $sql = "UPDATE trabajador SET nombre='$nombre', apellidos='$apellidos', correo='$correo', empresa='$empresa', departamento='$departamento', puesto='$puesto' WHERE nomina='$nomina'";

                    if (mysqli_query($conn, $sql)) {
                        echo "Registro de trabajador actualizado exitosamente";
                    } else {
                        echo "Error al actualizar el registro de trabajador: " . mysqli_error($conn);
                    }
                } elseif (isset($_POST['editar_equipo'])) {
                    $numero_serie = mysqli_real_escape_string($conn, $_POST["numero_serie"]);
                    $tipo = mysqli_real_escape_string($conn, $_POST["tipo"]);
                    $marca = mysqli_real_escape_string($conn, $_POST["marca"]);
                    $modelo = mysqli_real_escape_string($conn, $_POST["modelo"]);
                    $observaciones = mysqli_real_escape_string($conn, $_POST["observaciones"]);

                    $sql = "UPDATE equipo SET tipo='$tipo', marca='$marca', modelo='$modelo', observaciones='$observaciones' WHERE numero_serie='$numero_serie'";

                    if (mysqli_query($conn, $sql)) {
                        echo "Registro de equipo actualizado exitosamente";
                    } else {
                        echo "Error al actualizar el registro de equipo: " . mysqli_error($conn);
                    }
                } elseif (isset($_POST['editar_hardware'])) {
                    $hardware_id = mysqli_real_escape_string($conn, $_POST["hardware_id"]);
                    $hardware = mysqli_real_escape_string($conn, $_POST["hardware"]);
                    $capacidad = mysqli_real_escape_string($conn, $_POST["capacidad"]);
                    $velocidad = mysqli_real_escape_string($conn, $_POST["velocidad"]);
                    $observaciones = mysqli_real_escape_string($conn, $_POST["observaciones"]);

                    $sql = "UPDATE hardware SET hardware='$hardware', capacidad='$capacidad', velocidad='$velocidad', observaciones='$observaciones' WHERE id='$hardware_id'";

                    if (mysqli_query($conn, $sql)) {
                        echo "Registro de hardware actualizado exitosamente";
                    } else {
                        echo "Error al actualizar el registro de hardware: " . mysqli_error($conn);
                    }
                } elseif (isset($_POST['editar_software'])) {
                    $software_id = mysqli_real_escape_string($conn, $_POST["software_id"]);
                    $programa = mysqli_real_escape_string($conn, $_POST["programa"]);
                    $version = mysqli_real_escape_string($conn, $_POST["version"]);
                    $release_service_pack = mysqli_real_escape_string($conn, $_POST["release_service_pack"]);
                    $licencia = mysqli_real_escape_string($conn, $_POST["licencia"]);

                    $sql = "UPDATE software SET programa='$programa', version='$version', release_service_pack='$release_service_pack', licencia='$licencia' WHERE id='$software_id'";

                    if (mysqli_query($conn, $sql)) {
                        echo "Registro de software actualizado exitosamente";
                    } else {
                        echo "Error al actualizar el registro de software: " . mysqli_error($conn);
                    }
                }

                echo '<br><button onclick="window.location.href=\'index.php\'">Regresar</button>';
                echo '<button onclick="window.history.back()">Editar Otro</button>';
                echo '<button onclick="window.location.href=\'inicio.html\'">Salir</button>';

            } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
                $nomina = isset($_GET["nomina"]) ? mysqli_real_escape_string($conn, $_GET["nomina"]) : null;
                $numero_serie = isset($_GET["numero_serie"]) ? mysqli_real_escape_string($conn, $_GET["numero_serie"]) : null;
                $hardware_id = isset($_GET["hardware_id"]) ? mysqli_real_escape_string($conn, $_GET["hardware_id"]) : null;
                $software_id = isset($_GET["software_id"]) ? mysqli_real_escape_string($conn, $_GET["software_id"]) : null;

                if ($nomina) {
                    $sql = "SELECT * FROM trabajador WHERE nomina='$nomina'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        echo "<h2>Editar Trabajador</h2>";
                        echo "<form method='POST'>";
                        echo "<input type='hidden' name='nomina' value='" . $row["nomina"] . "'>";
                        echo "Nombre: <input type='text' name='nombre' value='" . $row["nombre"] . "'><br>";
                        echo "Apellidos: <input type='text' name='apellidos' value='" . $row["apellidos"] . "'><br>";
                        echo "Correo: <input type='email' name='correo' value='" . $row["correo"] . "'><br>";
                        echo "Empresa: <input type='text' name='empresa' value='" . $row["empresa"] . "'><br>";
                        echo "Departamento: <input type='text' name='departamento' value='" . $row["departamento"] . "'><br>";
                        echo "Puesto: <input type='text' name='puesto' value='" . $row["puesto"] . "'><br>";
                        echo "<input type='submit' name='editar_trabajador' value='Guardar Cambios'>";
                        echo "</form>";
                    } else {
                        echo "No se encontró ningún trabajador con la nómina $nomina.<br>";
                    }
                } elseif ($numero_serie) {
                    $sql = "SELECT * FROM equipo WHERE numero_serie='$numero_serie'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        echo "<h2>Editar Equipo</h2>";
                        echo "<form method='POST'>";
                        echo "<input type='hidden' name='numero_serie' value='" . $row["numero_serie"] . "'>";
                        echo "Tipo: <input type='text' name='tipo' value='" . $row["tipo"] . "'><br>";
                        echo "Marca: <input type='text' name='marca' value='" . $row["marca"] . "'><br>";
                        echo "Modelo: <input type='text' name='modelo' value='" . $row["modelo"] . "'><br>";
                        echo "Observaciones: <input type='text' name='observaciones' value='" . $row["observaciones"] . "'><br>";
                        echo "<input type='submit' name='editar_equipo' value='Guardar Cambios'>";
                        echo "</form>";
                    } else {
                        echo "No se encontró ningún equipo con el número de serie $numero_serie.<br>";
                    }
                } elseif ($hardware_id) {
                    $sql = "SELECT * FROM hardware WHERE id='$hardware_id'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        echo "<h2>Editar Hardware</h2>";
                        echo "<form method='POST'>";
                        echo "<input type='hidden' name='hardware_id' value='" . $row["id"] . "'>";
                        echo "Hardware: <input type='text' name='hardware' value='" . $row["hardware"] . "'><br>";
                        echo "Capacidad: <input type='text' name='capacidad' value='" . $row["capacidad"] . "'><br>";
                        echo "Velocidad: <input type='text' name='velocidad' value='" . $row["velocidad"] . "'><br>";
                        echo "Observaciones: <input type='text' name='observaciones' value='" . $row["observaciones"] . "'><br>";
                        echo "<input type='submit' name='editar_hardware' value='Guardar Cambios'>";
                        echo "</form>";
                    } else {
                        echo "No se encontró ningún hardware con el ID $hardware_id.<br>";
                    }
                } elseif ($software_id) {
                    $sql = "SELECT * FROM software WHERE id='$software_id'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        echo "<h2>Editar Software</h2>";
                        echo "<form method='POST'>";
                        echo "<input type='hidden' name='software_id' value='" . $row["id"] . "'>";
                        echo "Programa: <input type='text' name='programa' value='" . $row["programa"] . "'><br>";
                        echo "Versión: <input type='text' name='version' value='" . $row["version"] . "'><br>";
                        echo "Release/Service Pack: <input type='text' name='release_service_pack' value='" . $row["release_service_pack"] . "'><br>";
                        echo "Licencia: <input type='text' name='licencia' value='" . $row["licencia"] . "'><br>";
                        echo "<input type='submit' name='editar_software' value='Guardar Cambios'>";
                        echo "</form>";
                    } else {
                        echo "No se encontró ningún software con el ID $software_id.<br>";
                    }
                }
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
// Cerrar la conexión
mysqli_close($conn);
?>
