<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contabilidad App</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css?v=1.0">
</head>
<body>
    <div class="wrapper">
        <header>
            <h1>Bienvenido a la Aplicación de Contabilidad</h1>
            <nav>
                <ul>
                    <li><a href="/Sitio_web_contabilidad/cuentas">Cuentas</a></li>
                    <li><a href="/Sitio_web_contabilidad/polizas">Polizas</a></li>
                    <li><a href="/Sitio_web_contabilidad/detallePoliza">Detalle de Póliza</a></li>
                    <li><a href="/Sitio_web_contabilidad/reportes/diario.php">Reporte Diario</a></li>
                    <li><a href="/Sitio_web_contabilidad/reportes/mayor.php">Reporte Mayor</a></li>
                    <li><a href="/Sitio_web_contabilidad/reportes/balance.php">Reporte de Balance</a></li>
                </ul>
            </nav>
        </header>
        <main>
            <?php
            if (isset($content)) {
                include $content;
            }
            ?>
        </main>
        <footer>
            <p>&copy; <?php echo date("Y"); ?> Empresa Contabilidad. Todos los derechos reservados.</p>
        </footer>
    </div>
    <script src="/js/scripts.js"></script>
</body>
</html>