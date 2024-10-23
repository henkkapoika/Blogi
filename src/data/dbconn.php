<?php
$host = "mysql_db";
$dbname = "blog_db";
$user = "root";
$password = "root";

$mysqli = new mysqli($host, $user, $password, $dbname);

if ($mysqli->connect_error) {
    echo "Sorry, this website is experiencing problems.";
    echo 'Error: ' . $mysqli->connect_error . '\n';
    exit;
}
echo "Success: A proper connection to MySQL was made! The blog_db database is great." . PHP_EOL;


?>