<?php
mysqli_report(MYSQLI_REPORT_OFF);

$conexao = mysqli_connect("localhost", "root", "", "db_maqclick");

if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

mysqli_set_charset($conexao, "utf8mb4");

$lista_status = ["DISPONIVEL", "MANUTENCAO", "EMPRESTADO"];
