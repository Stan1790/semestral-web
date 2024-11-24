<?php
session_start();
include_once "models/General.php";

if (!isset($_SESSION['usuario'])) {
    General::redirect('pages/login.php');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <h1>Sistema de Denuncias Ciudadanas</h1>
        <nav>
            <a href="pages/denuncias.php">Denuncias</a>
            <a href="pages/usuarios.php">Usuarios</a>
            <a href="pages/logout.php">Cerrar Sesión</a>
        </nav>
    </header>
</body>
</html>
