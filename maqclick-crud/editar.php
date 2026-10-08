<?php
require_once "conexao.php";

// ---------- SALVAR ALTERAÇÃO ----------
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id        = (int) ($_POST["id"] ?? 0);
    $nome      = trim($_POST["nome"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");
    $status    = $_POST["status"] ?? "";
    $categoria = (int) ($_POST["categoria"] ?? 0);

    if ($id <= 0 || $nome === "" || $categoria <= 0 || !in_array($status, $lista_status)) {
        $aviso = "Preencha nome, status e categoria.";
    } else {
        $sql = "UPDATE equipamento
                SET nome_equipamento = ?, descricao_equipamento = ?,
                    status_equipamento = ?, id_categoria = ?
                WHERE id_equipamento = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, "sssii", $nome, $descricao, $status, $categoria, $id);
        $aviso = mysqli_stmt_execute($stmt) ? "Equipamento atualizado!" : "Erro ao atualizar.";
    }

    header("Location: index.php?aviso=" . urlencode($aviso));
    exit;
}

// ---------- CARREGAR O EQUIPAMENTO ----------
$id = (int) ($_GET["id"] ?? 0);

$stmt = mysqli_prepare($conexao, "SELECT * FROM equipamento WHERE id_equipamento = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$e = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$e) {
    header("Location: index.php?aviso=" . urlencode("Equipamento não encontrado."));
    exit;
}

$categorias = mysqli_query($conexao, "SELECT * FROM categoria ORDER BY nome_categoria");
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MAQCLICK - Editar</title>
    <link rel="stylesheet" href="estilo.css">
</head>

<body>

    <header>
        <h1>MAQ<span>CLICK</span></h1>
    </header>

    <main>

        <h2>Editar equipamento #<?= $e["id_equipamento"] ?></h2>

        <form method="POST">
            <input type="hidden" name="id" value="<?= $e["id_equipamento"] ?>">

            <label>Nome
                <input type="text" name="nome" maxlength="255" required
                    value="<?= htmlspecialchars($e["nome_equipamento"]) ?>">
            </label>

            <label>Descrição
                <input type="text" name="descricao" maxlength="255"
                    value="<?= htmlspecialchars($e["descricao_equipamento"] ?? "") ?>">
            </label>

            <label>Status
                <select name="status" required>
                    <?php foreach ($lista_status as $s): ?>
                        <option value="<?= $s ?>" <?= $e["status_equipamento"] === $s ? "selected" : "" ?>>
                            <?= $s ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Categoria
                <select name="categoria" required>
                    <?php while ($c = mysqli_fetch_assoc($categorias)): ?>
                        <option value="<?= $c["id_categoria"] ?>"
                            <?= (int) $e["id_categoria"] === (int) $c["id_categoria"] ? "selected" : "" ?>>
                            <?= htmlspecialchars($c["nome_categoria"]) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </label>

            <button type="submit">Salvar</button>
            <a class="botao perigo" href="index.php">Cancelar</a>
        </form>

    </main>

</body>

</html>
