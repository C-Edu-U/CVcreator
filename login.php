<?php
session_start();
require_once 'db.php';
require_once 'libs/Autoloader.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->connect();
    $userModel = new User($conn);

    $user = $userModel->getByEmail($_POST['email']);

    if ($user && $_POST['password'] === $user['password']) {
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['id_role'] = $user['id_role'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Credenciales inválidas.";
    }
}

require_once 'templates/header.php';
?>

<h2>Iniciar sesión</h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" class="w-50 mx-auto">
    <div class="mb-3">
        <label for="email" class="form-label">Correo</label>
        <input type="email" name="email" class="form-control" required />
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" required />
    </div>
    <button type="submit" class="btn btn-primary">Ingresar</button>
</form>

<?php require_once 'templates/footer.php'; ?>

