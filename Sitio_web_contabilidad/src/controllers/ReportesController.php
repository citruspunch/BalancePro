<?php

class ReportesController
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    public function reporteDiario()
    {
        try {
            $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? date('Y-m-d');
            $numPoliza = filter_input(INPUT_GET, 'numPoliza', FILTER_VALIDATE_INT);

            if (!$date) {
                throw new Exception("Debe especificar una fecha válida.");
            }

            $queryPolizas = "SELECT * FROM Polizas WHERE Fecha = :date";
            if ($numPoliza) {
                $queryPolizas .= " AND NumPoliza = :numPoliza";
            }
            $stmt = $this->db->prepare($queryPolizas);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            if ($numPoliza) {
                $stmt->bindParam(':numPoliza', $numPoliza, PDO::PARAM_INT);
            }
            $stmt->execute();
            $polizas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $queryDetalles = "SELECT D.*, C.NombreCuenta 
                              FROM DetallePoliza D
                              JOIN Cuentas C ON D.NumCuenta = C.NumCuenta
                              WHERE D.NumPoliza IN (SELECT NumPoliza FROM Polizas WHERE Fecha = :date)";
            if ($numPoliza) {
                $queryDetalles .= " AND D.NumPoliza = :numPoliza";
            }
            $stmt = $this->db->prepare($queryDetalles);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            if ($numPoliza) {
                $stmt->bindParam(':numPoliza', $numPoliza, PDO::PARAM_INT);
            }
            $stmt->execute();
            $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $totalDebe = 0;
            $totalHaber = 0;
            foreach ($polizas as $poliza) {
                $numPolizaActual = $poliza['NumPoliza'];
                $totalDebe += $this->calcularTotal('D', $date, $numPolizaActual);
                $totalHaber += $this->calcularTotal('H', $date, $numPolizaActual);
            }

            $content = './src/views/reportes/diario.php';
            include './src/views/layout.php';
        } catch (Exception $e) {
            error_log("Error al generar el reporte por póliza: " . $e->getMessage());
            echo "" . $e->getMessage();
            echo "Ocurrió un error al generar el reporte por póliza.";
        }
    }

    private function calcularTotal($debeHaber, $date, $numPoliza = null)
    {
        $query = "SELECT SUM(Valor) AS Total 
                FROM DetallePoliza 
                WHERE DebeHaber = :debeHaber 
                AND NumPoliza IN (SELECT NumPoliza FROM Polizas WHERE Fecha = :date)";
        if ($numPoliza) {
            $query .= " AND NumPoliza = :numPoliza";
        }
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':debeHaber', $debeHaber, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        if ($numPoliza) {
            $stmt->bindParam(':numPoliza', $numPoliza, PDO::PARAM_INT);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ? $result->Total : 0;
    }

    public function reporteMayor()
    {
        $cuenta = filter_input(INPUT_GET, 'cuenta', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$cuenta) {
            $query = "SELECT NumCuenta, NombreCuenta FROM Cuentas";
            $stmt = $this->db->query($query);
            $cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $content = './src/views/reportes/seleccion/SeleccionarCuenta.php';
            include './src/views/layout.php';
            return;
        }

        $query = "SELECT P.NumPoliza AS poliza, P.Fecha AS fecha, P.Descripcion AS descripcion,
                        D.DebeHaber AS debeHaber, D.Valor AS valor
                    FROM DetallePoliza D
                    JOIN Polizas P ON D.NumPoliza = P.NumPoliza
                    WHERE D.NumCuenta = :cuenta";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cuenta', $cuenta, PDO::PARAM_STR);
        $stmt->execute();
        $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = './src/views/reportes/mayor.php';
        include './src/views/layout.php';
    }

    public function reporteBalance()
    {
        $query = "SELECT Cuentas.NombreCuenta ,Cuentas.Tipo, DetallePoliza.NumCuenta, sum(if (debehaber='D',valor,0)) as Debe,
                    sum(if (debehaber='H',valor,0)) as Haber
                    FROM DetallePoliza, Cuentas
                    WHERE DetallePoliza.NumCuenta = Cuentas.NumCuenta
                    GROUP BY NumCuenta";
        $stmt = $this->db->query($query);
        $balanceData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = './src/views/reportes/balance.php';
        include './src/views/layout.php';
    }
}