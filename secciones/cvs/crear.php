<?php
ob_start();
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

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $contacto = trim($_POST['contacto']);
    $website = trim($_POST['website']);
    $personal_statement = trim($_POST['personal_statement']);
    $id_user = $_SESSION['user_id'];

    if (!empty($title)) {
        $created = $cvModel->create($title, $id_user, $contacto, $website, $personal_statement);
        if ($created) {
            $id_cv = $cvModel->getLastInsertId();
            header("Location: ../secciones/index.php?cv=$id_cv");
            exit;
        } else {
            $error = "Error al guardar el CV.";
        }
    } else {
        $error = "El título es obligatorio.";
    }
}
?>

<h1 class="mb-4">Crear nuevo CV</h1>

<form method="POST" class="w-75 mx-auto">

    <div class="mb-3">
        <label for="title" class="form-label">Título del CV</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="contacto" class="form-label">Datos de contacto</label>
        <textarea name="contacto" class="form-control" rows="2" placeholder="Correo, teléfono, dirección..."></textarea>
    </div>

    <div class="mb-3">
        <label for="website" class="form-label">Página web / LinkedIn</label>
        <input type="url" name="website" class="form-control" placeholder="https://tusitio.com">
    </div>

    <div class="mb-3">
        <label for="personal_statement" class="form-label">Personal Statement</label>
        <textarea name="personal_statement" class="form-control" rows="4" placeholder="Resumen profesional o perfil personal..."></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Crear CV</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php if (!empty($error)): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '<?= $error ?>'
});
</script>
<?php endif; ?>

<?php
require_once '../../templates/footer.php';
ob_end_flush();
?>
