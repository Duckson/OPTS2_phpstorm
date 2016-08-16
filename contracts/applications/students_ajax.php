<?php
include($_SERVER['DOCUMENT_ROOT'] . '/OPTS2/dependencies/session.php');

if(!empty($_GET['get_groups'])){
    $prep = $sql->prepare('SELECT name, id FROM student_groups WHERE faculty_id=:id');
    $prep->execute([
        ':id' => $_GET['get_groups']
    ]);
    while ($row = $prep->fetch()) {
        $result[] = $row;
    }
    echo json_encode($result);
}

if(!empty($_GET['get_students'])){
    $prep = $sql->prepare('SELECT name, login as id FROM students WHERE group_id=:id');
    $prep->execute([
        ':id' => $_GET['get_students']
    ]);
    while ($row = $prep->fetch()) {
        $result[] = $row;
    }
    echo json_encode($result);
}