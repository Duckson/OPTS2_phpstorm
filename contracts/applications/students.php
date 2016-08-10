<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Выбор студентов';
$step_one = $step_two = $step_tree = false;
$counter = 0;

function getFaculty(){
    global $sql;
    $prep = $sql->prepare('SELECT name FROM faculties WHERE id=:id');
    $prep->execute([
        ':id' => $_GET['faculty']
    ]);
    return $prep->fetch()[0];
}

if (empty($_GET['action']) || (($_GET['action'] == 'edit') && empty($_GET['id']))) $error = 'Ошибка отображения страницы';
else {
    if (!empty($_GET['students'])) {
        if (empty($_SESSION['create_students'])) $_SESSION['create_students'] = [];
        foreach ($_GET['students'] as $student){
            if(!in_array($student, $_SESSION['create_students'])){
                $_SESSION['create_students'][] = $student;
            }
        }
        header('Location: /OPTS2/contracts/applications/create.php');
    } elseif (!empty($_GET['group'])) {
        $step_tree = true;

        $prep = $sql->prepare('SELECT login as id, name FROM students WHERE group_id=:id');
        $prep->execute([
            ':id' => $_GET['group']
        ]);
        while ($row = $prep->fetch()) {
            $result[] = $row;
        }

        $faculty = getFaculty();

        $prep = $sql->prepare('SELECT name FROM student_groups WHERE id=:id');
        $prep->execute([
            ':id' => $_GET['group']
        ]);
        $group = $prep->fetch()[0];
    } elseif (!empty($_GET['faculty'])) {
        $step_two = true;

        $prep = $sql->prepare('SELECT id, name FROM student_groups WHERE faculty_id=:id');
        $prep->execute([
            ':id' => $_GET['faculty']
        ]);
        while ($row = $prep->fetch()) {
            $result[] = $row;
        }

        $faculty = getFaculty();
    } else {
        $step_one = true;

        $query = $sql->query('SELECT id, name FROM faculties');
        while ($row = $query->fetch()) {
            $result[] = $row;
        }
    }
}

$result_count = count($result);
include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>

<? if (empty($error)): ?>
    <script src="/OPTS2/dependencies/form_button.js"></script>
    <div class="row content marg-sides-10">
        <h2>Добавление студентов к приложению</h2>
        <? if ($step_one === true): ?>
            <h3>Шаг 1: Выбор факультета</h3>
            <div class="well well-lg">
                <form id="form" method="get" action="students.php">
                    <?= ($_GET['action'] == 'edit') ? '<input type="hidden" name="id" value="' . $_GET['id'] . '">' : '' ?>
                    <? if (!empty($result)): ?> <!-- Данная конструкция - кондидат на вынос, займусь позже -->
                        <? foreach ($result as $row): ?>
                            <?php $counter++ ?>
                            <? if (($counter % 3) - 1 == 0): ?>
                                <?php $is_open = true ?>
                                <div class="row-radio">
                            <? endif; ?>
                            <div class="div-radio" id="<?= $row['id'] ?>">
                                <input type="radio" class="radio" name="faculty" value="<?= $row['id'] ?>">
                                <?= $row['name'] ?>
                            </div>
                            <? if (($counter % 3 == 0) || ($counter == $result_count)): ?>
                                <?php $is_open = false ?>
                                </div>
                            <? endif; ?>
                        <? endforeach; ?>
                    <? else: ?>
                        <span class="form-error">Данные не найдены!</span><br>
                    <? endif; ?>
                    <br>
                    <button type="submit" id="submit" class="btn btn-primary">Выбрать факультет</button>
                    <input type="hidden" name="action" value="<?= $_GET['action'] ?>">
                </form>
            </div>
        <? endif; ?>
        <? if ($step_two === true): ?>
            <h3>Шаг 2: Выбор группы</h3>
            <b class="text-muted"><?= $faculty ?> <a href="students.php?action=<?= $_GET['action'] ?>"
                                                     class="glyphicon glyphicon-remove action-glyph"></a></b><br>
            <div class="well well-lg">
                <form id="form" method="get" action="students.php">
                    <?= ($_GET['action'] == 'edit') ? '<input type="hidden" name="id" value="' . $_GET['id'] . '">' : '' ?>
                    <? if (!empty($result)): ?>
                        <? foreach ($result as $row): ?>
                            <?php $counter++ ?>
                            <? if (($counter % 3) - 1 == 0): ?>
                                <?php $is_open = true ?>
                                <div class="row-radio">
                            <? endif; ?>
                            <div class="div-radio" id="<?= $row['id'] ?>">
                                <input type="radio" class="radio" name="group" value="<?= $row['id'] ?>">
                                <?= $row['name'] ?>
                            </div>
                            <? if (($counter % 3 == 0) || ($counter == $result_count)): ?>
                                <?php $is_open = false ?>
                                </div>
                            <? endif; ?>
                        <? endforeach; ?>
                    <? else: ?>
                        <span class="form-error">Данные не найдены!</span><br>
                    <? endif; ?>
                    <br>
                    <button type="submit" id="submit" class="btn btn-primary">Выбрать группу</button>
                    <input type="hidden" name="action" value="<?= $_GET['action'] ?>">
                    <input type="hidden" name="faculty" value="<?= $_GET['faculty'] ?>">
                </form>
            </div>
        <? endif; ?>
        <? if ($step_tree === true): ?>
            <h3>Шаг 3: Выбор студента(-ов)</h3>
            <b class="text-muted"><?= $faculty ?> <a href="students.php?action=<?= $_GET['action'] ?>"
                                                     class="glyphicon glyphicon-remove action-glyph"
                                                     data-toggle="tooltip" title="Отменить выбор факультета"></a> > </b>
            <b class="text-muted"><?= $group ?> <a href="students.php?action=<?= $_GET['action'] ?>&faculty=<?= $_GET['faculty'] ?>"
                                                   class="glyphicon glyphicon-remove action-glyph"
                                                   data-toggle="tooltip" title="Отменить выбор группы"></a></b><br>
            <div class="well well-lg">
                <form id="form" method="get" action="students.php">
                    <?= ($_GET['action'] == 'edit') ? '<input type="hidden" name="id" value="' . $_GET['id'] . '">' : '' ?>
                    <? if (!empty($result)): ?>
                        <? foreach ($result as $row): ?>
                            <?php $counter++ ?>
                            <? if (($counter % 3) - 1 == 0): ?>
                                <?php $is_open = true ?>
                                <div class="row-radio">
                            <? endif; ?>
                            <div class="div-radio" id="<?= $row['id'] ?>">
                                <input type="checkbox" class="radio" name="students[]" value="<?= $row['id'] ?>">
                                <?= $row['name'] ?>
                            </div>
                            <? if (($counter % 3 == 0) || ($counter == $result_count)): ?>
                                <?php $is_open = false ?>
                                </div>
                            <? endif; ?>
                        <? endforeach; ?>
                    <? else: ?>
                        <span class="form-error">Данные не найдены!</span><br>
                    <? endif; ?>
                    <br>
                    <button type="submit" id="submit" class="btn btn-primary">Выбрать студента(-ов)</button>
                    <input type="hidden" name="action" value="<?= $_GET['action'] ?>">
                </form>
            </div>
        <? endif; ?>
    </div>
<? else : ?>
    <?= $error ?>
    <a href="OPTS2/contracts/list.php" class="btn btn-primary">Вернутся к списку контрактов</a>
<? endif; ?>
</div>
