<?php
// Load login info
require "dbbp_mysql_config.php";

$ret = array("identifier" => array(), "final" => array());

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

//--------------- SQL STATEMENT to get identifiers from GrowthParameters 
//												with specific growth_class------------------
$sql = "SELECT BacteriaID,Replicate_Number_or_Letter,Well,Mainsource,Compound FROM GrowthParameters WHERE ";

foreach ($gclass as $key => $value) {
	$sql .= "Growth_Class='$value' ";
	
	if( $key != (sizeof($gclass)-1) ) {
		$sql .= " OR ";
	}
}

// //----------- EXTRACTING identifers FROM DATABASE -------------
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    array_push($ret["identifier"], $row);   
} 

//--------------- SQL STATEMENT to get final results from Experiment------------------
$nsql = "SELECT * FROM Experiment WHERE ";
foreach ($ret["identifier"] as $key => $value) {
	foreach ($value as $k => $v) {
		if($k == "BacteriaID") {
			$nsql .= "( ";
		}
		$state = "$k = '$v'";
		$nsql .= $state;

		if($k != "Compound") {
			$nsql .= " AND ";
		}
		if($k == "Compound") {
			$nsql .= ") OR ";
		}
	}
}
$nsql = rtrim($nsql, " OR ");

// //----------- EXTRACTING final data FROM DATABASE -------------
$answer = $conn->query($nsql);

while ($entry = $answer->fetch_assoc()) {
    array_push($ret["final"], $entry);   
}   

echo json_encode($ret["final"]);
$conn->close();
?>
