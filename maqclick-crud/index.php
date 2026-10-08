<?php
require_once "conexao.php";

// ---------- CADASTRAR e EXCLUIR ----------
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $acao = $_POST["acao"] ?? "";

    if ($acao === "cadastrar") {
        $nome      = trim($_POST["nome"] ?? "");
        $descricao = trim($_POST["descricao"] ?? "");
        $status    = $_POST["status"] ?? "";
        $categoria = (int) ($_POST["categoria"] ?? 0);

        if ($nome === "" || $categoria <= 0 || !in_array($status, $lista_status)) {
            $aviso = "Preencha nome, status e categoria.";
        } else {
            $sql = "INSERT INTO equipamento
                    (nome_equipamento, descricao_equipamento, status_equipamento, id_categoria)
                    VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexao, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $nome, $descricao, $status, $categoria);
            $aviso = mysqli_stmt_execute($stmt) ? "Equipamento cadastrado!" : "Erro ao cadastrar.";
        }
    }

    if ($acao === "excluir") {
        $id = (int) ($_POST["id"] ?? 0);
        $stmt = mysqli_prepare($conexao, "DELETE FROM equipamento WHERE id_equipamento = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            $aviso = "Equipamento excluído!";
        } elseif (mysqli_errno($conexao) == 1451) {
            $aviso = "Não dá para excluir: o equipamento tem empréstimo, agendamento ou manutenção.";
        } else {
            $aviso = "Erro ao excluir.";
        }
    }

    // Redireciona para o F5 não repetir a ação
    header("Location: index.php?aviso=" . urlencode($aviso));
    exit;
}

$aviso = $_GET["aviso"] ?? "";

// ---------- LISTAR (com pesquisa) ----------
$pesquisa = trim($_GET["pesquisa"] ?? "");
$termo = "%" . $pesquisa . "%";

$sql = "SELECT e.id_equipamento, e.nome_equipamento, e.descricao_equipamento,
               e.status_equipamento, c.nome_categoria
        FROM equipamento e
        INNER JOIN categoria c ON e.id_categoria = c.id_categoria
        WHERE e.nome_equipamento LIKE ?
           OR e.descricao_equipamento LIKE ?
           OR c.nome_categoria LIKE ?
        ORDER BY e.nome_equipamento";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "sss", $termo, $termo, $termo);
mysqli_stmt_execute($stmt);
$equipamentos = mysqli_stmt_get_result($stmt);

$categorias = mysqli_query($conexao, "SELECT * FROM categoria ORDER BY nome_categoria");
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MAQCLICK - Equipamentos</title>
    <link rel="stylesheet" href="estilo.css">
</head>

<body>

    <header>
        <h1>MAQ<span>CLICK</span></h1>
    </header>

    <main>

        <?php if ($aviso !== ""): ?>
            <p class="aviso"><?= htmlspecialchars($aviso) ?></p>
        <?php endif; ?>

        <h2>Novo equipamento</h2>

        <form method="POST">
            <input type="hidden" name="acao" value="cadastrar">

            <label>Nome
                <input type="text" name="nome" maxlength="255" required>
            </label>

            <label>Descrição
                <input type="text" name="descricao" maxlength="255">
            </label>

            <label>Status
                <select name="status" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($lista_status as $s): ?>
                        <option value="<?= $s ?>"><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Categoria
                <select name="categoria" required>
                    <option value="">Selecione...</option>
                    <?php while ($c = mysqli_fetch_assoc($categorias)): ?>
                        <option value="<?= $c["id_categoria"] ?>">
                            <?= htmlspecialchars($c["nome_categoria"]) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </label>

            <button type="submit">Cadastrar</button>
        </form>

        <h2>Equipamentos</h2>

        <form method="GET" class="pesquisa">
            <input type="text" name="pesquisa" placeholder="Pesquisar..."
                value="<?= htmlspecialchars($pesquisa) ?>">
            <button type="submit">Pesquisar</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>

            <?php while ($e = mysqli_fetch_assoc($equipamentos)): ?>
                <tr>
                    <td><?= $e["id_equipamento"] ?></td>
                    <td><?= htmlspecialchars($e["nome_equipamento"]) ?></td>
                    <td><?= htmlspecialchars($e["descricao_equipamento"] ?? "") ?></td>
                    <td><?= htmlspecialchars($e["nome_categoria"]) ?></td>
                    <td><?= htmlspecialchars($e["status_equipamento"] ?? "") ?></td>
                    <td>
                        <a class="botao" href="editar.php?id=<?= $e["id_equipamento"] ?>">Editar</a>

                        <form method="POST" class="inline">
                            <input type="hidden" name="acao" value="excluir">
                            <input type="hidden" name="id" value="<?= $e["id_equipamento"] ?>">
                            <button type="submit" class="perigo">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>

            <?php if (mysqli_num_rows($equipamentos) === 0): ?>
                <tr>
                    <td colspan="6">Nenhum equipamento encontrado.</td>
                </tr>
            <?php endif; ?>
        </table>

    </main>

</body>

</html>
