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
 
 /*$column is the textbox that is being typed in*/
$column = $_REQUEST["r"];
$array = [];

/*BacteriaID textbox is being typed in*/
 if ($column == "Name") { 
 	$sql = "SELECT DISTINCT $column FROM Sample";
 	$result = $conn->query($sql);
 	while ($row = $result->fetch_assoc()) {
         	array_push($array, $row["$column"]);
         }
   }

/*Mainsource textbox is being typed in*/
 elseif ($column == "Mainsource") {
 $sql = "SELECT DISTINCT $column FROM Plate";
 $result = $conn->query($sql);
 while ($row = $result->fetch_assoc()) {
         	array_push($array, $row["$column"]);
         }
    }

/*Compound textbox is being typed in*/ 
 elseif ($column == "Compound") {
 $sql = "SELECT DISTINCT $column FROM Plate";
 $result = $conn->query($sql);
 while ($row = $result->fetch_assoc()) {
         	array_push($array, $row["$column"]);
         }
    }
     
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
// $type = $_REQUEST["type"];
// if ($type == "raw") {
//     echo $hint1 === "" ? "no suggestion" : $hint1;
// } else {
//   echo $hint2 === "" ? "no suggestion" : $hint2;  
// }

echo $hint === "" ? "no suggestion" : $hint;

?>
