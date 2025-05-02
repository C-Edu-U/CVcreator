<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once '../../db.php';
require_once '../../libs/Autoloader.php';

$db = new Database();
$conn = $db->connect();
$cvModel = new Cv($conn);

$id_cv = $_GET['id'] ?? null;
$success = false;
$error = '';

if ($id_cv) {
    $cv = $cvModel->getById($id_cv);
    if (!$cv) {
        $error = "El CV no existe.";
    } elseif ($_SESSION['id_role'] != 1 && $cv['id_user'] != $_SESSION['user_id']) {
        $error = "No tienes permiso para eliminar este CV.";
    } else {
        $success = $cvModel->delete($id_cv);
        if (!$success) {
            $error = "No se pudo eliminar el CV.";
        }
    }
} else {
    $error = "ID de CV no proporcionado.";
}

$msg = $success ? 'eliminado=1' : 'error=' . urlencode($error);
header("Location: index.php?$msg");
exit;

