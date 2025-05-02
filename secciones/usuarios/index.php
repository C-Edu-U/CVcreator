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
$userModel = new User($conn);

$usuarios = $userModel->getAllWithRoles();
?>

<h1 class="mb-4">Gestión de Usuarios</h1>

<a href="crear.php" class="btn btn-success mb-3">➕ Crear Usuario</a>

<table class="table table-bordered table-striped" id="tablaUsuarios">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?= $u['id_user'] ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['role_name'] ?></td>
            <td><?= $u['status'] ?></td>
            <td>
                <a href="editar.php?id=<?= $u['id_user'] ?>" class="btn btn-sm btn-primary">✏️ Editar</a>
                <a href="eliminar.php?id=<?= $u['id_user'] ?>" class="btn btn-sm btn-danger btn-eliminar">🗑️ Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- SweetAlert para confirmar eliminación -->
<script>
$(document).ready(function () {
    $('#tablaUsuarios').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });

    $('.btn-eliminar').on('click', function (e) {
        e.preventDefault();
        const href = $(this).attr('href');

        Swal.fire({
            title: '¿Eliminar usuario?',
            text: 'No podrás deshacer esta acción.',
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
<?php if (isset($_GET['eliminado'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Usuario eliminado',
    text: 'El usuario fue eliminado correctamente.',
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
