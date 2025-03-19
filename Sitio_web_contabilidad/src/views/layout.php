<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contabilidad App</title>
    <link rel="stylesheet" href="/Sitio_web_contabilidad/public/css/styles.css">
    <style>
        nav {
            position: relative;
            width: 100%;
            height: 50px;
            margin: 0 auto;
            background: #263c53;
        }

        header,
        nav {
            display: block;
        }

        nav ul#menu {
            display: block;
            margin: 0;
            padding: 0;
            list-style: 0;
        }

        nav ul#menu li {
            position: relative;
            display: inline-block;
            margin-right: 10px;
            margin-left: 10px;
        }

        nav ul#menu li a {
            display: block;
            height: 50px;
            font-size: 1em;
            line-height: 50px;
            color: #4cb1f2;
            text-decoration: none;
            padding: 0 15px;
        }

        nav ul#menu li a:hover,
        nav ul#menu li:hover>a {
            background: #35424a;
        }

        nav ul#menu li:hover>#mega {
            display: block;
        }


        #mega {
            position: absolute;
            top: 100%;
            left: 0;
            width: 177px;
            height: 130px;
            padding: 20px 10px;
            background: #35424a;
            display: none;
        }

        ul#menu ul {
            float: left;
            width: 45%;
            /* margin: 0 15px 15px 0; */
            margin: 0;
            padding: 0;
            list-style: none;
        }

        ul#menu ul li a {
            font-size: 1em;
            color: #4cb1f2;
            text-decoration: none;
            padding: 0 0;
            line-height: 1.5em;
            white-space: nowrap;
        }

        ul#menu ul li a:hover {
            color: #fff;
            background: none;
            transition: color 0.3s ease;
        }

        nav ul:after {
            content: ".";
            display: block;
            clear: both;
            visibility: hidden;
            line-height: 0;
            height: 0;
        }

        nav ul {
            display: inline-block;
        }

        html[xmlns] nav ul {
            display: block;
        }

        * html nav ul {
            height: 1%;
        }

        #content {
            padding: 30px 0;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <header>
            <h1 class="mainTitle">Sistema de Contabilidad</h1>
            <nav>
                <ul id="menu">
                    <li><a href="/Sitio_web_contabilidad/cuentas">Cuentas</a></li>
                    <li><a href="/Sitio_web_contabilidad/polizas">Polizas</a></li>
                    <li>
                        <a href="#">Reportes</a>
                        <div id="mega">
                            <ul>
                                <li><a href="/Sitio_web_contabilidad/reportes/diario">Reporte Diario</a></li>
                                <li><a href="/Sitio_web_contabilidad/reportes/mayor">Reporte Mayor</a></li>
                                <li><a href="/Sitio_web_contabilidad/reportes/balance">Reporte de Balance</a></li>
                            </ul>
                        </div>
                    </li>
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
</body>

</html>