<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Создание приложения';
$counter = 0;

if (!empty($_GET['contract_id'])) {
    if (!empty($_POST) && (empty($_POST['c_start_date'] || empty($_POST['c_end_date']) || empty($_POST['c_practice_type']) || empty($_SESSION['create_students']))))
        $error = 'Не правильно заполнена форма';
    elseif (!empty($_POST)) {
        $prep = $sql->prepare('INSERT INTO applications (contract_id, start_date, end_date, practice_type_id) VALUES (?, ?, ?, ?)');
        $prep->execute([$_GET['contract_id'], $_POST['c_start_date'], $_POST['c_end_date'], $_POST['c_practice_type']]);
        $id = $sql->lastInsertId();
        foreach ($_POST['students'] as $student) {
            $prep = $sql->prepare('INSERT INTO student_app_link (student_login, app_id) VALUES (?, ?)');
            $prep->execute([$student, $id]);
        }
        unset($_SESSION['create_students']);
        header('Location: /OPTS2/contracts/view.php?id=' . $_GET['contract_id']);
    }

    $query = $sql->query('SELECT id, name FROM practice_types');
    while ($row = $query->fetch()) {
        $practice_types[] = $row;
    }

    $query = $sql->query('SELECT id, name FROM faculties');
    while ($row = $query->fetch()) {
        $faculties[] = $row;
    }
} else $critical_error = 'Произошла ошибка отображения страницы';

include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>

    <div class="row content">
        <script src="/OPTS2/dependencies/student_select.js"></script>
        <div id="div-app-create" class="marg-sides-10">
            <? if (empty($critical_error)): ?>
                <h3>Создать приложение</h3>
                <? if (isset($error)): ?>
                    <span class="form-error"><?= $error ?></span>
                <? endif ?>
                <form action="create.php?contract_id=<?= $_GET['contract_id'] ?>" method="post">
                    <div class="well well-lg">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="c_start_date">Дата начала практики:</label>
                                    <input type="date" class="form-control" name="c_start_date" id="c_start_date"
                                           value="<?= $_POST['c_start_date'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="c_end_date">Дата окончания практики:</label>
                                    <input type="date" class="form-control" name="c_end_date" id="c_end_date"
                                           value="<?= $_POST['c_end_date'] ?>">
                                </div>
                                <label for="c_practice_type">Тип практики:</label>
                                <select class="form-control" name="c_practice_type" id="c_practice_type">
                                    <? foreach ($practice_types as $row): ?>
                                        <option <?= ($_POST['c_practice_type'] == $row['id']) ? 'selected' : '' ?>
                                            value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                    <? endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <div class="marg-bottom-20">
                                    <span class="h3">Добавить студента</span>
                                </div>
                                <div class="flex-column">
                                    <div class="padding-none form-group col-sm-6">
                                        <select class="form-control" id="faculty-select">
                                            <option value="0">Выберите факультет</option>
                                            <? foreach ($faculties as $row): ?>
                                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                            <? endforeach; ?>
                                        </select>
                                    </div>
                                    <div style="display: none" class="padding-none form-group col-sm-6"
                                         id="group-select-div">
                                        <select class="form-control" id="group-select">
                                            <option value="0">Выберите группу</option>
                                        </select>
                                    </div>
                                    <div style="display: none" class="flex-row" id="student-select-div">
                                        <div class="padding-none form-group col-sm-6">
                                            <select class="form-control" id="student-select">
                                                <option value="0">Выберите студента</option>
                                            </select>
                                        </div>
                                        <span class="btn btn-primary" id="student-button">Добавить студента</span>
                                    </div>
                                    <br>
                                </div>
                                <div class="marg-bottom-20">
                                    <span class="h3">Список студентов</span>
                                </div>
                                <table id="students-table" class="table table-hover table-condensed table-bordered">
                                    <tr>
                                        <th>ФИО</th>
                                        <th>Группа</th>
                                        <th class="glyph_td"></th>
                                    </tr>

                                </table>
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Создать приложение">
                </form>
            <? else: ?>
                <?= $critical_error ?>
                <a href="/OPTS2/contracts/list.php" class="btn btn-primary">Вернутся к списку контрактов</a>
            <? endif; ?>
        </div>
    </div>
    </div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>