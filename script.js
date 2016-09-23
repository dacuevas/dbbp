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

// function showHint(str) {
// 	if (str.length == 0) {
// 				$("#texthint").load("");
// 	}	else {
// 		$(document).ready(function () {
// 			$("input").keyup(function () {
// 				$("#texthint").load("hintarray.php?q=" + str, true);
// 			})
// 		})	
// 	}
// }

var newstart = 0;
function previousButton() {
	newstart -= 20;
	searchTable();
}

function nextButton() {
	newstart += 20;
	searchTable();
}

ajx = "";

function searchTable() {
	var start = 0;
	start = newstart;
	tname = $("#table").val();
	bname = $("#bacteriaid").val();
	mname = $("#mainsource").val();
	cname = $("#compound").val();
	dest = "loadTablejson.php?table=";
	ajx = tname + "&bid=" + bname + "&msource=" + mname + "&cpound=" + cname + "&start=" + start;
 	dest = dest + ajx;
	$.ajax(dest)
        .done(function(results) {
            results = JSON.parse(results);
            var htmlcount = "Result set has " + results["count"] + " rows.";
            $("#answer").html(htmlcount);
            popTable(results["header"], results["data"]);
        });
}

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


 function download() {
	var filename = prompt("Save As: ", "filename");
	var slocation = prompt("Enter file path to save in: \n   Mac-- Users/myname/Downloads/ \n   PC--   C:\\Users\\myname\\Downloads\\ ", "/Users/myname/Downloads/");
 	downdest = "export.php?table=";
 	downdest = downdest + ajx;
 	downdest = downdest + "&outname=" + filename + "&path=" + slocation;
 	$.ajax(downdest)
 	
 }