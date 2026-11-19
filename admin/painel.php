<?php
require_once dirname(__DIR__) . '/config.php';
auth();
$nome = trim((string)($_GET['nome'] ?? ''));
$escola = (string)($_GET['escola'] ?? '');
$serie = (string)($_GET['serie'] ?? '');
$cidade = trim((string)($_GET['cidade'] ?? ''));
$ano = (string)($_GET['ano'] ?? '');
$sem = (string)($_GET['semestre'] ?? '');
$where = [];
$par = [];
if ($nome !== '') {
    $where[] = 'a.nome LIKE ?';
    $par[] = '%' . $nome . '%';
}
if ($escola !== '') {
    $where[] = 'a.nome_escola=?';
    $par[] = $escola;
}
if ($serie !== '') {
    $where[] = 'a.serie_escolar=?';
    $par[] = $serie;
}
if ($cidade !== '') {
    $where[] = 'a.cidade LIKE ?';
    $par[] = '%' . $cidade . '%';
}
if ($ano !== '' && ctype_digit($ano)) {
    $where[] = 'i.ano=?';
    $par[] = (int)$ano;
}
if (in_array($sem, ['1', '2'], true)) {
    $where[] = 'i.semestre=?';
    $par[] = (int)$sem;
}
$sql = ' FROM inscricoes i JOIN alunos a ON a.id_aluno=i.aluno_id ' . ($where ? ' WHERE ' . implode(' AND ', $where) : '');
$q = db()->prepare('SELECT COUNT(*)' . $sql);
$q->execute($par);
$total = (int)$q->fetchColumn();
$q = db()->prepare('SELECT i.id_inscricao,i.ano,i.semestre,i.data_inscricao,i.situacao,a.nome,a.cidade,a.serie_escolar,a.nome_escola' . $sql . ' ORDER BY i.data_inscricao DESC, i.id_inscricao DESC LIMIT 200');
$q->execute($par);
$rows = $q->fetchAll();
$escolas = db()->query('SELECT DISTINCT nome_escola FROM alunos ORDER BY nome_escola')->fetchAll();
$series = db()->query('SELECT DISTINCT serie_escolar FROM alunos ORDER BY serie_escolar')->fetchAll();
function option(string $v, string $t, string $current): void
{
    echo '<option value="' . e($v) . '"' . ($v === $current ? ' selected' : '') . '>' . e($t) . '</option>';
}
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel Galera Tech</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/painel.css">
</head>

<body>
    <header class="cabecalhopainel">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">Painel de Inscrições</h1>
                <div>Galera Tech • Administração</div>
            </div><a class="btn btn-light btn-sm" href="sair.php">Sair</a>
        </div>
    </header>
    <main class="container py-4">
        <div class="card secao">
            <div class="card-body">
                <h2 class="h5">Filtros</h2>
                <form method="get" class="row g-3">
                    <div class="col-md-4"><label class="form-label">Nome</label><input class="form-control" name="nome" value="<?= e($nome) ?>" placeholder="Pesquisar candidato"></div>
                    <div class="col-md-4"><label class="form-label">Escola</label><select class="form-select" name="escola"><?php option('', 'Todas', $escola);
                                                                                                                            foreach ($escolas as $v) option($v['nome_escola'], $v['nome_escola'], $escola); ?></select></div>
                    <div class="col-md-4"><label class="form-label">Série</label><select class="form-select" name="serie"><?php option('', 'Todas', $serie);
                                                                                                                            foreach ($series as $v) option($v['serie_escolar'], $v['serie_escolar'], $serie); ?></select></div>
                    <div class="col-md-4"><label class="form-label">Cidade</label><input name="cidade" class="form-control" value="<?= e($cidade) ?>" placeholder="Cidade de residência"></div>
                    <div class="col-md-4"><label class="form-label">Ano</label><select name="ano" class="form-select"><?php option('', 'Todos', $ano);
                                                                                                                        foreach (range(2026, 2030) as $v) option((string)$v, (string)$v, $ano); ?></select></div>
                    <div class="col-md-4"><label class="form-label">Semestre</label><select name="semestre" class="form-select"><?php option('', 'Todos', $sem);
                                                                                                                                option('1', '1º semestre', $sem);
                                                                                                                                option('2', '2º semestre', $sem); ?></select></div>
                    <div class="col-12 d-flex gap-2"><button class="btn btn-primary">Aplicar filtros</button><a class="btn btn-outline-secondary" href="painel.php">Limpar filtros</a></div>
                </form>
            </div>
        </div>
        <div class="alert alert-primary"><strong><?= $total ?></strong> inscrição(ões) encontrada(s) <?php if ($total > 200): ?> — exibindo as 200 mais recentes<?php endif; ?></div>
        <div class="card secao">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Escola</th>
                                <th>Série</th>
                                <th>Cidade</th>
                                <th>Período</th>
                                <th>Situação</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody><?php foreach ($rows as $r): ?><tr>
                                    <td><?= e($r['nome']) ?></td>
                                    <td><?= e($r['nome_escola']) ?></td>
                                    <td><?= e($r['serie_escolar']) ?></td>
                                    <td><?= e($r['cidade']) ?></td>
                                    <td><?= e($r['semestre'] . 'º/' . $r['ano']) ?></td>
                                    <td><?= e($r['situacao']) ?></td>
                                    <td><a class="btn btn-sm btn-outline-primary" href="detalhes.php?id=<?= (int)$r['id_inscricao'] ?>">Detalhes</a></td>
                                </tr><?php endforeach;
                                    if (!$rows): ?><tr>
                                    <td colspan="7">Nenhuma inscrição encontrada.</td>
                                </tr><?php endif; ?></tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>

</html>