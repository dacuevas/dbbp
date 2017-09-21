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

//$bid = "E.cloacae"; //96 rows
$bid = $_GET['bid'];

//--------PUTTING PARAMETERS INTO AN ARRAY -----
$bid = trim($bid, '"');
if(strpos($bid, ',') !== false) {
	$bid = explode(',' , $bid);   //array of passed in mainsouces	
} else {
	$bid = array($bid);	
}
//--------------- SQL STATEMENT------------------
$sql = "SELECT Sample.Name, Experiment.ReplicateID, Plate.Name, GrowthResults.Well, Plate.Mainsource, Plate.Compound, GrowthResults.MaxGrowthRate, GrowthResults.Asymptote, GrowthResults.Lag, GrowthResults.GrowthLevel, GrowthResults.MSE, GrowthResults.ExperimentID FROM (((Experiment INNER JOIN Sample ON Experiment.SampleID = Sample.SampleID) INNER JOIN GrowthResults ON Experiment.ExperimentID = GrowthResults.ExperimentID) INNER JOIN Plate ON GrowthResults.Name = Plate.Name AND GrowthResults.Well = Plate.Well) WHERE ";

foreach ($bid as $key => $value) {
	$sql .= "Sample.Name= '$value'  ";
	
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