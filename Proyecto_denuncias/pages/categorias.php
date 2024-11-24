<?php
session_start();
include_once "../models/General.php";
include_once "../db/Database.php";

if (!isset($_SESSION['usuario'])) {
    General::redirect('login.php', 'Por favor, inicia sesión.');
}

$db = new Database();
$conn = $db->getConnection();

// Crear una nueva categoría
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $nombre_categoria = General::sanitize($_POST['nombre_categoria']);
    $query = "INSERT INTO categoria (nombre_categoria) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nombre_categoria);

    if ($stmt->execute()) {
        General::redirect('categorias.php', 'Categoría creada con éxito.');
    } else {
        $error = "Error al crear la categoría.";
    }
}

// Eliminar una categoría
if (isset($_GET['eliminar'])) {
    $id_categoria = intval($_GET['eliminar']);
    $query = "DELETE FROM categoria WHERE id_categoria = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_categoria);

    if ($stmt->execute()) {
        General::redirect('categorias.php', 'Categoría eliminada con éxito.');
    } else {
        $error = "Error al eliminar la categoría.";
    }
}

// Obtener todas las categorías
$query = "SELECT * FROM categoria";
$resultado = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <header>
        <h1>Gestión de Categorías</h1>
        <nav>
            <a href="../index.php">Inicio</a>
            <a href="denuncias.php">Denuncias</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>
    <?php General::displayFlashMessage(); ?>
    <section>
        <h2>Registrar Nueva Categoría</h2>
        <form method="POST" action="">
            <input type="text" name="nombre_categoria" placeholder="Nombre de la Categoría" required>
            <button type="submit" name="crear">Crear Categoría</button>
        </form>
    </section>
    <section>
        <h2>Lista de Categorías</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $fila['id_categoria']; ?></td>
                        <td><?php echo $fila['nombre_categoria']; ?></td>
                        <td>
                            <a href="categorias.php?eliminar=<?php echo $fila['id_categoria']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
