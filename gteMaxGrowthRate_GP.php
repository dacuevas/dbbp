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

$maxgr = $_GET['maxgr'];

//--------------- SQL STATEMENT------------------
//removing quotation marks from strings with trim
$maxgr = trim($maxgr, '"'); 
$sql = "SELECT * FROM GrowthParameters WHERE Max_Growth_rate>='$maxgr'";

//----------- EXTRACTING FROM DATABASE -------------
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    array_push($ret["data"], $row);   
}   

echo json_encode($ret);
$conn->close();
?>