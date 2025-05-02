<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 403 Forbidden");
    exit("Acceso denegado. Debes iniciar sesión.");
}

header("Content-Type: application/rss+xml; charset=UTF-8");

require_once 'db.php';
require_once 'libs/Autoloader.php';

$db = new Database();
$conn = $db->connect();

echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
?>
<rss version="2.0">
  <channel>
    <title>Usuarios del Sistema</title>
    <link>http://localhost:8081/rss_usuarios.php</link>
    <description>Historial de usuarios registrados en el sistema</description>
    <language>es</language>

    <?php
    $usuarios = $conn->query("SELECT name, email FROM users ORDER BY id_user DESC");
    while ($row = $usuarios->fetch(PDO::FETCH_ASSOC)):
    ?>
    <item>
      <title><?= htmlspecialchars($row['name']) ?></title>
      <description><?= htmlspecialchars($row['email']) ?></description>
    </item>
    <?php endwhile; ?>
  </channel>
</rss>
