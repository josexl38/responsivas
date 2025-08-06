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
    $sql = "SELECT nombre, apellidos FROM trabajador WHERE nomina = '$nomina'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $nombre = $row['nombre'];
        $apellidos = $row['apellidos'];
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
        $this->Cell(0, 15, 'Carta Compromiso', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }
}

// Crear instancia de TCPDF
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Establecer metadatos del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Carta Compromiso');
$pdf->SetHeaderData('', '', 'Carta Compromiso', '');

// Establecer fuentes para encabezado y pie de página
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Establecer la fuente monoespaciada por defecto
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Establecer márgenes
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Habilitar el salto de página automático
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Escalar las imágenes de manera proporcional
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Añadir una página
$pdf->AddPage();

// Establecer la fuente
$pdf->SetFont('helvetica', '', 12);

// Formatear la fecha
setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'Spanish_Spain.1252');
$fecha = strftime('%e de %B de %Y');

// Crear el contenido del PDF
$html = <<<EOD
<p style="text-align:right;">San Luis Potosí, S.L.P., a $fecha</p>

<p><b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b><br>Presente.</p>

<p style="text-align:justify;">Estoy informado que el equipo de cómputo que se encuentra instalado en <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b>, funciona integrado a una red, que permite el acceso a toda información que se contiene en todas y cada una de las computadoras que integran el sistema, esta información es valiosa para <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b>, ya que es el producto del trabajo de los integrantes de dicha empresa, y es para uso exclusivo de la misma.</p>

<p style="text-align:justify;">La información a la que tengo acceso, en razón de su naturaleza está destinada única y exclusivamente al servicio de la citada empresa, quedándome prohibido darle usos privados o para cualquier persona o Entidad distinta a los fines de <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b></p>

<p style="text-align:justify;">Por lo antes referido, me comprometo a no copiar por ningún medio o usar los programas y la información que está a mi alcance por virtud de la red para usos distintos de los mencionados, ni para usos de carácter personal.</p>

<p style="text-align:justify;">Habida cuenta de que la información contenida en el sistema que integra la red tiene una finalidad específica y constituye además un bien valioso para <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b>, me hago sabedor de que está prohibido grabar información en mi computadora, sus archivos o en el material de soporte que sea de carácter privado, y además de que me queda estrictamente prohibido grabar o programar cualquier clase de juegos en la computadora que tengo asignada, o introducir o grabar programas ajenos o propiedad de terceros, si estos no han sido previamente aprobados por <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b>. Lo anterior a efecto de prevenir la contaminación del sistema y asegurar la compatibilidad operativa.</p>

<p style="text-align:justify;"><u>ASUMO DESDE AHORA EN FORMA PERSONAL Y PARA TODOS LOS EFECTOS LEGALES A QUE HAYA LUGAR, LAS RESPONSABILIDAD CIVIL, ADMINISTRATIVA Y PENAL, QUE DICHO ACTO PUDIERA TENER, DEBIDO A LA GRABACION, INSTALACION O ALMACENAJE DE CUALQUIER PROGRAMA DE COMPUTO QUE NO CUENTE CON LAS LICENCIAS CORRESPONDIENTES O QUE LA GRABACION SE HUBIERE EFECTUADO SIN CUMPLIR CON TODOS Y CADA UNO DE LOS REQUISITOS LEGALES QUE ESTABLECEN LOS DIVERSOS ORDENAMIENTOS JURIDICOS.</u></p>

<p style="text-align:justify;">Tengo además conocimiento de que en todo tiempo la persona que se designe por el área de informática de <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b>, tendrá acceso a todos los equipos de cómputo que integran la red, incluidos los archivos que en forma personal haya abierto en el equipo a que tengo a mi servicio, o cualquier clase de información, documentación o programas que se encuentren instalados o grabados, en el equipo que tengo a mi disposición.</p>

<p style="text-align:justify;">Para efectos del acceso al equipo de computación que tengo asignado, a continuación, les proporciono el código de acceso, mismo que le corresponde quedándome prohibido introducir códigos de protector de pantalla y códigos de acceso a documentos o archivos. Cualquier cambio en el código de acceso requerirá de autorización previa dada por <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b>, quien conservará un registro de los códigos de los usuarios.</p>

<p style="text-align:justify;">Aunado a lo anterior, manifiesto que estoy obligado a utilizar el equipo de cómputo que me ha sido asignado, como herramienta de trabajo, no para mi uso personal, asumiendo la responsabilidad de que el equipo se destinará a realizar las actividades y tareas que me sean asignadas por parte de <b>AUTOMOVILES COMPACTOS DE SAN LUIS, S.A. DE C.V.</b> y bajo ningún concepto y por ninguna circunstancia podré utilizar dicho equipo para llevar a cabo tareas o actividades de tipo personal o que no guarden relación con mi trabajo.</p>

<p style="text-align:center;">Atentamente.</p>

<p style="text-align:center;">________________________<br>$nombre $apellidos</p>
EOD;

// Escribir el HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Nombre del archivo PDF
$pdfFileName = "Carta_compromiso_N°$nomina.pdf";

// Salida del PDF como descarga
$pdf->Output($pdfFileName, 'D');

// Cerrar la conexión a la base de datos
mysqli_close($conn);

// Limpiar el buffer de salida y finalizar el buffering
ob_end_flush();
?>
