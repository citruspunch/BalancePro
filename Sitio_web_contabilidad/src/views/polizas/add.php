<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Poliza</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Agregar Nueva Poliza</h2>
            <form action="add.php" method="post">
                <div class="form-group">
                    <label for="numPoliza">Número de la Poliza:</label>
                    <input type="text" name="numPoliza" id="numPoliza" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" name="fecha" id="fecha" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <input type="text" name="descripcion" id="descripcion" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="numCuenta">Número de Cuenta:</label>
                    <input type="text" name="numCuenta" id="numCuenta" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="debeHaber">Debe o Haber:</label>
                    <select name="debeHaber" id="debeHaber" required class="form-control">
                        <option value="D">Debe</option>
                        <option value="H">Haber</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="valor">Valor:</label>
                    <input type="text" name="valor" id="valor" required class="form-control">
                </div>
                <button type="submit" class="btn btn-primary add-button">Agregar Poliza</button>
            <a href="list.php" class="btn btn-danger">Cancelar</a>
            </form>
        </div>
    </div>
</body>
</html>