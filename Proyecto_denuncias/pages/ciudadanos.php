<?php
session_start();
include_once "../models/General.php";
include_once "../db/Database.php";

if (!isset($_SESSION['usuario'])) {
    General::redirect('login.php', 'Por favor, inicia sesión.');
}

$db = new Database();
$conn = $db->getConnection();

// Registrar un nuevo ciudadano
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $id_ciudadano = General::sanitize($_POST['id_ciudadano']);
    $nombre_ciudadano = General::sanitize($_POST['nombre_ciudadano']);
    $telefono = General::sanitize($_POST['telefono']);
    $correo = General::sanitize($_POST['correo']);

    $query = "INSERT INTO ciudadano (id_ciudadano, nombre_ciudadano, telefono, correoelectronico) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $id_ciudadano, $nombre_ciudadano, $telefono, $correo);

    if ($stmt->execute()) {
        General::redirect('ciudadanos.php', 'Ciudadano registrado con éxito.');
    } else {
        $error = "Error al registrar al ciudadano. Verifica si el ID ya existe.";
    }
}

// Eliminar un ciudadano
if (isset($_GET['eliminar'])) {
    $id_ciudadano = General::sanitize($_GET['eliminar']);

    // Verificar si el ciudadano tiene denuncias asociadas
    $query = "SELECT * FROM denuncia WHERE id_ciudadano = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id_ciudadano);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "No se puede eliminar al ciudadano porque tiene denuncias asociadas.";
    } else {
        $query = "DELETE FROM ciudadano WHERE id_ciudadano = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $id_ciudadano);

        if ($stmt->execute()) {
            General::redirect('ciudadanos.php', 'Ciudadano eliminado con éxito.');
        } else {
            $error = "Error al eliminar al ciudadano.";
        }
    }
}

// Obtener todos los ciudadanos
$query = "SELECT * FROM ciudadano";
$resultado = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciudadanos</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <header>
        <h1>Gestión de Ciudadanos</h1>
        <nav>
            <a href="../index.php">Inicio</a>
            <a href="denuncias.php">Denuncias</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>
    <?php General::displayFlashMessage(); ?>
    <section>
        <h2>Registrar Nuevo Ciudadano</h2>
        <form method="POST" action="">
            <input type="text" name="id_ciudadano" placeholder="Cédula o ID" required>
            <input type="text" name="nombre_ciudadano" placeholder="Nombre Completo" required>
            <input type="text" name="telefono" placeholder="Teléfono" required>
            <input type="email" name="correo" placeholder="Correo Electrónico">
            <button type="submit" name="crear">Registrar Ciudadano</button>
        </form>
    </section>
    <section>
        <h2>Lista de Ciudadanos</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $fila['id_ciudadano']; ?></td>
                        <td><?php echo $fila['nombre_ciudadano']; ?></td>
                        <td><?php echo $fila['telefono']; ?></td>
                        <td><?php echo $fila['correoelectronico']; ?></td>
                        <td>
                            <a href="ciudadanos.php?eliminar=<?php echo $fila['id_ciudadano']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
