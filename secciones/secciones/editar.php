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

$id_section = $_GET['id'] ?? $_POST['id_section'] ?? null;
$success = false;
$error = '';

if (!$id_section) die("ID de sección no proporcionado.");

$section = $sectionModel->getById($id_section);
$cv = $cvModel->getById($section['id_cv']);
$id_cv = $section['id_cv'];

if (!$section || !$cv) die("Sección o CV no encontrados.");

$userId = $_SESSION['user_id'];
$isAdmin = $_SESSION['id_role'] == 1;
if (!$isAdmin && $cv['id_user'] != $userId) die("Acceso denegado.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $content = trim($_POST['content']);
    $order = 0;

    if (!empty($type) && !empty($content)) {
        $success = $sectionModel->update($id_section, $type, $content, $order);
        $section['type'] = $type;
        $section['content'] = $content;
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>

<h1 class="mb-4">Editar Sección del CV: <strong><?= htmlspecialchars($cv['title']) ?></strong></h1>

<form method="POST" class="w-100" id="sectionForm">
    <input type="hidden" name="id_section" value="<?= $section['id_section'] ?>">
    <input type="hidden" name="id_cv" value="<?= $id_cv ?>">

    <div class="mb-3">
        <label for="type" class="form-label">Tipo de sección</label>
        <select name="type" class="form-select" id="type" required>
            <option value="">-- Selecciona --</option>
            <option value="educacion" <?= $section['type'] === 'educacion' ? 'selected' : '' ?>>Educación</option>
            <option value="experiencia" <?= $section['type'] === 'experiencia' ? 'selected' : '' ?>>Experiencia</option>
            <option value="habilidad" <?= $section['type'] === 'habilidad' ? 'selected' : '' ?>>Habilidad</option>
            <option value="certificacion" <?= $section['type'] === 'certificacion' ? 'selected' : '' ?>>Certificación</option>
            <option value="proyecto" <?= $section['type'] === 'proyecto' ? 'selected' : '' ?>>Proyecto</option>
        </select>
    </div>

    <div id="itemsContainer"></div>

    <button type="button" class="btn btn-outline-secondary mb-3" onclick="addItem()">➕ Agregar ítem</button>

    <textarea name="content" id="content" class="form-control d-none"><?= htmlspecialchars($section['content']) ?></textarea>

    <button type="submit" class="btn btn-primary">Guardar cambios</button>
    <a href="index.php?cv=<?= $id_cv ?>" class="btn btn-secondary">Cancelar</a>
</form>

<script>
let itemIndex = 0;

window.onload = function () {
    const raw = document.getElementById('content').value.trim();
    const bloques = raw.split(/\n\s*\n/);

    bloques.forEach(b => {
        const lineas = b.trim().split('\n');
        if (lineas.length === 0) return;

        const headerMatch = lineas[0].match(/^(.*)\s\((\d{1,2}\/\d{4})\s*-\s*(.+?)\)$/i);
        if (!headerMatch) return;

        const place = headerMatch[1].trim();
        const start = formatToInputDate(headerMatch[2].padStart(7, '0'));
        const endRaw = headerMatch[3].toLowerCase().trim();
        const currently = /actualidad|en\s*curso/.test(endRaw);
        const end = currently ? '' : formatToInputDate(endRaw.padStart(7, '0'));

        const bullets = lineas.slice(1).map(l =>
            l.replace(/^[-•*]\s*/, '').trim()
        ).filter(b => b !== '');

        addItem({ place, start, end, currently, bullets });
    });
};

function addItem(data = {}) {
    const container = document.getElementById('itemsContainer');

    const div = document.createElement('div');
    div.className = 'border p-3 mb-3 rounded bg-light position-relative';

    div.innerHTML = `
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2" onclick="this.parentElement.remove()"></button>
        <div class="row mb-2">
            <div class="col-md-6">
                <label>Inicio</label>
                <input type="month" class="form-control start-date" value="${data.start || ''}">
            </div>
            <div class="col-md-6">
                <label>Fin</label>
                <input type="month" class="form-control end-date" ${data.currently ? 'disabled' : ''} value="${data.end || ''}">
                <div class="form-check mt-1">
                    <input type="checkbox" class="form-check-input currently" ${data.currently ? 'checked' : ''} onchange="toggleEndDate(this)">
                    <label class="form-check-label">Actualmente</label>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label>Institución / Empresa</label>
            <input type="text" class="form-control place" value="${data.place || ''}" required>
        </div>
        <div class="mb-2">
            <label>Bulletpoints</label>
            <ul class="list-group bullets mb-2">
                ${(data.bullets || []).map(b => `<li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>• ${b}</span><button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
                </li>`).join('')}
            </ul>
            <div class="input-group">
                <input type="text" class="form-control bullet-text" placeholder="Escribe un punto">
                <button type="button" class="btn btn-outline-primary" onclick="addBullet(this)">➕</button>
            </div>
        </div>
    `;
    container.appendChild(div);
}

function toggleEndDate(checkbox) {
    const endInput = checkbox.closest('.col-md-6').querySelector('.end-date');
    endInput.disabled = checkbox.checked;
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

function formatDate(ym) {
    if (!ym) return '';
    const [year, month] = ym.split("-");
    return `${month}/${year}`;
}

function formatToInputDate(mmyyyy) {
    const [mm, yyyy] = mmyyyy.split('/');
    return `${yyyy}-${mm}`;
}

document.getElementById('sectionForm').addEventListener('submit', function () {
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
</script>

<?php if ($success): ?>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Sección actualizada!',
    text: 'La sección fue modificada exitosamente.',
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
