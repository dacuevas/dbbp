<?php
// Load login info
require "dbbp_mysql_config.php";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$search = "SELECT DISTINCT Compound FROM Plates";
$sql = $search;
$result = mysqli_query($conn, $sql);
$compounds = array();
$n = 0;
 
if(mysqli_num_rows($result) > 0) {
       while($row = mysqli_fetch_assoc($result)) {
			$compounds[$n++] = $row["Compound"];
		}
} 

function cmp($a, $b){
    return strcmp($a["value"], $b["value"]);
}
usort($compounds, "cmp");

$final = array_values(array_unique($compounds));

echo "<option>  Compounds  </option>";
foreach ($final as $name) {
	echo "<option> $name </option>";
}

mysqli_close($conn);
?>
