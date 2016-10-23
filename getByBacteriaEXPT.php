<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "Data";

$ret = ["data" => []];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//$bid = "E.cloacae"; //96 rows
$bid = $_GET['bid'];

//--------PUTTING PARAMETERS INTO AN ARRAY -----
if(strpos($bid, ',') !== false) {
	$bid = explode(',' , $bid);   //array of passed in mainsouces	
} else {
	$bid = array($bid);	
}

//--------------- SQL STATEMENT------------------
$sql = "SELECT * FROM Experiment WHERE ";

foreach ($bid as $key => $value) {
	$sql .= "BacteriaID='$value'";
	
	if( $key != (sizeof($bid)-1) ) {
		$sql .= " OR ";
	}
}

echo $sql;
//----------- EXTRACTING FROM DATABASE -------------
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    array_push($ret["data"], $row);   
}   

echo json_encode($ret);
$conn->close();
?>