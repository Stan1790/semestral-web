<?php
include_once "../models/Denuncia.php";
include_once "../db/Database.php";

// Inicializar modelo de denuncia
$denuncia = new Denuncia();
$db = new Database();
$conn = $db->getConnection();

// Obtener datos para selects
$categorias = $conn->query("SELECT * FROM categoria");
$provincias = $conn->query("SELECT * FROM provincia");
$ciudadanos = $conn->query("SELECT * FROM ciudadano");

// Procesar creación de denuncia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $datos = [
        'descripcion_denuncia' => $_POST['descripcion_denuncia'],
        'id_ciudadano' => $_POST['id_ciudadano'],
        'id_categoria' => $_POST['id_categoria'],
        'id_provincia' => $_POST['id_provincia'],
        'fecha_denuncia' => $_POST['fecha_denuncia'],
        'estatus_denuncia' => $_POST['estatus_denuncia']
    ];
    $denuncia->crearDenuncia($datos);
}

// Procesar filtros
$filtros = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_GET['id_provincia'])) $filtros['id_provincia'] = $_GET['id_provincia'];
    if (!empty($_GET['id_categoria'])) $filtros['id_categoria'] = $_GET['id_categoria'];
    if (!empty($_GET['id_ciudadano'])) $filtros['id_ciudadano'] = $_GET['id_ciudadano'];
}

$denuncias = $denuncia->obtenerDenuncias($filtros);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denuncias</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <header>
        <h1>Gestión de Denuncias</h1>
        <nav>
            <a href="../index.php">Inicio</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>

    <!-- Formulario para registrar nueva denuncia -->
    <section>
        <h2>Registrar Nueva Denuncia</h2>
        <form method="POST" action="">
            <textarea name="descripcion_denuncia" placeholder="Descripción de la denuncia" required></textarea>
            <select name="id_ciudadano" required>
                <option value="">Seleccione Ciudadano</option>
                <?php while ($fila = $ciudadanos->fetch_assoc()): ?>
                    <option value="<?php echo $fila['id_ciudadano']; ?>"><?php echo $fila['nombre_ciudadano']; ?></option>
                <?php endwhile; ?>
            </select>
            <select name="id_categoria" required>
                <option value="">Seleccione Categoría</option>
                <?php while ($fila = $categorias->fetch_assoc()): ?>
                    <option value="<?php echo $fila['id_categoria']; ?>"><?php echo $fila['nombre_categoria']; ?></option>
                <?php endwhile; ?>
            </select>
            <select name="id_provincia" required>
                <option value="">Seleccione Provincia</option>
                <?php while ($fila = $provincias->fetch_assoc()): ?>
                    <option value="<?php echo $fila['id_provincia']; ?>"><?php echo $fila['nombre_provincia']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="date" name="fecha_denuncia" required>
            <select name="estatus_denuncia" required>
                <option value="A">Activa</option>
                <option value="P">En Atención</option>
                <option value="C">Cerrada</option>
                <option value="D">Cancelada</option>
            </select>
            <button type="submit" name="crear">Registrar Denuncia</button>
        </form>
    </section>

    <!-- Filtrado de denuncias -->
    <section>
        <h2>Filtrar Denuncias</h2>
        <form method="GET" action="">
            <select name="id_provincia">
                <option value="">Seleccione Provincia</option>
                <?php while ($fila = $provincias->fetch_assoc()): ?>
                    <option value="<?php echo $fila['id_provincia']; ?>"><?php echo $fila['nombre_provincia']; ?></option>
                <?php endwhile; ?>
            </select>
            <select name="id_categoria">
                <option value="">Seleccione Categoría</option>
                <?php while ($fila = $categorias->fetch_assoc()): ?>
                    <option value="<?php echo $fila['id_categoria']; ?>"><?php echo $fila['nombre_categoria']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="text" name="id_ciudadano" placeholder="ID Ciudadano">
            <button type="submit">Filtrar</button>
        </form>
    </section>

    <!-- Lista de denuncias -->
    <section>
        <h2>Lista de Denuncias</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Ciudadano</th>
                    <th>Categoría</th>
                    <th>Provincia</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($denuncias as $fila): ?>
                    <tr>
                        <td><?php echo $fila['id_denuncia']; ?></td>
                        <td><?php echo $fila['descripcion_denuncia']; ?></td>
                        <td><?php echo $fila['nombre_ciudadano']; ?></td>
                        <td><?php echo $fila['nombre_categoria']; ?></td>
                        <td><?php echo $fila['nombre_provincia']; ?></td>
                        <td><?php echo $fila['fecha_denuncia']; ?></td>
                        <td><?php echo $fila['estatus_denuncia']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
