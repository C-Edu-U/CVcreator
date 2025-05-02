<?php
require_once '../vendor/autoload.php';
require_once '../db.php';
require_once '../libs/Autoloader.php';

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

$id_cv = $_GET['cv'] ?? null;

if (!isset($_SESSION['user_id']) || !$id_cv) {
    die("Acceso denegado.");
}

$db = new Database();
$conn = $db->connect();

$cvModel = new Cv($conn);
$sectionModel = new Section($conn);

$cv = $cvModel->getById($id_cv);
$sections = $sectionModel->getByCv($id_cv);

if (!$cv || (!$sections && $_SESSION['id_role'] != 1 && $cv['id_user'] != $_SESSION['user_id'])) {
    die("No tienes permiso para ver este contenido.");
}

// Crear HTML del CV
ob_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; font-size: 20px; margin-bottom: 30px; }
        h2 { font-size: 14px; margin-top: 20px; border-bottom: 1px solid #ccc; }
        p { margin: 5px 0; }
    </style>
</head>
<body>
    <h1><?= htmlspecialchars($cv['title']) ?></h1>

    <?php foreach ($sections as $sec): ?>
        <h2><?= ucfirst($sec['type']) ?></h2>
        <p><?= nl2br(htmlspecialchars($sec['content'])) ?></p>
    <?php endforeach; ?>
</body>
</html>

<?php
$html = ob_get_clean();

// Configurar Dompdf
$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Descargar
$filename = "CV_" . preg_replace("/[^a-zA-Z0-9]/", "_", $cv['title']) . ".pdf";
$dompdf->stream($filename, ["Attachment" => true]);
exit;
