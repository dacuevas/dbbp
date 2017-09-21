
<?php
// Load login info
require "dbbp_mysql_config.php";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$search = "SELECT DISTINCT Name FROM Plate";
$sql = $search;
$result = mysqli_query($conn, $sql);
$plates = array();
$n = 0;
 
if(mysqli_num_rows($result) > 0) {
       while($row = mysqli_fetch_assoc($result)) {
			$plates[$n++] = $row["Name"];
		}
} 

function cmp($a, $b) {
    return strcmp($a["value"], $b["value"]);
}
usort($plates, "cmp");
$final = array_values(array_unique($plates));

echo "<option>  Plates  </option>";
foreach ($final as $name) {
	echo "<option> $name </option>";
}
msqli_close($conn);
?>