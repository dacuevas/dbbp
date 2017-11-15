
<?php
// Load login info
require "dbbp_mysql_config.php";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$search = "SELECT DISTINCT Mainsource FROM Plate";
$sql = $search;
$result = mysqli_query($conn, $sql);
$mainsource = array();
$n = 0; 
 
if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
			$mainsource[$n++] = $row["Mainsource"];
		}
}

function cmp($a, $b) {
    return strcmp($a["value"], $b["value"]);
}
usort($mainsource, "cmp");
$final = array_values(array_unique($mainsource));
	
echo "<option>  Mainsources  </option>";
foreach ($final as $name) {
	echo "<option> $name </option>";
}
msqli_close($conn);
?>
