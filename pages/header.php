<?php
//session_start();
require_once('config.php');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/script.js"></script>
    <title>Classificados</title>
</head>
<body>
<nav class="navbar navbar-inverse barramenu">
    <div class=""container-fluid>
        <div class="navbar-header">
            <a href="./" class="navbar-brand" id="titulo">Classificados</a>
        </div>
        <ul class="nav navbar-nav navbar-right menuacesso">
            <?php if(isset($_SESSION['cLogin']) && !empty($_SESSION['cLogin'])): ?>
                <li><a id="linkmenu">Olá <?php echo $_SESSION['cUsuario']; ?></a></li>
                <li><a id="linkmenu" href="meus-anuncios.php">Meus Anúncios</a></li>
                <li><a id="linkmenu" href="sair.php">Sair</a></li>
            <?php else: ?>
                <li><a id="linkmenu" href="cadastre-se.php">Cadastre-se</a></li>
                <li><a id="linkmenu" href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>