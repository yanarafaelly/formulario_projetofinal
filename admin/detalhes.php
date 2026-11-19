<?php
require_once dirname(__DIR__) . '/config.php';
auth();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('Inscrição inválida.');
}
$q = db()->prepare('SELECT i.*,a.*,r.nome AS nome_responsavel,r.parentesco,r.telefone AS telefone_responsavel FROM inscricoes i JOIN alunos a ON a.id_aluno=i.aluno_id LEFT JOIN responsaveis r ON a.responsavel_id=r.id_responsavel WHERE i.id_inscricao=?');
$q->execute([$id]);
$d = $q->fetch();
if (!$d) {
    http_response_code(404);
    exit('Inscrição não encontrada.');
}
$campos = ['id_inscricao' => 'Número da inscrição', 'nome' => 'Estudante', 'data_nascimento' => 'Nascimento', 'cpf' => 'CPF', 'rg' => 'RG', 'sexo' => 'Sexo', 'email' => 'E-mail', 'telefone' => 'WhatsApp', 'reside_rio_preto' => 'Reside em Rio Preto', 'cep' => 'CEP', 'logradouro' => 'Logradouro', 'numero' => 'Número', 'complemento' => 'Complemento', 'bairro' => 'Bairro', 'cidade' => 'Cidade', 'estado' => 'UF', 'nome_escola' => 'Escola pública', 'serie_escolar' => 'Série', 'origem_divulgacao' => 'Como conheceu', 'nome_responsavel' => 'Responsável', 'parentesco' => 'Parentesco', 'telefone_responsavel' => 'WhatsApp responsável', 'ano' => 'Ano', 'semestre' => 'Semestre', 'data_inscricao' => 'Data inscrição', 'situacao' => 'Situação', 'data_aceite' => 'Data declarações'];
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalhes | Galera Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Detalhes | Galera Tech</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #02047e;
            color: white;
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #ffd900;
            margin-bottom: 25px;
        }
        table {
            background-color: white;
            border-radius: 10px;
        }
        th {
            color: #163e75 ;
        } /* cor do texto da tabela */
        td {
            color: #333 ;
        } /*cor das celulas do cinza */
        .btn {
            border-radius: 8px;
        }
    </style>
</head>
</head>

<body>
    <main class="container py-5"><a href="painel.php" class="btn btn-outline-primary mb-4">Voltar ao painel</a>
        <h1 class="h3">Detalhes da inscrição</h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <tbody><?php foreach ($campos as $k => $label): ?><tr>
                            <th style="width:35%"><?= e($label) ?></th>
                            <td><?= e((string)($d[$k] ?? '—')) ?></td>
                        </tr><?php endforeach; ?></tbody>
            </table>
        </div>
        <p class="alert alert-secondary">Declarações de programa, transporte, veracidade e privacidade: <?= ((int)$d['ciencia_programa'] === 1 && (int)$d['ciencia_transporte'] === 1 && (int)$d['declarou_veracidade'] === 1 && (int)$d['ciencia_privacidade'] === 1) ? 'Confirmadas' : 'Pendentes' ?></p>
    </main>
</body>

</html>