<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');
include ($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/pagination.php');
$title = 'ОПТС - Список компаний';
$pagination = new Pagination(3, $_GET, $_SERVER['PHP_SELF']);

if(empty($_GET['action']) || (($_GET['action'] == 'edit') && empty($_GET['id']))) $error = 'Ошибка отображения страницы';

$page = (!empty($_GET['page'])) ? $_GET['page'] : 1;
$counter = 0;

if (!empty($_GET['name'])) {
    $where[] = 'name LIKE :name';
    $prep_names[] = ':name';
    $prep_types[] = PDO::PARAM_STR;
    $prep_vals[] = &$_GET['name'];
}

$limit_str = $pagination->getLimitStr();
if (empty($where)) {
    $query = $sql->query('SELECT id, name FROM companies' . $limit_str);
    $pagination->setItemsCount(intval($sql->query('SELECT count(*) FROM companies')->fetch()[0]));
    while ($row = $query->fetch()) {
        $result[] = $row;
    }

} else {
    $where_str = '';
    $where_str = ' WHERE ' . join(' AND ', $where);
    $prep = $sql->prepare('SELECT id, name FROM companies' . $where_str . $limit_str);
    foreach ($prep_vals as $key => $value) {
        $prep->bindParam($prep_names[$key], $value, $prep_types[$key]);
    }
    $prep->execute();
    while ($row = $prep->fetch()) {
        $result[] = $row;
    }
    $prep = $sql->prepare('SELECT count(*) FROM companies' . $where_str);
    foreach ($prep_vals as $key => $value) {
        $prep->bindParam($prep_names[$key], $value, $prep_types[$key]);
    }
    $prep->execute();
    $pagination->setItemsCount($prep->fetch()[0]);
}
$result_count = count($result);

include $_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/header.php';
?>
<? if (empty($error)): ?>
<script src="form_button.js"></script>
<h2><?= ($_GET['action'] == 'create')? 'Создание': 'Редактирование' ?> контракта</h2>
<h3>Шаг 1: выбор компании</h3>
<div class="well well-lg">
    <form method="get" action="company_select.php">
        <div class="form-group">
            <label for="company-name">Название:</label>
            <input type="text" name="name" id="company-name" placeholder="Введите имя компании" value="<?= $_GET['name'] ?>">
            <button type="submit" class="glyphicon glyphicon-search btn btn-success btn-sm"></button>
            '<input type="hidden" name="action" value="<?= $_GET['action'] ?>">
            <?= ($_GET['action'] == 'edit')? '<input type="hidden" name="id" value="' . $_GET['id'] . '">': '' ?>
        </div>
    </form>
    <form id="form" method="get" action="<?= ($_GET['action'] == 'create')? 'create.php': 'edit.php' ?>">
        <?= ($_GET['action'] == 'edit')? '<input type="hidden" name="id" value="' . $_GET['id'] . '">': '' ?>
        <? foreach ($result as $row): ?>
            <?php $counter++ ?>
            <? if (($counter % 3) - 1 == 0): ?>
                <?php $is_open = true ?>
                <div class="row-radio">
            <? endif; ?>
            <div class="div-radio" id="<?= $row['id'] ?>"><input type="radio" class="radio"  name="company" value="<?= $row['id'] ?>"
                                         > <?= $row['name'] ?></div>
            <? if (($counter % 3 == 0) || ($counter == $result_count)): ?>
                <?php $is_open = false ?>
                </div>
            <? endif; ?>
        <? endforeach; ?>
    <?= $pagination->getPagesStr() ?>

    <br>
        <button type="submit" id="submit" class="btn btn-primary">Выбрать компанию</button>
    </form>
</div>
<? else : ?>
    <?= $error ?>
    <a href="list.php" class="btn btn-primary">Вернутся к списку</a>
<? endif; ?>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/footer.php') ?>
