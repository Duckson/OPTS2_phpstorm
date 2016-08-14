<?php
session_start();
if ($_SESSION['role'] != 1) header('Location: /OPTS2/index.php');
$sql = new PDO("mysql:host=mysql;dbname=duckson_test;charset=utf8", 'root', 'qxevtnu7');
$sql->query('SET NAMES utf8');