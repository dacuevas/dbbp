<?php
//Load login info
require "dbbp_mysql_config.php";

$ret = array("data" => array());

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$bid = $_GET['bid'];
$well = $_GET['well'];

//-------------PUTTING PARAMETERS INTO RESP. ARRAYS----------------------
//removing quotation marks from strings with trim
$bid = trim($bid, '"'); 
$well = trim($well, '"');

//creating array of bacteria ids
if(strpos($bid, ',') !== false) {
	$bid = explode(',' , $bid);   //array of passed in bacteria ids	
} else {
	$bid = array($bid);	
}

//creating array of wells
if(strpos($well, ',') !== false) {
	$well = explode(',' , $well);   //array of passed in bacteria ids	
} else {
	$well = array($well);	
}
//--------------- SQL STATEMENT------------------
$sql = "SELECT Sample.Name, Experiment.ReplicateID, GrowthResults.Well, Plate.Mainsource, Plate.Compound, GrowthResults.MaxGrowthRate, GrowthResults.Asymptote, GrowthResults.Lag, GrowthResults.GrowthLevel, GrowthResults.MSE, GrowthResults.ExperimentID, GrowthResults.Name, GrowthResults.Well FROM (((Experiment INNER JOIN Sample ON Experiment.SampleID = Sample.SampleID) INNER JOIN GrowthResults ON Experiment.ExperimentID = GrowthResults.ExperimentID) INNER JOIN Plate ON GrowthResults.Name = Plate.Name AND GrowthResults.Well = Plate.Well) WHERE (";

foreach ($bid as $key => $value) {
	$sql .= "Sample.Name='$value'";
	
	if( $key != (sizeof($bid)-1) ) {
		$sql .= " OR ";
	}
}
$sql .=") AND (";

foreach ($well as $key => $value) {
	$sql .= "Plate.Well='$value'";
	
	if( $key != (sizeof($well)-1) ) {
		$sql .= " OR ";
	}
}
$sql .= ")";
// //----------- EXTRACTING FROM DATABASE -------------
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    array_push($ret["data"], $row);   
}   

echo json_encode($ret);
$conn->close();
?>