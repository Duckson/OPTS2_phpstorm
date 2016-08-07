<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Редактирование приложения';
include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>

    <div class="row content">
        <div class="marg-sides-10">
            <h3>Редактировать приложение</h3>
            <form action="../view.php" method="post">
                <div class="well well-lg">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="e_start_date">Дата начала практики:</label>
                                <input type="date" class="form-control" name="e_start_date" value="2015-11-13"
                                       id="e_start_date">
                            </div>
                            <div class="form-group">
                                <label for="e_end_date">Дата окончания практики:</label>
                                <input type="date" class="form-control" name="e_end_date" value="2016-02-02"
                                       id="e_end_date">
                            </div>
                            <label for="e_practice_type">Тип практики:</label>
                            <select class="form-control" name="e_practice_type" id="e_practice_type">
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
                                    <th>Кафедра</th>
                                    <th class="glyph_td"></th>
                                </tr>
                                <tr>
                                    <td>Попкин Илья Васильев</td>
                                    <td>П-23</td>
                                    <td>ПрИТ</td>
                                    <td class="glyph_td">
                                        <a class="glyphicon glyphicon-remove action-glyph"
                                           onclick="alert('нинада')"></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-primary" value="Сохранить приложение">
            </form>
        </div>
    </div>
    </div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>