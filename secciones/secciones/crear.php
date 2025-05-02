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

$sectionModel = new Section($conn);
$cvModel = new Cv($conn);

$id_cv = $_GET['cv'] ?? $_POST['id_cv'] ?? null;

if (!$id_cv) {
    die("ID de CV no proporcionado.");
}

$cv = $cvModel->getById($id_cv);
$userId = $_SESSION['user_id'];
$isAdmin = $_SESSION['id_role'] == 1;

if (!$cv || (!$isAdmin && $cv['id_user'] != $userId)) {
    die("No tienes permiso para acceder a este CV.");
}

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $content = trim($_POST['content']);
    $order = 0;

    if (!empty($type) && !empty($content)) {
        $success = $sectionModel->create($type, $content, $order, $id_cv);
    } else {
        $error = "Debes completar todos los campos.";
    }
}
?>

<h1 class="mb-4">Crear Sección del CV: <strong><?= htmlspecialchars($cv['title']) ?></strong></h1>

<form method="POST" class="w-100" id="sectionForm">
    <input type="hidden" name="id_cv" value="<?= $id_cv ?>">
    <div class="mb-3">
        <label for="type" class="form-label">Tipo de sección</label>
        <select name="type" class="form-select" id="type" required>
            <option value="">-- Selecciona --</option>
            <option value="educacion">Educación</option>
            <option value="experiencia">Experiencia</option>
            <option value="habilidad">Habilidad</option>
            <option value="certificacion">Certificación</option>
            <option value="proyecto">Proyecto</option>
        </select>
    </div>

    <div id="itemsContainer"></div>

    <button type="button" class="btn btn-outline-secondary mb-3" onclick="addItem()">➕ Agregar ítem</button>

    <textarea name="content" id="content" class="form-control d-none"></textarea>

    <button type="submit" class="btn btn-primary">Guardar Sección</button>
    <a href="index.php?cv=<?= $id_cv ?>" class="btn btn-secondary">Cancelar</a>
</form>

<script>
let itemIndex = 0;

function addItem() {
    const container = document.getElementById('itemsContainer');

    const div = document.createElement('div');
    div.className = 'border p-3 mb-3 rounded bg-light position-relative';
    div.innerHTML = `
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2" onclick="this.parentElement.remove()"></button>
        <div class="row mb-2">
            <div class="col-md-6">
                <label>Inicio</label>
                <input type="month" class="form-control start-date">
            </div>
            <div class="col-md-6">
                <label>Fin</label>
                <input type="month" class="form-control end-date">
                <div class="form-check mt-1">
                    <input type="checkbox" class="form-check-input currently">
                    <label class="form-check-label">Actualmente</label>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label>Institución / Empresa</label>
            <input type="text" class="form-control place" required>
        </div>
        <div class="mb-2">
            <label>Bulletpoints</label>
            <ul class="list-group bullets mb-2"></ul>
            <div class="input-group">
                <input type="text" class="form-control bullet-text" placeholder="Escribe un punto">
                <button type="button" class="btn btn-outline-primary" onclick="addBullet(this)">➕</button>
            </div>
        </div>
    `;
    container.appendChild(div);
}

function addBullet(button) {
    const container = button.closest('div').parentElement;
    const textInput = container.querySelector('.bullet-text');
    const bulletsList = container.querySelector('.bullets');

    if (textInput.value.trim() !== "") {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = `<span>• ${textInput.value}</span><button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>`;
        bulletsList.appendChild(li);
        textInput.value = '';
    }
}

document.getElementById('sectionForm').addEventListener('submit', function (e) {
    const items = document.querySelectorAll('#itemsContainer > div');
    let fullText = "";

    items.forEach(item => {
        const start = item.querySelector('.start-date').value;
        const end = item.querySelector('.end-date').value;
        const currently = item.querySelector('.currently').checked;
        const place = item.querySelector('.place').value;
        const bullets = item.querySelectorAll('.bullets li span');

        let line = `${place} (${formatDate(start)} - ${currently ? 'actualidad' : formatDate(end)})\n`;
        bullets.forEach(b => line += `• ${b.innerText.trim().slice(2)}\n`);
        fullText += line + "\n";
    });

    document.getElementById('content').value = fullText.trim();
});

function formatDate(ym) {
    if (!ym) return '';
    const [year, month] = ym.split("-");
    return `${month}/${year}`;
}
</script>

<?php if ($success): ?>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Sección añadida!',
    text: 'La nueva sección ha sido guardada exitosamente.',
}).then(() => {
    window.location.href = "index.php?cv=<?= $id_cv ?>";
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
