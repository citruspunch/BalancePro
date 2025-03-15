<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cuenta</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Editar Cuenta</h2>
            <form action="" method="POST">
                <input type="hidden" name="NumCuenta" value="<?php echo htmlspecialchars($numCuenta); ?>">
                <div class="form-group">
                    <label for="nombreCuenta">Nombre de Cuenta:</label>
                    <input type="text" name="NombreCuenta" id="nombreCuenta" value="<?php echo htmlspecialchars($nombreCuenta); ?>" required>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo:</label>
                    <select name="Tipo" id="tipo" required>
                        <option value="A" <?php echo ($tipo == 'A') ? 'selected' : ''; ?>>Activo</option>
                        <option value="P" <?php echo ($tipo == 'P') ? 'selected' : ''; ?>>Pasivo</option>
                        <option value="C" <?php echo ($tipo == 'C') ? 'selected' : ''; ?>>Capital</option>
                        <option value="I" <?php echo ($tipo == 'I') ? 'selected' : ''; ?>>Ingreso</option>
                        <option value="G" <?php echo ($tipo == 'G') ? 'selected' : ''; ?>>Gasto</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary add-button">Actualizar Cuenta</button>
            </form>
            <a href="/Sitio_web_contabilidad/cuentas" class="btn btn-secondary">Volver a la lista</a>
        </div>
    </div>
</body>
</html>