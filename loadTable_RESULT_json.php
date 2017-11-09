<?php
// Load login info
require "dbbp_mysql_config.php";

$ret = ["data" => [] , "header" => [], "count" => []];
// $head = array();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$start = $_GET['start'];
$bid = $_GET['bid'];
$msource = $_GET['msource'];
$cpound = $_GET['cpound'];


//--------SEARCH FOR CORRECT SQL STATEMENT----------
$sql = "SELECT Sample.Name, Experiment.ReplicateID, Plate.Mainsource, Plate.Compound, GrowthResults.Well, GrowthResults.Name, GrowthResults.MaxGrowthRate, GrowthResults.Asymptote, GrowthResults.Lag, GrowthResults.GrowthLevel, GrowthResults.MSE 
FROM (((Experiment INNER JOIN Sample ON Experiment.SampleID = Sample.SampleID) 
INNER JOIN GrowthResults ON Experiment.ExperimentID = GrowthResults.ExperimentID) 
INNER JOIN Plate ON GrowthResults.Name = Plate.Name AND GrowthResults.Well = Plate.Well) ";
$limitForRows = " LIMIT 20 OFFSET $start";
if (empty($bid) && empty($msource) && empty($cpound)) {
	$sqlwhere = ""; 
}
elseif (empty($msource) && empty($cpound)) {
    $sqlwhere = "WHERE Sample.Name='$bid'";
}
elseif (empty($bid) && empty($cpound)) {
	$sqlwhere = "WHERE Plate.Mainsource='$msource'";	//only mainsource
}
elseif (empty($bid) && empty($msource)) {
	$sqlwhere = "WHERE Plate.Compound='$cpound'"; //not bacteriaID or Mainsource
}
elseif (empty($bid)) {
	$sqlwhere = "WHERE Plate.Mainsource='$msource' AND Plate.Compound='$cpound'"; //all but bacteriaID
}
elseif (empty($msource)) {
	$sqlwhere = "WHERE Sample.Name='$bid' AND Plate.Compound='$cpound'";	
}
elseif (empty($cpound)) {
	$sqlwhere = "WHERE Sample.Name='$bid' AND Plate.Mainsource='$msource'";	
}
else { //all filled
   $sqlwhere = "WHERE Sample.Name='$bid' AND Plate.Mainsource='$msource' AND Plate.Compound='$cpound'";  
}
$sql .= $sqlwhere;
$sql .= $limitForRows;

//-------PRINTS TABLE--------
// $sqlHeader = "SHOW COLUMNS from $table";
// $resultHeader = $conn->query($sqlHeader);
// 
// while ($rowHeader = $resultHeader->fetch_assoc()) {
//     array_push($ret["header"], $rowHeader["Field"]);   
// }
// 
// 
// $result = $conn->query($sql);
// 
// if ($start >= 0) {
//     while ($row = $result->fetch_assoc()) {
//         array_push($ret["data"], $row);   
//     }   
// }


$result = $conn->query($sql);



	/* Get field information  (only name field) for all columns for Header*/
        $finfo = mysqli_fetch_fields($result);
        

        	foreach ($finfo as $val) {
            	array_push($ret["header"], $val->name);
        	}
        	
        /*Getting data for table rows*/
        if ($start >= 0) {
     		while ($row = $result->fetch_assoc()) {
         		array_push($ret["data"], $row);   
              }
         }   
    
//array_push($ret["count"], count($ret["data"])); NEED TO FETCH FOR TOTAL

echo json_encode($ret);
//print_r($ret);
$conn->close();


?>