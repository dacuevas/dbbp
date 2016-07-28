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
	}, 36000);
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

function searchTable() {
	var start = 0;
	start = newstart;	
	tname = $("#table").val();
	bname = $("#bacteriaid").val();
	mname = $("#mainsource").val();
	cname = $("#compound").val();
	ajx = "loadTable.php?table=" + tname + "&bid=" + bname + "&msource=" + mname + "&cpound=" + cname + "&start=" + start;
	$.ajax(ajx)
        .done(function(results) {
            $("#answer").html(results);
            results = JSON.parse(results);
            var html = "";
            for (i in results) {
                html += i;
            }
            $("#answer").html(html);
        });
}
