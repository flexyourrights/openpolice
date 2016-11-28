/* resources/views/openpolice/nodes/452-ajax-unresolved-charges.blade.php */

$(".n443fldCls").click(function(){
	if (document.getElementById("n443fld1").checked || document.getElementById("n443fld2").checked) {
		$("#node443kidsN").slideDown("50");
		setNodeVisib(444, true);
	} 
	else {
		$("#node443kidsN").slideUp("50");
		setNodeVisib(444, false);
	}
	if (document.getElementById("n443fld0").checked) {
		$("#node443kidsY").slideDown("50");
		setNodeVisib(562, true);
	} 
	else {
		$("#node443kidsY").slideUp("50");
		setNodeVisib(562, false);
	}
}); 

conditionNodes[268] = true;
nodeKidList[268] = [563];
$(".n268fldCls").click(function(){
	var foundKidResponse = false;
	if (document.getElementById("n268fld0").checked) foundKidResponse = true;
	if (document.getElementById("n268fld2").checked) foundKidResponse = true;
	
	if (foundKidResponse) { $("#node268kids").slideDown("50"); kidsVisible(268, true, true); } 
	else { $("#node268kids").slideUp("50"); kidsVisible(268, false, true); }
});

$("#nFormComplete").click(function(){
	if (checkNodeForm())
	{
		document.getElementById("n439hidden").value=298;
		document.getElementById("stepID").value="next";
		runFormSub();
	}
	return false;
});
$("#nFormAnonymous").click(function(){
	if (checkNodeForm())
	{
		document.getElementById("n439hidden").value=299;
		document.getElementById("stepID").value="next";
		runFormSub();
	}
	return false;
});
$(".showStoryCopy").click(function(){
	$("#copyClipWrap").slideDown("50");
});
$("#copyToClip").click(function(){
	document.getElementById("confirmCopied").style.display='none';
	copyToClipboard(document.getElementById("ComSummaryID"));
	setTimeout("document.getElementById('confirmCopied').style.display='block'", 400);
});
