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
            <?php if (!empty($error)): ?>
                <div id="errorModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <p><?php echo $error; ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <form action="/Sitio_web_contabilidad/polizas?action=editPoliza" method="post">
                <input type="hidden" name="numPoliza" value="<?php echo htmlspecialchars($poliza->NumPoliza); ?>">
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" name="fecha" id="fecha" required class="form-control" value="<?php echo htmlspecialchars($poliza->Fecha); ?>">
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <input type="text" name="descripcion" id="descripcion" required class="form-control" value="<?php echo htmlspecialchars($poliza->Descripcion); ?>">
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
                        <?php if (!empty($detalles)): ?>
                            <?php foreach ($detalles as $detalle): ?>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <select name="numCuenta[]" required class="form-control">
                                                <option value="">Seleccione una Cuenta</option>
                                                <?php foreach ($cuentas as $cuenta): ?>
                                                    <option value="<?php echo htmlspecialchars($cuenta['NumCuenta']); ?>"
                                                        <?php echo ($cuenta['NumCuenta'] == $detalle['NumCuenta']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($cuenta['NombreCuenta']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="debe[]" step="0.01" min="0" class="form-control debe" placeholder="Q"
                                            value="<?php echo ($detalle['DebeHaber'] == 'D') ? htmlspecialchars($detalle['Valor']) : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="haber[]" step="0.01" min="0" class="form-control haber" placeholder="Q"
                                            value="<?php echo ($detalle['DebeHaber'] == 'H') ? htmlspecialchars($detalle['Valor']) : ''; ?>">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger remove-row">Eliminar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Si no hay detalles, mostrar dos filas vacías -->
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
                                    <input type="number" name="debe[]" step="0.01" min="0" class="form-control debe" placeholder="Q">
                                </td>
                                <td>
                                    <input type="number" name="haber[]" step="0.01" min="0" class="form-control haber" placeholder="Q">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger remove-row">Eliminar</button>
                                </td>
                            </tr>
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
                                    <input type="number" name="debe[]" step="0.01" min="0" class="form-control debe" placeholder="Q">
                                </td>
                                <td>
                                    <input type="number" name="haber[]" step="0.01" min="0" class="form-control haber" placeholder="Q">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger remove-row">Eliminar</button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <button type="button" id="addRow" class="btn btn-success">Agregar Cuenta</button>
                <button type="submit" class="btn btn-primary add-button">Guardar Cambios</button>
                <a href="/Sitio_web_contabilidad/polizas" class="btn btn-danger">Cancelar</a>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Función para agregar una nueva fila
            document.getElementById('addRow').addEventListener('click', function () {
                if (document.querySelectorAll('#cuentasTable tbody tr').length >= <?php echo count($cuentas); ?>) {
                    return;
                }
                var tableBody = document.querySelector('#cuentasTable tbody');
                var newRow = tableBody.querySelector('tr').cloneNode(true);

                newRow.querySelector('select').selectedIndex = 0;
                newRow.querySelector('.debe').value = '';
                newRow.querySelector('.haber').value = '';

                newRow.querySelector('.debe').style.display = "";
                newRow.querySelector('.haber').style.display = "";

                tableBody.appendChild(newRow);

                assignInputEvents(newRow);
            });

            // Función para eliminar una fila
            document.addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-row')) {
                    var row = event.target.closest('tr');
                    if (row && document.querySelectorAll('#cuentasTable tbody tr').length > 2) {
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

            // Asignar eventos a todas las filas existentes
            document.querySelectorAll('#cuentasTable tbody tr').forEach(function (row) {
                assignInputEvents(row);
            });
        });
    </script>
    <script src="/Sitio_web_contabilidad/public/js/modal.js"></script>
</body>
</html>