<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Список типов практики';
$prep_str = '';

if(!empty($_POST['delete_id'])){
    $prep = $sql->prepare('DELETE FROM practice_types WHERE id=?');
    $prep->bindParam(':id', $_POST['delete_id'], PDO::PARAM_INT);
    $prep->execute();
    if($sql->errorCode() != '00000') $delete_error = 'Произошла ошибка при удалении! Возможно, что эта запись уже где-то используется.';
    else $delete_success = 'Запись успешно удалена!';
}

if (!empty($_GET['name'])) {
    $where[] = 'name LIKE :name';
    $prep_names[] = ':name';
    $prep_types[] = PDO::PARAM_STR;
    $prep_vals[] = &$_GET['name'];
}

if (empty($where)) {
    $query = $sql->query('SELECT id, name FROM practice_types');
    while ($row = $query->fetch()) {
        $result[] = $row;
    }
} else {
    $where_str = '';
    $where_str = ' WHERE ' . join(' AND ', $where);
    $prep = $sql->prepare('SELECT id, name FROM practice_types' . $where_str);
    foreach ($prep_vals as $key=>$value){
        $prep->bindParam($prep_names[$key], $value, $prep_types[$key]);
    }
    $prep->execute();
    while ($row =  $prep->fetch()) {
        $result[] = $row;
    }
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
                <input class="btn btn-primary" type="submit" value="Применить">
            </form>
            <form action="list.php" method="get">
                <input class="btn btn-warning" type="submit" value="Очистить">
            </form>
        </div>
    </div>
    <div class="col-sm-9">
        <? if(!empty($delete_error)): ?>
            <span class="text-danger"><?= $delete_error ?></span><br>
        <?endif;?>
        <? if(!empty($delete_success)): ?>
            <span class="text-success"><?= $delete_success ?></span><br>
        <?endif;?>
        <span class="h3">Типы практики</span><a href="create.php" class="btn btn-success pull-right button-create">Добавить
            тип практики</a>
        <table class="table table-hover table-condensed table-bordered">
            <tr>
                <th>Название</th>
                <th class="glyph_td"></th>
            </tr>
            <? foreach ($result as $row) : ?>
                <tr>
                    <td><?= $row['name'] ?></td>
                    <td class="glyph_td">
                        <form class="form-glyph" method="post" action="list.php?<?= http_build_query($_GET) ?>">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="glyphicon glyphicon-pencil action-glyph"></a>
                            <button type="submit" name="delete_id" value="<?= $row['id'] ?>" class="btn-glyph glyphicon glyphicon-remove action-glyph">
                        </form>
                    </td>
                </tr>
            <? endforeach; ?>
        </table>
    </div>
</div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>
