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
                        echo "<h3>Datos del trabajador:</h3>";
                        while ($row = mysqli_fetch_assoc($result_trabajador)) {
                            echo "Nómina: " . $row["nomina"] . "<br>";
                            echo "Nombre: " . $row["nombre"] . "<br>";
                            echo "Apellidos: " . $row["apellidos"] . "<br>";
                            echo "Correo: " . $row["correo"] . "<br>";
                            echo "Empresa: " . $row["empresa"] . "<br>";
                            echo "Departamento: " . $row["departamento"] . "<br>";
                            echo "Puesto: " . $row["puesto"] . "<br>";
                            echo "<a href='editar.php?nomina=" . $row["nomina"] . "'>Editar</a> | ";
                            echo "<a href='eliminar.php?nomina=" . $row["nomina"] . "'>Eliminar</a><br>";
                            echo "<hr>";
                        }
                    } else {
                        echo "<p>No se encontró ningún trabajador con la nómina $buscar_nomina.</p>";
                    }
                } elseif (!empty($buscar_apellidos)) {
                    // Realizar la búsqueda por apellidos
                    $sql = "SELECT * FROM trabajador WHERE apellidos LIKE '%$buscar_apellidos%'";
                    $result = mysqli_query($conn, $sql);

                    echo "<h2>Resultados de búsqueda para Apellidos: $buscar_apellidos</h2>";

                    if (mysqli_num_rows($result) > 0) {
                        echo "<h3>Datos de los trabajadores:</h3>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "Nómina: " . $row["nomina"] . "<br>";
                            echo "Nombre: " . $row["nombre"] . "<br>";
                            echo "Apellidos: " . $row["apellidos"] . "<br>";
                            echo "Correo: " . $row["correo"] . "<br>";
                            echo "Empresa: " . $row["empresa"] . "<br>";
                            echo "Departamento: " . $row["departamento"] . "<br>";
                            echo "Puesto: " . $row["puesto"] . "<br>";
                            echo "<a href='editar.php?nomina=" . $row["nomina"] . "'>Editar</a> | ";
                            echo "<a href='eliminar.php?nomina=" . $row["nomina"] . "'>Eliminar</a><br>";
                            echo "<hr>";
                        }
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
                    echo "<h3>Datos del equipo:</h3>";
                    while ($row = mysqli_fetch_assoc($result_equipo)) {
                        echo "Tipo: " . $row["tipo"] . "<br>";
                        echo "Marca: " . $row["marca"] . "<br>";
                        echo "Modelo: " . $row["modelo"] . "<br>";
                        echo "Número de serie: " . $row["numero_serie"] . "<br>";
                        echo "Observaciones: " . $row["observaciones"] . "<br>";
                        echo "<a href='editar.php?numero_serie=" . $row["numero_serie"] . "'>Editar</a> | ";
                        echo "<a href='eliminar.php?numero_serie=" . $row["numero_serie"] . "'>Eliminar</a><br>";
                        echo "<hr>";
                    }
                } else {
                    echo "<p>No se encontró ningún equipo asociado con la nómina $buscar_nomina.</p>";
                }

                // Buscar en la tabla hardware
                $sql_hardware = "SELECT * FROM hardware WHERE nomina = '$buscar_nomina'";
                $result_hardware = mysqli_query($conn, $sql_hardware);

                if (mysqli_num_rows($result_hardware) > 0) {
                    echo "<h3>Datos del hardware:</h3>";
                    while ($row = mysqli_fetch_assoc($result_hardware)) {
                         "ID: " . $row["id"] . "<br>";
                         "Nómina: " . $row["nomina"] . "<br>";
                        echo "Hardware: " . $row["hardware"] . "<br>";
                        echo "Capacidad: " . $row["capacidad"] . "<br>";
                        echo "Velocidad: " . $row["velocidad"] . "<br>";
                        echo "Observaciones: " . $row["observaciones"] . "<br>";
                        echo "<a href='editar.php?hardware_id=" . $row["id"] . "'>Editar</a> | ";
                        echo "<a href='eliminar.php?hardware_id=" . $row["id"] . "'>Eliminar</a><br>";
                        echo "<hr>";
                    }
                } else {
                    echo "<p>No se encontró ningún hardware asociado con la nómina $buscar_nomina.</p>";
                }

                // Buscar en la tabla software
                $sql_software = "SELECT * FROM software WHERE nomina = '$buscar_nomina'";
                $result_software = mysqli_query($conn, $sql_software);

                if (mysqli_num_rows($result_software) > 0) {
                    echo "<h3>Datos del software:</h3>";
                    while ($row = mysqli_fetch_assoc($result_software)) {
                         "ID: " . $row["id"] . "<br>";
                         "Nómina: " . $row["nomina"] . "<br>";
                        echo "Programa: " . $row["programa"] . "<br>";
                        echo "Versión: " . $row["version"] . "<br>";
                        echo "Release/Service Pack: " . $row["release_service_pack"] . "<br>";
                        echo "Licencia: " . $row["licencia"] . "<br>";
                        echo "<a href='editar.php?software_id=" . $row["id"] . "'>Editar</a> | ";
                        echo "<a href='eliminar.php?software_id=" . $row["id"] . "'>Eliminar</a><br>";
                        echo "<hr>";
                    }
                } else {
                    echo "<p>No se encontró ningún software asociado con la nómina $buscar_nomina.</p>";
                }

                echo '<br><button onclick="window.location.href=\'index.php\'">Regresar</button>';
                echo '<button onclick="window.history.back()">Buscar Otro</button>';
                echo '<button onclick="window.location.href=\'inicio.html\'">Salir</button>';

                // Cerrar la conexión a la base de datos
                close_connection($conn);
            }
            ?>
        </div>
    </div>
</body>
</html>