<?php
function getDBConnection(){

$host = 'sql312.infinityfree.com';
$username = 'if0_38929999';  
$password = 'faithfaith19';      
$dbname = 'if0_38929999_faiths';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

return $connection;
}

?>
