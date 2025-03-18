<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Eliminar Poliza</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <h2>Confirmar eliminación de Poliza</h2>
            <p>¿Está seguro de que desea eliminar la <strong> Poliza No. <?php echo $cuenta->NumPoliza; ?></strong>?
            </p>
            <form method="post" action="?action=deletePoliza">
                <input type="hidden" name="numPoliza" value="<?php echo htmlspecialchars($cuenta->NumPoliza); ?>">
                <button type="submit" class="btn btn-danger">Eliminar</button>
                <a href="/Sitio_web_contabilidad/polizas" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</body>

</html>