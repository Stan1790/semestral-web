<?php
include_once "../db/Database.php";

class Usuario {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function crearUsuario($datos) {
        $query = "INSERT INTO usuario (nombre_usuario, apellido_usuario, correodel_usuario, password) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "ssss",
            $datos['nombre_usuario'],
            $datos['apellido_usuario'],
            $datos['correodel_usuario'],
            $datos['password']
        );
        return $stmt->execute();
    }

    public function obtenerUsuarios() {
        $query = "SELECT id_usuario, nombre_usuario, apellido_usuario, correodel_usuario FROM usuario";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function eliminarUsuario($id) {
        $query = "DELETE FROM usuario WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function __destruct() {
        $this->conn->close();
    }
}
?>
