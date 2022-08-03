<?php
require_once 'pages/header.php';
?>

<div class="container">
    <h1>Cadastre-se</h1>
    <?php
        require_once 'classes/usuarios.class.php';
        $u = new Usuarios();
        if(isset($_POST['nome']) && !empty($_POST['nome'])) {
            $nome = addslashes($_POST['nome']);
            $email = addslashes($_POST['email']);
            $senha = $_POST['senha'];
            $telefone = addslashes($_POST['email']);

            //Se as variáveis não estiverem vazias, cadastra
            if(!empty($nome) && !empty($email) && !empty($senha)) {
                // No servidor verifica se já existe ou se não cadastra.
                if($u->cadastrar($nome, $email, $senha, $telefone)){
                    ?>
                        <div class="alert alert-success">
                            Cadastro efetuado com sucesso!
                            <a href="login.php" class="alert-link"><strong>Clique Aqui</strong></a> e faça seu login!
                        </div>
                    <?php 
                } else {
                    ?>
                        <div class="alert alert-warning">
                            Este usuário já exite!
                            <a href="login.php" class="alert-link">Faça o login agora</a>
                        </div>
                    <?php 
                }
            }
            else {
                ?>
                    <div class="alert alert-warning">
                        Preencha todos os campos!
                    </div>
                <?php
            }
        }
    ?>
    <!--    INICIO DO FORMULÁRIO DE CADASTRO-->
    <form method="POST">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" class="form-control">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" class="form-control">
        </div>
        <div class="form-group">
            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" class="form-control">
        </div>
        <input type="submit" value="Cadastrar" class="btn btn-primary"/>
    </form>
</div>

<?php require_once 'pages/footer.php'; ?>
