<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=mydb", "root", "");
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}

?>
