<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once '../../db.php';
require_once '../../libs/Autoloader.php';
require_once '../../templates/header.php';

$db = new Database();
$conn = $db->connect();

$cvModel = new Cv($conn);
$sectionModel = new Section($conn);

$id_cv = $_GET['cv'] ?? null;
if (!$id_cv) die("ID de CV no proporcionado.");

$cv = $cvModel->getById($id_cv);
$sections = $sectionModel->getByCvId($id_cv);

if (!$cv) die("CV no encontrado.");
?>

<div class="bg-white p-4 border rounded shadow mb-4">
    <h1 class="mb-2"><?= htmlspecialchars($cv['title']) ?></h1>
    <p class="text-muted mb-1">Creado el: <?= date('d/m/Y', strtotime($cv['fecha_creacion'])) ?></p>

    <?php if (!empty($cv['contacto'])): ?>
    <p><strong>Contacto:</strong> <?= nl2br(htmlspecialchars($cv['contacto'])) ?></p>
    <?php endif; ?>

    <?php if (!empty($cv['website'])): ?>
    <p><strong>Sitio web:</strong> <a href="<?= htmlspecialchars($cv['website']) ?>" target="_blank"><?= htmlspecialchars($cv['website']) ?></a></p>
    <?php endif; ?>

    <?php if (!empty($cv['personal_statement'])): ?>
    <div class="mb-3">
        <h5 class="text-primary">Personal Statement</h5>
        <p><?= nl2br(htmlspecialchars($cv['personal_statement'])) ?></p>
    </div>
    <?php endif; ?>
</div>

<?php if (count($sections) > 0): ?>
    <?php foreach ($sections as $sec): ?>
    <div class="bg-light border rounded p-3 mb-4">
        <h4 class="text-secondary text-uppercase"><?= ucfirst($sec['type']) ?></h4>
        <pre class="mb-0"><?= htmlspecialchars($sec['content']) ?></pre>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-info">Este CV aún no tiene secciones añadidas.</div>
<?php endif; ?>

<a href="index.php" class="btn btn-secondary mt-3">← Volver</a>

<?php require_once '../../templates/footer.php'; ?>
