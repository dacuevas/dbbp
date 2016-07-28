<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "Data";

$compoundA = array();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT DISTINCT Compound FROM Plate_1";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
        	array_push($compoundA, $row["Compound"]);
        }

$sql = "SELECT DISTINCT Compound FROM Plate_2";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
        	array_push($compoundA, $row["Compound"]);
        }
        
$sql = "SELECT Compound FROM GrowthParameters";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
        	array_push($compoundA, $row["Compound"]);
        }

$sql = "SELECT Compound FROM Experiment";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
        	array_push($compoundA, $row["Compound"]);
        }
        
// $sql = "SELECT Compound FROM Bacteria";
// $result = $conn->query($sql);
// while ($row = $result->fetch_assoc()) {
//         	array_push($compoundA, $row["Compound"]);
//         }   
     
$distinct = array_values(array_unique($compoundA));

function cmp($a, $b){
    return strcmp($a["value"], $b["value"]);
}

//FINAL SORTED ARRAY
usort($distinct, "cmp"); 

// print_r($distinct);

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










