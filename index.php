<?php
require_once('pages/header.php');
require_once('classes/anuncios.class.php');
require_once('classes/usuarios.class.php');
require_once('classes/categorias.class.php');

// INSTÂNCIA DE ANÚNCIOS
$a = new Anuncios();
$u = new Usuarios();
$c = new Categorias();

// RECEBEMOS OS FILTROS DE PESQUISA NO GET
$filtros = [
    'categoria' => '',
    'preco' => '',
    'estado' => ''
];
if(isset($_GET['filtros'])) {
    $filtros = $_GET['filtros'];
}

// BUSCA O TOTAL DE ANUNCIOS E USUÁRIOS
$total_anuncios = $a->getTotalAnuncios($filtros);
$total_usuarios = $u->getTotalUsuarios();


// CONTROLE DA PAGINAÇÃO
$p = 1;
if(isset($_GET['p']) && !empty($_GET['p'])) {
    $p = addslashes($_GET['p']);
}
$porPagina = 2;
$totalPaginas = ceil($total_anuncios / $porPagina);

// BUSCA OS ÚLTIMOS ANÚNCIOS
// envia como parametros o número da paginação atual e quantidade de itens 
// por página.
$anuncios = $a->getUltimosAnuncios($p, $porPagina, $filtros);
// BUSCA TODAS AS CATEGORIAS DO BANCO.
$categorias = $c->getLista();
?>
    <div class="container-fluid">
        <div class="jumbotron">
            <h2>Nós temos hoje <?php echo $total_anuncios; ?> anúncios.</h2>
            <p>E mais de <?php echo $total_usuarios; ?> usuários cadastrados</p>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <h4>Pesquisa Avançada</h4>
                <!-- FORMULÁRIO DE PESQUISA - FILTROS INDEX -->
                <form action="" method="GET">

                    <!-- CATEGORIAS -->
                    <label for="categoria">Categoria</label>
                    <div class="form-group">
                        <select name="filtros[categoria]" id="categoria" class="form-control">
                            <option></option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $filtros['categoria']) ? 'selected="selected"' : ''; ?>><?php echo utf8_encode($cat['nome']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- VALOR -->
                    <label for="preco">Valor</label>
                    <div class="form-group">
                        <select name="filtros[preco]" id="preco" class="form-control">
                            <option></option>
                            <option value="0-50" <?php echo ($filtros['preco'] == '0-50') ? 'selected="selected"' : ''; ?>>R$ 0 - 50</option>
                            <option value="51-100" <?php echo ($filtros['preco'] == '51-100') ? 'selected="selected"' : ''; ?>>R$ 51 - 100</option>
                            <option value="101-200" <?php echo ($filtros['preco'] == '101-200') ? 'selected="selected"' : ''; ?>>R$ 101 - 200</option>
                            <option value="201-500" <?php echo ($filtros['preco'] == '201-500') ? 'selected="selected"' : ''; ?>>R$ 201 - 500</option>
                        </select>
                    </div>

                    <!-- ESTADO -->
                    <label for="estado">Estado de conservação</label>
                    <div class="form-group">
                        <select name="filtros[estado]" id="estado" class="form-control">
                            <option></option>
                            <option value="0" <?php echo ($filtros['estado'] == '0') ? 'selected="selected"' : ''; ?>>Ruim</option>
                            <option value="1" <?php echo ($filtros['estado'] == '1') ? 'selected="selected"' : ''; ?>>Bom</option>
                            <option value="2" <?php echo ($filtros['estado'] == '2') ? 'selected="selected"' : ''; ?>>Ótimo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-info" value="Buscar" />
                    </div>
                </form>
            </div>
            <div class="col-sm-9">
                <h4>Últimos Anúncios</h4>
                <!-- TABELA DE ULTIMOS ANUNCIOS -->
                <table class="table table-striped">
                    <tbody>
                        <?php foreach($anuncios as $anuncio): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($anuncio['url'])): ?>
                                        <img src="assets/images/anuncios/<?php echo $anuncio['url']; ?>" alt="" height="50"></td>
                                    <?php else: ?>
                                        <img src="assets/images/default.jpg" height="50"></td>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="produto.php?id=<?php echo $anuncio['id']; ?>"><?php echo $anuncio['titulo']; ?></a><br/>
                                    <?php echo utf8_encode($anuncio['categoria']); ?>
                                </td>
                                <td>
                                    R$ <?php echo number_format($anuncio['valor'], 2); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- FIM DA TABELA -->
                <!-- PAGINAÇÃO -->
                <ul class="pagination">
                    <?php for($q = 1; $q <= $totalPaginas; $q++): ?>
                        <li class="<?php echo ($p == $q) ? 'active' : ''; ?>"><a href="index.php?<?php 
                        $w = $_GET;
                        $w['p'] = $q;
                        echo http_build_query($w);
                        ?>"><?php echo $q; ?></a></li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>
    </div>

<?php require_once('pages/footer.php'); ?>

