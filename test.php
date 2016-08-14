<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');

$prep = $sql->query("INSERT INTO duckson_test.departments (name) VALUES ('test')");
var_dump($sql->errorInfo());

$prep = $sql->query('SELECT * FROM duckson_test.departments');
while ($row = $prep->fetch()) {
    $result[] = $row;
}
var_dump($sql->errorInfo());