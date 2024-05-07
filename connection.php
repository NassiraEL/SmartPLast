<?php
try {
    $db = new PDO("mysql:host=localhost:3307;dbname=coolplast", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>