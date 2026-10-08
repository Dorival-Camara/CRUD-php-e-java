<?php
// DELETE - chamado pelo botão Excluir do index.php (via POST)
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$id = (int) ($_POST["id"] ?? 0);

$stmt = mysqli_prepare($conexao, "DELETE FROM equipamento WHERE id_equipamento = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php?msg=excluido");
} elseif (mysqli_errno($conexao) == 1451) {
    // 1451 = o equipamento está ligado a empréstimo, agendamento ou manutenção (chave estrangeira)
    header("Location: index.php?msg=vinculado");
} else {
    header("Location: index.php?msg=erro");
}
exit;
