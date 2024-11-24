<?php
include_once "../db/Database.php";

class Denuncia {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function crearDenuncia($datos) {
        $query = "INSERT INTO denuncia (descripcion_denuncia, id_ciudadano, id_categoria, id_provincia, fecha_denuncia, estatus_denuncia) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "siiiss",
            $datos['descripcion_denuncia'],
            $datos['id_ciudadano'],
            $datos['id_categoria'],
            $datos['id_provincia'],
            $datos['fecha_denuncia'],
            $datos['estatus_denuncia']
        );
        return $stmt->execute();
    }

    public function obtenerDenuncias($filtros = []) {
        $query = "SELECT 
                    denuncia.id_denuncia,
                    denuncia.descripcion_denuncia,
                    ciudadano.nombre_ciudadano,
                    categoria.nombre_categoria,
                    provincia.nombre_provincia,
                    denuncia.fecha_denuncia,
                    denuncia.estatus_denuncia
                  FROM denuncia
                  JOIN ciudadano ON denuncia.id_ciudadano = ciudadano.id_ciudadano
                  JOIN categoria ON denuncia.id_categoria = categoria.id_categoria
                  JOIN provincia ON denuncia.id_provincia = provincia.id_provincia
                  WHERE 1=1";

        if (!empty($filtros['id_provincia'])) {
            $query .= " AND denuncia.id_provincia = " . intval($filtros['id_provincia']);
        }
        if (!empty($filtros['id_categoria'])) {
            $query .= " AND denuncia.id_categoria = " . intval($filtros['id_categoria']);
        }
        if (!empty($filtros['id_ciudadano'])) {
            $query .= " AND denuncia.id_ciudadano = '" . $this->conn->real_escape_string($filtros['id_ciudadano']) . "'";
        }

        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function __destruct() {
        $this->conn->close();
    }
}
?>
