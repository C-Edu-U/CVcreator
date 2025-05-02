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

$id = $_GET['id'] ?? null;
$success = false;
$error = '';

if (!$id) {
    die("ID de rol no proporcionado.");
}

$rol = $roleModel->getById($id);
if (!$rol) {
    die("Rol no encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['role_name']);
    $desc = trim($_POST['description']);

    if (!empty($name)) {
        $success = $roleModel->update($id, $name, $desc);
        $rol = $roleModel->getById($id); // actualizar datos en pantalla
    } else {
        $error = "El nombre del rol es obligatorio.";
    }
}
?>

<h1 class="mb-4">Editar Rol</h1>

<form method="POST" class="w-50">
    <div class="mb-3">
        <label for="role_name" class="form-label">Nombre del Rol</label>
        <input type="text" name="role_name" class="form-control" value="<?= htmlspecialchars($rol['role_name']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Descripción</label>
        <textarea name="description" rows="3" class="form-control"><?= htmlspecialchars($rol['description']) ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Guardar cambios</button>
    <a href="roles.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php if ($success): ?>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Rol actualizado!',
    text: 'Los cambios han sido guardados correctamente.'
}).then(() => {
    window.location.href = "roles.php";
});
</script>
<?php elseif (!empty($error)): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '<?= $error ?>'
});
</script>
<?php endif; ?>

<?php require_once '../../templates/footer.php'; ?>
