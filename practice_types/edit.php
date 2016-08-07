<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Редактирование типа практики';
if (!empty($_GET['id'])) {
    if (!empty($_POST) && empty($_POST['e_name'])) $error = 'Не правильно заполнена форма';
    elseif (!empty($_POST)) {
        $query = "UPDATE practice_types SET name=:name WHERE id=:id";
        $prep = $sql->prepare($query);
        $prep->bindParam(':name', $_POST['e_name'], PDO::PARAM_STR);
        $prep->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $prep->execute();
        header('Location: /OPTS2/practice_types/list.php?name=' . $_POST['e_name']);
    }

    $prep = $sql->prepare('SELECT name FROM practice_types WHERE id=:id');
    $prep->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $prep->execute();
    $result = $prep->fetch();
} else $error = 'Произошла ошибка отображения страницы';

include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>

    <div class="row content">
        <div class="marg-sides-10">
            <? if (empty($error)): ?>
                <h3>Редактировать тип практики</h3>
                <form action="edit.php?id=<?= $_GET['id'] ?>" method="post">
                    <div class="well well-lg">
                        <div class="form-group">
                            <label for="e_name">Название:</label>
                            <input type="text" class="form-control" name="e_name" id="e_name" value="<?= $result['name'] ?>">
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Сохранить тип практики">
                </form>
            <? else : ?>
                <?= $error ?>
                <a href="list.php" class="btn btn-primary">Вернутся к списку</a>
            <? endif; ?>
        </div>
    </div>
    </div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>