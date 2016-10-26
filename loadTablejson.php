<?php
// Load login info
require "dbbp_mysql_config.php";

$ret = ["data" => [] , "header" => []];
// $head = array();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$start = $_GET['start'];
$table = $_GET['table'];
$bid = $_GET['bid'];
$msource = $_GET['msource'];
$cpound = $_GET['cpound'];

//--------SEARCH FOR CORRECT SQL STATEMENT----------
$sql = "SELECT * FROM $table ";
$limitForRows = " LIMIT 20 OFFSET $start";
if (empty($bid) && empty($msource) && empty($cpound)) {
	$sqlwhere = ""; 
}
elseif (empty($msource) && empty($cpound)) {
    $sqlwhere = "WHERE BacteriaID='$bid'";
}
elseif (empty($bid) && empty($cpound)) {
	$sqlwhere = "WHERE Mainsource='$msource'";	//only mainsource
}
elseif (empty($bid) && empty($msource)) {
	$sqlwhere = "WHERE Compound='$cpound'"; //not bacteriaID or Mainsource
}
elseif (empty($bid)) {
	$sqlwhere = "WHERE Mainsource='$msource' AND Compound='$cpound'"; //all but bacteriaID
}
elseif (empty($msource)) {
	$sqlwhere = "WHERE BacteriaID='$bid' AND Compound='$cpound'";	
}
elseif (empty($cpound)) {
	$sqlwhere = "WHERE BacteriaID='$bid' AND Mainsource='$msource'";	
}
else { //all filled
   $sqlwhere = "WHERE BacteriaID='$bid' AND Mainsource='$msource' AND Compound='$cpound'";  
}
$sql .= $sqlwhere;
 
//-------PRINTS TABLE--------
$sqlHeader = "SHOW COLUMNS from $table";
$resultHeader = $conn->query($sqlHeader);

while ($rowHeader = $resultHeader->fetch_assoc()) {
    array_push($ret["header"], $rowHeader["Field"]);   
}

$sql .= $limitForRows;
$result = $conn->query($sql);

if ($start >= 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($ret["data"], $row);   
    }   
}
echo json_encode($ret);
//echo "Rows Displayed: " . count($ret["data"]) . " (Max. 20)";
// print_r($ret);
$conn->close();


?>
