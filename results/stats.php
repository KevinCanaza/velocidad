<?php
session_start();
error_reporting(0);

require 'telemetry_settings.php';
require_once 'telemetry_db.php';

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0, s-maxage=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Velocidad</title>

    <link rel="stylesheet" href="../css/frontend.css">
    <link rel="shortcut icon" href="../favicon.ico">
    <style type="text/css">
        html,
        body {
            margin: 0;
            padding: 0;
            border: none;
            width: 100%;
            min-height: 100%;
        }

        * {
            box-sizing: border-box;
        }

        html {
            background-color: #15202b;
            font-family: "Segoe UI", "Roboto", sans-serif;
        }

        body {
            background-color: #15202b;
            color: #fff;
            box-sizing: border-box;
            width: 100%;
            max-width: 70em;
            margin: 4em auto;
            box-shadow: 0 1em 6em #00000080;
            padding: 1em 1em 4em 1em;
            border-radius: 0.4em;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 300;
            margin-bottom: 0.1em;
        }

        h1 {
            text-align: center;
        }

        table {
            margin: 2em 0;
            width: 100%;
        }

        table,
        tr,
        th,
        td {
            border: 1px solid #AAAAAA;
        }

        th {
            width: 6em;
        }

        td {
            word-break: break-all;
        }

        div {
            margin: 1em 0;
        }
    </style>

</head>

<body>
    <h1>Medidor de Velocidad de Internet</h1>
    <?php
    if (!isset($stats_password) || $stats_password === 'PASSWORD') {
    ?>
        requiere password
        <?php
    } elseif ($_SESSION['logged'] === true) {
        if ($_GET['op'] === 'logout') {
            $_SESSION['logged'] = false;
        ?><script type="text/javascript">
                window.location = location.protocol + "//" + location.host + location.pathname;
            </script><?php
                    } else {
                        ?>
            <form action="stats.php" method="GET"><input type="hidden" name="op" value="logout" /><input type="submit" value="Salir" /></form>
            <form action="stats.php" method="GET">
                <h3>Resultados</h3>
                <input type="hidden" name="op" value="id" />
                <input type="text" name="id" id="id" placeholder="Test ID" value="" />
                <input type="submit" value="Buscar" />
                <input type="submit" onclick="document.getElementById('id').value=''" value="Mostrar los ultimos 100 registros" />
            </form>
            <?php
                        if ($_GET['op'] === 'id' && !empty($_GET['id'])) {
                            $speedtest = getSpeedtestUserById($_GET['id']);
                            $speedtests = [];
                            if (false === $speedtest) {
                                echo '<div>There was an error trying to fetch the speedtest result for ID "' . htmlspecialchars($_GET['id'], ENT_HTML5, 'UTF-8') . '".</div>';
                            } elseif (null === $speedtest) {
                                echo '<div>Could not find a speedtest result for ID "' . htmlspecialchars($_GET['id'], ENT_HTML5, 'UTF-8') . '".</div>';
                            } else {
                                $speedtests = [$speedtest];
                            }
                        } else {
                            $speedtests = getLatestSpeedtestUsers();
                            if (false === $speedtests) {
                                echo '<div>There was an error trying to fetch latest speedtest results.</div>';
                            } elseif (empty($speedtests)) {
                                echo '<div>Could not find any speedtest results in database.</div>';
                            }
                        }
                        foreach ($speedtests as $speedtest) {
            ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <td><?= htmlspecialchars($speedtest['id_formatted'], ENT_HTML5, 'UTF-8') ?></td>
                    </tr>
                    <tr>
                        <th>Fecha y Hora</th>
                        <td><?= htmlspecialchars($speedtest['timestamp'], ENT_HTML5, 'UTF-8') ?></td>
                    </tr>
                    <tr>
                        <th>IP e informacion ISP</th>
                        <td>
                            <?= htmlspecialchars($speedtest['ip'], ENT_HTML5, 'UTF-8') ?><br />
                            <?= htmlspecialchars($speedtest['ispinfo'], ENT_HTML5, 'UTF-8') ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Navegador</th>
                        <td><?= htmlspecialchars($speedtest['ua'], ENT_HTML5, 'UTF-8') ?><br />
                            <?= htmlspecialchars($speedtest['lang'], ENT_HTML5, 'UTF-8') ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Velocidad de Bajada</th>
                        <td><?= htmlspecialchars($speedtest['dl'], ENT_HTML5, 'UTF-8') ?></td>
                    </tr>
                    <tr>
                        <th>Velocidad de Subida</th>
                        <td><?= htmlspecialchars($speedtest['ul'], ENT_HTML5, 'UTF-8') ?></td>
                    </tr>
                    <tr>
                        <th>Ping</th>
                        <td><?= htmlspecialchars($speedtest['ping'], ENT_HTML5, 'UTF-8') ?></td>
                    </tr>
                    <tr>
                        <th>Jitter</th>
                        <td><?= htmlspecialchars($speedtest['jitter'], ENT_HTML5, 'UTF-8') ?></td>
                    </tr>
                    <tr>
                        <th>Log</th>
                        <td><?= htmlspecialchars($speedtest['log'], ENT_HTML5, 'UTF-8') ?></td>
                    </tr>
                    <tr>
                        <th>Informacion Extra</th>
                        <td><?= htmlspecialchars($speedtest['extra'], ENT_HTML5, 'UTF-8') ?></td>
                    </tr>
                </table>
        <?php
                        }
                    }
                } elseif ($_GET['op'] === 'login' && $_POST['password'] === $stats_password) {
                    $_SESSION['logged'] = true;
        ?><script type="text/javascript">
            window.location = location.protocol + "//" + location.host + location.pathname;
        </script><?php
                } else {
                    ?>
        <!-- <form action="stats.php?op=login" method="POST">
            <h3>Usuario</h3>
            <input type="password" name="password" placeholder="Contraseña" value="" />
            <input type="submit" value="Entrar" />
        </form> -->
        <div class="container-form">
            <div class="box">
                <form action="stats.php?op=login" method="POST">
                    <h2 class="form-tittle">INGRESAR</h2>
                    <!-- <div class="inputBox">
                        <input type="text" required>
                        <span>Username</span>
                        <i></i>
                    </div> -->

                    <div class="inputBox">
                        <!-- <input type="password" required> -->
                        <input type="password" name="password" value="" required />
                        <span>Contraseña</span>
                        <i></i>
                    </div>

                    <!-- <input type="submit" class="Login"> -->
                    <input type="submit" value="Entrar" />

                    <div class="links">
                        <a href="#">Forgot Password?</a>
                        <a href="#">Sing up</a>

                    </div>
                </form>
            </div>
        </div>
    <?php
                }
    ?>
    <!-- <a id="startBtn" href="../index.html">PAGINA PRINCIPAL</a> -->

    <a class="btn-home" href="../index.html">
        <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
            <path d="M357.7 8.5c-12.3-11.3-31.2-11.3-43.4 0l-208 192c-9.4 8.6-12.7 22-8.5 34c87.1 25.3 155.6 94.2 180.3 181.6H464c26.5 0 48-21.5 48-48V256h32c13.2 0 25-8.1 29.8-20.3s1.6-26.2-8.1-35.2l-208-192zM288 208c0-8.8 7.2-16 16-16h64c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H304c-8.8 0-16-7.2-16-16V208zM24 256c-13.3 0-24 10.7-24 24s10.7 24 24 24c101.6 0 184 82.4 184 184c0 13.3 10.7 24 24 24s24-10.7 24-24c0-128.1-103.9-232-232-232zm8 256a32 32 0 1 0 0-64 32 32 0 1 0 0 64zM0 376c0 13.3 10.7 24 24 24c48.6 0 88 39.4 88 88c0 13.3 10.7 24 24 24s24-10.7 24-24c0-75.1-60.9-136-136-136c-13.3 0-24 10.7-24 24z" />
        </svg>
        <span>PAGINA PRINCIPAL</span>
    </a>
</body>

</html>