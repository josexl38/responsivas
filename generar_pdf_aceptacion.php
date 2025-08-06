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
} else {
    ob_end_clean(); // Limpiar el buffer de salida antes de salir
    die("No se ha proporcionado un número de nómina.");
}

// Crear el PDF
class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 15, 'CARTA ACEPTACIÓN', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Carta Aceptación');
$pdf->SetHeaderData('', '', 'Carta Aceptación', '');

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
<p style="text-align:right;">$fecha</p>
<p style="text-align:left;"><b>Carta Aceptación</b></p>

<p style="text-align:justify;">Nombre: $nombre $apellidos</p>
<p style="text-align:justify;">Puesto: $puesto</p>
EOD;

$pdf->writeHTML($html, true, false, true, false, '');

// Obtener los datos del equipo
$equipoSql = "SELECT tipo, marca, modelo, numero_serie, observaciones FROM equipo WHERE nomina = '$nomina' AND tipo IN ('EQUIPO PORTATIL', 'CPU', 'MONITOR', 'TECLADO', 'MOUSE', 'IMPRESORA', 'No-Break', 'TABLET', 'TELEFONO')";
$equipoResult = mysqli_query($conn, $equipoSql);

$equipoHtml = '<h4>Descripción del equipo:</h4><table border="1" cellpadding="4"><tr><th>Tipo</th><th>Marca</th><th>Modelo</th><th>No. Serie</th><th>Observaciones</th></tr>';
while ($row = mysqli_fetch_assoc($equipoResult)) {
    $equipoHtml .= '<tr>';
    $equipoHtml .= '<td>' . $row['tipo'] . '</td>';
    $equipoHtml .= '<td>' . $row['marca'] . '</td>';
    $equipoHtml .= '<td>' . $row['modelo'] . '</td>';
    $equipoHtml .= '<td>' . $row['numero_serie'] . '</td>';
    $equipoHtml .= '<td>' . $row['observaciones'] . '</td>';
    $equipoHtml .= '</tr>';
}
$equipoHtml .= '</table>';
$pdf->writeHTML($equipoHtml, true, false, true, false, '');

// Obtener los datos de los dispositivos periféricos
$hardwareSql = "SELECT hardware, capacidad, velocidad, observaciones FROM hardware WHERE nomina = '$nomina' AND hardware IN ('procesador', 'almacenamiento', 'ram', 'cd/dvd', 'otro')";
$hardwareResult = mysqli_query($conn, $hardwareSql);

$hardwareHtml = '<h4>Dispositivos Periféricos:</h4><table border="1" cellpadding="4"><tr><th>Tipo</th><th>Capacidad</th><th>Velocidad</th><th>Observaciones</th></tr>';
while ($row = mysqli_fetch_assoc($hardwareResult)) {
    $hardwareHtml .= '<tr>';
    $hardwareHtml .= '<td>' . $row['hardware'] . '</td>';
    $hardwareHtml .= '<td>' . $row['capacidad'] . '</td>';
    $hardwareHtml .= '<td>' . $row['velocidad'] . '</td>';
    $hardwareHtml .= '<td>' . $row['observaciones'] . '</td>';
    $hardwareHtml .= '</tr>';
}
$hardwareHtml .= '</table>';
$pdf->writeHTML($hardwareHtml, true, false, true, false, '');

// Texto final
$finalHtml = <<<EOD
<p style="text-align:justify;">
Recibí el equipo de cómputo y software instalado descritos en esta responsiva, como herramienta de trabajo. Manifestando que usaré y destinaré única y exclusivamente para el desempeño de mis funciones y actividades encomendadas por mí empresa <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b> </p>

<p style="text-align:justify;">Así mismo, con la firma de la presente me comprometo a notificar inmediatamente al área de Sistemas cualquier siniestro y/o requerimiento de servicio o reparación que llegasen a necesitar tanto el equipo como el software.</p>

<p style="text-align:justify;">En el momento en que me sea requerida por <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b> me comprometo a entregar a ésta el equipo y software mencionado, en las mismas condiciones en que los he recibido, sin más deterioro que el ocasionado por el uso normal y el transcurso del tiempo.</p>

<p style="text-align:justify;">Para el caso de terminación de la relación laboral por cualquier causa, o bien que me sea requerido el equipo en cualquier momento, me obligo a entregar inmediatamente el equipo asignado. Y en el evento de que dicho equipo no lo entregue en el momento que me sea requerido por la empresa, o entregándolo este presente algún daño, ya sea intencional, o negligencia inexcusable, me obligo a cubrir el pago de los daños o perjuicios ocasionados, autorizando que me sea descontado de mi pago de salarios o bien me sea descontado de mi finiquito en caso de terminación de la relación laboral.</p>

<p style="text-align:justify;">En virtud de lo anterior, desde ahora me hago sabedor del contenido de esta responsiva, por lo que me responsabilizo de las consecuencias por el mal uso, daño provocado o indebida disposición de los hardware descritos, comprometiéndome a pagar cualquier sanción, multa, daño o perjuicio ocasionado por mi negligencia o mala fe, obligándome a responder de ello ante la propia sociedad o ante cualquier tercero que en su caso resulte afectado.</p>

<p style="text-align:justify;">La presente responsiva la firmo de conformidad habiendo leído su contenido.
</p>
<p style="text-align:justify;">   </p>
<p style="text-align:center;"><b>_________________________</b></p>
<p style="text-align:center;">$nombre $apellidos</p>
EOD;

$pdf->writeHTML($finalHtml, true, false, true, false, '');

// Nombre del archivo PDF
$pdfFileName = "Carta_Aceptacion_N°$nomina.pdf";

// Forzar la descarga del archivo PDF
$pdf->Output($pdfFileName, 'D');

mysqli_close($conn);

ob_end_flush(); // Enviar el buffer de salida
?>
