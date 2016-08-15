<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Список контрактов';

if (!empty($_POST['delete_id'])) {
    $prep = $sql->prepare('DELETE FROM contracts WHERE id=?');
    $prep->bindParam(':id', $_POST['delete_id'], PDO::PARAM_INT);
    $prep->execute();
    if ($prep->errorCode() != '00000') $delete_error = 'Произошла ошибка при удалении! Возможно, что эта запись уже где-то используется.';
    else $delete_success = 'Запись успешно удалена!';
}

if (!empty($_GET['company'])) {
    $where[] = 'companies.name LIKE :company';
    $prep_names[] = ':company';
    $prep_types[] = PDO::PARAM_STR;
    $prep_vals[] = &$_GET['company'];
}
if (!empty($_GET['start_date'])) {
    $start_date = '%' . $_GET['start_date'] . '%';
    $where[] = 'start_date LIKE :start_date';
    $prep_names[] = ':start_date';
    $prep_types[] = PDO::PARAM_STR;
    $prep_vals[] = &$start_date;
}

if (empty($where)) {
    $query = $sql->query('SELECT contracts.id AS id, contracts.start_date AS start_date, companies.name AS company FROM contracts
                      LEFT JOIN companies ON company_id=companies.id');
    while ($row = $query->fetch()) {
        $result[] = $row;
    }
} else {
    $where_str = '';
    $where_str = ' WHERE ' . join(' AND ', $where);
    $prep = $sql->prepare('SELECT contracts.id AS id, contracts.start_date AS start_date, companies.name AS company FROM contracts
    LEFT JOIN companies ON company_id=companies.id' . $where_str);
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
                    <label for="company">Компания:</label>
                    <input type="text" class="form-control" name="company" id="company" value="<?= $_GET['company'] ?>">
                </div>
                <div class="form-group">
                    <label for="start_date">Год заключения:</label>
                    <input type="text" class="form-control" name="start_date" id="start_date"
                           value="<?= $_GET['start_date'] ?>">
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
        <span class="h3">Контракты</span><a href="company_select.php?action=create" class="btn btn-success pull-right button-create">Добавить
            Контракт</a>
        <table class="table table-hover table-condensed table-bordered">
            <tr>
                <th>Компания</th>
                <th>Дата заключения</th>
                <th class="glyph_td"></th>
            </tr>
            <? if ($result): ?>
                <? foreach ($result as $row): ?>
                    <tr>
                        <td><?= $row['company'] ?></td>
                        <td><?= $row['start_date'] ?></td>
                        <td class="glyph_td">
                            <form class="form-glyph" method="post" action="list.php?<?= http_build_query($_GET) ?>">
                                <a href="company_select.php?id=<?= $row['id'] ?>&action=edit" class="glyphicon glyphicon-pencil action-glyph"></a>
                                <a href="view.php?id=<?= $row['id'] ?>"
                                   class="glyphicon glyphicon-resize-full action-glyph"></a>
                                <button type="submit" name="delete_id" value="<?= $row['id'] ?>" class="btn-glyph glyphicon glyphicon-remove action-glyph">
                            </form>
                        </td>
                    </tr>
                <? endforeach ?>
            <? endif; ?>
        </table>
    </div>
</div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>
