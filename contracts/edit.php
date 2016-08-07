<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Редактирование контракта';
if (!empty($_GET['id'])) {
    if (!empty($_POST) && (empty($_POST['e_start_date']) || empty($_POST['e_end_date']))) $error = 'Не правильно заполнена форма';
    elseif (!empty($_POST)) {
        $prep = $sql->prepare("UPDATE contracts SET company_id=:company_id, start_date=:start_date, end_date=:end_date, description=:description WHERE id=:id");
        $prep->execute([
            ':company_id' => $_POST['company'],
            ':start_date' => $_POST['e_start_date'],
            ':end_date' => $_POST['e_end_date'],
            ':description' => $_POST['e_description'],
            ':id' => $_GET['id']
        ]);
        header('Location: /OPTS2/contracts/view.php?id=' . $_GET['id']);
    }

    $prep = $sql->prepare('SELECT start_date, end_date, description FROM contracts WHERE id=:id');
    $prep->execute([
        ':id' => $_GET['id']
    ]);
    $result = $prep->fetch();

    if (empty($_GET['company'])) $error_critical = 'Произошла ошибка отображения страницы';
    else {
        $prep = $sql->prepare('SELECT name FROM companies WHERE :id=id');
        $prep->execute([
            ':id' => $_GET['company']
        ]);
        $name = $prep->fetch()[0];
    }
} else $error_critical = 'Произошла ошибка отображения страницы';

include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>

<? if (empty($error_critical)): ?>
    <div class="row content">
        <div class="marg-sides-10">
            <h2>Редактирование контракта</h2>
            <h3>Шаг 2: заполнение данных</h3>
            <? if (!empty($error)): ?>
                <span class="form-error"><?= $error ?></span>
            <? endif ?>
            <form action="edit.php?id=<?= $_GET['id'] ?>" method="post">
                <div class="well well-lg">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="e_company">Компании:</label>
                                <b>Компания:</b> <?= $name ?> <input type="hidden" name="company"
                                                                     value="<?= $_GET['company'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="e_start_date">Дата заключения:</label>
                                <input type="date" class="form-control" name="e_start_date" id="e_start_date"
                                       value="<?= $result['start_date'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="e_end_date">Дата окончания:</label>
                                <input type="date" class="form-control" name="e_end_date" id="e_end_date"
                                       value="<?= $result['end_date'] ?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="e_description">Описание:</label>
                                <textarea rows="7" class="form-control" name="e_description"
                                          id="c_description"><?= $result['description'] ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-primary" value="Сохранить контракт">
            </form>
        </div>
    </div>
<? else : ?>
    <?= $error_critical ?>
    <a href="list.php" class="btn btn-primary">Вернутся к списку</a>
<? endif; ?>
    </div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>