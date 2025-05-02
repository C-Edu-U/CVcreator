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

$isAdmin = $_SESSION['id_role'] == 1;

$cvs = $isAdmin ? $cvModel->getAll() : $cvModel->getAllByUser($_SESSION['user_id']);
?>

<h1 class="mb-4">Mis CVs</h1>

<a href="crear.php" class="btn btn-success mb-3">➕ Crear nuevo CV</a>

<table class="table table-striped table-bordered" id="tablaCVs">
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Fecha de creación</th>
            <th>Usuario</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cvs as $cv): ?>
        <tr>
            <td><?= $cv['id_cv'] ?></td>
            <td><?= htmlspecialchars($cv['title']) ?></td>
            <td><?= isset($cv['fecha_creacion']) ? substr($cv['fecha_creacion'], 0, 10) : '—' ?></td>
            <td><?= $isAdmin ? htmlspecialchars($cv['user_name'] ?? '—') : 'Yo' ?></td>
            <td>
                <a href="editar.php?id=<?= $cv['id_cv'] ?>" class="btn btn-sm btn-primary">✏️ Editar</a>
                <a href="../secciones/index.php?cv=<?= $cv['id_cv'] ?>" class="btn btn-sm btn-secondary">📂 Secciones</a>
                <a href="ver.php?cv=<?= $cv['id_cv'] ?>" class="btn btn-sm btn-outline-info">👁️ Ver</a>
                <a href="../../reportes/ver_pdf.php?cv=<?= $cv['id_cv'] ?>" target="_blank" class="btn btn-sm btn-outline-success">📄 PDF</a>
                <a href="eliminar.php?id=<?= $cv['id_cv'] ?>" class="btn btn-sm btn-danger btn-eliminar">🗑️</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
$(document).ready(function () {
    $('#tablaCVs').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
});

document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const href = this.getAttribute('href');

        Swal.fire({
            title: '¿Eliminar CV?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    });
});
</script>

<?php require_once '../../templates/footer.php'; ?>

