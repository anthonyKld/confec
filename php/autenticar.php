<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receber dados do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Criptografar a senha informada pelo usuário usando MD5
    $senha_criptografada = md5($senha);

    // Preparar e executar a consulta SQL para verificar as credenciais
    $sql = "SELECT * FROM usuarios WHERE email = ? AND senha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $senha_criptografada);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Autenticação bem-sucedida
        $_SESSION['usuario'] = $email;
        $_SESSION['nome'] = $email;
        print_r ($result);
        header("Location: ./"); // Redireciona para a página principal após o login
        exit(); // Certifique-se de que o script pare de executar após o redirecionamento
    } else {
        // Autenticação falhou
        $_SESSION['erro'] = "E-mail ou senha incorretos.";
        //header("Location: ./login.php");
        exit();
    }

    $stmt->close();
}
?>
