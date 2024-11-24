<?php
session_start();
include_once "../models/Usuario.php";
include_once "../models/General.php";

if (!isset($_SESSION['usuario'])) {
    General::redirect('login.php', 'Por favor, inicia sesión.');
}

$usuario = new Usuario();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $datos = [
        'nombre_usuario' => General::sanitize($_POST['nombre_usuario']),
        'apellido_usuario' => General::sanitize($_POST['apellido_usuario']),
        'correo_usuario' => General::sanitize($_POST['correo_usuario']),
        'password' => General::sanitize($_POST['password'])
    ];
    $usuario->crearUsuario($datos);
    General::redirect('usuarios.php', 'Usuario creado con éxito.');
}

if (isset($_GET['eliminar'])) {
    $usuario->eliminarUsuario($_GET['eliminar']);
    General::redirect('usuarios.php', 'Usuario eliminado con éxito.');
}

$usuarios = $usuario->obtenerUsuarios();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <header>
        <h1>Gestión de Usuarios</h1>
        <nav>
            <a href="../index.php">Inicio</a>
            <a href="denuncias.php">Denuncias</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>
    <?php General::displayFlashMessage(); ?>
    <section>
        <h2>Registrar Nuevo Usuario</h2>
        <form method="POST" action="">
            <input type="text" name="nombre_usuario" placeholder="Nombre" required>
            <input type="text" name="apellido_usuario" placeholder="Apellido" required>
            <input type="email" name="correo_usuario" placeholder="Correo Electrónico">
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" name="crear">Registrar Usuario</button>
        </form>
    </section>
    <section>
        <h2>Lista de Usuarios</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?php echo $u['id_usuario']; ?></td>
                        <td><?php echo $u['nombre_usuario']; ?></td>
                        <td><?php echo $u['apellido_usuario']; ?></td>
                        <td><?php echo $u['correo_usuario']; ?></td>
                        <td>
                            <a href="usuarios.php?eliminar=<?php echo $u['id_usuario']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
