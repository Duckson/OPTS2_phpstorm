<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Редактирование компании';
if (!empty($_GET['id'])) {
    if (!empty($_POST) && empty($_POST['e_name'])) $error = 'Не правильно заполнена форма';
    elseif (!empty($_POST)) {
        $query = "UPDATE companies SET name=:name, telephone=:telephone, address=:address, representative=:representative, description=:description WHERE id=:id";
        $prep = $sql->prepare($query);
        $prep->bindParam(':name', $name, PDO::PARAM_STR);
        $prep->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $prep->bindParam(':address', $address, PDO::PARAM_STR);
        $prep->bindParam(':representative', $representative, PDO::PARAM_STR);
        $prep->bindParam(':description', $description, PDO::PARAM_STR);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $name = $_POST['e_name'];
        $telephone = $_POST['e_telephone'];
        $address = $_POST['e_address'];
        $representative = $_POST['e_fio'];
        $description = $_POST['e_description'];
        $id = $_GET['id'];
        $prep->execute();
        header('Location: /OPTS2/companies/view.php?id=' . $_GET['id']);
    }
    $prep = $sql->prepare('SELECT name, telephone, address, representative, description FROM companies WHERE id=:id');
    $prep->bindParam(':id', $id, PDO::PARAM_INT);
    $id = $_GET['id'];
    $prep->execute();
    $result = $prep->fetch();
} else $error = 'Произошла ошибка отображения страницы';

include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>

    <div class="row content">
        <div class="marg-sides-10">
            <? if (empty($error)): ?>
                <h3>Редактировать компанию</h3>
                <form action="edit.php?id=<?= $_GET['id'] ?>" method="post">
                    <div class="well well-lg">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="e_name">Название компании:</label>
                                    <input type="text" class="form-control" name="e_name" id="e_name"
                                           value="<?= $result['name'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="e_telephone">Номер телефона:</label>
                                    <input type="text" class="form-control" name="e_telephone" id="e_telephone"
                                           value="<?= $result['telephone'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="e_address">Адрес:</label>
                                    <textarea rows="1" class="form-control" name="e_address"
                                              id="e_address"><?= $result['address'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="e_fio">ФИО представителя:</label>
                                    <input type="text" class="form-control" name="e_fio" id="e_fio"
                                           value="<?= $result['representative'] ?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="e_description">Описание:</label>
                                    <textarea rows="12" class="form-control" name="e_description"
                                              id="e_description"><?= $result['description'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Сохранить компанию">
                    </div>
                </form>
            <? else : ?>
                <?= $error ?>
                <a href="list.php" class="btn btn-primary">Вернутся к списку</a>
            <? endif; ?>
        </div>
    </div>
    </div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>