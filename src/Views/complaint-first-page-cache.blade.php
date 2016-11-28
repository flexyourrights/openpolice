<!-- resources/views/vendor/openpolice/complaint-first-page-cache.blade.php -->

@extends('vendor.survloop.master')

@section('content')



<a name="navBar"></a><div id="burgerBar"><a id="navBurger" href="#navBar"><i class="fa fa-bars"></i></a></div>
		<div id="navSummary"><nobr><a href="/" class="f18">Sharing Your Story</a></nobr> 
		<nobr>: <a href="javascript:void(0)" id="navJump1" class="f16">Basic Info</a></nobr><nobr> : <a id="jump157" href="javascript:void(0)" class="navJump f14">Tell Your Story</a></nobr>
		<div id="percWrap"><div id="percMark" style="width: 1%;"></div></div>
		</div><div class="fC"></div><div id="progressBar"><ul>
<li><a href="javascript:void(0)" id="jump1" class="navJump f18 progBarCurr">Basic Info</a>
 :  <a href="javascript:void(0)" id="jump157" class="navJump f16 progBarCurr">Tell Your Story</a>
, <span class="progSectDisable f16">When</span>
, <span class="progSectDisable f16">Where</span>
</li><li><span class="progSectDisable f18">Who's Involved</span>
 :  <span class="progSectDisable f16">About You</span>
, <span class="progSectDisable f16">Victims</span>
, <span class="progSectDisable f16">Witnesses</span>
, <span class="progSectDisable f16"><nobr>Police Departments</nobr></span>
, <span class="progSectDisable f16">Officers</span>
</li><li><span class="progSectDisable f18">What Happened</span>
 :  <span class="progSectDisable f16">The Scene</span>
, <span class="progSectDisable f16">Stops, Searches, Force, & Arrests</span>
, <span class="progSectDisable f16">Injuries</span>
, <span class="progSectDisable f16">Citations</span>
</li><li><span class="progSectDisable f18">Review Allegations & Share</span>
 :  <span class="progSectDisable f16">Check Allegations</span>
, <span class="progSectDisable f16">Publishing Options</span>
, <span class="progSectDisable f16">Submit Complaint</span>
</li></ul></div><div class="p5 w100"></div>
<div style="min-height: 20px;"><div id="legendReq" class="fR taR slRedDark">*required</div>
		<div id="setNavHintRemove" class="fR">remove <span class="nFormLnkDel"><i class="fa fa-minus-square-o"></i></span></div>
		<div id="setNavHintEdit" class="fR">edit <span class="nFormLnkEdit"><i class="fa fa-pencil fa-flip-horizontal"></i></span></div>
		<div class="fC"></div></div>
		<form id="postNodeForm" name="postNode" method="post" action="#"  >
		<input type="hidden" name="node" id="nodeID" value="1">
		<input type="hidden" name="set" id="setID" value="">
		<input type="hidden" name="setItem" id="setItemID" value="-3">
		<input type="hidden" name="step" id="stepID" value="next">
		<input type="hidden" name="jumpTo" id="jumpToID" value="-3">
		<input type="hidden" name="afterJumpTo" id="afterJumpToID" value="-3">
<input type="hidden" name="n1Visible" id="n1VisibleID" value="1"><div class="fC"></div><div><div id="node1" class="nodeWrap">

<div id="nLabel1" class="nPrompt"><label for="n1FldID"><span class="slRedDark">*</span> <div class="nPromptHeader disIn">Your Story Matters</div><br />Tell us the simplest version of the story of what happened DURING this police encounter. Later, you will provide more details about the people involved. Focus on the facts more than your feelings, or anything unrelated. Don't worry about spelling and grammar now, because you can review this draft at the end of the process.</label></div>
<div class="nFld"><textarea name="n1fld" id="n1FldID"  onKeyUp=" fldOnKeyUp1(); "  ></textarea></div>

</div></div> <!-- end #node1 -->
<div id="pageBtns" class="w100"><div id="formErrorMsg" class="w100 taR slRedDark" ></div><div class="fC p10"></div><div class="nodeSub"><input type="submit" value="Next" class="nFormBtnSub" id="nFormNext"><div class="fC"></div></div></div>
<script type="text/javascript">
addFld("n1FldID"); 

function checkNodeForm() {
	if (document.getElementById("stepID").value == "back") return true;
	totFormErrors=0; formErrorsEng = "";
if (document.getElementById('n1VisibleID').value == 1) reqFormFld(1);

	if (totFormErrors > 0) { setFormErrs(); return false; }
	else { clearFormErrs(); }
	return true; 
}
$(function() {
	function runFormSub() {
		$("#ajaxWrap").slideUp(100); 
		window.location="#"; blurAllFlds();
		$.ajax({ type: "POST", url: "?ajax=1&sub=1", data: $("#postNodeForm").serialize(), success: function(data) { $("#ajaxWrap").empty(); $("#ajaxWrap").append(data); } });
		return false;
	}
	$("#nFormBack").click(function() { document.getElementById("stepID").value="back";  return runFormSub(); });
	$("#nFormNext").click(function() { if (checkNodeForm()) { runFormSub(); } return false; });
	
	$(document).on("click", "a.navJump", function() {
		document.getElementById("jumpToID").value = $(this).attr("id").replace("jump", ""); return runFormSub(); 
	});
				$("#navBurger").click(function(){ $("#progressBar").slideToggle("slow"); });
				$(".privacyJump").click(function() { document.getElementById("jumpToID").value="137"; document.getElementById("afterJumpToID").value="1"; return runFormSub(); });
nodeKidList[1] = new Array();
 
});
</script></form>
<style> #n1FldID { height: 300px; } #wordCnt, #narrativeTimer { font-size: 18px; margin-right: 50px; } </style>
<div id="wordCnt" class="fL"></div>
<div id="narrativeTimer" class="fL"></div>
<div class="fC"></div>
<div class="gry9">This is part of a formal complaint,<br />so avoid using ALL CAPS, excessive exclamation points, etc.</div>
<script type="text/javascript">
function fldOnKeyUp1() {
  var cnt = getWordCnt(document.getElementById('n1FldID'));
  var cntWords = '<span class="slRedLight">'+cnt+' words</span>';
  if (cnt >= 250 && cnt <= 500) cntWords = '<span class="slBlueLight">'+cnt+' words</span>';
  document.getElementById('wordCnt').innerHTML=cntWords+' <span class="gry9 f14"><i>(400 recommended)</i></span>';
  return true;
}
setTimeout("fldOnKeyUp1()", 1000);
var timeCnt = 0;
function reloadTimer() {
  if (timeCnt%60 == 0) {
    var cntWords = '<span class="slRedLight">'+Math.floor(timeCnt/60)+' minutes</span>';
    if (timeCnt >= 180 && timeCnt <= 360) cntWords = '<span class="slBlueLight">'+Math.floor(timeCnt/60)+' minutes</span>';
    document.getElementById('narrativeTimer').innerHTML=cntWords+' <span class="gry9 f14"><i>(5 recommended)</i></span>';
  }
  timeCnt++;
  setTimeout("reloadTimer()", 1000);
  return true;
}
setTimeout("reloadTimer()", 1000);
</script><style> #legendReq { display: block; } </style><div class="nodeGap"></div>



@endsection