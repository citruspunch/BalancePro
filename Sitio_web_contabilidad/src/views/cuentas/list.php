<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Cuentas</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Listado de Cuentas</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Número de Cuenta</th>
                    <th>Nombre de Cuenta</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cuentas)): ?>
                    <?php
                    $tipoDeCuentas = [
                        'A' => 'Activo',
                        'P' => 'Pasivo',
                        'C' => 'Capital',
                        'I' => 'Ingreso',
                        'G' => 'Gasto'
                    ];
                    ?>
                    <?php foreach ($cuentas as $cuenta): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cuenta['NumCuenta']); ?></td>
                            <td><?php echo htmlspecialchars($cuenta['NombreCuenta']); ?></td>
                            <td><?php echo htmlspecialchars($tipoDeCuentas[$cuenta['Tipo']]); ?></td>
                            <td>
                                <a href="?action=edit&NumCuenta=<?php echo htmlspecialchars($cuenta['NumCuenta']); ?>" class="btn btn-warning">Editar</a>
                                <a href="?action=delete&NumCuenta=<?php echo htmlspecialchars($cuenta['NumCuenta']); ?>" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">No hay cuentas registradas.</td></tr>
                <?php endif; ?>
                <tr>
                    <td colspan="4" class="text-center">
                        <a href="?action=add" class="btn btn-primary add-button">Agregar Nueva Cuenta</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="/Sitio_web_contabilidad/public/js/scripts.js"></script>
</body>
</html>