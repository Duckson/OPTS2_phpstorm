<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Создание типа практики';
if (!empty($_POST) && empty($_POST['c_name'])) $error = 'Не правильно заполнена форма';
elseif(!empty($_POST)) {
    $prep = $sql->prepare('INSERT INTO practice_types (name) VALUES (:name)');
    $prep->bindParam(':name', $_POST['c_name'], PDO::PARAM_STR);
    $prep->execute();
    header('Location: /OPTS2/practice_types/list.php?name=' . $_POST['c_name']);
}
include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>
   
    <div class="row content">
        <div class="marg-sides-10">
            <h3>Создать тип практики</h3>
            <form action="create.php" method="post">
                <div class="well well-lg">
                    <div class="form-group">
                        <label for="c_name">Название:</label>
                        <input type="text" class="form-control" name="c_name" id="c_name">
                    </div>
                </div>
                <input type="submit" class="btn btn-primary" value="Создать тип практики">
            </form>
        </div>
    </div>
    </div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>