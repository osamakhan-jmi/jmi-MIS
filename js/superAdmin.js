$(document).ready(function() {
		$("#updateMarks").click(function() {
		var roll = $("#sturoll").val();
		var type = $("#type").val();
		var code = $("#stucode").val();
		
		var sCode = new String(code);
		var i = sCode.indexOf("/")+1;
		sCode = sCode.substring(i,sCode.length);
		//alert(sCode);
		if(roll==""||type=="choose")
			alert("all entries required!!");
		else{
		$.ajax({
			url:"alterMarks.php",
			data:{"code":sCode,"roll":roll,"type":type},
			success: function(data){
					alert(data);
			},
			error:function(data){
				alert("Server Busy");
			}
		});
		}
	});
		$("#genMarks").click(function() {
		var qRoll = $("#qRoll").val();
		var qClas = $("#qClas").val();
		if(qClas==""||qRoll=="")
			alert("all entries required!!");
		else{
			window.open("singleMarksheet.php?roll="+qRoll+"&clas="+qClas);
		}
	});
	
	$("#gen12Marks").click(function(){
		var qStream = $("#qStream12").val();
		if(qStream=="Choose")
			alert("all entries required!!");
		else{
			window.open('class12'+qStream+'.php');
		}
		});

		$("#gen10Marks").click(function(){
		var qStream = $("#qStream10").val();
		if(qStream=="Choose")
			alert("all entries required!!");
		else{
			if(qStream == 'Private')
				window.open('class10private.php');
			else
				window.open('class10nonprivate.php');
		}
		});
});
