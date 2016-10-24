<?php
//db details
$dbHost = 'localhost';
$dbUsername = 'ecohouse';
$dbPassword = 'MlkmZIceCASc3S';
$dbName = 'ecohouse';

//Connect and select the database
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);


if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>