<?php
session_start();
if ($_SESSION['role'] != 1 && $_SESSION['role'] != 0) header('Location: /OPTS2/index.php');
$title = 'ОПТС - Отчёт';
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
                        <input type="text" class="form-control" name="name" id="name">
                    </div>
                    <div class="form-group">
                        <label for="group">Группа:</label>
                        <input type="text" class="form-control" name="group" id="group">
                    </div>
                    <div class="form-group">
                        <label for="department">Кафедра:</label>
                        <input type="text" class="form-control" name="department" id="department">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="contract">Есть ли договор?
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
                    <th>ФИО</th>
                    <th>Группа</th>
                    <th>Кафедра</th>
                    <th>Есть ли договор</th>
                </tr>
            </table>
        </div>
    </div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>
