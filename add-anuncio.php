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

// GRAVAR ANUNCIO NO BANCO
require 'classes/anuncios.class.php';
$a = new Anuncios();

if(isset($_POST['titulo']) && !empty($_POST['titulo'])) {
    $id_usuario = $_SESSION['cLogin'];
    $titulo = addslashes($_POST['titulo']);
    $categoria = addslashes($_POST['categoria']);
    $valor = addslashes($_POST['valor']);
    $descricao = addslashes($_POST['descricao']);
    $estado = addslashes($_POST['estado']);

    $a->addAnuncio($id_usuario, $titulo, $categoria, $valor, $descricao, $estado);
    ?>
    <div class="alert alert-success">
        Produto adicionado com sucesso! Retornar aos <a href="meus-anuncios.php"><strong>meus anúncios</strong></a>
    </div>
<?php
}
?>

<div class="container">
    <h1>Adicionar Anúncio</h1>
    <form method="POST" enctype="multipart/form-data">
<!--        CATEGORIA-->
        <div class="form-group">
            <label for="categoria">Categoria</label>
            <select name="categoria" id="categoria" class="form-control">
            <?php
                require_once 'classes/categorias.class.php';
                $c = new Categorias();
                $categorias = $c->getLista();
                foreach($categorias as $cat):
            ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo utf8_encode($cat['nome']); ?></option>
            <?php endforeach; ?>
            </select>
        </div>
<!--        TITULO-->
        <div class="form-group">
            <label for="titulo_anuncio">Titulo</label>
            <input type="text" name="titulo" id="titulo_anuncio" class="form-control"/>
        </div>
<!--        VALOR-->
        <div class="form-group">
            <label for="valor">Valor</label>
            <input type="text" name="valor" id="valor" class="form-control"/>
        </div>
<!--        DESCRIÇÃO-->
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea class="form-control" name="descricao" id="descricao"></textarea>
        </div>
<!--        ESTADO CONSERVAÇÃO-->
        <div class="form-group">
            <label for="estado">Estado de Conservação</label>
            <select name="estado" id="estado" class="form-control"/>
                <option value="0">Ruim</option>
                <option value="1">Bom</option>
                <option value="2">Ótimo</option>
            </select>
        </div>
        <input type="submit" value="Adicionar" class="btn btn-primary"/>
    </form>
</div>



<?php
require_once 'pages/footer.php';
?>
