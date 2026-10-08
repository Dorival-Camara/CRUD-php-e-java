<?php
// CREATE - recebe o formulário do index.php e faz o INSERT
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$nome      = trim($_POST["nome"] ?? "");
$descricao = trim($_POST["descricao"] ?? "");
$status    = $_POST["status"] ?? "";
$categoria = (int) ($_POST["categoria"] ?? 0);

// Validação no servidor (o JavaScript sozinho não basta)
if ($nome === "" || $categoria <= 0 || !in_array($status, $status_validos)) {
    header("Location: index.php?msg=invalido");
    exit;
}

$sql = "
    INSERT INTO equipamento
    (nome_equipamento, descricao_equipamento, status_equipamento, id_categoria)
    VALUES (?, ?, ?, ?)
";

$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "sssi", $nome, $descricao, $status, $categoria);

if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php?msg=criado");
} else {
    header("Location: index.php?msg=erro");
}
exit;
