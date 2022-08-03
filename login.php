<?php
require_once 'pages/header.php';
?>
<div class="container">
    <h1>Faça seu Login</h1>
    <?php
        require_once 'classes/usuarios.class.php';
        $u = new Usuarios();
        if(isset($_POST['email']) && !empty($_POST['email'])) {
            $email = addslashes($_POST['email']);
            $senha = $_POST['senha'];
            
            // se login retornar true, retorna para a home já logado
            if($u->login($email, $senha)) {
                ?>
                <script>
                    window.location.href="./";
                </script>
                <?php
            }
            else {
                ?>
                    <div class="alert alert-danger">
                        Usuário ou senha incorretos.
                    </div>
                <?php
            }
        }
    ?>
    <!-- INICIO DO FORMULÁRIO DE CADASTRO -->
    <form method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" class="form-control">
        </div>
        <input type="submit" value="Fazer Login" class="btn btn-primary"/>
    </form>
</div>

<?php require_once 'pages/footer.php'; ?>
