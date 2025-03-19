<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Mayor</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>Reporte Mayor</h1>
            <?php if (!empty($reportData)): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Póliza</th>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Debe</th>
                            <th>Haber</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalDebe = 0;
                        $totalHaber = 0;
                        foreach ($reportData as $row):
                            if ($row['debeHaber'] === 'D') {
                                $totalDebe += $row['valor'];
                            } elseif ($row['debeHaber'] === 'H') {
                                $totalHaber += $row['valor'];
                            }
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['poliza']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                                <td class="valor">
                                    <?php echo $row['debeHaber'] === 'D' ? number_format($row['valor'], 2) : ''; ?>
                                </td>
                                <td class="valor">
                                    <?php echo $row['debeHaber'] === 'H' ? number_format($row['valor'], 2) : ''; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3"><strong>Totales</strong></td>
                            <td class="valor"><strong><?php echo number_format($totalDebe, 2); ?></strong></td>
                            <td class="valor"><strong><?php echo number_format($totalHaber, 2); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="alert alert-warning">No hay registros para mostrar.</p>
            <?php endif; ?>
            <a href="/Sitio_web_contabilidad/reportes/mayor" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</body>

</html>