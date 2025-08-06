<?php 
ob_start(); // Iniciar el buffer de salida
require_once('tcpdf/tcpdf.php');

// Conectar a la base de datos
$dbhost = "localhost"; 
$dbuser = "root"; 
$dbpass = "12@BeCeDaR10"; 
$dbname = "responsivas"; 
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname); 

if (!$conn) { 
    ob_end_clean(); // Limpiar el buffer de salida antes de salir
    die("Error de conexión: " . mysqli_connect_error()); 
} 

if (isset($_POST['nomina'])) { 
    $nomina = mysqli_real_escape_string($conn, $_POST['nomina']); 
    
    // Obtener los datos del trabajador
    $sql = "SELECT nombre, apellidos, departamento, puesto FROM trabajador WHERE nomina = '$nomina'"; 
    $result = mysqli_query($conn, $sql); 
    
    if (mysqli_num_rows($result) > 0) { 
        $row = mysqli_fetch_assoc($result); 
        $nombre = $row['nombre']; 
        $apellidos = $row['apellidos']; 
        $departamento = $row['departamento']; 
        $puesto = $row['puesto']; 
    } else { 
        ob_end_clean(); // Limpiar el buffer de salida antes de salir
        die("No se encontró ningún trabajador con esa nómina."); 
    } 
    
    // Obtener los datos de software excluyendo IP y Nomenclatura
    $softwareSql = "SELECT programa, version, release_service_pack, licencia FROM software WHERE programa NOT IN ('IP', 'Nomenclatura') AND nomina = '$nomina'";
    $softwareResult = mysqli_query($conn, $softwareSql);
    $softwareHtml = '<h4>Software:</h4><table border="1" cellpadding="4"><tr><th>Programa</th><th>Versión</th><th>Release/Service Pack</th><th>Licencia</th></tr>';
    
    while ($row = mysqli_fetch_assoc($softwareResult)) { 
        $softwareHtml .= '<tr>'; 
        $softwareHtml .= '<td>' . $row['programa'] . '</td>'; 
        $softwareHtml .= '<td>' . $row['version'] . '</td>'; 
        $softwareHtml .= '<td>' . $row['release_service_pack'] . '</td>'; 
        $softwareHtml .= '<td>' . $row['licencia'] . '</td>'; 
        $softwareHtml .= '</tr>'; 
    } 
    $softwareHtml .= '</table>'; 

    // Obtener los datos del equipo (CPU y equipo portátil)
    $equipoSql = "SELECT numero_serie FROM equipo WHERE tipo IN ('cpu', 'equipo portatil') AND nomina = '$nomina'";
    $equipoResult = mysqli_query($conn, $equipoSql);
    $equipoHtml = '<h4>Equipo:</h4><table border="1" cellpadding="4"><tr><th>Número de Serie del Equipo</th><th>Ubicación</th></tr>';
    
    while ($row = mysqli_fetch_assoc($equipoResult)) { 
        $equipoHtml .= '<tr>'; 
        $equipoHtml .= '<td>' . $row['numero_serie'] . '</td>'; 
        $equipoHtml .= '<td>' . $departamento . '</td>'; // Utilizando 'departamento' de la tabla 'trabajador'
        $equipoHtml .= '</tr>'; 
    } 
    $equipoHtml .= '</table>'; 

    // Obtener la nomenclatura del equipo y dirección IP
    $nomenclaturaSql = "SELECT version AS nomenclatura FROM software WHERE programa = 'Nomenclatura' AND nomina = '$nomina'";
    $nomenclaturaResult = mysqli_query($conn, $nomenclaturaSql);
    $nomenclatura = (mysqli_num_rows($nomenclaturaResult) > 0) ? mysqli_fetch_assoc($nomenclaturaResult)['nomenclatura'] : 'N/A';

    $ipSql = "SELECT version AS ip FROM software WHERE programa = 'IP' AND nomina = '$nomina'";
    $ipResult = mysqli_query($conn, $ipSql);
    $ip = (mysqli_num_rows($ipResult) > 0) ? mysqli_fetch_assoc($ipResult)['ip'] : 'N/A';

    // Obtener el no-break
    $noBreakSql = "SELECT marca, numero_serie FROM equipo WHERE tipo = 'no-break' AND nomina = '$nomina'";
    $noBreakResult = mysqli_query($conn, $noBreakSql);
    $noBreakRow = mysqli_fetch_assoc($noBreakResult);
    $noBreakMarca = $noBreakRow['marca'] ?? 'N/A';
    $noBreakNumeroSerie = $noBreakRow['numero_serie'] ?? 'N/A';

    // Crear el PDF
    class MYPDF extends TCPDF { 
        public function Header() { 
            $this->SetFont('helvetica', 'B', 12); 
            $this->Cell(0, 15, 'CARTA RESPONSIVA', 0, false, 'C', 0, '', 0, false, 'M', 'M'); 
        } 
    } 

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 
    $pdf->SetCreator(PDF_CREATOR); 
    $pdf->SetTitle('Carta Responsiva'); 
    $pdf->SetHeaderData('', '', 'Carta Responsiva', ''); 
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN)); 
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA)); 
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT); 
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER); 
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER); 
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM); 
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
    $pdf->AddPage(); 
    $pdf->SetFont('helvetica', '', 12); 

    // Formatear la fecha
    setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'Spanish_Spain.1252');
    $fecha = strftime('%e de %B de %Y'); 

    // Crear el contenido del PDF
    $html = <<<EOD
<p style="text-align:left;"><b>Política sobre el uso de programas de computadoras</b></p>
<p style="text-align:justify;">1. <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b> tiene licencias que se enlistan en el presente documento. <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b> no es propietaria de este software ni de su documentación relacionada y a menos que así lo autorice el titular de los derechos de autor, no tiene el derecho de reproducirlo.</p>
<p style="text-align:justify;">2. Con respecto a redes de área local o en varios equipos, los empleados de <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b> deberán usar el software solamente de la manera establecida en el contrato de licencia.</p>
<p style="text-align:justify;">3. Los empleados de <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b> que sepan del uso no autorizado de software o de su documentación relacionada dentro de la empresa deberán notificar al gerente del departamento o al representante legal de la concesionaria.</p>
<p style="text-align:justify;">4. Según las leyes de derechos de autor, las personas implicadas en la reproducción ilegal de software pueden ser demandadas por daños y perjuicios y enfrentar penas criminales, incluyendo multas y prisión. <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b> no permite la duplicación ilegal de software. Los empleados de <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b> que realicen, adquieran o usen copias no autorizadas de programas de computadoras serán recriminados según las políticas de la empresa. Asimismo, <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b> no los amparará ni defenderá a quienes violen los derechos de autor de otros.</p>
<p style="text-align:justify;">5. Cualquier pregunta que se tenga con respecto a esta política debe dirigirla al gerente de cada área.</p>
<p style="text-align:justify;">6. Los responsables del uso y resguardo de las contraseñas y el equipo de cómputo asignado serán los usuarios del mismo. Los usuarios deberán mantener el equipo de cómputo en óptimas condiciones de operación, no deberán cambiar ni eliminar el software instalado en el equipo, salvo autorización por escrito del área de sistemas.</p>
<p style="text-align:justify;">7. Con mi firma y la recepción del equipo me hago responsable del equipo y aplicaciones que a continuación se enlistan:</p>
<p style="text-align:justify;">   </p>
EOD;
    $pdf->writeHTML($html, true, false, true, false, '');

    // Añadir la tabla de software al PDF
    $pdf->writeHTML($softwareHtml, true, false, true, false, '');

    // Añadir la tabla de equipo al PDF
    $pdf->writeHTML($equipoHtml, true, false, true, false, '');

    // Añadir la tabla de nomenclatura del equipo al PDF
    $nomenclaturaHtml = '<h4>Nomenclatura del equipo:</h4><table border="1" cellpadding="4"><tr><th>Nomenclatura</th><th>Dirección IP</th><th>No break</th><th>Número de serie</th></tr>';
    $nomenclaturaHtml .= '<tr>';
    $nomenclaturaHtml .= '<td>' . $nomenclatura . '</td>';
    $nomenclaturaHtml .= '<td>' . $ip . '</td>';
    $nomenclaturaHtml .= '<td>' . $noBreakMarca . '</td>';
    $nomenclaturaHtml .= '<td>' . $noBreakNumeroSerie . '</td>';
    $nomenclaturaHtml .= '</tr>';
    $nomenclaturaHtml .= '</table>';
    $pdf->writeHTML($nomenclaturaHtml, true, false, true, false, '');

    // Añadir nombre, apellidos y puesto en la parte inferior
    $pdf->SetY(-80); // Ajustar la posición vertical
    $trabajadorHtml = '<p style="text-align:center;"><b>_____________________________</b></p>';
    $trabajadorHtml .= '<p style="text-align:center;">' . $nombre . ' ' . $apellidos . '</p>';
    $trabajadorHtml .= '<p style="text-align:center;">' . $puesto . '</p>';
    $pdf->writeHTML($trabajadorHtml, true, false, true, false, '');

    // Nombre del archivo PDF
    $pdfFileName = "Carta_Responsiva_N°$nomina.pdf";

    // Forzar la descarga del archivo PDF
    $pdf->Output($pdfFileName, 'D');

    mysqli_close($conn); 
    ob_end_flush(); // Enviar el buffer de salida
} else {
    ob_end_clean(); // Limpiar el buffer de salida antes de salir
    die("No se proporcionó una nómina.");
}
?>
