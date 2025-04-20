<?php
$host = "sql12.freesqldatabase.com";    
$dbname = "sql12774318";                 
$username = "sql12774318";               
$password = "NGdkBa85ej";  
$port = 3306;     

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
