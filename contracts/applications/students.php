<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
$title = 'ОПТС - Выбор студентов';
$step_one = $step_two = $step_tree = false;
$counter = 0;

$result_count = 0;
include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>

<div class="row content">
    <? if ($step_one == true): ?>
        <div class="well well-lg">
            <form method="get" action="students.php">
                <div class="form-group">
                    <label for="company-name">Название:</label>
                    <input type="text" name="name" id="company-name" placeholder="Введите название факультета"
                           value="<?= $_GET['name'] ?>">
                    <button type="submit" class="glyphicon glyphicon-search btn btn-success btn-sm"></button>
                    '<input type="hidden" name="action" value="<?= $_GET['action'] ?>">
                    <?= ($_GET['action'] == 'edit') ? '<input type="hidden" name="id" value="' . $_GET['id'] . '">' : '' ?>
                </div>
            </form>
            <form id="form" method="get" action="students.php">
                <?= ($_GET['action'] == 'edit') ? '<input type="hidden" name="id" value="' . $_GET['id'] . '">' : '' ?>
                <? foreach ($result as $row): ?>
                    <?php $counter++ ?>
                    <? if (($counter % 3) - 1 == 0): ?>
                        <?php $is_open = true ?>
                        <div class="row-radio">
                    <? endif; ?>
                    <div class="div-radio" id="<?= $row['id'] ?>"><input type="radio" class="radio" name="company"
                                                                         value="<?= $row['id'] ?>"
                        > <?= $row['name'] ?></div>
                    <? if (($counter % 3 == 0) || ($counter == $result_count)): ?>
                        <?php $is_open = false ?>
                        </div>
                    <? endif; ?>
                <? endforeach; ?>
                <br>
                <button type="submit" id="submit" class="btn btn-primary">Выбрать компанию</button>
            </form>
        </div>
    <? endif; ?>
    <? if ($step_two == true): ?>
    <? endif; ?>
    <? if ($step_tree == true): ?>
    <? endif; ?>
</div>
</div>
