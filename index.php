<?php
session_start();
if(isset($_POST['role'])){
    $_SESSION['role'] = $_POST['role'];
    header('Location: /OPTS2/report.php');
}
$title = 'ОПТС - Вход';
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
            </div>
        </nav>
    </div>
    <div class="row content">
        <form action="index.php" method="post">
            Выберите роль: <select class="form-control" name="role">
                <option value="0">Кафедра</option>
                <option value="1">ОПТС</option>
            </select><br>
            <input type="submit" value="Вход">
        </form>
    </div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>



