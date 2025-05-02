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

$userModel = new User($conn);

$id_user = $_GET['id'] ?? null;
$success = false;
$error = '';

if (!$id_user) {
    $error = "ID de usuario no proporcionado.";
} elseif ($id_user == $_SESSION['user_id']) {
    $error = "No puedes eliminar tu propia cuenta.";
} else {
    $usuario = $userModel->getById($id_user);
    if (!$usuario) {
        $error = "El usuario no existe.";
    } else {
        $success = $userModel->delete($id_user);
        if (!$success) {
            $error = "Error al eliminar el usuario.";
        }
    }
}

$msg = $success ? 'eliminado=1' : 'error=' . urlencode($error);
header("Location: index.php?$msg");
exit;
