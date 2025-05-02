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
$roleModel = new Role($conn); // asumimos que tienes un modelo Role

$roles = $roleModel->getAll(); // para el desplegable
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $id_role = $_POST['id_role'];
    $status = $_POST['status'];

    if ($name && $email && $password && $id_role) {
        $userModel->name = $name;
        $userModel->email = $email;
        $userModel->password = $password; // texto plano (para testeo)
        $userModel->id_role = $id_role;
        $userModel->status = $status;

        $success = $userModel->create();
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>

<h1 class="mb-4">Crear Usuario</h1>

<form method="POST" class="w-50">
    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="id_role" class="form-label">Rol</label>
        <select name="id_role" class="form-select" required>
            <option value="">-- Selecciona un rol --</option>
            <?php foreach ($roles as $r): ?>
                <option value="<?= $r['id_role'] ?>"><?= $r['role_name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Estado</label>
        <select name="status" class="form-select">
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php if ($success): ?>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Usuario creado!',
    text: 'El usuario se ha registrado correctamente.'
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
