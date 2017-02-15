<?php
// Load login info
require "dbbp_mysql_config.php";

$ret = array("data" => array());

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$glevel = $_GET['glevel'];

//--------------- SQL STATEMENT------------------
//removing quotation marks from strings with trim
$glevel = trim($glevel, '"'); 
$sql = "SELECT * FROM GrowthParameters WHERE Growth_Level>='$glevel'";

//----------- EXTRACTING FROM DATABASE -------------
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    array_push($ret["data"], $row);   
}   

echo json_encode($ret);
$conn->close();
?>
