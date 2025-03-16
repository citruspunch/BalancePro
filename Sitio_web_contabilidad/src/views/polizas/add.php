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
                <table class="table table-bordered" id="cuentasTable">
                    <thead>
                        <tr>
                            <th>Nombre de la Cuenta</th>
                            <th>Debe</th>
                            <th>Haber</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <select name="numCuenta[]" required class="form-control">
                                        <option value="">Seleccione una Cuenta</option>
                                        <?php foreach ($cuentas as $cuenta): ?>
                                            <option value="<?php echo htmlspecialchars($cuenta['NumCuenta']); ?>">
                                                <?php echo htmlspecialchars($cuenta['NombreCuenta']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <input type="number" name="debe[]" step="0.01" min="0" class="form-control debe">
                            </td>
                            <td>
                                <input type="number" name="haber[]" step="0.01" min="0" class="form-control haber">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger remove-row">Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" id="addRow" class="btn btn-success">Agregar Fila</button>
                <button type="submit" class="btn btn-primary add-button">Agregar Poliza</button>
                <a href="/Sitio_web_contabilidad/polizas" class="btn btn-danger">Cancelar</a>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Función para agregar una nueva fila
            document.getElementById('addRow').addEventListener('click', function () {
                var tableBody = document.querySelector('#cuentasTable tbody');
                var newRow = tableBody.querySelector('tr').cloneNode(true);

                newRow.querySelector('select').selectedIndex = 0;
                newRow.querySelector('.debe').value = '';
                newRow.querySelector('.haber').value = '';

                tableBody.appendChild(newRow);

                assignInputEvents(newRow);
            });

            // Función para eliminar una fila
            document.addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-row')) {
                    var row = event.target.closest('tr');
                    if (row && document.querySelectorAll('#cuentasTable tbody tr').length > 1) {
                        row.remove(); 
                    }
                }
            });

            // Función para asignar eventos a los campos "Debe" y "Haber"
            function assignInputEvents(row) {
                var debeInput = row.querySelector('.debe');
                var haberInput = row.querySelector('.haber');

                debeInput.addEventListener('input', function () {
                    if (this.value.trim() !== "") {
                        haberInput.disabled = true;
                    } else {
                        haberInput.disabled = false;
                    }
                });

                haberInput.addEventListener('input', function () {
                    if (this.value.trim() !== "") {
                        debeInput.disabled = true;
                    } else {
                        debeInput.disabled = false;
                    }
                });
            }

            assignInputEvents(document.querySelector('#cuentasTable tbody tr'));
        });
    </script>
</body>
</html>