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
            default:
                $this->listPolizas();
                break;
        }
    }

    // Método para agregar una póliza y sus detalles
    public function addPoliza()
    {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $numPoliza = filter_input(INPUT_POST, 'NumPoliza', FILTER_VALIDATE_INT);
                $fecha = filter_input(INPUT_POST, 'Fecha', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $descripcion = filter_input(INPUT_POST, 'Descripcion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $numCuentas = $_POST['numCuenta'];
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

                // Insertar la póliza
                $query = "INSERT INTO Polizas (NumPoliza, Fecha, Descripcion) VALUES (:numPoliza, :fecha, :descripcion)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':numPoliza', $numPoliza, PDO::PARAM_INT);
                $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
                $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                $stmt->execute();

                // Insertar los detalles de la póliza
                $query = "INSERT INTO DetallePoliza (NumPoliza, NumCuenta, DebeHaber, Valor) VALUES (:numPoliza, :numCuenta, :debeHaber, :valor)";
                $stmt = $this->db->prepare($query);


                // Recorrer los arrays y procesar cada fila
                for ($i = 0; $i < count($numCuentas); $i++) {
                    $numCuenta = filter_var($numCuentas[$i], FILTER_VALIDATE_INT);
                    $debe = filter_var($debes[$i], FILTER_VALIDATE_FLOAT);
                    $haber = filter_var($haberes[$i], FILTER_VALIDATE_FLOAT);

                    // Validar que no se ingresen valores en ambos campos (Debe y Haber)
                    if ($debe && $haber) {
                        $error = "No se puede ingresar un valor en Debe y Haber en la misma fila.";
                        throw new Exception($error);
                    }

                    // Determinar si es Debe o Haber
                    if ($debe) {
                        $debeHaber = 'D';
                        $valor = $debe;
                    } elseif ($haber) {
                        $debeHaber = 'H';
                        $valor = $haber;
                    } else {
                        $error = "Debe ingresar un valor en Debe o Haber.";
                        throw new Exception($error);
                    }

                    // Insertar el detalle de la póliza
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
                $error = "Ocurrió un error al agregar la póliza.<br> Verifique que el número de póliza o el detalle de poliza no esté registrado.";
            }
        }
        // Obtener los nombres de las cuentas para el formulario
        $query = "SELECT NumCuenta, NombreCuenta FROM Cuentas";
        $stmt = $this->db->query($query);
        $cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = './src/views/polizas/add.php';
        include './src/views/layout.php';
    }


    // Método para editar una póliza y sus detalles
    public function editPoliza()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $numPoliza = filter_input(INPUT_POST, 'NumPoliza', FILTER_VALIDATE_INT);
                $fecha = filter_input(INPUT_POST, 'Fecha', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $descripcion = filter_input(INPUT_POST, 'Descripcion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $numCuenta = filter_input(INPUT_POST, 'NumCuenta', FILTER_VALIDATE_INT);
                $debeHaber = filter_input(INPUT_POST, 'DebeHaber', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $valor = filter_input(INPUT_POST, 'Valor', FILTER_VALIDATE_FLOAT);

                if (!$numPoliza || !$fecha || !$descripcion || !$numCuenta || !$debeHaber || !$valor) {
                    throw new Exception("Datos de entrada no válidos.");
                }

                $this->db->beginTransaction();

                // Query para editar la póliza
                $query = "UPDATE Polizas SET Fecha = :fecha, Descripcion = :descripcion WHERE NumPoliza = :numPoliza";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':numPoliza', $numPoliza, PDO::PARAM_INT);
                $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
                $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                $stmt->execute();

                // Query para editar el detalle de la póliza
                $query = "UPDATE DetallePoliza SET NumCuenta = :numCuenta, DebeHaber = :debeHaber, Valor = :valor WHERE NumPoliza = :numPoliza";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':numPoliza', $numPoliza, PDO::PARAM_INT);
                $stmt->bindParam(':numCuenta', $numCuenta, PDO::PARAM_INT);
                $stmt->bindParam(':debeHaber', $debeHaber, PDO::PARAM_STR);
                $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
                $stmt->execute();

                $this->db->commit();

                header("Location: /Sitio_web_contabilidad/polizas");
                exit();
            } catch (Exception $e) {
                $this->db->rollBack();
                error_log("Error al editar póliza: " . $e->getMessage());
                $error = "Ocurrió un error al editar la póliza.";
            }
        } else {
            $numPoliza = filter_input(INPUT_GET, 'numPoliza', FILTER_VALIDATE_INT);
            if (!$numPoliza) {
                throw new Exception("Número de poliza no válido.");
            }
            $cuenta = $this->getPoliza($numPoliza);
            $content = './src/views/polizas/edit.php';
            include './src/views/layout.php';
        }
    }

    // Método para eliminar una póliza y sus detalles
    public function deletePoliza()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $numPoliza = filter_input(INPUT_POST, 'NumPoliza', FILTER_VALIDATE_INT);
                $numCuenta = filter_input(INPUT_POST, 'NumCuenta', FILTER_VALIDATE_INT);

                if (!$numPoliza || !$numCuenta) {
                    throw new Exception("Datos de entrada no válidos.");
                }

                $this->db->beginTransaction();

                $query = "DELETE FROM Polizas WHERE NumPoliza = :numPoliza";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':numPoliza', $numPoliza, PDO::PARAM_INT);
                $stmt->execute();

                $query = "DELETE FROM DetallePoliza WHERE NumPoliza = :numPoliza AND NumCuenta = :numCuenta";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':numPoliza', $numPoliza, PDO::PARAM_INT);
                $stmt->bindParam(':numCuenta', $numCuenta, PDO::PARAM_INT);
                $stmt->execute();

                $this->db->commit();

                header("Location: /Sitio_web_contabilidad/polizas");
                exit();
            } catch (Exception $e) {
                $this->db->rollBack();
                error_log("Error al eliminar póliza: " . $e->getMessage());
                $error = "Ocurrió un error al eliminar la póliza.";
            }
        } else {
            $numPoliza = filter_input(INPUT_GET, 'numPoliza', FILTER_VALIDATE_INT);
            if (!$numPoliza) {
                throw new Exception("Número de poliza no válido.");
            }
            $cuenta = $this->getPoliza($numPoliza);
            $content = './src/views/polizas/delete.php';
            include './src/views/layout.php';
        }
    }

    // Método para listar todas las pólizas
    public function listPolizas()
    {
        try {
            $query = "SELECT * FROM Polizas, DetallePoliza WHERE Polizas.NumPoliza = DetallePoliza.NumPoliza";
            $stmt = $this->db->query($query);
            $polizas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $content = './src/views/polizas/list.php';
            include './src/views/layout.php';
        } catch (Exception $e) {
            error_log("Error al listar pólizas: " . $e->getMessage());
            throw new Exception("Ocurrió un error al listar las pólizas.");
        }

    }

    // Método para obtener una póliza y sus detalles
    public function getPoliza($numPoliza)
    {
        try {
            $query = "SELECT * FROM Polizas, DetallePoliza WHERE Polizas.NumPoliza = DetallePoliza.NumPoliza AND Polizas.NumPoliza = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $numPoliza);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("" . $e->getMessage());
            throw new Exception("Ocurrió un error al obtener la póliza.");
        }
    }
}
?>