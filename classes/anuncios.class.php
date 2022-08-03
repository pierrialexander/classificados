<?php

/**
 * Classe model responsável por tratar dos anúncios.
 * @author Pierri Alexander Vidmar
 * @since 31/07/2022
 */
class Anuncios
{
    /**
     * Método responsável por buscar todos os anúncios de um usuário.
     * @return array|false
     */
    public function getMeusAnuncios() {
        global $pdo;

        $array = [];
        $sql = $pdo->prepare("SELECT *, (SELECT anuncios_imagens.url from anuncios_imagens 
                                          where anuncios_imagens.id_anuncio = anuncios.id limit 1) as url 
                                FROM anuncios WHERE id_usuario = :id_usuario");
        $sql->bindValue(":id_usuario", $_SESSION['cLogin']);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $array = $sql->fetchAll();
        }
        return $array;
    }

    /**
     * Método responsável por adicionar um anúncio no banco de dados.
     * @param $id_usuario
     * @param $titulo
     * @param $categoria
     * @param $valor
     * @param $descricao
     * @param $estado
     * @return bool
     */
    public function addAnuncio($id_usuario, $titulo, $categoria, $valor, $descricao, $estado) {
        global $pdo;
        $sql = $pdo->prepare("INSERT INTO anuncios SET id_usuario = :id_usuario, titulo = :titulo, id_categoria = :id_categoria, valor = :valor, descricao = :descricao, estado = :estado");
        $sql->bindValue(":id_usuario", $id_usuario);
        $sql->bindValue(":titulo", $titulo);
        $sql->bindValue(":id_categoria", $categoria);
        $sql->bindValue(":valor", $valor);
        $sql->bindValue(":descricao", $descricao);
        $sql->bindValue(":estado", $estado);
        $sql->execute();
        return true;
    }

    /**
     * Método responsável por excluir um anúncio.
     * @param $id
     * @return bool
     */
    public function excluirAnuncio($id) {
        global $pdo;

        $sql = $pdo->prepare("DELETE FROM anuncios_imagens WHERE id_anuncio = :id_anuncio");
        $sql->bindValue(":id_anuncio", $id);
        $sql->execute();

        $sql = $pdo->prepare("DELETE FROM anuncios WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();
        return true;
    }

    /**
     * Método responsável por efetuar a exclusão de uma imagem do anúncio.
     * @param int $id
     * @return int $id_anuncio
     */
    public function excluirFoto($id) {
        global $pdo;

        $sql = $pdo->prepare("SELECT id_anuncio FROM anuncios_imagens WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $row = $sql->fetch();
            $id_anuncio = $row['id_anuncio'];
        }
        $sql = $pdo->prepare("DELETE FROM anuncios_imagens WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();

        return $id_anuncio;
    }

    /**
     * Método responsável por buscar um anúncio.
     * @param $id_anuncio
     * @return array|mixed
     */
    public function getAnuncio($id_anuncio) {
        global $pdo;

        $array = [];
        $sql = $pdo->prepare("SELECT *,
                             (SELECT categorias.nome from categorias 
                               where categorias.id = anuncios.id_categoria) as categoria,
                             (SELECT usuarios.telefone from usuarios 
                               where usuarios.id = anuncios.id_usuario) as telefone
                                FROM anuncios WHERE id = :id");
        $sql->bindValue(":id", $id_anuncio);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $array = $sql->fetch();
            $array['fotos'] = [];

            // consulta as imagens
            $sql = $pdo->prepare("SELECT id, url FROM anuncios_imagens WHERE id_anuncio = :id_anuncio");
            $sql->bindValue(":id_anuncio", $id_anuncio);
            $sql->execute();
            
            if($sql->rowCount() > 0) {
                $array['fotos'] = $sql->fetchAll();
            }
        }
        return $array;
    }

    /**
     * Método responsável por Editar um anúncio.
     * @param $id
     * @param $id_usuario
     * @param $titulo
     * @param $categoria
     * @param $valor
     * @param $descricao
     * @param $estado
     * @return bool
     */
    public function editAnuncio($id, $id_usuario, $titulo, $categoria, $valor, $descricao, $estado, $fotos) {
        global $pdo;
        $sql = $pdo->prepare("UPDATE anuncios SET id = :id, id_usuario = :id_usuario, titulo = :titulo, id_categoria = :id_categoria, valor = :valor, descricao = :descricao, estado = :estado 
                                    WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->bindValue(":id_usuario", $id_usuario);
        $sql->bindValue(":titulo", $titulo);
        $sql->bindValue(":id_categoria", $categoria);
        $sql->bindValue(":valor", $valor);
        $sql->bindValue(":descricao", $descricao);
        $sql->bindValue(":estado", $estado);
        $sql->execute();

        // VERIFICAÇÃO SE FOI ENVIADO FOTOS E RECEPÇÃO DA MESMA
        if(count($fotos) > 0) {
            for($q = 0; $q < count($fotos['tmp_name']); $q++) {
                $tipo = $fotos['type'][$q];
                //verifica o formato da imagem enviada
                if(in_array($tipo, ['image/jpeg', 'image/png'])) {
                    // prepara o nome e formato do arquivo a ser salvo
                    $tmpname = md5(time().rand(0,9999)) . '.jpg';
                    // move para a pasta correta e salva
                    move_uploaded_file($fotos['tmp_name'][$q], 'assets/images/anuncios/' . $tmpname);
                    
                    // procedimentos para redimencionar o arquivo e salvar no servidor
                    list($width_orig, $height_orig) = getimagesize('assets/images/anuncios/' . $tmpname);
                    $ratio = $width_orig/$height_orig;
                    $width = 500;
                    $height = 500;
                    if($width/$height > $ratio) {
                        $width = $height * $ratio;
                    }
                    else {
                        $height = $width / $ratio;
                    }
                    $img = imagecreatetruecolor($width, $height);
                    if($tipo == 'image/jpeg') {
                        $origi = imagecreatefromjpeg('assets/images/anuncios/' . $tmpname);
                    }
                    else if($tipo == 'image/png') {
                        $origi = imagecreatefrompng('assets/images/anuncios/' . $tmpname);
                    }
                    imagecopyresampled($img, $origi, 0,0,0,0, $width, $height, $width_orig, $height_orig);

                    imagejpeg($img, 'assets/images/anuncios/' . $tmpname, 80);

                    // APÓS CONCLUIDO, VAMOS SALVAR NO ANCO DE DADOS O ID E URL DA IMAGEM
                    $sql = $pdo->prepare("INSERT INTO anuncios_imagens SET id_anuncio = :id_anuncio, url = :url");
                    $sql->bindValue(":id_anuncio", $id);
                    $sql->bindValue(":url", $tmpname);
                    $sql->execute();
                }
            }
        }
        // FIM DAS TRATATIVAS DE FOTO =================================================
        return true;
    }

    /**
     * Retorna o total de anúncios cadastrados no sistema
     * @return array $row
     */
    public function getTotalAnuncios($filtros) {
        global $pdo;

        $sql = $pdo->prepare("SELECT COUNT(*) as c FROM anuncios WHERE " . implode(' AND ', $this->trataFiltros($filtros)));

        if(!empty($filtros['categoria'])){
            $sql->bindValue(':id_categoria', $filtros['categoria']);
        }
        if(!empty($filtros['preco'])){
            $preco = explode('_', $filtros['preco']);
            $sql->bindValue(':preco1', $preco[0]);
            $sql->bindValue(':preco2', $preco[1]);
        }
        if(!empty($filtros['estado'])){
            $sql->bindValue(':estado', $filtros['estado']);
        }

        $sql->execute();

        $row = $sql->fetch();

        return $row['c'];
    }

    /**
     * Método para tratamento dos filtros, caso definidos.
     */
    public function trataFiltros($filtros) {
        // tratamento dos filtros caso exista
        $filtroString = ['1=1'];
        if(!empty($filtros['categoria'])){
            $filtroString[] = 'anuncios.id_categoria = :id_categoria';
        }
        if(!empty($filtros['preco'])){
            $filtroString[] = 'anuncios.valor BETWEEN :preco1 AND :preco2';
        }
        if(!empty($filtros['estado'])){
            $filtroString[] = 'anuncios.estado = :estado';
        }

        return $filtroString;
    }


    /**
     * 
     */
    public function getUltimosAnuncios($page, $perPage, $filtros) {
        global $pdo;

        $offset = ($page - 1) * $perPage;

        $array = [];

        $sql = $pdo->prepare("SELECT *, (SELECT anuncios_imagens.url from anuncios_imagens 
                                          where anuncios_imagens.id_anuncio = anuncios.id limit 1) as url, 
                                        (SELECT categorias.nome from categorias 
                                          where categorias.id = anuncios.id_categoria) as categoria
                                FROM anuncios WHERE " . implode(' AND ', $this->trataFiltros($filtros)) . " ORDER BY id DESC LIMIT $offset, $perPage");
        
        if(!empty($filtros['categoria'])){
            $sql->bindValue(':id_categoria', $filtros['categoria']);
        }
        if(!empty($filtros['preco'])){
            $preco = explode('_', $filtros['preco']);
            $sql->bindValue(':preco1', $preco[0]);
            $sql->bindValue(':preco2', $preco[1]);
        }
        if(!empty($filtros['estado'])){
            $sql->bindValue(':estado', $filtros['estado']);
        }
        
        $sql->execute();

        if($sql->rowCount() > 0) {
            $array = $sql->fetchAll();
        }
        return $array;
    }
}