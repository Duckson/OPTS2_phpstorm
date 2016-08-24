<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
include ($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/pagination.php');
$title = 'ОПТС - Отчёт';
$pagination = new Pagination(15, $_GET, $_SERVER['PHP_SELF']);

if (!empty($_GET['name'])) {
    $name = '%' . $_GET['name'] . '%';
    $where[] = 'students.name LIKE :name';
    $prep_names[] = ':name';
    $prep_types[] = PDO::PARAM_STR;
    $prep_vals[] = &$name;
}
if (!empty($_GET['group'])) {
    $group = '%' . $_GET['group'] . '%';
    $where[] = 'student_groups.name LIKE :group';
    $prep_names[] = ':group';
    $prep_types[] = PDO::PARAM_STR;
    $prep_vals[] = &$group;
}
if (!empty($_GET['department'])) {
    $where[] = 'departments.name LIKE :department';
    $prep_names[] = ':department';
    $prep_types[] = PDO::PARAM_STR;
    $prep_vals[] = &$_GET['department'];
}
if (!empty($_GET['is_ok'])) {
    $where[] = 't_count.count > 0';
}

$limit_str = $pagination->getLimitStr();

$query_str = 'SELECT students.name AS name, student_groups.name AS student_group, departments.name AS department,
                      t_count.count AS is_ok
                      FROM students
                      LEFT JOIN student_groups ON students.group_id = student_groups.id
                      LEFT JOIN curricula ON student_groups.curricilum_id = curricula.id
                      LEFT JOIN departments ON curricula.department_id = departments.id
                      LEFT JOIN (SELECT count(student_login) AS count, student_login FROM student_app_link
                                 LEFT JOIN applications ON student_app_link.app_id = applications.id
                                 WHERE applications.end_date > CURDATE()
                                 GROUP BY student_login) AS t_count 
                      ON t_count.student_login=students.login';

$query_count_str = 'SELECT count(*)
                      FROM students
                      LEFT JOIN student_groups ON students.group_id = student_groups.id
                      LEFT JOIN curricula ON student_groups.curricilum_id = curricula.id
                      LEFT JOIN departments ON curricula.department_id = departments.id
                      LEFT JOIN (SELECT count(student_login) AS count, student_login FROM student_app_link
                                 LEFT JOIN applications ON student_app_link.app_id = applications.id
                                 WHERE applications.end_date > CURDATE()
                                 GROUP BY student_login) AS t_count 
                      ON t_count.student_login=students.login';
$order_str = ' ORDER BY students.name';

if (empty($where)) {
    $query = $sql->query($query_str . $order_str . $limit_str);
    while ($row = $query->fetch()) {
        $result[] = $row;
    }
    $query = $sql->query($query_count_str);

    $pagination->setItemsCount($query->fetch()[0]);

} else {
    $where_str = '';
    $where_str = ' WHERE ' . join(' AND ', $where);
    $prep = $sql->prepare($query_str . $where_str . $order_str . $limit_str);
    if (!empty($prep_vals))
    foreach ($prep_vals as $key => $value) {
        $prep->bindParam($prep_names[$key], $value, $prep_types[$key]);
    }
    $prep->execute();
    while ($row = $prep->fetch()) {
        $result[] = $row;
    }

    $prep = $sql->prepare($query_count_str . $where_str);
    if (!empty($prep_vals))
        foreach ($prep_vals as $key => $value) {
            $prep->bindParam($prep_names[$key], $value, $prep_types[$key]);
        }
    $prep->execute();
    $pagination->setItemsCount($prep->fetch()[0]);
}


?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="OPTS2.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <title><?= $title ?></title>
</head>
<body class="body-wrap">
<div class="container-fluid wrap">
    <div class="row content">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">ОПТС</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="report.php">Отчёт</a></li>
                        <?php if ($_SESSION['role'] == 1): ?>
                            <li><a href="/OPTS2/practice_types/list.php">Типы практики</a></li>
                            <li><a href="/OPTS2/contracts/list.php">Контракты</a></li>
                            <li><a href="/OPTS2/companies/list.php">Компании</a></li>
                        <?php endif; ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="/OPTS2/index.php">Выход</a></li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </div>
    <div class="row content">
        <div class="col-sm-3">
            <div class="well well-sm">
                <span class="h3">Фильтр</span>
                <form action="report.php" method="get">
                    <div class="form-group">
                        <label for="name">ФИО:</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?= $_GET['name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="group">Группа:</label>
                        <input type="text" class="form-control" name="group" id="group" value="<?= $_GET['group'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="department">Кафедра:</label>
                        <input type="text" class="form-control" name="department" id="department" value="<?= $_GET['department'] ?>">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input <?= ($_GET['is_ok'])? 'checked': '' ?> type="checkbox" name="is_ok" value="1">Есть ли договор?
                        </label>
                    </div>
                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" value="Применить">
                    </div>
                </form>
                <form action="report.php" method="get">
                    <div class="form-group">
                        <input class="btn btn-warning" type="submit" value="Очистить">
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-9">
            <span class="h3">Отчёт</span>
            <table class="table table-hover table-condensed table-bordered">
                <tr>
                    <th style="width: 40%;">ФИО</th>
                    <th style="width: 15%;">Группа</th>
                    <th style="width: 30%;">Кафедра</th>
                    <th style="width: 15%;">Есть ли договор</th>
                </tr>
                <? if (!empty($result)): ?>
                    <? foreach ($result as $row): ?>
                        <tr>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['student_group'] ?></td>
                            <td><?= $row['department'] ?></td>
                            <td>
                                <? if ($row['is_ok'] == 0): ?>
                                    <span class="glyphicon glyphicon-remove glyph-report-bad"></span>
                                <? else: ?>
                                    <span class="glyphicon glyphicon-ok glyph-report-good"></span>
                                <? endif; ?>
                            </td>
                        </tr>
                    <? endforeach; ?>
                <? endif; ?>
            </table>
            <?= $pagination->getPagesStr() ?>
        </div>
    </div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>
