<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "Data";

$ret = ["stuff" => []];
$head = array();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$start = $_GET['start'];
$table = $_GET['table'];
$bid = $_GET['bid'];
$msource = $_GET['msource'];
$cpound = $_GET['cpound'];
$sql = "SELECT * FROM $table WHERE BacteriaID='$bid' AND Mainsource='$msource' AND Compound='$cpound'";
$limitForRows = " LIMIT 20 OFFSET $start";

if (empty($bid)) {
	$sql = "SELECT * FROM $table WHERE Mainsource='$msource' AND Compound='$cpound'"; //all but bacteriaID
}
if (empty($msource)) {
	$sql = "SELECT * FROM $table WHERE BacteriaID='$bid' AND Compound='$cpound'";	
}
if (empty($cpound)) {
	$sql = "SELECT * FROM $table WHERE BacteriaID='$bid' AND Mainsource='$msource'";	
}
if (empty($bid) && empty($msource)) {
	$sql = "SELECT * FROM $table WHERE Compound='$cpound'"; //not bacteriaID or Mainsource
}
if (empty($bid) && empty($cpound)) {
	$sql = "SELECT * FROM $table WHERE Mainsource='$msource'";	//only mainsource
}	
if (empty($msource) && empty($cpound)) {
    $sql = "SELECT * FROM $table WHERE BacteriaID='$bid'";
}
if (empty($bid) && empty($msource) && empty($cpound)) {
	$sql = "SELECT * FROM $table"; 
}

$sql .= $limitForRows;
$result = $conn->query($sql);

echo "<table>";
 if ($table == "Plate_1" || $table == "Plate_2") {
        $sql2= "SHOW COLUMNS from $table";
        $result2 = $conn->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
        	array_push($head, $row2["Field"]);
        }
    echo "<tr>";
    foreach ($head as $value) {
        echo	"<th> $value </th>";
        } 
    echo "</tr>";
}
if ($table == "Experiment") {
    $sql2= "SHOW COLUMNS from $table";
        $result2 = $conn->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
        	array_push($head, $row2["Field"]);
        }
    echo "<tr>";
    foreach ($head as $value) {
        echo	"<th> $value </th>";
        } 
    echo "</tr>";
   }
if ($table == "GrowthParameters") {
    $sql2= "SHOW COLUMNS from $table";
        $result2 = $conn->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
        	array_push($head, $row2["Field"]);
        }
    echo "<tr>";
    foreach ($head as $value) {
        echo	"<th> $value </th>";
        } 
    echo "</tr>";
   }
if ($table == "Bacteria") {
    $sql2= "SHOW COLUMNS from $table";
        $result2 = $conn->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
        	array_push($head, $row2["Field"]);
        }
    echo "<tr>";
    foreach ($head as $value) {
        echo	"<th> $value </th>";
        } 
    echo "</tr>";
   }
while( $row = $result->fetch_assoc()) {
    if ($table == "Plate_1") {
        array_push($ret["stuff"], $row);
        echo "<tr>
        <td>" . $row['Plate'] . "</td>
        <td>" . $row['Well'] . "</td>
        <td>" . $row['Mainsource'] . "</td>
        <td>" . $row['Compound'] . "</td>
        <td>" . $row['Concentration'] . "</td>
        </tr>";
    }
    if ($table == "Plate_2") {
        array_push($ret["stuff"], $row);
        echo "<tr>
        <td>" . $row['Plate'] . "</td>
        <td>" . $row['Well'] . "</td>
        <td>" . $row['Mainsource'] . "</td>
        <td>" . $row['Compound'] . "</td>
        <td>" . $row['Concentration'] . "</td>
        </tr>";
    }
    if ($table == "GrowthParameters") {
        array_push($ret["stuff"], $row);
        echo "<tr>
        <td>" . $row['BacteriaID'] . "</td>
        <td>" . $row['Replicate Number or Letter'] . "</td>
        <td>" . $row['Well'] . "</td>
        <td>" . $row['Mainsource'] . "</td>
        <td>" . $row['Compound'] . "</td>
        <td>" . $row['y0'] . "</td>
        <td>" . $row['Lag'] . "</td>
        <td>" . $row['Max Growth Rate'] . "</td>
        <td>" . $row['Asymptote'] . "</td>
        <td>" . $row['Growth Level'] . "</td>
        <td>" . $row['Growth Level Scaled'] . "</td>
        <td>" . $row['R'] . "</td>
        <td>" . $row['AUC Raw'] . "</td>
        <td>" . $row['AUC Raw Shifted'] . "</td>
        <td>" . $row['AUC Logistic'] . "</td>
        <td>" . $row['AUC Logistic Shifted'] . "</td>
        <td>" . $row['Growth Class'] . "</td>
        <td>" . $row['Logistic MSE'] . "</td>
        </tr>";
    }
    if ($table == "Experiment") {
        array_push($ret["stuff"], $row);
        echo "<tr>
        <td>" . $row['BacteriaID'] . "</td>
        <td>" . $row['Replicate Number or Letter'] . "</td>
        <td>" . $row['Mainsource'] . "</td>
        <td>" . $row['Compound'] . "</td>
        <td>" . $row['Well'] . "</td>
        <td>" . $row['Date'] . "</td>
        <td>" . $row['Plate'] . "</td>
        <td>" . $row['Time'] . "</td>
        <td>" . $row['OD'] . "</td>
        </tr>";
    }
    //$counter++;
}
echo "</table>";

//$ret = $result->fetch_assoc();
//echo json_encode($ret);

echo "Number of Rows Displayed: " . count($ret["stuff"]);
$conn->close();


?>