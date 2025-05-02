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
$userId = $_SESSION['user_id'];
$isAdmin = $_SESSION['id_role'] == 1;

if (!$id_cv) {
    die("ID de CV no proporcionado.");
}

$cv = $cvModel->getById($id_cv);

if (!$cv) {
    die("CV no encontrado.");
}

if (!$isAdmin && $cv['id_user'] != $userId) {
    die("No tienes permiso para ver estas secciones.");
}

$secciones = $sectionModel->getByCv($id_cv);
?>

<h1 class="mb-4">Secciones del CV: <strong><?= htmlspecialchars($cv['title']) ?></strong></h1>

<a href="crear.php?cv=<?= $id_cv ?>" class="btn btn-success mb-3">➕ Añadir Sección</a>

<table class="table table-bordered table-striped" id="tablaSecciones">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Contenido</th>
            <th>Orden</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($secciones as $sec): ?>
        <tr>
            <td><?= $sec['id_section'] ?></td>
            <td><?= ucfirst($sec['type']) ?></td>
            <td><?= nl2br(htmlspecialchars($sec['content'])) ?></td>
            <td><?= $sec['position_order'] ?></td>
            <td>
                <a href="editar.php?id=<?= $sec['id_section'] ?>&cv=<?= $id_cv ?>" class="btn btn-sm btn-primary">✏️ Editar</a>
                <a href="eliminar.php?id=<?= $sec['id_section'] ?>&cv=<?= $id_cv ?>" class="btn btn-sm btn-danger">🗑️ Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
$(document).ready(function () {
    $('#tablaSecciones').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
});
</script>

<?php require_once '../../templates/footer.php'; ?>
