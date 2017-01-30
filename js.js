if (hs) {
	hs.easing = 'easeInBack';
}
function chkFrom(frm){
	var tmp=parseInt( $('#slctData').text() );
	tmp = isNaN( tmp )?0:tmp;
	frm.inpDay.value=selectedYear+'-'+selectedMonth+'-'+tmp;
	if (frm.chkDelete.checked){
		if (!parseInt(frm.recId.value)){
			alert('Выберите запсиь в таблице!');
			return false;
		}else
			frm.op.value="delete";
	}
	return true;
}
if (window.jQuery)
$(function(){
	$(".tdNumber").click(function(event){
		if (event.target.tagName=='TD'){
			$(".tdNumber").removeClass("tdSelected");
			$(this).addClass("tdSelected");
			var num=parseInt($("span",this).text());
			//alert(num);
			$("#inpData").val(num);
			$("#slctData").text(num);
			$('#post').val('');
			$('#startTime').val('');
			$('#endTime').val('');
		}
	});
	$(".user-link").click(function(){
		var recId=parseInt($(this).attr('id'));
		//var num=parseInt( $("span:first", $(this).parent().parent() ).text());
		var num=parseInt( $("span:first", $(this).parent().parent() ).text());
		$("#slctData").text(num);
		$("#recId").val(recId);
		var codes=$(".user-link ~ code", $(this).parent());
		var startTime = parseInt( $(codes.get(0)).text() );
		if (startTime) startTime+='0000'; else startTime ='0';
		var finishTime = parseInt( $(codes.get(1)).text() );
		if (finishTime) finishTime+='0000'; else finishTime ='0';
		var note = $("#content"+recId).children(".highslide-body");
		console.log( note );
		note = note.text();		
		$("#post").val(note);
		$("#startTime").val(startTime);
		$("#endTime").val(finishTime);
		return false;
	});

	$("#slctUser").change(function(){
		var userId=$(this).val();
		$("input[name=idUser]").val(userId);
		//alert(userId);
		//location.href=curr_Page+"?userId="+userId;
	});

	$("#idSlctMonth").change(function(){
		selectedMonth=$(this).val();
		//alert(userId);
		//alert(curr_Page+"&m="+mon+"&y="+year);
		location.href=curr_Page+"?m="+selectedMonth+"&y="+selectedYear;
	});

	$("#idSlctYear").change(function(){
		selectedYear = $(this).val();
		//alert(userId);
		//alert(curr_Page+"&m="+mon+"&y="+year);
		location.href=curr_Page+"?m="+selectedMonth+"&y="+selectedYear;
	});

	//.trigger("change");

	// comment
});