<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "Data";

$ret = array("data" => array());

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$gclass = $_GET['gclass'];
//-------------PUTTING PARAMETERS INTO RESP. ARRAYS----------------------
//removing quotation marks from strings with trim
$gclass = trim($gclass, '"'); 

//creating array of growth classes
if(strpos($gclass, ',') !== false) {
	$gclass = explode(',' , $gclass);   //array of passed in growth classes
} else {
	$gclass = array($gclass);	
}
//--------------- SQL STATEMENT------------------
$sql = "SELECT * FROM GrowthParameters WHERE ";

foreach ($gclass as $key => $value) {
	$sql .= "Growth_Class='$value' ";
	
	if( $key != (sizeof($gclass)-1) ) {
		$sql .= " OR ";
	}
}

// //----------- EXTRACTING FROM DATABASE -------------
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    array_push($ret["data"], $row);   
}   

echo json_encode($ret);
$conn->close();
?>