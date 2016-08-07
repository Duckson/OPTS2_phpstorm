<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Создание контракта';

if (!empty($_POST) && (empty($_POST['c_start_date']) || empty($_POST['c_end_date']))) $error = 'Не правильно заполнена форма';
elseif (!empty($_POST)) {
    $prep = $sql->prepare("INSERT INTO contracts (company_id, start_date, end_date, description) VALUES (:company_id, :start_date, :end_date, :description)");
    $prep->execute([
        ':company_id' => $_POST['company'],
        ':start_date' => $_POST['c_start_date'],
        ':end_date' => $_POST['c_end_date'],
        ':description' => $_POST['c_description']
    ]);
    header('Location: /OPTS2/contracts/view.php?id=' . $sql->lastInsertId());
}

if (empty($_GET['company'])) $error_critical = 'Ошибка отображения страницы';
else {
    $prep = $sql->prepare('SELECT name FROM companies WHERE :id=id');
    $prep->execute([
        ':id' => $_GET['company']
    ]);
    $name = $prep->fetch()[0];
}
include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>

<? if (empty($error_critical)): ?>
    <div class="row content">
        <div class="marg-sides-10">
            <h2>Создание контракта</h2>
            <h3>Шаг 2: заполнение данных</h3>
            <? if (!empty($error)): ?>
                <span class="form-error"><?= $error ?></span>
            <? endif ?>
            <form action="create.php" method="post">
                <div class="well well-lg">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <b>Компания:</b> <?= $name ?> <input type="hidden" name="company" value="<?=$_GET['company']?>">
                            </div>
                            <div class="form-group">
                                <label for="c_start_date">Дата заключения:</label>
                                <input type="date" class="form-control" name="c_start_date" id="c_start_date">
                            </div>
                            <div class="form-group">
                                <label for="c_end_date">Дата окончания:</label>
                                <input type="date" class="form-control" name="c_end_date" id="c_end_date">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="c_description">Описание:</label>
                                <textarea rows="7" class="form-control" name="c_description"
                                          id="c_description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-primary" value="Создать контракт">
            </form>
        </div>
    </div>
<? else : ?>
    <?= $error_critical ?>
    <a href="list.php" class="btn btn-primary">Вернутся к списку</a>
<? endif; ?>
    </div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>