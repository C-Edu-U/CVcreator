<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../db.php';
require_once '../libs/Autoloader.php';
require_once '../templates/header.php';

$db = new Database();
$conn = $db->connect();
$logModel = new Log($conn);
$logs = $logModel->getAll();
?>

<h1 class="mb-4">Historial de Cambios del Sistema</h1>

<table class="table table-striped table-bordered" id="tablaLogs">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Tipo de Acción</th>
            <th>Descripción</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs as $log): ?>
        <tr>
            <td><?= $log['created_at'] ?></td>
            <td><?= htmlspecialchars($log['user_name'] ?? 'Sistema') ?></td>
            <td><?= htmlspecialchars($log['action_type']) ?></td>
            <td><?= htmlspecialchars($log['description']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
$(document).ready(function () {
    $('#tablaLogs').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
});
</script>

<?php require_once '../templates/footer.php'; ?>
