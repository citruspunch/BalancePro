<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contabilidad App</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <header>
        <h1>Bienvenido a la Aplicación de Contabilidad</h1>
        <nav>
            <ul>
                <li><a href="/cuentas">Cuentas</a></li>
                <li><a href="/polizas">Pólizas</a></li>
                <li><a href="/detallePoliza">Detalle de Póliza</a></li>
                <li><a href="/reportes/diario.php">Reporte Diario</a></li>
                <li><a href="/reportes/mayor.php">Reporte Mayor</a></li>
                <li><a href="/reportes/balance.php">Reporte de Balance</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?php include $content; ?>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Empresa XXXX. Todos los derechos reservados.</p>
    </footer>
    <script src="/js/scripts.js"></script>
</body>
</html>