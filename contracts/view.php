<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Просмотр контракта';
if (!empty($_POST['delete_id'])) {
    $prep = $sql->prepare('DELETE FROM applications WHERE id=:id');
    $prep->bindParam(':id', $_POST['delete_id'], PDO::PARAM_INT);
    $prep->execute();
    if ($prep->errorCode() != '00000') $delete_error = 'Произошла ошибка при удалении! Возможно, что эта запись уже где-то используется.';
    else $delete_success = 'Запись успешно удалена!';
}

if (!empty($_GET['id'])) {
    $prep = $sql->prepare('SELECT contracts.start_date AS start_date,contracts.description AS description, companies.name AS company FROM contracts
                           LEFT JOIN companies ON company_id=companies.id WHERE contracts.id=:id');
    $prep->execute([
        ':id' => $_GET['id']
    ]);
    $contact = $prep->fetch();

    $prep = $sql->prepare('SELECT applications.id AS id, applications.start_date AS start_date, applications.end_date AS end_date,
                           practice_types.name AS practice_type FROM applications LEFT JOIN practice_types ON practice_types.id=applications.practice_type_id
                           WHERE applications.contract_id=:id');
    $prep->execute([
        ':id' => $_GET['id']
    ]);
    while ($row = $prep->fetch()) {
        $query = $sql->query('SELECT students.name FROM students WHERE students.login IN 
                              (SELECT student_login FROM student_app_link WHERE app_id=' . $row['id'] . ') LIMIT 4');
        while ($row2 = $query->fetch()) {
            $result[] = $row2;
        }
        $row['students'] = $result;
        $result = [];
        $apps[] = $row;
    }
} else $error = 'Произошла ошибка отображения страницы';

include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>

<div class="row content">
    <div class="marg-sides-10">
        <? if (empty($error)): ?>
            <h3>Просмотр контракта</h3>
            <b>Компания:</b> <?= $contact['company'] ?><br>
            <b>Дата заключения:</b> <?= $contact['start_date'] ?><br>
            <b>Описание:</b> <?= $contact['description'] ?><br>
            <br><span class="h3">Приложения к данному контракту</span><a
                href="applications/create.php?contract_id=<?= $_GET['id'] ?>"
                class="btn btn-success pull-right button-create">Добавить
                Приложение</a><br>
            <? if (!empty($delete_error)): ?>
                <span class="text-danger"><?= $delete_error ?></span><br>
            <? endif; ?>
            <? if (!empty($delete_success)): ?>
                <span class="text-success"><?= $delete_success ?></span><br>
            <? endif; ?>
            <table class="table table-hover table-condensed table-bordered">
                <tr>
                    <th>Дата начала практики</th>
                    <th>Дата окончания практики</th>
                    <th>Тип практики</th>
                    <th>Студенты(показывается не более 4)</th>
                    <th class="glyph_td"></th>
                </tr>
                <? if (!empty($apps)): ?>
                    <? foreach ($apps as $app): ?>
                        <tr>
                            <td><?= $app['start_date'] ?></td>
                            <td><?= $app['end_date'] ?></td>
                            <td><?= $app['practice_type'] ?></td>
                            <td>
                                <? if (!empty($app['students'])): ?>
                                    <ul>
                                        <? foreach ($app['students'] as $student): ?>
                                            <li><?= $student['name'] ?></li>
                                        <? endforeach; ?>
                                    </ul>
                                <? endif; ?>
                            </td>
                            <td class="glyph_td">
                                <form class="form-glyph" method="post" action="view.php?<?= http_build_query($_GET) ?>">
                                    <a href="applications/edit.php?id=<?= $app['id'] ?>&contract_id=<?= $_GET['id'] ?>"
                                       class="glyphicon glyphicon-pencil action-glyph"></a>
                                    <button type="submit" name="delete_id" value="<?= $app['id'] ?>"
                                            class="btn-glyph glyphicon glyphicon-remove action-glyph">
                                </form>
                            </td>
                        </tr>
                    <? endforeach; ?>
                <? endif; ?>
            </table>
        <? else : ?>
            <?= $error ?>
            <a href="list.php" class="btn btn-primary">Вернутся к списку</a>
        <? endif; ?>
    </div>
</div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>
