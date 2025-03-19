<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Cuenta</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="card">
        <h1>Seleccionar Cuenta</h1>
            <form method="GET" action="/Sitio_web_contabilidad/reportes/mayor">
                <div class="form-group">
                    <label for="cuenta">Nombre de Cuenta:</label>
                    <div class="form-group">
                        <select name="cuenta" required class="form-control">
                            <option value="">Seleccione una Cuenta</option>
                            <?php foreach ($cuentas as $cuenta): ?>
                                <option value="<?php echo htmlspecialchars($cuenta['NumCuenta']); ?>">
                                    <?php echo htmlspecialchars($cuenta['NombreCuenta']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Generar Reporte</button>
            </form>
        </div>
        
    </div>
</body>
</html>