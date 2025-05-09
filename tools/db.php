<?php
function getDBConnection(){

$host = 'nozomi.proxy.rlwy.net';
$port = 18630;
$user = 'root';
$password = 'EjrmcdKSZqZznVGpwZcFbvwUFhGqfurW';
$dbname = 'railway';

// Create connection
$connection = new mysqli($host, $user, $password, $dbname, $port);

if($connection->connect_error){
    die("Error: Failed to connect to MySQL. ".$connection->connect_error);
}

return $connection;
}

?>
