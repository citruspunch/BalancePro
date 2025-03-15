<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Cuenta</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Confirmar eliminación</h2>
            <p>¿Está seguro de que desea eliminar la cuenta: <?php echo htmlspecialchars($cuenta['NombreCuenta']); ?>?</p>
            <form method="POST" action="?action=delete">
                <input type="hidden" name="NumCuenta" value="<?php echo htmlspecialchars($cuenta['NumCuenta']); ?>">
                <button type="submit" class="btn btn-danger">Eliminar</button>
                <a href="/Sitio_web_contabilidad/cuentas" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</body>
</html>