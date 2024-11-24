<?php
session_start();
include_once "../db/Database.php";
include_once "../models/General.php";

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = General::sanitize($_POST['usuario']);
    $password = General::sanitize($_POST['password']);

    $query = "SELECT * FROM usuario WHERE nombre_usuario = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $usuario, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['usuario'] = $usuario;
        General::redirect('../index.php');
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <form method="POST" action="">
        <h2>Iniciar Sesión</h2>
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Ingresar</button>
        <?php if (isset($error)) echo "<p class='flash-message'>$error</p>"; ?>
    </form>
</body>
</html>
