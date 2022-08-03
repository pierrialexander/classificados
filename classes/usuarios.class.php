<?php
//require_once '../config.php';

/**
 * Classe model responsável por tratar do sistema.
 * @author Pierri Alexander Vidmar
 * @since 31/07/2022
 */
class Usuarios {

    /**
     * Método responsável por fazer o cadastro do usuário na base de dados.
     * @param string $nome
     * @param string $email
     * @param string $senha
     * @param string $telefone
     * @return bool
     */
    public function cadastrar($nome, $email, $senha, $telefone) {
        global $pdo;
        $sql = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $sql->bindValue(":email", $email);
        $sql->execute();

        if($sql->rowCount() == 0) {
            $sql = $pdo->prepare("INSERT INTO usuarios SET nome = :nome, email = :email, senha = :senha, telefone = :telefone");
            $sql->bindValue(":nome", $nome);
            $sql->bindValue(":email", $email);
            $sql->bindValue(":senha", md5($senha));
            $sql->bindValue(":telefone", $telefone);

            $sql->execute();
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Método responsável por efetuar o login e salvar a ID na sessão.
     * @param string $email
     * @param string $senha
     * @return bool
     */
    public function login($email, $senha){
        global $pdo;
        $sql = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email AND senha = :senha");
        $sql->bindValue(":email", $email);
        $sql->bindValue(":senha", md5($senha));
        $sql->execute();

        if($sql->rowCount() > 0) {
            $dado = $sql->fetch();
            $_SESSION['cLogin'] = $dado['id'];
            $this->getNomeUsuario($dado['id']);
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Método responsável por coletar os dados do nome do usuário logado e salvar na sessão.
     * @param int $id
     * @return bool
     */
    public function getNomeUsuario($id) {
        global $pdo;
        $sql = $pdo->prepare("SELECT nome FROM usuarios WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $dado = $sql->fetch();
            $_SESSION['cUsuario'] = $dado['nome'];
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Retorna o total de usuários cadastrados no sistema
     * @return array $row
     */
    public function getTotalUsuarios() {
        global $pdo;

        $sql = $pdo->query("SELECT COUNT(*) as c FROM usuarios");
        $row = $sql->fetch();

        return $row['c'];
    }
}