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
    $id = $_GET['id'];
    $titulo = addslashes($_POST['titulo']);
    $categoria = addslashes($_POST['categoria']);
    $valor = addslashes($_POST['valor']);
    $descricao = addslashes($_POST['descricao']);
    $estado = addslashes($_POST['estado']);
    if(isset($_FILES['fotos'])) {
        $fotos = $_FILES['fotos'];
    }
    else {
        $fotos = [];
    }

    $a->editAnuncio($id, $id_usuario, $titulo, $categoria, $valor, $descricao, $estado, $fotos);
    ?>
    <div class="alert alert-success">
        Produto editado com sucesso! Retornar aos <a href="meus-anuncios.php"><strong>meus anúncios</strong></a>
    </div>
    <?php
}
$info = $a->getAnuncio($_GET['id']);
?>

<div class="container">
    <h1>Editar Anúncio</h1>
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
                    <option value="<?php echo $cat['id']; ?>" <?php echo ($info['id_categoria'] ==  $cat['id']) ? 'selected="selected"' : ''; ?>"><?php echo utf8_encode($cat['nome']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <!--        TITULO-->
        <div class="form-group">
            <label for="titulo_anuncio">Titulo</label>
            <input type="text" name="titulo" id="titulo_anuncio" class="form-control" value="<?php echo $info['titulo']; ?>"/>
        </div>
        <!--        VALOR-->
        <div class="form-group">
            <label for="valor">Valor</label>
            <input type="text" name="valor" id="valor" class="form-control" value="<?php echo $info['valor']; ?>"/>
        </div>
        <!--        DESCRIÇÃO-->
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea class="form-control" name="descricao" id="descricao"><?php echo $info['descricao']; ?></textarea>
        </div>
        <!--        ESTADO CONSERVAÇÃO-->
        <div class="form-group">
            <label for="estado">Estado de Conservação</label>
            <select name="estado" id="estado" class="form-control"/>
            <option value="0" <?php echo ($info['estado'] == '0') ? 'selected="selected"' : ''; ?>>Ruim</option>
            <option value="1" <?php echo ($info['estado'] == '1') ? 'selected="selected"' : ''; ?>>Bom</option>
            <option value="2" <?php echo ($info['estado'] == '2') ? 'selected="selected"' : ''; ?>>Ótimo</option>
            </select>
        </div>

        <!-- ENVIO DAS FOTOS DO ANÚNCIO -->
        <div class="form-group">
            <label for="add_foto">Fotos do anúncio</label>
            <input type="file" name="fotos[]" multiple /><br>
            <div class="panel panel-default">
                <div class="panel-heading">Fotos do Anúncio</div>
                <div class="panel-body">
                    <?php foreach($info['fotos'] as $foto): ?>
                        <div class="foto_item">
                            <img src="assets/images/anuncios/<?php echo $foto['url']; ?>" class="img_thumbnail"/>
                            <br><br>
                            <a href="excluir-foto.php?id=<?php echo $foto['id']; ?>" class="btn btn-default">Excluir Imagem</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <input type="submit" value="Salvar" class="btn btn-primary"/>
        <br>
        <br>
    </form>
</div>



<?php
require_once 'pages/footer.php';
?>
