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
        </div>
</body>
</html>