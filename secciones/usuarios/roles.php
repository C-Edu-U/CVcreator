<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['id_role'] != 1) {
    header("Location: ../../login.php");
    exit;
}

require_once '../../db.php';
require_once '../../libs/Autoloader.php';
require_once '../../templates/header.php';

$db = new Database();
$conn = $db->connect();

$roleModel = new Role($conn);
$roles = $roleModel->getAll();
?>

<h1 class="mb-4">Gestión de Roles</h1>

<a href="crear_rol.php" class="btn btn-success mb-3">➕ Crear Rol</a>

<table class="table table-bordered table-striped" id="tablaRoles">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre del Rol</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($roles as $r): ?>
        <tr>
            <td><?= $r['id_role'] ?></td>
            <td><?= htmlspecialchars($r['role_name']) ?></td>
            <td><?= htmlspecialchars($r['description']) ?></td>
            <td>
                <a href="editar_rol.php?id=<?= $r['id_role'] ?>" class="btn btn-sm btn-primary">✏️ Editar</a>
                <a href="eliminar_rol.php?id=<?= $r['id_role'] ?>" class="btn btn-sm btn-danger btn-eliminar">🗑️ Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
$(document).ready(function () {
    $('#tablaRoles').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });

    $('.btn-eliminar').on('click', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        Swal.fire({
            title: '¿Eliminar rol?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});
</script>
<?php if (isset($_GET['eliminado'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Rol eliminado',
    text: 'El rol fue eliminado correctamente.',
    timer: 2000,
    showConfirmButton: false
});
</script>
<?php elseif (isset($_GET['error'])): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '<?= htmlspecialchars($_GET['error']) ?>'
});
</script>
<?php endif; ?>


<?php require_once '../../templates/footer.php'; ?>
