<?php
session_start();
if ($_SESSION['role'] != 1) header('Location: /OPTS2/index.php');
$sql = new PDO("mysql:host=localhost;dbname=opts;charset=utf8", 'root', 'root');