<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');


$prep = $sql->prepare('SELECT * FROM departments WHERE name LIKE :name');
while ($row = $prep->fetch()) {
    $result[] = $row;
}
var_dump($sql->errorInfo());