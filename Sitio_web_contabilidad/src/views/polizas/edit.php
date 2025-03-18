<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Poliza</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Editar Poliza</h2>
            <form action="/Sitio_web_contabilidad/polizas?action=editPoliza" method="POST">
                <input type="hidden" name="NumPoliza" value="<?php echo htmlspecialchars($poliza->NumPoliza); ?>">
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" name="fecha" value="<?php echo htmlspecialchars($poliza->Fecha); ?>" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <input type="text" name="descripcion" value="<?php echo htmlspecialchars($poliza->Descripcion); ?>" required>
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
                        <?php foreach ($detalles as $detalle): ?>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <select name="numCuenta[]" required>
                                            <option value="">Seleccione una Cuenta</option>
                                            <?php foreach ($cuentas as $cuenta): ?>
                                                <option value="<?php echo htmlspecialchars($cuenta['NumCuenta']); ?>"
                                                    <?php echo $cuenta['NumCuenta'] == $detalle['NumCuenta'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($cuenta['NombreCuenta']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <input type="number" name="debe[]" step="0.01" min="0" class="debe" value="<?php echo $detalle['DebeHaber'] === 'D' ? htmlspecialchars($detalle['Valor']) : ''; ?>">
                                </td>
                                <td>
                                    <input type="number" name="haber[]" step="0.01" min="0" class="haber" value="<?php echo $detalle['DebeHaber'] === 'H' ? htmlspecialchars($detalle['Valor']) : ''; ?>">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger remove-row">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary add-row">Agregar Cuenta</button>
                <button type="submit" class="btn btn-primary add-button">Actualizar Poliza</button>
            </form>
            <a href="/Sitio_web_contabilidad/polizas" class="btn btn-danger">Cancelar</a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('.add-row').addEventListener('click', function () {
                var tableBody = document.querySelector('#cuentasTable tbody');
                var newRow = tableBody.querySelector('tr').cloneNode(true);
                newRow.querySelector('select').selectedIndex = 0;
                newRow.querySelector('input[name="debe[]"]').value = '';
                newRow.querySelector('input[name="haber[]"]').value = '';
                tableBody.appendChild(newRow);
                assignInputEvents(newRow);
            });

            document.addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-row')) {
                    var row = event.target.closest('tr');
                    if (row && document.querySelectorAll('#cuentasTable tbody tr').length > 1) {
                        row.remove();
                    }
                }
            });

            function assignInputEvents(row) {
                var debeInput = row.querySelector('input[name="debe[]"]');
                var haberInput = row.querySelector('input[name="haber[]"]');

                debeInput.addEventListener('input', function () {
                    if (this.value.trim() !== "") {
                        haberInput.value = "0";
                        haberInput.style.display = "none";
                    } else {
                        haberInput.style.display = "";
                    }
                });

                haberInput.addEventListener('input', function () {
                    if (this.value.trim() !== "") {
                        debeInput.value = "0";
                        debeInput.style.display = "none";
                    } else {
                        debeInput.style.display = "";
                    }
                });
            }

            document.querySelectorAll('#cuentasTable tbody tr').forEach(function (row) {
                assignInputEvents(row);
            });
        });
    </script>
</body>
</html>