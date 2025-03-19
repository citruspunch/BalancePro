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
            <?php if (!empty($error)): ?>
                <div id="errorModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <p><?php echo $error; ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <h2>Confirmar eliminación</h2>
            <p>¿Está seguro de que desea eliminar la cuenta: <?php echo htmlspecialchars($cuenta['NombreCuenta']); ?>?
            </p>
            <form method="POST" action="?action=delete">
                <input type="hidden" name="NumCuenta" value="<?php echo htmlspecialchars($cuenta['NumCuenta']); ?>">
                <button type="submit" class="btn btn-danger">Eliminar</button>
                <a href="/Sitio_web_contabilidad/cuentas" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
    <script src="/Sitio_web_contabilidad/public/js/modal.js"></script>
</body>
</html>