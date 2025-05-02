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
$userModel = new User($conn);

$id_user = $_SESSION['user_id'];
$user = $userModel->getById($id_user);

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($name && $email) {
        $userModel->id_user = $id_user;
        $userModel->name = $name;
        $userModel->email = $email;

        if (!empty($password)) {
            $userModel->password = $password;
        }

        $success = $userModel->updateSelf();
        $user = $userModel->getById($id_user);
        $_SESSION['user_name'] = $user['name'];
    } else {
        $error = "Nombre y correo son obligatorios.";
    }
}
?>

<h1 class="mb-4">Mi Perfil</h1>

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
        <label for="password" class="form-label">Nueva contraseña (opcional)</label>
        <input type="password" name="password" class="form-control" placeholder="Deja vacío si no deseas cambiarla">
    </div>

    <button type="submit" class="btn btn-primary">Guardar cambios</button>
</form>

<?php if ($success): ?>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Perfil actualizado!',
    text: 'Tu información ha sido modificada correctamente.'
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
