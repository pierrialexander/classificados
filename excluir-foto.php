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
    $id_anuncio = $a->excluirFoto($_GET['id']);
}

// se tiver id do anuncio ele após a exclusão retorna para a página do anuncio
if(isset($id_anuncio)) {
    header("Location: meus-anuncios.php?id=" . $id_anuncio);
}
else {
    header("Location: meus-anuncios.php");
}