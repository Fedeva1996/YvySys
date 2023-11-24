<?php
// Connection data
$hostname = 'localhost';
$dbname   = 'yvysys';
$username = 'postgres';
$password = 'Maitei.pg96';

// Create connection string
$conn_string = "host=$hostname dbname=$dbname user=$username password=$password";

// Create connection
$conn = pg_connect($conn_string);

// Check connection
if (!$conn) {
    die("Error in connection: " . pg_last_error());
}
?>
