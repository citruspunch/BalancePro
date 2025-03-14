<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Cuenta</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Agregar Nueva Cuenta</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="/Sitio_web_contabilidad/cuentas/add" method="post">
            <div class="form-group">
                <label for="numCuenta">Número de la Cuenta:</label>
                <input type="text" name="NumCuenta" id="numCuenta" required>
            </div>
            <div class="form-group">
                <label for="nombreCuenta">Nombre de la Cuenta:</label>
                <input type="text" name="NombreCuenta" id="nombreCuenta" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <select name="Tipo" id="tipo" required>
                    <option value="">Seleccione</option>
                    <option value="A">Activo</option>
                    <option value="P">Pasivo</option>
                    <option value="C">Capital</option>
                    <option value="I">Ingreso</option>
                    <option value="G">Gasto</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary add-button">Agregar Cuenta</button>
            <a href="/Sitio_web_contabilidad/cuentas" class="btn btn-danger">Cancelar</a>
        </form>
    </div>
</body>
</html>