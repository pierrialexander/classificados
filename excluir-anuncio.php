<?php
require_once 'config.php';
// VERIFICA SE A SESSÃO ESTÁ INICIADA
if(empty($_SESSION['cLogin'])) {
    header("Location: login.php");
    exit;
}
require_once 'classes/anuncios.class.php';
$a = new Anuncios();

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $a->excluirAnuncio($_GET['id']);
}

header("Location: meus-anuncios.php");