<?php
// UPDATE - recebe o formulário do editar.php
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$id        = (int) ($_POST["id"] ?? 0);
$nome      = trim($_POST["nome"] ?? "");
$descricao = trim($_POST["descricao"] ?? "");
$status    = $_POST["status"] ?? "";
$categoria = (int) ($_POST["categoria"] ?? 0);

if ($id <= 0 || $nome === "" || $categoria <= 0 || !in_array($status, $status_validos)) {
    header("Location: index.php?msg=invalido");
    exit;
}

$sql = "
    UPDATE equipamento
    SET
        nome_equipamento = ?,
        descricao_equipamento = ?,
        status_equipamento = ?,
        id_categoria = ?
    WHERE id_equipamento = ?
";

$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "sssii", $nome, $descricao, $status, $categoria, $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php?msg=atualizado");
} else {
    header("Location: index.php?msg=erro");
}
exit;
