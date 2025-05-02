<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'templates/header.php';

$role = $_SESSION['id_role'];
?>
<h1 class="mb-4">Panel Principal</h1>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <div class="col">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">📝 Mis CVs</h5>
                <p class="card-text">Crea, edita y gestiona tus currículums en formato Harvard.</p>
                <a href="secciones/cvs/index.php" class="btn btn-primary">Ir a CVs</a>
            </div>
        </div>
    </div>

    <?php if ($role == 1): ?>
    <div class="col">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">👥 Usuarios</h5>
                <p class="card-text">Gestiona los usuarios registrados y sus permisos.</p>
                <a href="secciones/usuarios/index.php" class="btn btn-primary">Ir a Usuarios</a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">🔐 Roles</h5>
                <p class="card-text">Administra los roles del sistema.</p>
                <a href="secciones/usuarios/roles.php" class="btn btn-primary">Ver Roles</a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">📜 Historial</h5>
                <p class="card-text">Consulta los cambios realizados en el sistema.</p>
                <a href="rss_usuarios.php" target="_blank" class="btn btn-outline-secondary">📄 Historial RSS</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="col">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">🚪 Cerrar sesión</h5>
                <p class="card-text">Finaliza tu sesión de forma segura.</p>
                <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>
