<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <h2>Reporte Diario</h2>
            <form method="GET" action="/Sitio_web_contabilidad/reportes/diario">
                <div class="form-group">
                    <label for="date">Fecha:</label>
                    <input type="date" name="date" id="date"
                        value="<?php echo htmlspecialchars($_GET['date'] ?? date('Y-m-d')); ?>" required>
                </div>
                <div class="form-group">
                    <label for="numPoliza">Número de Póliza (opcional):</label>
                    <input type="number" name="numPoliza" id="numPoliza"
                        value="<?php echo htmlspecialchars($_GET['numPoliza'] ?? ''); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>

            <?php if (!empty($polizas)): ?>
                <br> <br>
                <?php foreach ($polizas as $poliza): ?>
                    <p><strong>Póliza Número: <?php echo htmlspecialchars($poliza['NumPoliza']); ?></strong></p>
                    <p><strong>Fecha:</strong> <?php echo htmlspecialchars($poliza['Fecha']); ?></p>
                    <p><strong>Descripción:</strong> <?php echo htmlspecialchars($poliza['Descripcion']); ?></p>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre de Cuenta</th>
                                <th>Debe</th>
                                <th>Haber</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalDebe = 0;
                            $totalHaber = 0;
                            foreach ($detalles as $detalle):
                                if ($detalle['NumPoliza'] === $poliza['NumPoliza']):
                                    $totalDebe += $detalle['DebeHaber'] === 'D' ? $detalle['Valor'] : 0;
                                    $totalHaber += $detalle['DebeHaber'] === 'H' ? $detalle['Valor'] : 0;
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($detalle['NombreCuenta']); ?></td>
                                        <td class="valor"><?php echo $detalle['DebeHaber'] === 'D' ? htmlspecialchars(number_format($detalle['Valor'], 2)) : ''; ?></td>
                                        <td class="valor"><?php echo $detalle['DebeHaber'] === 'H' ? htmlspecialchars(number_format($detalle['Valor'],2)) : ''; ?></td>
                                    </tr>
                                <?php
                                endif;
                            endforeach;
                            ?>
                            <tr>
                                <td><strong>Totales</strong></td>
                                <td><strong><?php echo number_format($totalDebe, 2); ?></strong></td>
                                <td><strong><?php echo number_format($totalHaber, 2); ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="alert alert-warning">No se encontraron pólizas para la fecha especificada.</p>
            <?php endif; ?>

            <a href="/Sitio_web_contabilidad" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</body>

</html>