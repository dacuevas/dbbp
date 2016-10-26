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

$bid = $_GET['bid'];
$msource = $_GET['ms'];
$cpound = $_GET['cp'];

//-------------PUTTING PARAMETERS INTO RESP. ARRAYS----------------------
if(strpos($bid, ',') !== false) {
	$bid = explode(',' , $bid);   //array of passed in mainsouces	
} else {
	$bid = array($bid);	
}

//--------------- SQL STATEMENT------------------
$sql = "SELECT * FROM Experiment WHERE (";

foreach ($bid as $key => $value) {
	$sql .= "BacteriaID='$value'";
	
	if( $key != (sizeof($bid)-1) ) {
		$sql .= " OR ";
	}
}
$sql .= ") AND Mainsource='$msource' AND Compound='$cpound' ";

// //----------- EXTRACTING FROM DATABASE -------------
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    array_push($ret["data"], $row);   
}   

echo json_encode($ret);
$conn->close();
?>
