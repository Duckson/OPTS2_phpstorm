<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
include ($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/pagination.php');
$title = 'ОПТС - Список компаний';
$pagination = new Pagination(3, $_GET, $_SERVER['PHP_SELF']);

if (!empty($_POST['delete_id'])) {
    $prep = $sql->prepare('DELETE FROM companies WHERE id=:id');
    $prep->bindParam(':id', $_POST['delete_id'], PDO::PARAM_INT);
    $prep->execute();
    if ($prep->errorCode() != '00000') $delete_error = 'Произошла ошибка при удалении! Возможно, что эта запись уже где-то используется.';
    else $delete_success = 'Запись успешно удалена!';
}

if (!empty($_GET['name'])) {
    $where[] = 'name LIKE :name';
    $prep_names[] = ':name';
    $prep_types[] = PDO::PARAM_STR;
    $prep_vals[] = &$_GET['name'];
}
if (!empty($_GET['telephone'])) {
    $where[] = 'telephone LIKE :telephone';
    $prep_names[] = ':telephone';
    $prep_types[] = PDO::PARAM_STR;
    $prep_vals[] = &$_GET['telephone'];
}

$limit_str = $pagination->getLimitStr();
if (empty($where)) {
    $query = $sql->query('SELECT id, telephone, name FROM companies' . $limit_str);
    $pagination->setItemsCount(intval($sql->query('SELECT count(*) FROM companies')->fetch()[0]));
    while ($row = $query->fetch()) {
        $result[] = $row;
    }

} else {
    $where_str = '';
    $where_str = ' WHERE ' . join(' AND ', $where);
    $prep = $sql->prepare('SELECT id, telephone, name FROM companies' . $where_str . $limit_str);
    foreach ($prep_vals as $key=>$value){
        $prep->bindParam($prep_names[$key], $value, $prep_types[$key]);
    }
    $prep->execute();
    while ($row =  $prep->fetch()) {
        $result[] = $row;
    }
    $prep = $sql->prepare('SELECT count(*) FROM companies' . $where_str);
    foreach ($prep_vals as $key=>$value){
        $prep->bindParam($prep_names[$key], $value, $prep_types[$key]);
    }
    $prep->execute();
    $pagination->setItemsCount($prep->fetch()[0]);
}

include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>

<div class="row content">
    <div class="col-sm-3">
        <div class="well well-sm">
            <span class="h3">Фильтр</span>
            <form action="list.php" method="get">
                <div class="form-group">
                    <label for="name">Название:</label>
                    <input type="text" class="form-control" name="name" id="name" value="<?= $_GET['name'] ?>">
                </div>
                <div class="form-group">
                    <label for="telephone">Номер телефона:</label>
                    <input type="text" class="form-control" name="telephone" id="telephone"
                           value="<?= $_GET['telephone'] ?>">
                </div>
                <input class="btn btn-primary" type="submit" value="Применить">
            </form>
            <form action="list.php" method="get">
                <input class="btn btn-warning" type="submit" value="Очистить">
            </form>
        </div>
    </div>
    <div class="col-sm-9">
        <? if (!empty($delete_error)): ?>
            <span class="text-danger"><?= $delete_error ?></span><br>
        <? endif; ?>
        <? if (!empty($delete_success)): ?>
            <span class="text-success"><?= $delete_success ?></span><br>
        <? endif; ?>
        <span class="h3">Компании</span><a href="create.php" class="btn btn-success pull-right button-create">Добавить
            компанию</a>
        <table class="table table-hover table-condensed table-bordered">
            <tr>
                <th style="width: 70%">Название</th>
                <th>Номер телефона</th>
                <th class="glyph_td"></th>
            </tr>
            <? if ($result): ?>
                <? foreach ($result as $row): ?>
                    <tr>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['telephone'] ?></td>
                        <td class="glyph_td">
                            <form class="form-glyph" method="post" action="list.php?<?= http_build_query($_GET) ?>">
                                <a href="edit.php?id=<?= $row['id'] ?>"
                                   class="glyphicon glyphicon-pencil action-glyph"></a>
                                <a href="view.php?id=<?= $row['id'] ?>"
                                   class="glyphicon glyphicon-resize-full action-glyph"></a>
                                <button type="submit" name="delete_id" value="<?= $row['id'] ?>" class="btn-glyph glyphicon glyphicon-remove action-glyph">
                            </form>
                        </td>
                    </tr>
                <? endforeach ?>
            <? endif ?>
        </table>
        <?= $pagination->getPagesStr() ?>
    </div>
</div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>
