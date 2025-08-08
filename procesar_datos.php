<?php include 'session_check.php'; ?>
<?php 
require_once 'error_handler.php';
require_once 'database.php';
require_once 'validator.php';
?>

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
            try {
            function close_connection($conn) {
                if ($conn && !$conn->connect_error) {
                    mysqli_close($conn);
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $db = Database::getInstance();
                $conn = $db->getConnection();

                $action = $_POST['action'];
                
                // Sanitizar datos de entrada
                $_POST = Validator::sanitize($_POST);

                if ($action == 'agregar_usuario') {
                    // Validar datos
                    $validator = new Validator($_POST);
                    $validator->validate('nomina', ['required', 'nomina', 'unique:trabajador,nomina'])
                             ->validate('nombre', ['required', 'min_length:2', 'max_length:100'])
                             ->validate('apellido', ['required', 'min_length:2', 'max_length:100'])
                             ->validate('correo', ['required', 'email', 'unique:trabajador,correo'])
                             ->validate('empresa', ['required'])
                             ->validate('departamento', ['required'])
                             ->validate('puesto', ['required']);
                    
                    if ($validator->fails()) {
                        echo "<div class='alert alert-error'>";
                        echo "<h3>Errores de validación:</h3>";
                        echo "<ul>";
                        foreach ($validator->getErrors() as $field => $errors) {
                            foreach ($errors as $error) {
                                echo "<li>{$error}</li>";
                            }
                        }
                        echo "</ul>";
                        echo "</div>";
                    } else {
                        try {
                            $data = [
                                'nomina' => $_POST['nomina'],
                                'nombre' => $_POST['nombre'],
                                'apellidos' => $_POST['apellido'],
                                'correo' => $_POST['correo'],
                                'empresa' => $_POST['empresa'],
                                'departamento' => $_POST['departamento'],
                                'puesto' => $_POST['puesto']
                            ];
                            
                            $insertId = $db->insert('trabajador', $data);
                            echo "<div class='alert alert-success'>";
                            echo "<p>✅ Registro de usuario creado exitosamente (ID: {$insertId})</p>";
                            echo "</div>";
                        } catch (Exception $e) {
                            echo "<div class='alert alert-error'>";
                            echo "<p>❌ Error al crear el registro: " . $e->getMessage() . "</p>";
                            echo "</div>";
                        }
                    }
                } elseif ($action == 'agregar_equipo') {
                    // Validar datos
                    $validator = new Validator($_POST);
                    $validator->validate('nomina', ['required', 'nomina', 'exists:trabajador,nomina'])
                             ->validate('tipo', ['required'])
                             ->validate('marca', ['required'])
                             ->validate('modelo', ['required'])
                             ->validate('numero_serie', ['required', 'unique:equipo,numero_serie']);
                    
                    if ($validator->fails()) {
                        echo "<div class='alert alert-error'>";
                        echo "<h3>Errores de validación:</h3>";
                        echo "<ul>";
                        foreach ($validator->getErrors() as $field => $errors) {
                            foreach ($errors as $error) {
                                echo "<li>{$error}</li>";
                            }
                        }
                        echo "</ul>";
                        echo "</div>";
                    } else {
                        try {
                            $data = [
                                'nomina' => $_POST['nomina'],
                                'tipo' => $_POST['tipo'],
                                'marca' => $_POST['marca'],
                                'modelo' => $_POST['modelo'],
                                'numero_serie' => strtoupper($_POST['numero_serie']),
                                'observaciones' => $_POST['observaciones']
                            ];
                            
                            $insertId = $db->insert('equipo', $data);
                            echo "<div class='alert alert-success'>";
                            echo "<p>✅ Registro de equipo creado exitosamente (ID: {$insertId})</p>";
                            echo "</div>";
                        } catch (Exception $e) {
                            echo "<div class='alert alert-error'>";
                            echo "<p>❌ Error al crear el registro de equipo: " . $e->getMessage() . "</p>";
                            echo "</div>";
                        }
                    }
                } elseif ($action == 'agregar_hardware') {
                    // Validar datos
                    $validator = new Validator($_POST);
                    $validator->validate('nomina', ['required', 'nomina', 'exists:trabajador,nomina'])
                             ->validate('hardware', ['required']);
                    
                    if ($validator->fails()) {
                        echo "<div class='alert alert-error'>";
                        echo "<h3>Errores de validación:</h3>";
                        echo "<ul>";
                        foreach ($validator->getErrors() as $field => $errors) {
                            foreach ($errors as $error) {
                                echo "<li>{$error}</li>";
                            }
                        }
                        echo "</ul>";
                        echo "</div>";
                    } else {
                        try {
                            $data = [
                                'nomina' => $_POST['nomina'],
                                'hardware' => $_POST['hardware'],
                                'capacidad' => $_POST['capacidad'],
                                'velocidad' => $_POST['velocidad'],
                                'observaciones' => $_POST['observaciones']
                            ];
                            
                            $insertId = $db->insert('hardware', $data);
                            echo "<div class='alert alert-success'>";
                            echo "<p>✅ Registro de hardware creado exitosamente (ID: {$insertId})</p>";
                            echo "</div>";
                        } catch (Exception $e) {
                            echo "<div class='alert alert-error'>";
                            echo "<p>❌ Error al crear el registro de hardware: " . $e->getMessage() . "</p>";
                            echo "</div>";
                        }
                    }
                } elseif ($action == 'agregar_software') {
                    // Validar datos
                    $validator = new Validator($_POST);
                    $validator->validate('nomina', ['required', 'nomina', 'exists:trabajador,nomina'])
                             ->validate('programa', ['required']);
                    
                    if ($validator->fails()) {
                        echo "<div class='alert alert-error'>";
                        echo "<h3>Errores de validación:</h3>";
                        echo "<ul>";
                        foreach ($validator->getErrors() as $field => $errors) {
                            foreach ($errors as $error) {
                                echo "<li>{$error}</li>";
                            }
                        }
                        echo "</ul>";
                        echo "</div>";
                    } else {
                        try {
                            $data = [
                                'nomina' => $_POST['nomina'],
                                'programa' => $_POST['programa'],
                                'version' => $_POST['version'],
                                'release_service_pack' => $_POST['release_service_pack'],
                                'licencia' => $_POST['licencia']
                            ];
                            
                            $insertId = $db->insert('software', $data);
                            echo "<div class='alert alert-success'>";
                            echo "<p>✅ Registro de software creado exitosamente (ID: {$insertId})</p>";
                            echo "</div>";
                        } catch (Exception $e) {
                            echo "<div class='alert alert-error'>";
                            echo "<p>❌ Error al crear el registro de software: " . $e->getMessage() . "</p>";
                            echo "</div>";
                        }
                    }
                }

                // Botones adicionales
                echo '<br><button onclick="window.location.href=\'index.php\'">Regresar</button>';
                echo '<button onclick="window.history.back()">Agregar Otro</button>';
                echo '<button onclick="window.location.href=\'inicio.html\'">Salir</button>';

                // Cerrar la conexión a la base de datos
                // La conexión se cierra automáticamente
            } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
                $db = Database::getInstance();
                $conn = $db->getConnection();

                // Sanitizar datos de entrada
                $_GET = Validator::sanitize($_GET);
                
                $buscar_nomina = $_GET["buscar_nomina"] ?? '';
                $buscar_apellidos = $_GET["buscar_apellidos"] ?? '';

                if (!empty($buscar_nomina)) {
                    // Realizar la búsqueda por nómina
                    $sql_trabajador = "SELECT * FROM trabajador WHERE nomina = ?";
                    $result_trabajador = $db->query($sql_trabajador, [$buscar_nomina]);

                    echo "<h2>Resultados de búsqueda para Nómina: $buscar_nomina</h2>";

                    if ($result_trabajador->num_rows > 0) {
                        echo "<div class='results-info'>Se encontró 1 trabajador con la nómina: $buscar_nomina</div>";
                        echo "<div class='table-container search-results'>";
                        echo "<table>";
                        echo "<thead>";
                        echo "<tr><th>Nómina</th><th>Nombre</th><th>Apellidos</th><th>Correo</th><th>Empresa</th><th>Departamento</th><th>Puesto</th><th>Acciones</th></tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = $result_trabajador->fetch_assoc()) {
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
                    $sql = "SELECT * FROM trabajador WHERE apellidos LIKE ?";
                    $result = $db->query($sql, ["%{$buscar_apellidos}%"]);

                    $num_results = $result->num_rows;
                    echo "<h2>Resultados de búsqueda para Apellidos: $buscar_apellidos</h2>";

                    if ($result->num_rows > 0) {
                        echo "<div class='results-info'>Se encontraron $num_results trabajador" . ($num_results != 1 ? 'es' : '') . " con apellidos que contienen: $buscar_apellidos</div>";
                        echo "<div class='table-container search-results'>";
                        echo "<table>";
                        echo "<thead>";
                        echo "<tr><th>Nómina</th><th>Nombre</th><th>Apellidos</th><th>Correo</th><th>Empresa</th><th>Departamento</th><th>Puesto</th><th>Acciones</th></tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = $result->fetch_assoc()) {
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
                if (!empty($buscar_nomina)) {
                    $sql_equipo = "SELECT * FROM equipo WHERE nomina = ?";
                    $result_equipo = $db->query($sql_equipo, [$buscar_nomina]);

                    if ($result_equipo->num_rows > 0) {
                        $num_equipos = $result_equipo->num_rows;
                        echo "<h3>Equipos Asignados</h3>";
                        echo "<div class='results-info'>Se encontraron $num_equipos equipo" . ($num_equipos != 1 ? 's' : '') . " asignado" . ($num_equipos != 1 ? 's' : '') . "</div>";
                        echo "<div class='table-container search-results'>";
                        echo "<table>";
                        echo "<thead>";
                        echo "<tr><th>Tipo</th><th>Marca</th><th>Modelo</th><th>Número de Serie</th><th>Observaciones</th><th>Acciones</th></tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = $result_equipo->fetch_assoc()) {
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
                    $sql_hardware = "SELECT * FROM hardware WHERE nomina = ?";
                    $result_hardware = $db->query($sql_hardware, [$buscar_nomina]);

                    if ($result_hardware->num_rows > 0) {
                        $num_hardware = $result_hardware->num_rows;
                        echo "<h3>Hardware Asignado</h3>";
                        echo "<div class='results-info'>Se encontraron $num_hardware componente" . ($num_hardware != 1 ? 's' : '') . " de hardware</div>";
                        echo "<div class='table-container search-results'>";
                        echo "<table>";
                        echo "<thead>";
                        echo "<tr><th>ID</th><th>Hardware</th><th>Capacidad</th><th>Velocidad</th><th>Observaciones</th><th>Acciones</th></tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = $result_hardware->fetch_assoc()) {
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
                    $sql_software = "SELECT * FROM software WHERE nomina = ?";
                    $result_software = $db->query($sql_software, [$buscar_nomina]);

                    if ($result_software->num_rows > 0) {
                        $num_software = $result_software->num_rows;
                        echo "<h3>Software Asignado</h3>";
                        echo "<div class='results-info'>Se encontraron $num_software programa" . ($num_software != 1 ? 's' : '') . " de software</div>";
                        echo "<div class='table-container search-results'>";
                        echo "<table>";
                        echo "<thead>";
                        echo "<tr><th>ID</th><th>Programa</th><th>Versión</th><th>Release/Service Pack</th><th>Licencia</th><th>Acciones</th></tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = $result_software->fetch_assoc()) {
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
                }

                echo "<div style='margin-top: 20px; text-align: center;'>";
                echo '<br><button onclick="window.location.href=\'index.php\'">Regresar</button>';
                echo '<button onclick="window.history.back()">Buscar Otro</button>';
                echo '<button onclick="window.location.href=\'inicio.html\'">Salir</button>';
                echo "</div>";

                // Cerrar la conexión a la base de datos
                // La conexión se cierra automáticamente
            }
            } catch (Exception $e) {
                echo "<div class='alert alert-error'>";
                echo "<h3>❌ Error del Sistema</h3>";
                echo "<p>Ha ocurrido un error inesperado. Por favor, contacte al administrador del sistema.</p>";
                echo "<p><small>Error ID: " . uniqid() . "</small></p>";
                echo "</div>";
                
                ErrorHandler::logCustom('CRITICAL', 'Error no manejado en procesar_datos.php: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
            ?>
        </div>
    </div>
</body>
</html>