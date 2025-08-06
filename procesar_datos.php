<?php include 'session_check.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Datos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body data-page="procesar_datos">
    <div class="container">
        <div class="box">
            <?php
            function close_connection($conn) {
                if ($conn && !$conn->connect_error) {
                    mysqli_close($conn);
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Conectar a la base de datos MySQL
                include 'config.php';
                $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

                // Comprobar la conexión
                if (!$conn) {
                    die("<p>Error de conexión: " . mysqli_connect_error() . "</p>");
                }

                $action = $_POST['action'];

                if ($action == 'agregar_usuario') {
                    // Recibir y limpiar los datos del formulario
                    $nomina = mysqli_real_escape_string($conn, $_POST["nomina"]);
                    $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
                    $apellido = mysqli_real_escape_string($conn, $_POST["apellido"]);
                    $correo = mysqli_real_escape_string($conn, $_POST["correo"]);
                    $empresa = mysqli_real_escape_string($conn, $_POST["empresa"]);
                    $departamento = mysqli_real_escape_string($conn, $_POST["departamento"]);
                    $puesto = mysqli_real_escape_string($conn, $_POST["puesto"]);

                    // Verificar si la nómina ya existe
                    $check_nomina = "SELECT * FROM trabajador WHERE nomina = '$nomina'";
                    $result = mysqli_query($conn, $check_nomina);

                    if (mysqli_num_rows($result) > 0) {
                        echo "<p>Error: El trabajador con la nómina $nomina ya está registrado.</p>";
                    } else {
                        // Preparar la consulta SQL para insertar datos
                        $sql = "INSERT INTO trabajador (nomina, nombre, apellidos, correo, empresa, departamento, puesto) VALUES ('$nomina', '$nombre', '$apellido', '$correo', '$empresa', '$departamento', '$puesto')";

                        // Ejecutar la consulta SQL
                        if (mysqli_query($conn, $sql)) {
                            echo "<p>Registro de usuario creado exitosamente.</p>";
                        } else {
                            echo "<p>Error al crear el registro: " . mysqli_error($conn) . "</p>";
                        }
                    }
                } elseif ($action == 'agregar_equipo') {
                    // Recibir y limpiar los datos del formulario
                    $nomina = mysqli_real_escape_string($conn, $_POST["nomina"]);
                    $tipo = mysqli_real_escape_string($conn, $_POST["tipo"]);
                    $marca = mysqli_real_escape_string($conn, $_POST["marca"]);
                    $modelo = mysqli_real_escape_string($conn, $_POST["modelo"]);
                    $numero_serie = mysqli_real_escape_string($conn, $_POST["numero_serie"]);
                    $observaciones = mysqli_real_escape_string($conn, $_POST["observaciones"]);

                    // Verificar si la nómina existe en la tabla trabajador
                    $check_nomina = "SELECT * FROM trabajador WHERE nomina = '$nomina'";
                    $result = mysqli_query($conn, $check_nomina);

                    if (mysqli_num_rows($result) > 0) {
                        // Preparar la consulta SQL para insertar datos de equipo
                        $sql = "INSERT INTO equipo (nomina, tipo, marca, modelo, numero_serie, observaciones) VALUES ('$nomina', '$tipo', '$marca', '$modelo', '$numero_serie', '$observaciones')";

                        // Ejecutar la consulta SQL
                        if (mysqli_query($conn, $sql)) {
                            echo "<p>Registro de equipo creado exitosamente.</p>";
                        } else {
                            echo "<p>Error al crear el registro de equipo: " . mysqli_error($conn) . "</p>";
                        }
                    } else {
                        echo "<p>Error: No existe un trabajador con la nómina $nomina.</p>";
                    }
                } elseif ($action == 'agregar_hardware') {
                    // Recibir y limpiar los datos del formulario
                    $nomina = mysqli_real_escape_string($conn, $_POST["nomina"]);
                    $hardware = mysqli_real_escape_string($conn, $_POST["hardware"]);
                    $capacidad = mysqli_real_escape_string($conn, $_POST["capacidad"]);
                    $velocidad = mysqli_real_escape_string($conn, $_POST["velocidad"]);
                    $observaciones = mysqli_real_escape_string($conn, $_POST["observaciones"]);

                    // Verificar si la nómina existe en la tabla trabajador
                    $check_nomina = "SELECT * FROM trabajador WHERE nomina = '$nomina'";
                    $result = mysqli_query($conn, $check_nomina);

                    if (mysqli_num_rows($result) > 0) {
                        // Preparar la consulta SQL para insertar datos de hardware
                        $sql = "INSERT INTO hardware (nomina, hardware, capacidad, velocidad, observaciones) VALUES ('$nomina', '$hardware', '$capacidad', '$velocidad', '$observaciones')";

                        // Ejecutar la consulta SQL
                        if (mysqli_query($conn, $sql)) {
                            echo "<p>Registro de hardware creado exitosamente.</p>";
                        } else {
                            echo "<p>Error al crear el registro de hardware: " . mysqli_error($conn) . "</p>";
                        }
                    } else {
                        echo "<p>Error: No existe un trabajador con la nómina $nomina.</p>";
                    }
                } elseif ($action == 'agregar_software') {
                    // Recibir y limpiar los datos del formulario
                    $nomina = mysqli_real_escape_string($conn, $_POST["nomina"]);
                    $programa = mysqli_real_escape_string($conn, $_POST["programa"]);
                    $version = mysqli_real_escape_string($conn, $_POST["version"]);
                    $release_service_pack = mysqli_real_escape_string($conn, $_POST["release_service_pack"]);
                    $licencia = mysqli_real_escape_string($conn, $_POST["licencia"]);

                    // Verificar si la nómina existe en la tabla trabajador
                    $check_nomina = "SELECT * FROM trabajador WHERE nomina = '$nomina'";
                    $result = mysqli_query($conn, $check_nomina);

                    if (mysqli_num_rows($result) > 0) {
                        // Preparar la consulta SQL para insertar datos de software
                        $sql = "INSERT INTO software (nomina, programa, version, release_service_pack, licencia) VALUES ('$nomina', '$programa', '$version', '$release_service_pack', '$licencia')";

                        // Ejecutar la consulta SQL
                        if (mysqli_query($conn, $sql)) {
                            echo "<p>Registro de software creado exitosamente.</p>";
                        } else {
                            echo "<p>Error al crear el registro de software: " . mysqli_error($conn) . "</p>";
                        }
                    } else {
                        echo "<p>Error: No existe un trabajador con la nómina $nomina.</p>";
                    }
                }

                // Botones adicionales
                echo '<br><button onclick="window.location.href=\'index.php\'">Regresar</button>';
                echo '<button onclick="window.history.back()">Agregar Otro</button>';
                echo '<button onclick="window.location.href=\'inicio.html\'">Salir</button>';

                // Cerrar la conexión a la base de datos
                close_connection($conn);
            } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
                // Conectar a la base de datos MySQL
                include 'config.php';
                $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

                // Comprobar la conexión
                if (!$conn) {
                    die("<p>Error de conexión: " . mysqli_connect_error() . "</p>");
                }

                $buscar_nomina = mysqli_real_escape_string($conn, $_GET["buscar_nomina"]);
                $buscar_apellidos = mysqli_real_escape_string($conn, $_GET["buscar_apellidos"]);

                if (!empty($buscar_nomina)) {
                    // Realizar la búsqueda por nómina
                    $sql_trabajador = "SELECT * FROM trabajador WHERE nomina = '$buscar_nomina'";
                    $result_trabajador = mysqli_query($conn, $sql_trabajador);

                    echo "<h2>Resultados de búsqueda para Nómina: $buscar_nomina</h2>";

                    if (mysqli_num_rows($result_trabajador) > 0) {
                        echo "<div class='results-info'>Se encontró 1 trabajador con la nómina: $buscar_nomina</div>";
                        echo "<div class='table-container search-results'>";
                        echo "<table>";
                        echo "<thead>";
                        echo "<tr><th>Nómina</th><th>Nombre</th><th>Apellidos</th><th>Correo</th><th>Empresa</th><th>Departamento</th><th>Puesto</th><th>Acciones</th></tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_assoc($result_trabajador)) {
                            echo "<tr>";
                            echo "<td>" . $row["nomina"] . "</td>";
                            echo "<td>" . $row["nombre"] . "</td>";
                            echo "<td>" . $row["apellidos"] . "</td>";
                            echo "<td>" . $row["correo"] . "</td>";
                            echo "<td>" . $row["empresa"] . "</td>";
                            echo "<td>" . $row["departamento"] . "</td>";
                            echo "<td>" . $row["puesto"] . "</td>";
                            echo "<td><div class='action-links'>";
                            echo "<a href='editar.php?nomina=" . $row["nomina"] . "' class='edit'>Editar</a>";
                            echo "<a href='eliminar.php?nomina=" . $row["nomina"] . "' class='delete'>Eliminar</a>";
                            echo "</div></td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                    } else {
                        echo "<p>No se encontró ningún trabajador con la nómina $buscar_nomina.</p>";
                    }
                } elseif (!empty($buscar_apellidos)) {
                    // Realizar la búsqueda por apellidos
                    $sql = "SELECT * FROM trabajador WHERE apellidos LIKE '%$buscar_apellidos%'";
                    $result = mysqli_query($conn, $sql);

                    $num_results = mysqli_num_rows($result);
                    echo "<h2>Resultados de búsqueda para Apellidos: $buscar_apellidos</h2>";

                    if (mysqli_num_rows($result) > 0) {
                        echo "<div class='results-info'>Se encontraron $num_results trabajador" . ($num_results != 1 ? 'es' : '') . " con apellidos que contienen: $buscar_apellidos</div>";
                        echo "<div class='table-container search-results'>";
                        echo "<table>";
                        echo "<thead>";
                        echo "<tr><th>Nómina</th><th>Nombre</th><th>Apellidos</th><th>Correo</th><th>Empresa</th><th>Departamento</th><th>Puesto</th><th>Acciones</th></tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row["nomina"] . "</td>";
                            echo "<td>" . $row["nombre"] . "</td>";
                            echo "<td>" . $row["apellidos"] . "</td>";
                            echo "<td>" . $row["correo"] . "</td>";
                            echo "<td>" . $row["empresa"] . "</td>";
                            echo "<td>" . $row["departamento"] . "</td>";
                            echo "<td>" . $row["puesto"] . "</td>";
                            echo "<td><div class='action-links'>";
                            echo "<a href='editar.php?nomina=" . $row["nomina"] . "' class='edit'>Editar</a>";
                            echo "<a href='eliminar.php?nomina=" . $row["nomina"] . "' class='delete'>Eliminar</a>";
                            echo "</div></td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                    } else {
                        echo "<p>No se encontró ningún trabajador con los apellidos que coincidan con: $buscar_apellidos.</p>";
                    }
                } else {
                    echo "<p>Por favor, ingrese una nómina o apellidos para buscar.</p>";
                }

                // Buscar en la tabla equipo
                $sql_equipo = "SELECT * FROM equipo WHERE nomina = '$buscar_nomina'";
                $result_equipo = mysqli_query($conn, $sql_equipo);

                if (mysqli_num_rows($result_equipo) > 0) {
                    $num_equipos = mysqli_num_rows($result_equipo);
                    echo "<h3>Equipos Asignados</h3>";
                    echo "<div class='results-info'>Se encontraron $num_equipos equipo" . ($num_equipos != 1 ? 's' : '') . " asignado" . ($num_equipos != 1 ? 's' : '') . "</div>";
                    echo "<div class='table-container search-results'>";
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr><th>Tipo</th><th>Marca</th><th>Modelo</th><th>Número de Serie</th><th>Observaciones</th><th>Acciones</th></tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row = mysqli_fetch_assoc($result_equipo)) {
                        echo "<tr>";
                        echo "<td>" . $row["tipo"] . "</td>";
                        echo "<td>" . $row["marca"] . "</td>";
                        echo "<td>" . $row["modelo"] . "</td>";
                        echo "<td>" . $row["numero_serie"] . "</td>";
                        echo "<td>" . $row["observaciones"] . "</td>";
                        echo "<td><div class='action-links'>";
                        echo "<a href='editar.php?numero_serie=" . $row["numero_serie"] . "' class='edit'>Editar</a>";
                        echo "<a href='eliminar.php?numero_serie=" . $row["numero_serie"] . "' class='delete'>Eliminar</a>";
                        echo "</div></td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "<p>No se encontró ningún equipo asociado con la nómina $buscar_nomina.</p>";
                }

                // Buscar en la tabla hardware
                $sql_hardware = "SELECT * FROM hardware WHERE nomina = '$buscar_nomina'";
                $result_hardware = mysqli_query($conn, $sql_hardware);

                if (mysqli_num_rows($result_hardware) > 0) {
                    $num_hardware = mysqli_num_rows($result_hardware);
                    echo "<h3>Hardware Asignado</h3>";
                    echo "<div class='results-info'>Se encontraron $num_hardware componente" . ($num_hardware != 1 ? 's' : '') . " de hardware</div>";
                    echo "<div class='table-container search-results'>";
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr><th>ID</th><th>Hardware</th><th>Capacidad</th><th>Velocidad</th><th>Observaciones</th><th>Acciones</th></tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row = mysqli_fetch_assoc($result_hardware)) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["hardware"] . "</td>";
                        echo "<td>" . $row["capacidad"] . "</td>";
                        echo "<td>" . $row["velocidad"] . "</td>";
                        echo "<td>" . $row["observaciones"] . "</td>";
                        echo "<td><div class='action-links'>";
                        echo "<a href='editar.php?hardware_id=" . $row["id"] . "' class='edit'>Editar</a>";
                        echo "<a href='eliminar.php?hardware_id=" . $row["id"] . "' class='delete'>Eliminar</a>";
                        echo "</div></td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "<p>No se encontró ningún hardware asociado con la nómina $buscar_nomina.</p>";
                }

                // Buscar en la tabla software
                $sql_software = "SELECT * FROM software WHERE nomina = '$buscar_nomina'";
                $result_software = mysqli_query($conn, $sql_software);

                if (mysqli_num_rows($result_software) > 0) {
                    $num_software = mysqli_num_rows($result_software);
                    echo "<h3>Software Asignado</h3>";
                    echo "<div class='results-info'>Se encontraron $num_software programa" . ($num_software != 1 ? 's' : '') . " de software</div>";
                    echo "<div class='table-container search-results'>";
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr><th>ID</th><th>Programa</th><th>Versión</th><th>Release/Service Pack</th><th>Licencia</th><th>Acciones</th></tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row = mysqli_fetch_assoc($result_software)) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["programa"] . "</td>";
                        echo "<td>" . $row["version"] . "</td>";
                        echo "<td>" . $row["release_service_pack"] . "</td>";
                        echo "<td>" . $row["licencia"] . "</td>";
                        echo "<td><div class='action-links'>";
                        echo "<a href='editar.php?software_id=" . $row["id"] . "' class='edit'>Editar</a>";
                        echo "<a href='eliminar.php?software_id=" . $row["id"] . "' class='delete'>Eliminar</a>";
                        echo "</div></td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "<p>No se encontró ningún software asociado con la nómina $buscar_nomina.</p>";
                }

                echo "<div style='margin-top: 20px; text-align: center;'>";
                echo '<br><button onclick="window.location.href=\'index.php\'">Regresar</button>';
                echo '<button onclick="window.history.back()">Buscar Otro</button>';
                echo '<button onclick="window.location.href=\'inicio.html\'">Salir</button>';
                echo "</div>";

                // Cerrar la conexión a la base de datos
                close_connection($conn);
            }
            ?>
        </div>
    </div>
</body>
</html>