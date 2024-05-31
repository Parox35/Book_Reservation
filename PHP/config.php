<?php
define('DB_SERVEUR', '');
define('DB_USERNAME', '');
define('DB_PASSWORD', '');
define('DB_NAME', '');

$conn = mysqli_connect(DB_SERVEUR, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($conn === false)
    die("ERROR: Could not connect" . mysqli_connect_error());

?>