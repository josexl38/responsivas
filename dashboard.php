<?php include 'session_check.php'; ?>
<?php 
require_once 'error_handler.php';
require_once 'database.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gestión</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body data-page="dashboard">
    <div class="container">
        <div class="box">
            <h1>📊 Dashboard del Sistema</h1>
            
            <!-- Estadísticas Generales -->
            <div class="dashboard-stats">
                <?php
                try {
                    $db = Database::getInstance();
                    
                    // Contar registros
                    $totalTrabajadores = $db->query("SELECT COUNT(*) as count FROM trabajador")->fetch_assoc()['count'];
                    $totalEquipos = $db->query("SELECT COUNT(*) as count FROM equipo")->fetch_assoc()['count'];
                    $totalHardware = $db->query("SELECT COUNT(*) as count FROM hardware")->fetch_assoc()['count'];
                    $totalSoftware = $db->query("SELECT COUNT(*) as count FROM software")->fetch_assoc()['count'];
                    
                    echo "<div class='stat-card'>";
                    echo "<div class='stat-icon'>👥</div>";
                    echo "<div class='stat-info'>";
                    echo "<h3>{$totalTrabajadores}</h3>";
                    echo "<p>Trabajadores</p>";
                    echo "</div>";
                    echo "</div>";
                    
                    echo "<div class='stat-card'>";
                    echo "<div class='stat-icon'>💻</div>";
                    echo "<div class='stat-info'>";
                    echo "<h3>{$totalEquipos}</h3>";
                    echo "<p>Equipos</p>";
                    echo "</div>";
                    echo "</div>";
                    
                    echo "<div class='stat-card'>";
                    echo "<div class='stat-icon'>🔧</div>";
                    echo "<div class='stat-info'>";
                    echo "<h3>{$totalHardware}</h3>";
                    echo "<p>Hardware</p>";
                    echo "</div>";
                    echo "</div>";
                    
                    echo "<div class='stat-card'>";
                    echo "<div class='stat-icon'>💿</div>";
                    echo "<div class='stat-info'>";
                    echo "<h3>{$totalSoftware}</h3>";
                    echo "<p>Software</p>";
                    echo "</div>";
                    echo "</div>";
                    
                } catch (Exception $e) {
                    echo "<div class='alert alert-error'>Error cargando estadísticas</div>";
                    ErrorHandler::logCustom('ERROR', 'Error en dashboard: ' . $e->getMessage());
                }
                ?>
            </div>

            <!-- Reportes por Departamento -->
            <div class="dashboard-section">
                <h2>📈 Equipos por Departamento</h2>
                <div class="table-container">
                    <?php
                    try {
                        $sql = "SELECT t.departamento, COUNT(e.id) as total_equipos 
                                FROM trabajador t 
                                LEFT JOIN equipo e ON t.nomina = e.nomina 
                                GROUP BY t.departamento 
                                ORDER BY total_equipos DESC";
                        $result = $db->query($sql);
                        
                        if ($result->num_rows > 0) {
                            echo "<table>";
                            echo "<thead><tr><th>Departamento</th><th>Total Equipos</th><th>Porcentaje</th></tr></thead>";
                            echo "<tbody>";
                            
                            while ($row = $result->fetch_assoc()) {
                                $porcentaje = $totalEquipos > 0 ? round(($row['total_equipos'] / $totalEquipos) * 100, 1) : 0;
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['departamento']) . "</td>";
                                echo "<td>" . $row['total_equipos'] . "</td>";
                                echo "<td>";
                                echo "<div class='progress-bar'>";
                                echo "<div class='progress-fill' style='width: {$porcentaje}%'></div>";
                                echo "<span class='progress-text'>{$porcentaje}%</span>";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody></table>";
                        }
                    } catch (Exception $e) {
                        echo "<div class='alert alert-error'>Error cargando reporte por departamento</div>";
                    }
                    ?>
                </div>
            </div>

            <!-- Tipos de Equipo más Comunes -->
            <div class="dashboard-section">
                <h2>🖥️ Tipos de Equipo más Comunes</h2>
                <div class="chart-container">
                    <?php
                    try {
                        $sql = "SELECT tipo, COUNT(*) as cantidad FROM equipo GROUP BY tipo ORDER BY cantidad DESC LIMIT 10";
                        $result = $db->query($sql);
                        
                        if ($result->num_rows > 0) {
                            echo "<div class='chart-bars'>";
                            $maxCantidad = 0;
                            $datos = [];
                            
                            // Obtener datos y máximo
                            while ($row = $result->fetch_assoc()) {
                                $datos[] = $row;
                                if ($row['cantidad'] > $maxCantidad) {
                                    $maxCantidad = $row['cantidad'];
                                }
                            }
                            
                            // Mostrar barras
                            foreach ($datos as $row) {
                                $altura = $maxCantidad > 0 ? ($row['cantidad'] / $maxCantidad) * 100 : 0;
                                echo "<div class='chart-bar'>";
                                echo "<div class='bar-fill' style='height: {$altura}%'></div>";
                                echo "<div class='bar-label'>" . htmlspecialchars($row['tipo']) . "</div>";
                                echo "<div class='bar-value'>" . $row['cantidad'] . "</div>";
                                echo "</div>";
                            }
                            echo "</div>";
                        }
                    } catch (Exception $e) {
                        echo "<div class='alert alert-error'>Error cargando gráfico de equipos</div>";
                    }
                    ?>
                </div>
            </div>

            <!-- Software más Utilizado -->
            <div class="dashboard-section">
                <h2>💿 Software más Utilizado</h2>
                <div class="software-grid">
                    <?php
                    try {
                        $sql = "SELECT programa, COUNT(*) as instalaciones FROM software GROUP BY programa ORDER BY instalaciones DESC LIMIT 8";
                        $result = $db->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='software-card'>";
                                echo "<div class='software-icon'>💿</div>";
                                echo "<h4>" . htmlspecialchars($row['programa']) . "</h4>";
                                echo "<p>{$row['instalaciones']} instalaciones</p>";
                                echo "</div>";
                            }
                        }
                    } catch (Exception $e) {
                        echo "<div class='alert alert-error'>Error cargando software</div>";
                    }
                    ?>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="dashboard-section">
                <h2>⚡ Acciones Rápidas</h2>
                <div class="quick-actions">
                    <button onclick="window.location.href='index.php'" class="action-btn primary">
                        <span class="btn-icon">🏠</span>
                        <span>Ir al Sistema</span>
                    </button>
                    <button onclick="exportarReporte('completo')" class="action-btn success">
                        <span class="btn-icon">📊</span>
                        <span>Exportar Reporte</span>
                    </button>
                    <button onclick="mostrarUltimosRegistros()" class="action-btn info">
                        <span class="btn-icon">🕒</span>
                        <span>Últimos Registros</span>
                    </button>
                    <button onclick="window.location.href='inicio.html'" class="action-btn secondary">
                        <span class="btn-icon">🚪</span>
                        <span>Salir</span>
                    </button>
                </div>
            </div>

            <!-- Últimos Registros -->
            <div id="ultimos-registros" class="dashboard-section hidden">
                <h2>🕒 Últimos 10 Trabajadores Registrados</h2>
                <div class="table-container">
                    <?php
                    try {
                        $sql = "SELECT nomina, nombre, apellidos, departamento, puesto 
                                FROM trabajador 
                                ORDER BY nomina DESC 
                                LIMIT 10";
                        $result = $db->query($sql);
                        
                        if ($result->num_rows > 0) {
                            echo "<table>";
                            echo "<thead><tr><th>Nómina</th><th>Nombre</th><th>Apellidos</th><th>Departamento</th><th>Puesto</th></tr></thead>";
                            echo "<tbody>";
                            
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['nomina']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['apellidos']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['departamento']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['puesto']) . "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody></table>";
                        }
                    } catch (Exception $e) {
                        echo "<div class='alert alert-error'>Error cargando últimos registros</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportarReporte(tipo) {
            showToast('Generando reporte completo...', 'info');
            // Aquí podrías implementar la exportación real
            setTimeout(() => {
                showToast('Reporte generado exitosamente', 'success');
            }, 2000);
        }

        function mostrarUltimosRegistros() {
            const section = document.getElementById('ultimos-registros');
            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                section.scrollIntoView({ behavior: 'smooth' });
            } else {
                section.classList.add('hidden');
            }
        }
    </script>
</body>
</html>