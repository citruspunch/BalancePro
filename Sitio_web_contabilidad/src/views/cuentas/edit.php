<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cuenta</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css">
</head>
<body>
    <h1>Editar Cuenta</h1>
    <form action="" method="POST">
        <input type="hidden" name="NumCuenta" value="<?php echo htmlspecialchars($numCuenta); ?>">
        <label for="nombreCuenta">Nombre de Cuenta:</label>
        <input type="text" name="NombreCuenta" id="nombreCuenta" value="<?php echo htmlspecialchars($nombreCuenta); ?>" required>

        <label for="tipo">Tipo:</label>
        <select name="Tipo" id="tipo" required>
            <option value="A" <?php echo ($tipo == 'A') ? 'selected' : ''; ?>>Activo</option>
            <option value="P" <?php echo ($tipo == 'P') ? 'selected' : ''; ?>>Pasivo</option>
            <option value="C" <?php echo ($tipo == 'C') ? 'selected' : ''; ?>>Capital</option>
            <option value="I" <?php echo ($tipo == 'I') ? 'selected' : ''; ?>>Ingreso</option>
            <option value="G" <?php echo ($tipo == 'G') ? 'selected' : ''; ?>>Gasto</option>
        </select>

        <button type="submit">Actualizar Cuenta</button>
    </form>
    <a href="list.php">Volver a la lista</a>
</body>
</html>