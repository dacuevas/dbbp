$(document).ready(function() {
	$('#mainsourceSelect').load('nameRefreshConnectMainSource.php');
		setInterval(function() {
			$('#mainsourceSelect').load('nameRefreshConnectMainSource.php')
	}, 3600000);
});

$(document).ready(function() {
	$('#plateSelect').load('nameRefreshConnectPlate.php');
		setInterval(function() {
			$('#plateSelect').load('nameRefreshConnectPlate.php')
	}, 3600000);
});

$(document).ready(function() {
	$('#compoundSelect').load('nameRefreshConnectCompound.php');
		setInterval(function() {
			$('#compoundSelect').load('nameRefreshConnectCompound.php')
	}, 3600000);
});

function showHintRAW(str, col) {
    if (str.length == 0) { 
        document.getElementById("hintRAW").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("hintRAW").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", "betterhint.php?q=" + str + "&r=" + col, true);
        xmlhttp.send();
    }
}   

function showHintRESULT(str, col) {
    if (str.length == 0) { 
        document.getElementById("hintRESULT").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("hintRESULT").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", "betterhint.php?q=" + str + "&r=" + col, true);
        xmlhttp.send();
    }
}   


// -------TABLE POPULATION FUNCTIONS--------
type = "";

var newstart = 0;
function previousButton() {
	newstart -= 20;
	searchTableRAW(type);
}

function nextButton() {
	newstart += 20;
	searchTableRAW(type);
}

dest = "";
ajx = "";

//NEED TO FIX: IF SEARCH WITH NEW INPUTS AFTER PAGING, OFFSET WILL NOT RESTART TO ZERO
function searchTableRAW(table) {
	var start = 0;
	start = newstart;
	type = table;
	bname = $("#bacteriaid").val();
	mname = $("#mainsource").val();
	cname = $("#compound").val();
	if (table == "raw") {
		dest = 	"loadTable_RAW_json.php?";
	} else {
		dest = "loadTable_RESULT_json.php?";
	}
	
	ajx = "bid=" + bname + "&msource=" + mname + "&cpound=" + cname + "&start=" + start;
 	dest = dest + ajx;
	$.ajax(dest)
        .done(function(results) {
            results = JSON.parse(results);
            var htmlcount = "Result set has " + results["count"] + " rows.";
            $("#answer").html(htmlcount);
            popTable(results["header"], results["data"]);
        });
}

// function searchTableRESULT() {
// 	var start = 0;
// 	start = newstart;
// 	bname = $("#bacteriaid").val();
// 	mname = $("#mainsource").val();
// 	cname = $("#compound").val();
// 	dest = "loadTable_RESULT_json.php?";
// 	ajx = "bid=" + bname + "&msource=" + mname + "&cpound=" + cname + "&start=" + start;
//  	dest = dest + ajx;
// 	$.ajax(dest)
//         .done(function(results) {
//             results = JSON.parse(results);
//             var htmlcount = "Result set has " + results["count"] + " rows.";
//             $("#answer").html(htmlcount);
//             popTable(results["header"], results["data"]);
//         });
// }

function popTable(dataH, dataD) {
	var html = "";
	html += "<table>";
	html += "<tr>";
	//table heading
	for (var d in dataH) {
		item = dataH[d];
		if (item == "null") {
			item = "-";
		}
        html += "<th>" + item + "</th>";
   	}
   	html += "</tr>";
   	
   	//table data
	for (var d in dataD) {
		html += "<tr>";
		for (var i in dataD[d]) {
			var item = dataD[d][i];
            if (item == "null") {
                item = "-";
            }
            html += "<td>" + item + "</td>";
        }
        html += "</tr>";
    }
    html += "</table>";
	$("#answer").html(html);
}

// -------DOWNLOAD TABLE-----------
 function download() {
	var filename = prompt("Save As: ", "filename");
	var slocation = prompt("Enter file path to save in: \n   Mac-- Users/myname/Downloads/ \n   PC--   C:\\Users\\myname\\Downloads\\ ", "/Users/myname/Downloads/");
 	downdest = "export.php?table=";
 	downdest = downdest + ajx;
 	downdest = downdest + "&outname=" + filename + "&path=" + slocation;
 	$.ajax(downdest)
 	
 }