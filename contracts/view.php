<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Просмотр контракта';
if (!empty($_GET['id'])) {
    $prep = $sql->prepare('SELECT contracts.start_date AS start_date,contracts.description AS description, companies.name AS company FROM contracts
                           LEFT JOIN companies ON company_id=companies.id WHERE contracts.id=:id');
    $prep->execute([
        ':id' => $_GET['id']
    ]);
    $result = $prep->fetch();

    $prep = $sql->prepare('SELECT applications.id AS id, applications.start_date AS start_date, applications.end_date AS end_date,
                           practice_types.name AS practice_type FROM applications LEFT JOIN practice_types ON practice_types.id=applications.practice_type_id
                           WHERE applications.contract_id=:id');
    $prep->execute([
        ':id' => $_GET['id']
    ]);
    while ($row =  $prep->fetch()) {
        $apps[] = $row;
        $query = $sql->query('SELECT students.name, students.login FROM students WHERE students.login IN (SELECT student_login from 111) LIMIT 4');
    }
} else $error = 'Произошла ошибка отображения страницы';

include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>

<div class="row content">
    <div class="marg-sides-10">
        <h3>Просмотр контракта</h3>
        <b>Компания:</b> <?= $result['company'] ?><br>
        <b>Дта заключения:</b> <?= $result['start_date'] ?><br>
        <b>Описание:</b> <?= $result['description'] ?><br>
        <br><span class="h3">Приложения к данному контракту</span><a href="applications/create.php"
                                                                     class="btn btn-success pull-right button-create">Добавить
            Приложение</a>
        <table class="table table-hover table-condensed table-bordered">
            <tr>
                <th>Дата начала практики</th>
                <th>Дата окончания практики</th>
                <th>Тип практики</th>
                <th>Студенты</th>
                <th class="glyph_td"></th>
            </tr>
            <tr>
                <td>13.11.2015</td>
                <td>02.02.2016</td>
                <td>Спа</td>
                <td>
                    <ul>
                        <li>Попкин Илья Васильев</li>
                        <li>НеПопкин НеИлья НеВасильев</li>
                        <li>НуПопкин НуИлья НуВасильев</li>
                    </ul>
                </td>
                <td class="glyph_td">
                    <a href="applications/edit.php" class="glyphicon glyphicon-pencil action-glyph"></a>
                    <a class="glyphicon glyphicon-remove action-glyph" onclick="alert('нинада')"></a>
                </td>
            </tr>
        </table>
    </div>
</div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>
