<div class="container">
    <h2>Lista de Cuentas</h2>
    <a href="add.php" class="btn btn-primary">Agregar Nueva Cuenta</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Número de Cuenta</th>
                <th>Nombre de Cuenta</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($num > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    echo "<tr>
                            <td>{$NumCuenta}</td>
                            <td>{$NombreCuenta}</td>
                            <td>{$Tipo}</td>
                            <td>
                                <a href='edit.php?id={$NumCuenta}' class='btn btn-warning'>Editar</a>
                                <a href='delete.php?id={$NumCuenta}' class='btn btn-danger'>Eliminar</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay cuentas registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>