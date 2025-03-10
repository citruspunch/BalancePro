<?php

class CuentasController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function addCuenta() {
        $nombreCuenta = '';
        $tipo = '';
        $error = '';
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar los datos
                $numCuenta = filter_input(INPUT_POST, 'NumCuenta', FILTER_VALIDATE_INT);
                $nombreCuenta = filter_input(INPUT_POST, 'NombreCuenta', FILTER_SANITIZE_STRING);
                $tipo = filter_input(INPUT_POST, 'Tipo', FILTER_SANITIZE_STRING);
    
                if (!$numCuenta || !$nombreCuenta || !$tipo) {
                    throw new Exception("Datos de entrada no válidos.");
                }
    
                $query = "INSERT INTO Cuentas (NumCuenta, NombreCuenta, Tipo) VALUES (:numCuenta, :nombreCuenta, :tipo)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':numCuenta', $numCuenta, PDO::PARAM_INT);
                $stmt->bindParam(':nombreCuenta', $nombreCuenta, PDO::PARAM_STR);
                $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                $stmt->execute();
    
                header("Location: /cuentas");
                exit();
            } catch (Exception $e) {
                error_log("Error al agregar cuenta: " . $e->getMessage());
                $error = "Ocurrió un error al agregar la cuenta.";
            }
        }
    
        include './src/views/cuentas/add.php';
    }

    public function editCuenta() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {

                $numCuenta = filter_input(INPUT_POST, 'NumCuenta', FILTER_VALIDATE_INT);
                $nombreCuenta = filter_input(INPUT_POST, 'NombreCuenta', FILTER_SANITIZE_STRING);
                $tipo = filter_input(INPUT_POST, 'Tipo', FILTER_SANITIZE_STRING);

                if (!$numCuenta || !$nombreCuenta || !$tipo) {
                    throw new Exception("Datos de entrada no válidos.");
                }

                $query = "UPDATE Cuentas SET NombreCuenta = ?, Tipo = ? WHERE NumCuenta = ?";
                $this->executeQuery($query, [$nombreCuenta, $tipo, $numCuenta]);

                header("Location: /cuentas");
                die();
            } catch (Exception $e) {
                error_log("Error al editar cuenta: " . $e->getMessage());
                throw new Exception("Ocurrió un error al editar la cuenta.");
            }
        } else {
            $numCuenta = filter_input(INPUT_GET, 'NumCuenta', FILTER_VALIDATE_INT);
            if (!$numCuenta) {
                throw new Exception("Número de cuenta no válido.");
            }
            $cuenta = $this->getCuenta($numCuenta);
            include './src/views/cuentas/edit.php';
        }
    }

    public function deleteCuenta() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $numCuenta = filter_input(INPUT_POST, 'NumCuenta', FILTER_VALIDATE_INT);
                if (!$numCuenta) {
                    throw new Exception("Número de cuenta no válido.");
                }

                $query = "DELETE FROM Cuentas WHERE NumCuenta = ?";
                $this->executeQuery($query, [$numCuenta]);

                header("Location: /cuentas");
                exit();
            } catch (Exception $e) {
                error_log("Error al eliminar cuenta: " . $e->getMessage());
                throw new Exception("Ocurrió un error al eliminar la cuenta.");
            }
        } else {
            $numCuenta = filter_input(INPUT_GET, 'NumCuenta', FILTER_VALIDATE_INT);
            if (!$numCuenta) {
                throw new Exception("Número de cuenta no válido.");
            }
            $cuenta = $this->getCuenta($numCuenta);
            include './src/views/cuentas/delete.php';
        }
    }

    public function listCuentas() {
        try {
            $query = "SELECT * FROM Cuentas";
            $result = $this->db->query($query);
            $cuentas = $result->fetch_all(MYSQLI_ASSOC);
            include './src/views/cuentas/list.php';
        } catch (Exception $e) {
            error_log("Error al listar cuentas: " . $e->getMessage());
            throw new Exception("Ocurrió un error al listar las cuentas.");
        }
    }

    private function getCuenta($numCuenta) {
        try {
            $query = "SELECT * FROM Cuentas WHERE NumCuenta = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $numCuenta);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error al obtener cuenta: " . $e->getMessage());
            throw new Exception("Ocurrió un error al obtener la cuenta.");
        }
    }

    private function executeQuery($query, $params = []) {
        try {
            $stmt = $this->db->prepare($query);
            if (!empty($params)) {
                $stmt->bind_param(str_repeat('s', count($params)), ...$params);
            }
            $stmt->execute();
            return $stmt;
        } catch (Exception $e) {
            error_log("Error al ejecutar consulta: " . $e->getMessage());
            throw new Exception("Ocurrió un error al ejecutar la consulta.");
        }
    }
}
?>