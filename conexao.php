<?php

$host = "localhost";
$user = "root";
$dbname = "moduloauditoria";
$pass = "";
$post = 3306;

$conn = new PDO("mysql:host=$host;port=$post;dbname=" . $dbname, $user, $pass);