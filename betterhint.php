<?php
// Load login info
require "dbbp_mysql_config.php";

$array = array();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
$column = $_REQUEST["r"];

 if ($column == "Table") {
	$sql = "SHOW TABLES FROM Data";
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
        	array_push($array,$row["Tables_in_data"]);
        }
} else {

//---------------------------- Plates doesn't have BacteriaIDs
 if ($column != "BacteriaID") { 
 	$sql = "SELECT DISTINCT $column FROM Plates";
 	$result = $conn->query($sql);
 	while ($row = $result->fetch_assoc()) {
         	array_push($array, $row["$column"]);
         }
   }
 //-----------------------------
 
 $sql = "SELECT DISTINCT $column FROM GrowthParameters";
 $result = $conn->query($sql);
 while ($row = $result->fetch_assoc()) {
         	array_push($array, $row["$column"]);
         }
 
 $sql = "SELECT DISTINCT $column FROM Experiment";
 $result = $conn->query($sql);
 while ($row = $result->fetch_assoc()) {
         	array_push($array, $row["$column"]);
         }
    
$sql = "SELECT DISTINCT $column FROM Bacteria";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
        	array_push($array, $row["$column"]);
        }   
 
 } //-----end of else-----
     
$distinct = array_values(array_unique($array));

function cmp($a, $b){
    return strcmp($a["value"], $b["value"]);
}

//FINAL SORTED ARRAY
usort($distinct, "cmp"); 

//   print_r($distinct);
//   echo count($distinct);

//DOING HINT SEARCHES----->

//get the q parameter from URL
$q = $_REQUEST["q"];

$hint = "";

//lookup all hints from array if $q is different from ""
if ($q !== "") {
	$q = strtolower($q);
	$length = strlen($q);
	foreach ($distinct as $name) {
		if (stristr($q, substr($name, 0, $length))) {
			if ($hint === "") {
				$hint = $name;
			} else {
				$hint .= ", $name";
			}
		}	
	}
}

//output "no suggestion" if no hint was found
echo $hint === "" ? "no suggestion" : $hint;
        
?>
