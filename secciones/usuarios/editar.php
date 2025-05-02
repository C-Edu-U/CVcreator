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
$roleModel = new Role($conn);

$id_user = $_GET['id'] ?? null;
$roles = $roleModel->getAll();
$success = false;
$error = '';

if (!$id_user) {
    die("ID de usuario no proporcionado.");
}

$user = $userModel->getById($id_user);
if (!$user) {
    die("Usuario no encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $id_role = $_POST['id_role'];
    $status = $_POST['status'];

    if ($name && $email && $id_role) {
        $userModel->id_user = $id_user;
        $userModel->name = $name;
        $userModel->email = $email;
        $userModel->id_role = $id_role;
        $userModel->status = $status;

        if (!empty($password)) {
            $userModel->password = $password;
        }

        $success = $userModel->update();
        $user = $userModel->getById($id_user); // actualizar datos en la vista
    } else {
        $error = "Los campos marcados son obligatorios.";
    }
}
?>

<h1 class="mb-4">Editar Usuario</h1>

<form method="POST" class="w-50">
    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Nueva contraseña</label>
        <input type="password" name="password" class="form-control" placeholder="Deja vacío para mantener la actual">
    </div>

    <div class="mb-3">
        <label for="id_role" class="form-label">Rol</label>
        <select name="id_role" class="form-select" required>
            <?php foreach ($roles as $r): ?>
                <option value="<?= $r['id_role'] ?>" <?= $r['id_role'] == $user['id_role'] ? 'selected' : '' ?>>
                    <?= $r['role_name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Estado</label>
        <select name="status" class="form-select">
            <option value="activo" <?= $user['status'] === 'activo' ? 'selected' : '' ?>>Activo</option>
            <option value="inactivo" <?= $user['status'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Guardar cambios</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php if ($success): ?>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Usuario actualizado!',
    text: 'Los cambios han sido guardados correctamente.'
}).then(() => {
    window.location.href = "index.php";
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
