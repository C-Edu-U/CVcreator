<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['id_role'] != 1) {
    header("Location: ../../login.php");
    exit;
}

require_once '../../db.php';
require_once '../../libs/Autoloader.php';

$db = new Database();
$conn = $db->connect();
$roleModel = new Role($conn);

$id = $_GET['id'] ?? null;
$error = '';
$success = false;

if (!$id) {
    $error = "ID de rol no proporcionado.";
} elseif (in_array($id, [1, 2])) {
    $error = "No se puede eliminar un rol predeterminado.";
} else {
    $rol = $roleModel->getById($id);
    if (!$rol) {
        $error = "Rol no encontrado.";
    } elseif (!$roleModel->canDelete($id)) {
        $error = "Este rol no puede eliminarse porque está asignado a uno o más usuarios.";
    } else {
        $success = $roleModel->delete($id);
        if (!$success) {
            $error = "No se pudo eliminar el rol.";
        }
    }
}

$msg = $success ? 'eliminado=1' : 'error=' . urlencode($error);
header("Location: roles.php?$msg");
exit;
