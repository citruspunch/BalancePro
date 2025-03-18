<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Póliza</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Detalles de la Póliza</h2>
            <p><strong>Número de Póliza:</strong> <?php echo htmlspecialchars($poliza->NumPoliza); ?></p>
            <p><strong>Fecha:</strong> <?php echo htmlspecialchars($poliza->Fecha); ?></p>
            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($poliza->Descripcion); ?></p>
            <h3>Detalles</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre de Cuenta</th>
                        <th>Debe</th>
                        <th>Haber</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles as $detalle): ?>
                        <tr>
                            <td><?php echo ($detalle['NombreCuenta']); ?></td>
                            <td><?php echo $detalle['DebeHaber'] === 'D'? htmlspecialchars($detalle['Valor']) : ""; ?></td>
                            <td><?php echo $detalle['DebeHaber'] === 'H'? htmlspecialchars($detalle['Valor']) : ""; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td><strong>Total</strong></td>
                        <td><strong><?php echo $totalDebe; ?></strong></td>
                        <td><strong><?php echo $totalHaber; ?></strong></td>
                    </tr>
                </tbody>
            </table>
            <a href="/Sitio_web_contabilidad/polizas" class="btn btn-primary">Volver</a>
        </div>
    </div>
</body>
</html>