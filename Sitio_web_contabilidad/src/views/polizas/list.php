<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pólizas</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css">
</head>

<body>
    <div class="container">
        <h1>Lista de Pólizas</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Número de Póliza</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($polizas)): ?>
                    <tr>
                        <td colspan="4">No hay pólizas registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($polizas as $poliza): ?>
                        <tr>
                            <td><?php echo $poliza['NumPoliza']; ?></td>
                            <td><?php echo $poliza['Fecha']; ?></td>
                            <td><?php echo $poliza['Descripcion']; ?></td>
                            <td>
                                <a href="?action=viewPoliza&numPoliza=<?php echo htmlspecialchars($poliza['NumPoliza']); ?>" class="btn btn-info">Ver</a>
                                <a href="?action=editPoliza&numPoliza=<?php echo htmlspecialchars($poliza['NumPoliza']); ?>" class="btn btn-warning">Editar</a>
                                <a href="?action=deletePoliza&numPoliza=<?php echo htmlspecialchars($poliza['NumPoliza']); ?>" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                <tr>
                    <td colspan="4" class="text-center">
                        <a href="?action=addPoliza" class="btn btn-primary add-button">Agregar Nueva Poliza</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>