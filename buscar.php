<?php
include 'session_check.php';
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Conectar a la base de datos MySQL
    include 'config.php';

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    // Comprobar la conexión
    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Recibir y limpiar los datos de búsqueda
    $buscar_nomina = mysqli_real_escape_string($conn, $_GET["buscar_nomina"]);
    $buscar_apellidos = mysqli_real_escape_string($conn, $_GET["buscar_apellidos"]);

    // Construir la consulta SQL con condiciones según los parámetros recibidos
    $sql = "SELECT * FROM trabajador WHERE 1=1";
    if (!empty($buscar_nomina)) {
        $sql .= " AND nomina = '$buscar_nomina'";
    }
    if (!empty($buscar_apellidos)) {
        $sql .= " AND apellidos LIKE '%$buscar_apellidos%'";
    }
    $result = mysqli_query($conn, $sql);

    // Mostrar los resultados de la búsqueda
    echo "<!DOCTYPE html>";
    echo "<html lang='es'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<title>Resultados de Búsqueda</title>";
    echo "<link rel='stylesheet' href='styles.css'>";
    echo "</head>";
    echo "<body>";
    echo "<div class='container'>";
    echo "<div class='box'>";
    
    if (mysqli_num_rows($result) > 0) {
        // Mostrar los resultados en una tabla
        echo "<h2>Resultados de la búsqueda</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Nómina</th><th>Nombre</th><th>Apellido</th><th>Correo electrónico</th><th>Empresa</th><th>Departamento</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["nomina"] . "</td>";
            echo "<td>" . $row["nombre"] . "</td>";
            echo "<td>" . $row["apellidos"] . "</td>";
            echo "<td>" . $row["correo"] . "</td>";
            echo "<td>" . $row["empresa"] . "</td>";
            echo "<td>" . $row["departamento"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No se encontraron trabajadores con los criterios de búsqueda proporcionados.</p>";
    }
    echo "<button onclick=\"window.location.href='index.php'\">Regresar</button>";
    echo "</div>";
    echo "</div>";
    echo "</body>";
    echo "</html>";

    // Cerrar la conexión a la base de datos
    mysqli_close($conn);
} else {
    echo "Acceso no autorizado";
}
?>
