<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: inicio.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Llenar Base de Datos MySQL</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body data-page="index">
    <div class="container">
        <div class="box">
            <h1>Formulario para rellenar base de datos</h1>
            <div id="formulario_inicio">
                <h2>Bienvenido al sistema de gestión</h2>
                <p>Seleccione una opción para comenzar.</p>
            </div>
            <div id="buttons">
                <button onclick="window.location.href='inicio.html'">Inicio</button>
                <button onclick="mostrarFormulario('usuario')">Usuario</button>
                <button onclick="mostrarFormulario('equipo')">Equipo</button>
                <button onclick="mostrarFormulario('hardware')">Hardware</button>
                <button onclick="mostrarFormulario('software')">Software</button>
                <div class="dropdown">
                    <button class="dropdown-btn">Más opciones</button>
                    <div class="dropdown-content hidden">
                        <a href="#" onclick="mostrarFormulario('buscar')">Buscar</a>
                        <a href="#" onclick="mostrarFormulario('responsivas')">Carta Compromiso</a>
                        <a href="#" onclick="mostrarFormulario('responsivas_software')">Carta Responsiva</a>
                        <a href="#" onclick="mostrarFormulario('responsivas_hardware')">Carta Aceptacion</a>
                    </div>
                </div>
            </div>
            <div id="formulario_usuario" class="hidden">
                <h2>Agregar Usuario</h2>
                <form action="procesar_datos.php" method="post">
                    <input type="hidden" name="action" value="agregar_usuario">
                    <label for="nomina">Nómina:</label>
                    <input type="text" id="nomina" name="nomina" required><br>
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required><br>
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" required><br>
                    <label for="correo">Correo electrónico:</label>
                    <input type="email" id="correo" name="correo" required><br>
                    <label for="empresa">Empresa:</label>
                    <select id="empresa" name="empresa" required>
                        <option value="Automoviles Compactos de San Luis, S.A. de C.V.">Automoviles Compactos de San Luis, S.A. de C.V.</option>
                    </select><br>
                    <label for="departamento">Departamento:</label>
                    <input type="text" id="departamento" name="departamento" required><br>
                    <label for="puesto">Puesto:</label>
                    <input type="text" id="puesto" name="puesto" required><br>
                    <button type="submit">Enviar</button>
                    <button type="reset">Limpiar</button>
                    <button type="button" onclick="window.location.href='index.php'">Regresar</button>
                </form>
            </div>
            <div id="formulario_equipo" class="hidden">
                <h2>Agregar Equipo</h2>
                <form action="procesar_datos.php" method="post">
                    <input type="hidden" name="action" value="agregar_equipo">
                    <label for="nomina">Nómina:</label>
                    <input type="text" id="nomina" name="nomina" required><br>
                    <label for="tipo">Tipo de equipo:</label>
                    <select id="tipo" name="tipo" required>
                        <option value="cpu">CPU</option>
                        <option value="equipo portatil">Equipo Portatil</option>
                        <option value="monitor">Monitor</option>
                        <option value="teclado">Teclado</option>
                        <option value="mouse">Mouse</option>
                        <option value="impresora">Impresora</option>
                        <option value="telefono">Teléfono</option>
                        <option value="no-break">No-Break </option>
                        <option value="tablet">Tablet</option>
                    </select><br>
                    <label for="marca">Marca:</label>
                    <input type="text" id="marca" name="marca" required><br>
                    <label for="modelo">Modelo:</label>
                    <input type="text" id="modelo" name="modelo" required><br>
                    <label for="numero_serie">Número de serie:</label>
                    <input type="text" id="numero_serie" name="numero_serie" required><br>
                    <label for="observaciones">Observaciones:</label>
                    <textarea id="observaciones" name="observaciones"></textarea><br>
                    <button type="submit">Enviar</button>
                    <button type="reset">Limpiar</button>
                    <button type="button" onclick="window.location.href='index.php'">Regresar</button>
                </form>
            </div>
            <div id="formulario_hardware" class="hidden">
                <h2>Agregar Hardware</h2>
                <form action="procesar_datos.php" method="post">
                    <input type="hidden" name="action" value="agregar_hardware">
                    <label for="nomina">Nómina:</label>
                    <input type="text" id="nomina" name="nomina" required><br>
                    <label for="hardware">Hardware:</label>
                    <select id="hardware" name="hardware" required>
                        <option value="procesador">Procesador</option>
                        <option value="almacenamiento">Almacenamiento</option>
                        <option value="ram">Ram</option>
                        <option value="cd/dvd">CD/DVD</option>
                        <option value="otro">Otro</option>
                    </select><br>
                    <label for="capacidad">Capacidad:</label>
                    <input type="text" id="capacidad" name="capacidad"><br>
                    <label for="velocidad">Velocidad:</label>
                    <input type="text" id="velocidad" name="velocidad"><br>
                    <label for="observaciones">Observaciones:</label>
                    <textarea id="observaciones" name="observaciones"></textarea><br>
                    <button type="submit">Enviar</button>
                    <button type="reset">Limpiar</button>
                    <button type="button" onclick="window.location.href='index.php'">Regresar</button>
                </form>
            </div>
            <div id="formulario_software" class="hidden">
                <h2>Agregar Software</h2>
                <form action="procesar_datos.php" method="post">
                    <input type="hidden" name="action" value="agregar_software">
                    <label for="nomina">Nómina:</label>
                    <input type="text" id="nomina" name="nomina" required><br>
                    <label for="programa">Programa:</label>
                    <select id="programa" name="programa" required>
                        <option value="office">Office</option>
                        <option value="windows">Windows</option>
                        <option value="thunderbird">Thunderbird</option>
                        <option value="outlook">Outlook</option>
                        <option value="total_dealer">Total Dealer</option>
                        <option value="ip">IP</option>
                        <option value="nomenclatura">Nomenclatura</option>
                    </select><br>
                    <label for="version">Versión:</label>
                    <input type="text" id="version" name="version"><br>
                    <label for="release_service_pack">Release/Service Pack:</label>
                    <input type="text" id="release_service_pack" name="release_service_pack"><br>
                    <label for="licencia">Licencia:</label>
                    <input type="text" id="licencia" name="licencia"><br>
                    <button type="submit">Enviar</button>
                    <button type="reset">Limpiar</button>
                    <button type="button" onclick="window.location.href='index.php'">Regresar</button>
                </form>
            </div>
            <div id="formulario_buscar" class="hidden">
                <h2>Buscar Información</h2>
                <form action="procesar_datos.php" method="get">
                    <label for="buscar_nomina">Nómina:</label>
                    <input type="text" id="buscar_nomina" name="buscar_nomina"><br>
                    <label for="buscar_apellidos">Buscar por Apellidos:</label>
                    <input type="text" id="buscar_apellidos" name="buscar_apellidos">
                    <button type="submit">Buscar</button>
                    <button type="reset">Limpiar</button>
                    <button type="button" onclick="window.location.href='index.php'">Regresar</button>
                </form>
            </div>
            <div id="formulario_responsivas" class="hidden">
                <h2>Generar Carta Compromiso</h2>
                <form action="generar_pdf.php" method="post">
                    <label for="nomina">Número de Nómina:</label>
                    <input type="text" id="nomina" name="nomina" required><br>
                    <button type="submit">Generar PDF</button>
                    <button type="reset">Limpiar</button>
                    <button type="button" onclick="window.location.href='index.php'">Regresar</button>
                </form>
            </div>
            <div id="formulario_responsivas_software" class="hidden">
                <h2>Generar Carta Responsiva de Software</h2>
                <form action="generar_pdf_software.php" method="post">
                    <label for="nomina">Número de Nómina:</label>
                    <input type="text" id="nomina" name="nomina" required><br>
                    <button type="submit">Generar PDF</button>
                    <button type="reset">Limpiar</button>
                    <button type="button" onclick="window.location.href='index.php'">Regresar</button>
                </form>
            </div>
            <div id="formulario_responsivas_hardware" class="hidden">
                <h2>Generar Carta Aceptación de Hardware</h2>
                <form action="generar_pdf_aceptacion.php" method="post">
                    <label for="nomina">Número de Nómina:</label>
                    <input type="text" id="nomina" name="nomina" required><br>
                    <button type="submit">Generar PDF</button>
                    <button type="reset">Limpiar</button>
                    <button type="button" onclick="window.location.href='index.php'">Regresar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
