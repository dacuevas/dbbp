<?php
// Load login info
require "dbbp_mysql_config.php";

$ret = array();
$head = array();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$path = $_GET['path'];
$outname = $_GET['outname'];
$table = $_GET['table'];
$bid = $_GET['bid'];
$msource = $_GET['msource'];
$cpound = $_GET['cpound'];
$sql = "SELECT * FROM $table WHERE BacteriaID='$bid' AND Mainsource='$msource' AND Compound='$cpound'";
// $export = " INTO OUTFILE $outname.txt";

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

// $sql .= $export;
$result = $conn->query($sql);

while( $row = $result->fetch_assoc()) {
    if ($table == "Plates") {
        array_push($ret, $row);
    }
    if ($table == "GrowthParameters") {
        array_push($ret, $row);
    }
    if ($table == "Experiment") {
        array_push($ret, $row);
    }
}

$fileName = "$outname.csv";
 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header('Content-Description: File Transfer');
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename={$fileName}");
header("Expires: 0");
header("Pragma: public");

if ($path != null) {
    $fh = @fopen( "$path$fileName", "w" );
    $headerDisplayed = false;

    foreach ( $ret as $data ) {
		    // Add a header row if it hasn't been added yet
    	if ( !$headerDisplayed ) {
       		 // Use the keys from $data as the titles
        	fputcsv($fh, array_keys($data));
       		 $headerDisplayed = true;
    	}
  		 // Put the data into the stream
   		 fputcsv($fh, $data);
    }
}

// Close the file
fclose($fh);
// Make sure nothing else is sent, our file is done
exit;

$conn->close();

?>
