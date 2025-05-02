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

$id_cv = $_GET['id'] ?? $_POST['id_cv'] ?? null;
$success = false;
$error = '';

if (!$id_cv) die("ID de CV no proporcionado.");

$cv = $cvModel->getById($id_cv);
if (!$cv) die("CV no encontrado.");

$userId = $_SESSION['user_id'];
$isAdmin = $_SESSION['id_role'] == 1;
if (!$isAdmin && $cv['id_user'] != $userId) die("Acceso denegado.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $contacto = trim($_POST['contacto']);
    $website = trim($_POST['website']);
    $personal_statement = trim($_POST['personal_statement']);

    if (!empty($title)) {
        $success = $cvModel->update($id_cv, $title, $contacto, $website, $personal_statement);
        $cv = $cvModel->getById($id_cv); // refrescar datos
    } else {
        $error = "El título es obligatorio.";
    }
}
?>

<h1 class="mb-4">Editar CV</h1>

<form method="POST" class="w-75 mx-auto">
    <input type="hidden" name="id_cv" value="<?= $cv['id_cv'] ?>">

    <div class="mb-3">
        <label for="title" class="form-label">Título del CV</label>
        <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($cv['title']) ?>">
    </div>

    <div class="mb-3">
        <label for="contacto" class="form-label">Datos de contacto</label>
        <textarea name="contacto" class="form-control" rows="2"><?= htmlspecialchars($cv['contacto']) ?></textarea>
    </div>

    <div class="mb-3">
        <label for="website" class="form-label">Página web / LinkedIn</label>
        <input type="url" name="website" class="form-control" value="<?= htmlspecialchars($cv['website']) ?>">
    </div>

    <div class="mb-3">
        <label for="personal_statement" class="form-label">Personal Statement</label>
        <textarea name="personal_statement" class="form-control" rows="4"><?= htmlspecialchars($cv['personal_statement']) ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Guardar cambios</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php if ($success): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'CV actualizado',
    text: 'Los cambios se guardaron correctamente.',
    timer: 2000,
    showConfirmButton: false
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
