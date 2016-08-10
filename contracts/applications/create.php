<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Создание приложения';

if (!empty($_GET['delete_id'])) {
    unset($_SESSION['create_students'][array_search($_GET['delete_id'], $_SESSION['create_students'])]);
    unset($_GET['delete_id']);
    header('Location: /OPTS2/contracts/applications/create.php');
}

if (!empty($_POST) && (empty($_POST['c_start_date'] || empty($_POST['c_end_date']) || empty($_POST['c_practice_type']))))
    $error = 'Не правильно заполнена форма';
elseif (!empty($_POST)) {
    //WIP
}
if (!empty($_SESSION['create_students'])) {
    $prep = $sql->prepare('SELECT students.login AS id, students.name AS name, student_groups.name AS student_group
                               FROM students LEFT JOIN student_groups ON students.group_id = student_groups.id
                               WHERE students.login IN (' . join(',', array_fill(0, count($_SESSION['create_students']), '?')) . ')');
    $prep->execute(array_values($_SESSION['create_students']));
    while ($row = $prep->fetch()) {
        $students[] = $row;
    }
}

include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>

    <div class="row content">
        <div class="marg-sides-10">
            <h3>Создать приложение</h3>
            <? if (isset($error)): ?>
                <span class="form-error"><?= $error ?></span>
            <? endif ?>
            <form action="create.php" method="post">
                <div class="well well-lg">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="c_start_date">Дата начала практики:</label>
                                <input type="date" class="form-control" name="c_start_date" id="c_start_date">
                            </div>
                            <div class="form-group">
                                <label for="c_end_date">Дата окончания практики:</label>
                                <input type="date" class="form-control" name="c_end_date" id="c_end_date">
                            </div>
                            <label for="c_practice_type">Тип практики:</label>
                            <select class="form-control" name="c_practice_type" id="c_practice_type">
                                <option value="spa">Спа</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <span class="h3">Студенты</span><a href="students.php?action=create"
                                                               class="btn btn-success pull-right button-create">Добавить
                                Студента</a>
                            <table class="table table-hover table-condensed table-bordered">
                                <tr>
                                    <th>ФИО</th>
                                    <th>Группа</th>
                                    <th class="glyph_td"></th>
                                </tr>
                                <? if (!empty($students)): ?>
                                    <? foreach ($students as $row): ?>
                                        <tr>
                                            <td><?= $row['name'] ?></td>
                                            <td><?= $row['student_group'] ?></td>
                                            <td class="glyph_td">
                                                <a href="create.php?delete_id=<?= $row['id'] ?>"
                                                   class="glyphicon glyphicon-remove action-glyph"></a>
                                            </td>
                                        </tr>
                                        <input type="hidden" name="students[]" value="<?= $row['id'] ?>">
                                    <? endforeach; ?>
                                <? endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
                <input type="submit" disabled class="btn btn-primary" value="Создать приложение"> <!-- WIP -->
            </form>
        </div>
    </div>
    </div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>