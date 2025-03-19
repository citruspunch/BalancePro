<?php
class PolizasController
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    public function handlePolizaRequest()
    {
        $action = $_GET['action'] ?? 'list';
        switch ($action) {
            case 'addPoliza':
                $this->addPoliza();
                break;
            case 'editPoliza':
                $this->editPoliza();
                break;
            case 'deletePoliza':
                $this->deletePoliza();
                break;
            case 'viewPoliza':
                $this->viewPoliza();
                break;
            default:
                $this->listPolizas();
                break;
        }
    }

    public function addPoliza()
    {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $numPoliza = filter_input(INPUT_POST, 'numPoliza', FILTER_VALIDATE_INT);
                $fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $numCuentas = $_POST['numCuenta'] ?? [];
                $debes = $_POST['debe'] ?? [];
                $haberes = $_POST['haber'] ?? [];

                if (!$numPoliza || !$fecha || !$descripcion || empty($numCuentas) || empty($debes) || empty($haberes)) {
                    throw new Exception("Datos de entrada no válidos.");
                }

                $sumaDebe = array_sum($debes);
                $sumaHaber = array_sum($haberes);
                if ($sumaDebe !== $sumaHaber) {
                    $error = "La suma de los valores tanto del Debe como del Haber deben ser iguales.";
                    throw new Exception($error);
                }

                $this->db->beginTransaction();

                $query = "INSERT INTO Polizas (NumPoliza, Fecha, Descripcion) VALUES (:numPoliza, :fecha, :descripcion)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':numPoliza', $numPoliza, PDO::PARAM_INT);
                $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
                $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                $stmt->execute();

                $query = "INSERT INTO DetallePoliza (NumPoliza, NumCuenta, DebeHaber, Valor) VALUES (:numPoliza, :numCuenta, :debeHaber, :valor)";
                $stmt = $this->db->prepare($query);


                for ($i = 0; $i < count($numCuentas); $i++) {
                    $numCuenta = filter_var($numCuentas[$i], FILTER_VALIDATE_INT);
                    $debe = isset($debes[$i]) ? filter_var($debes[$i], FILTER_VALIDATE_FLOAT) : 0;
                    $haber = isset($haberes[$i]) ? filter_var($haberes[$i], FILTER_VALIDATE_FLOAT) : 0;

                    if ($debe > 0 && $haber > 0) {
                        $error = "No se puede ingresar un valor en Debe y Haber en la misma fila.";
                        throw new Exception($error);
                    }

                    if ($debe > 0) {
                        $debeHaber = 'D';
                        $valor = $debe;
                    } elseif ($haber > 0) {
                        $debeHaber = 'H';
                        $valor = $haber;
                    } else {
                        $error = "Debe ingresar un valor en Debe o Haber.";
                        throw new Exception($error);
                    }

                    $stmt->bindParam(':numPoliza', $numPoliza, PDO::PARAM_INT);
                    $stmt->bindParam(':numCuenta', $numCuenta, PDO::PARAM_INT);
                    $stmt->bindParam(':debeHaber', $debeHaber, PDO::PARAM_STR);
                    $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
                    $stmt->execute();
                }

                $this->db->commit();

                header("Location: /Sitio_web_contabilidad/polizas");
                exit();
            } catch (Exception $e) {
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                error_log("Error al agregar póliza: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }
        // Obtener los nombres de las cuentas para el formulario
        $query = "SELECT NumCuenta, NombreCuenta FROM Cuentas";
        $stmt = $this->db->query($query);
        $cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = './src/views/polizas/add.php';
        include './src/views/layout.php';
    }

    public function editPoliza()
    {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $numPoliza = filter_input(INPUT_POST, 'numPoliza', FILTER_VALIDATE_INT);
                $fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $numCuentas = $_POST['numCuenta'] ?? [];
                $debes = $_POST['debe'] ?? [];
                $haberes = $_POST['haber'] ?? [];

                if (!$numPoliza || !$fecha || !$descripcion || empty($numCuentas)) {
                    throw new Exception("Datos de entrada no válidos.");
                }

                if (count($numCuentas) !== count(array_unique($numCuentas))) {
                    throw new Exception("No se permiten nombres de cuentas duplicados.");
                }

                $debes = array_map(function ($value) {
                    return filter_var($value, FILTER_VALIDATE_FLOAT) ?: 0;
                }, $debes);

                $haberes = array_map(function ($value) {
                    return filter_var($value, FILTER_VALIDATE_FLOAT) ?: 0;
                }, $haberes);

                $sumaDebe = array_sum($debes);
                $sumaHaber = array_sum($haberes);
                if ($sumaDebe !== $sumaHaber) {
                    $error = "La suma de los valores tanto del Debe como del Haber deben ser iguales.";
                    throw new Exception($error);
                }

                $this->db->beginTransaction();

                $query = "UPDATE Polizas SET Fecha = :fecha, Descripcion = :descripcion WHERE NumPoliza = :numPoliza";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':numPoliza', $numPoliza, PDO::PARAM_INT);
                $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
                $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                $stmt->execute();

                $query = "DELETE FROM DetallePoliza WHERE NumPoliza = :numPoliza";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':numPoliza', $numPoliza, PDO::PARAM_INT);
                $stmt->execute();

                $query = "INSERT INTO DetallePoliza (NumPoliza, NumCuenta, DebeHaber, Valor) VALUES (:numPoliza, :numCuenta, :debeHaber, :valor)";
                $stmt = $this->db->prepare($query);

                for ($i = 0; $i < count($numCuentas); $i++) {
                    $numCuenta = filter_var($numCuentas[$i], FILTER_VALIDATE_INT);
                    $debe = $debes[$i];
                    $haber = $haberes[$i];

                    if ($debe > 0 && $haber > 0) {
                        $error = "No se puede ingresar un valor en Debe y Haber en la misma fila.";
                        throw new Exception($error);
                    }

                    if ($debe > 0) {
                        $debeHaber = 'D';
                        $valor = $debe;
                    } elseif ($haber > 0) {
                        $debeHaber = 'H';
                        $valor = $haber;
                    } else {
                        $error = "Debe ingresar un valor en Debe o Haber.";
                        throw new Exception($error);
                    }

                    $stmt->bindParam(':numPoliza', $numPoliza, PDO::PARAM_INT);
                    $stmt->bindParam(':numCuenta', $numCuenta, PDO::PARAM_INT);
                    $stmt->bindParam(':debeHaber', $debeHaber, PDO::PARAM_STR);
                    $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
                    $stmt->execute();
                }

                $this->db->commit();

                header("Location: /Sitio_web_contabilidad/polizas");
                exit();
            } catch (Exception $e) {
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                error_log("Error al editar póliza: " . $e->getMessage());
                $error = $e->getMessage();
            }
        } else {
            $numPoliza = filter_input(INPUT_GET, 'numPoliza', FILTER_VALIDATE_INT);
            if (!$numPoliza) {
                throw new Exception("Número de poliza no válido.");
            }
        }
        $poliza = $this->getPoliza($numPoliza);
        $detalles = $this->getDetallesPoliza($numPoliza);
        $query = "SELECT NumCuenta, NombreCuenta FROM Cuentas";
        $stmt = $this->db->query($query);
        $cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $content = './src/views/polizas/edit.php';
        include './src/views/layout.php';
    }

    // Método para eliminar una póliza y sus detalles
    public function deletePoliza()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $numPoliza = filter_input(INPUT_POST, 'numPoliza', FILTER_VALIDATE_INT);

                if (!$numPoliza) {
                    throw new Exception("Datos de entrada no válidos.");
                }

                $this->db->beginTransaction();

                $query = "DELETE FROM Polizas WHERE NumPoliza = :numPoliza";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':numPoliza', $numPoliza, PDO::PARAM_INT);
                $stmt->execute();

                $query = "DELETE FROM DetallePoliza WHERE NumPoliza = :numPoliza";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':numPoliza', $numPoliza, PDO::PARAM_INT);
                $stmt->execute();

                $this->db->commit();

                header("Location: /Sitio_web_contabilidad/polizas");
                exit();
            } catch (Exception $e) {
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                error_log("Error al eliminar póliza: " . $e->getMessage());
                $error = "Ocurrió un error al eliminar la póliza.";
            }
        } else {
            $numPoliza = filter_input(INPUT_GET, 'numPoliza', FILTER_VALIDATE_INT);
            if (!$numPoliza) {
                throw new Exception("Número de poliza no válido.");
            }
            $cuenta = $this->getPoliza($numPoliza);
        }
        $content = './src/views/polizas/delete.php';
        include './src/views/layout.php';
    }

    public function viewPoliza()
    {
        try {
            $numPoliza = filter_input(INPUT_GET, 'numPoliza', FILTER_VALIDATE_INT);
            if (!$numPoliza) {
                throw new Exception("Número de póliza no válido.");
            }
            $query = "SELECT * FROM Polizas WHERE NumPoliza = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $numPoliza, PDO::PARAM_INT);
            $stmt->execute();
            $poliza = $stmt->fetch(PDO::FETCH_OBJ);

            $query = 'SELECT D.DebeHaber, D.Valor, C.NombreCuenta FROM DetallePoliza D, Cuentas C WHERE D.NumCuenta = C.NumCuenta AND D.NumPoliza = ?';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $numPoliza, PDO::PARAM_INT);
            $stmt->execute();
            $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $query = 'SELECT SUM(Valor) AS TotalDebe FROM DetallePoliza WHERE NumPoliza = ? AND DebeHaber = "D"';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $numPoliza, PDO::PARAM_INT);
            $stmt->execute();
            $totalDebe = $stmt->fetch(PDO::FETCH_OBJ)->TotalDebe;

            $query = 'SELECT SUM(Valor) AS TotalHaber FROM DetallePoliza WHERE NumPoliza = ? AND DebeHaber = "H"';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $numPoliza, PDO::PARAM_INT);
            $stmt->execute();
            $totalHaber = $stmt->fetch(PDO::FETCH_OBJ)->TotalHaber;

            $content = './src/views/polizas/view.php';
            include './src/views/layout.php';
        } catch (Exception $e) {
            error_log("Error al ver póliza: " . $e->getMessage());
            throw new Exception("Ocurrió un error al ver la póliza.");
        }
    }

    // Método para listar todas las pólizas
    public function listPolizas()
    {
        try {
            $query = "SELECT * FROM Polizas WHERE Polizas.NumPoliza";
            $stmt = $this->db->query($query);
            $polizas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $content = './src/views/polizas/list.php';
            include './src/views/layout.php';
        } catch (Exception $e) {
            error_log("Error al listar pólizas: " . $e->getMessage());
            throw new Exception("Ocurrió un error al listar las pólizas.");
        }

    }

    public function getPoliza($numPoliza)
    {
        try {
            $query = "SELECT * FROM Polizas WHERE NumPoliza = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $numPoliza, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("" . $e->getMessage());
            throw new Exception("Ocurrió un error al obtener la póliza.");
        }
    }

    public function getDetallesPoliza($numPoliza)
    {
        try {
            $query = "SELECT * FROM DetallePoliza WHERE NumPoliza = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $numPoliza, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener detalles de la póliza: " . $e->getMessage());
            throw new Exception("Ocurrió un error al obtener los detalles de la poliza.");
        }
    }
}
?>