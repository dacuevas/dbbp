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
$sql = "SELECT Sample.Name, Experiment.ReplicateID, GrowthResults.Well, Plate.Mainsource, Plate.Compound, GrowthResults.MaxGrowthRate, GrowthResults.Asymptote, GrowthResults.Lag, GrowthResults.GrowthLevel, GrowthResults.MSE, GrowthResults.ExperimentID, GrowthResults.Name FROM (((Experiment INNER JOIN Sample ON Experiment.SampleID = Sample.SampleID) INNER JOIN GrowthResults ON Experiment.ExperimentID = GrowthResults.ExperimentID) INNER JOIN Plate ON GrowthResults.Name = Plate.Name AND GrowthResults.Well = Plate.Well) WHERE GrowthResults.MaxGrowthRate>='$maxgr'";
echo $sql;
//----------- EXTRACTING FROM DATABASE -------------
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    array_push($ret["data"], $row);   
}   

echo json_encode($ret);
$conn->close();
?>