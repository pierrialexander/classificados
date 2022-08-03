<?php
require_once 'pages/header.php';
// VERIFICA SE A SESSÃO ESTÁ INICIADA
if(empty($_SESSION['cLogin'])) {
    ?>
    <script>
        window.location.href="login.php";
    </script>
    <?php
    exit;
}
?>

<div class="container">
    <h1>Meus anuncios</h1><br>
    <a href="add-anuncio.php" class="btn btn-default">Adicionar Anúncio</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Titulo</th>
                <th>Valor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <?php
            require_once 'classes/anuncios.class.php';
            $a = new Anuncios();
            $anuncios = $a->getMeusAnuncios();
            foreach((array)$anuncios as $anuncio):
            ?>
                <tr>
                    <td>
                    <?php if (!empty($anuncio['url'])): ?>
                        <img src="assets/images/anuncios/<?php echo $anuncio['url']; ?>" alt="" height="50"></td>
                    <?php else: ?>
                        <img src="assets/images/default.jpg" height="50"></td>
                    <?php endif; ?>
                    
                    <td><?php echo $anuncio['titulo']; ?></td>
                    <td>R$ <?php echo number_format($anuncio['valor'], 2); ?></td>
                    <td>
                        <a href="editar-anuncio.php?id=<?php echo $anuncio['id']; ?>" class="btn btn-primary">Editar</a>
                        <a href="excluir-anuncio.php?id=<?php echo $anuncio['id']; ?>" class="btn btn-danger">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
    </table>
</div>

<?php
require_once 'pages/footer.php';
?>
