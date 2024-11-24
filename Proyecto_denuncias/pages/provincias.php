<?php
session_start();
include_once "../models/General.php";
include_once "../db/Database.php";

if (!isset($_SESSION['usuario'])) {
    General::redirect('login.php', 'Por favor, inicia sesión.');
}

$db = new Database();
$conn = $db->getConnection();

// Crear una nueva provincia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $nombre_provincia = General::sanitize($_POST['nombre_provincia']);

    // Validar si la provincia ya existe
    $query = "SELECT * FROM provincia WHERE nombre_provincia = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nombre_provincia);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "La provincia ya existe.";
    } else {
        // Insertar la nueva provincia
        $query = "INSERT INTO provincia (nombre_provincia) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nombre_provincia);

        if ($stmt->execute()) {
            General::redirect('provincias.php', 'Provincia creada con éxito.');
        } else {
            $error = "Error al crear la provincia.";
        }
    }
}

// Eliminar una provincia
if (isset($_GET['eliminar'])) {
    $id_provincia = intval($_GET['eliminar']);
    $query = "DELETE FROM provincia WHERE id_provincia = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_provincia);

    if ($stmt->execute()) {
        General::redirect('provincias.php', 'Provincia eliminada con éxito.');
    } else {
        $error = "Error al eliminar la provincia.";
    }
}

// Obtener todas las provincias
$query = "SELECT * FROM provincia";
$resultado = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provincias</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <header>
        <h1>Gestión de Provincias</h1>
        <nav>
            <a href="../index.php">Inicio</a>
            <a href="denuncias.php">Denuncias</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>
    <?php General::displayFlashMessage(); ?>
    <section>
        <h2>Registrar Nueva Provincia</h2>
        <form method="POST" action="">
            <input type="text" name="nombre_provincia" placeholder="Nombre de la Provincia" required>
            <button type="submit" name="crear">Crear Provincia</button>
        </form>
    </section>
    <section>
        <h2>Lista de Provincias</h2>
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
                        <td><?php echo $fila['id_provincia']; ?></td>
                        <td><?php echo $fila['nombre_provincia']; ?></td>
                        <td>
                            <a href="provincias.php?eliminar=<?php echo $fila['id_provincia']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
