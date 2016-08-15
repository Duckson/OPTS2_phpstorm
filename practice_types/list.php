<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Список типов практики';
$counter = 0;
$prep_str = '';

if (!empty($_POST['delete_id'])) {
    $prep = $sql->prepare('DELETE FROM practice_types WHERE id=:id');
    $prep->bindParam(':id', $_POST['delete_id'], PDO::PARAM_INT);
    $prep->execute();
    if ($sql->errorCode() != '00000') $delete_error = 'Произошла ошибка при удалении! Возможно, что эта запись уже где-то используется.';
    else $delete_success = 'Запись успешно удалена!';
}


$query = $sql->query('SELECT id, name FROM practice_types ORDER BY name ASC');
while ($row = $query->fetch()) {
    $result[] = $row;
}

$result_count = count($result);

include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>

<div class="row content marg-sides-10">
    <div class="marg-bottom-20">
        <? if (!empty($delete_error)): ?>
            <span class="text-danger"><?= $delete_error ?></span><br>
        <? endif; ?>
        <? if (!empty($delete_success)): ?>
            <span class="text-success"><?= $delete_success ?></span><br>
        <? endif; ?>
        <span class="h3 marg-right-20">Типы практики</span><a href="create.php" class="btn btn-success button-create">Добавить
            тип практики</a>
    </div>
    <div class="well well-md">
        <? if (!empty($result)): ?>
            <? foreach ($result as $row): ?>
                <?php $counter++ ?>
                <? if (($counter % 3) - 1 == 0): ?>
                    <?php $is_open = true ?>
                    <div class="row-radio">
                <? endif; ?>
                <div class="div-radio">
                    <?= $row['name'] ?>
                    <form class="form-glyph pull-right" method="post" action="list.php">
                        <a href="edit.php?id=<?= $row['id'] ?>" class="glyphicon glyphicon-pencil action-glyph"></a>
                        <button type="submit" name="delete_id" value="<?= $row['id'] ?>"
                                class="btn-glyph glyphicon glyphicon-remove action-glyph">
                    </form>
                </div>
                <? if (($counter % 3 == 0) || ($counter == $result_count)): ?>
                    <?php $is_open = false ?>
                    </div>
                <? endif; ?>
            <? endforeach; ?>
        <? endif; ?>
    </div>
</div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>


