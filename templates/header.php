<?php $url_base = "http://localhost:8082/"; ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Generador de CVs</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
          crossorigin="anonymous" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery (requerido por DataTables y SweetAlert) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= $url_base ?>">
                Generador de CVs
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= $url_base ?>secciones/cvs/index.php">CVs</a></li>
                    <?php if (isset($_SESSION['id_role']) && $_SESSION['id_role'] == 1): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= $url_base ?>secciones/usuarios/index.php">Usuarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $url_base ?>secciones/usuarios/roles.php">Roles</a></li>
                    <?php endif; ?>
                </ul>
                <?php if (isset($_SESSION['user_name'])): ?>
                <li class="nav-item dropdown ms-auto">
                    <a class="btn btn-outline-light dropdown-toggle text-dark bg-white fw-bold" href="#" role="button" data-bs-toggle="dropdown">
                        👤 <?= $_SESSION['user_name'] ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= $url_base ?>secciones/usuarios/perfil.php">Mi Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= $url_base ?>logout.php">Cerrar sesión</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>

<main class="container py-4">
