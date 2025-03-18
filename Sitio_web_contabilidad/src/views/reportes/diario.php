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
                <h3>Pólizas</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Número de Póliza</th>
                            <th>Fecha</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($polizas as $poliza): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($poliza['NumPoliza']); ?></td>
                                <td><?php echo htmlspecialchars($poliza['Fecha']); ?></td>
                                <td><?php echo htmlspecialchars($poliza['Descripcion']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No se encontraron polizas para la fecha especificada.</p>
            <?php endif; ?>

            <?php if (!empty($detalles)): ?>
                <h3>Detalles de las Pólizas</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Número de Póliza</th>
                            <th>Nombre de Cuenta</th>
                            <th>Debe</th>
                            <th>Haber</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalles as $detalle): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($detalle['NumPoliza']); ?></td>
                                <td><?php echo htmlspecialchars($detalle['NombreCuenta']); ?></td>
                                <td><?php echo $detalle['DebeHaber'] === 'D' ? htmlspecialchars($detalle['Valor']) : ''; ?></td>
                                <td><?php echo $detalle['DebeHaber'] === 'H' ? htmlspecialchars($detalle['Valor']) : ''; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <a href="/Sitio_web_contabilidad" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</body>

</html>