<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Balance</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>REPORTE BALANCE</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Cuenta</th>
                        <th>Debe</th>
                        <th>Haber</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalGeneralDebe = 0;
                    $totalGeneralHaber = 0;

                    $tipos = ['A' => 'ACTIVO', 'P' => 'PASIVO', 'C' => 'CAPITAL', 'I' => 'INGRESOS', 'G' => 'GASTOS'];
                    foreach ($tipos as $tipo => $nombreTipo):
                        $totalDebe = 0;
                        $totalHaber = 0;
                        ?>
                        <tr>
                            <td colspan="3"><strong><?php echo htmlspecialchars($nombreTipo); ?></strong></td>
                        </tr>
                        <?php foreach ($balanceData as $row): ?>
                            <?php if ($row['Tipo'] === $tipo): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['NombreCuenta']); ?></td>
                                    <td class="valor"><?php echo !empty($row['Debe']) && $row['Debe'] !== 0 ? number_format($row['Debe'], 2) : ''; ?></td>
                                    <td class="valor"><?php echo !empty($row['Haber']) && $row['Haber'] !== 0 ? number_format($row['Haber'], 2) : ''; ?></td>
                                </tr>
                                <?php
                                $totalDebe += $row['Debe'];
                                $totalHaber += $row['Haber'];
                                ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php
                        $totalGeneralDebe += $totalDebe;
                        $totalGeneralHaber += $totalHaber;
                        ?>
                    <?php endforeach; ?>
                    <tr>
                        <td><strong>Totales Generales</strong></td>
                        <td class="valor"><strong><?php echo number_format($totalGeneralDebe, 2); ?></strong></td>
                        <td class="valor"><strong><?php echo number_format($totalGeneralHaber, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>