<?php
require_once '../vendor/autoload.php';
require_once '../db.php';
require_once '../libs/Autoloader.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$id_cv = $_GET['cv'] ?? null;
if (!$id_cv) die("ID de CV no proporcionado.");

$db = new Database();
$conn = $db->connect();

$cvModel = new Cv($conn);
$sectionModel = new Section($conn);

$cv = $cvModel->getById($id_cv);
$sections = $sectionModel->getByCvId($id_cv);

if (!$cv) die("CV no encontrado.");

// HTML para renderizar
ob_start();
?>

<style>
    body {
        font-family: "Times New Roman", Times, serif;
        font-size: 12pt;
        line-height: 1.5;
        color: #000;
        margin: 30px;
    }

    h1 {
        text-align: center;
        font-size: 20pt;
        margin-bottom: 5px;
    }

    .subinfo {
        text-align: center;
        font-size: 11pt;
        margin-bottom: 20px;
    }

    .section {
        margin-top: 20px;
    }

    .section h4 {
        font-size: 13pt;
        font-weight: bold;
        text-transform: uppercase;
        border-bottom: 1px solid #333;
        margin-bottom: 5px;
    }

    .section pre {
    white-space: pre-wrap;
    margin: 0;
    font-size: 12pt;
    font-family: "Times New Roman", Times, serif;
    }


    .ps {
        font-size: 12pt;
        margin-top: 5px;
    }
</style>

<h1><?= htmlspecialchars($cv['title']) ?></h1>

<div class="subinfo">
    <?= !empty($cv['contacto']) ? nl2br(htmlspecialchars($cv['contacto'])) . "<br>" : "" ?>
    <?= !empty($cv['website']) ? htmlspecialchars($cv['website']) . "<br>" : "" ?>
</div>

<?php if (!empty($cv['personal_statement'])): ?>
<div class="section">
    
    <div class="ps"><?= nl2br(htmlspecialchars($cv['personal_statement'])) ?></div>
</div>
<?php endif; ?>

<?php foreach ($sections as $sec): ?>
<div class="section">
    <h4><?= ucfirst($sec['type']) ?></h4>
    <pre><?= htmlspecialchars($sec['content']) ?></pre>
</div>
<?php endforeach; ?>

<?php
$html = ob_get_clean();

// Configuración de Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Times New Roman');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Mostrar directamente en navegador
header("Content-Type: application/pdf");
echo $dompdf->output();
exit;
