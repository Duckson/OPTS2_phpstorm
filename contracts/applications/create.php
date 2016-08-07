<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Создание приложения';
include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>

    <div class="row content">
        <div class="marg-sides-10">
            <h3>Создать приложение</h3>
            <form action="../view.php" method="post">
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
                            <span class="h3">Студенты</span><a href="students.php"
                                                               class="btn btn-success pull-right button-create">Добавить
                                Студента</a>
                            <table class="table table-hover table-condensed table-bordered">
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
        </div>
    </div>
    </div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>