<!-- resources/views/auth/registerComplaint.blade.php -->

<form id="postNodeForm" name="postNode" method="post" action="/auth/register" target="_parent" >
{!! csrf_field() !!}
<input type="hidden" name="ajax" value="1">
<input type="hidden" name="emailBlock" id="emailBlockID" value="1">
<input type="hidden" name="node" id="nodeID" value="8">
<input type="hidden" name="loop" id="loopID" value="">
<input type="hidden" name="loopItem" id="loopItemID" value="-3">
<input type="hidden" name="step" id="stepID" value="next">
<input type="hidden" name="jumpTo" id="jumpToID" value="-3">
<input type="hidden" name="afterJumpTo" id="afterJumpToID" value="-3"> 
<input type="hidden" name="name" id="nameID" value="Session#{{ $complaintID }}" >

@if ($anonyLogin)
	<script type="text/javascript">
	function anonymousLogin() {
		document.getElementById('emailID').value='anonymous.{{ $complaintID }}@openpolicecomplaints.org';
		document.getElementById('password').value='{{ $anonyPass }}';
		document.getElementById('password_confirmation').value='{{ $anonyPass }}';
		document.postNode.submit();
		return true;
	}
	setTimeout("anonymousLogin()", 500);
	</script>
	<br />
	<center><h2><i>Creating a temporary, anonymous account.</i></h2></center>
	<!-- hiding form from anonymous users or those with unresolved charges -->
	<div class="disNon">
@endif

<div class="jumbotron"><h1>Your Story Matters</h1><p>
Please create a login. It will let you finish your complaint later, if we get interrupted. 
It is also important so we can contact you about the progress of your complaint.
</p></div>

<div id="node001" class="nodeWrap">
	<div id="nLabel001" class="nPrompt">
		<label for="emailID">Email:</label> <span class="slRedDark f12">*required</span>
	</div>
	<div class="nFld">
		<input id="emailID" name="email" value="{{ old('email') }}" type="email" class="form-control">
	</div>
</div>

<div id="emailWarning" class="disNon">
	<div class="alert alert-warning mT20 w66" role="alert">
		<div class="row">
			<div class="col-md-8 f22">
				<b>Have you created a complaint with this email address before?</b> 
				Please login to review and/or update your earlier complaint, 
				or to file a new complaint with this email address.
			</div>
			<div class="col-md-4">
				<a href="/auth/login" class="btn btn-lg btn-primary f26 mT20">Login</a>
			</div>
		</div>
	</div>
</div>

<div class="nodeGap"></div>

<div id="node002" class="nodeWrap">
<div id="nLabel002" class="nPrompt"><label for="password">Password: <span class="slRedDark f12">*required, <i>6 character minimum</i></span></label></div>
<div class="nFld"><input id="password" name="password" type="password" class="form-control"></div>
</div>

<div class="nodeGap"></div>

<div id="node003" class="nodeWrap">
<div id="nLabel003" class="nPrompt"><label for="password_confirmation">Confirm Password:</label> <span class="slRedDark f12">*required</span></div>
<div class="nFld"><input id="password_confirmation" name="password_confirmation" type="password" class="form-control"></div>
</div>

<div class="nodeGap"></div>


<div class="fC p10"></div>
<div class="nodeSub">
<input type="submit" value="Next" class="fR btn btn-lg btn-primary" id="nFormNext">
<input type="button" value="Back" class="fL btn btn-lg btn-default" id="nFormBack">
<div class="fC"></div>
</div>

@if ($anonyLogin)
	</div> <!-- end div hiding form from anonymous users or those with unresolved charges -->
@endif

<script type="text/javascript">
$(function() {
	function checkNodeForm()
	{
		if (document.getElementById("stepID").value == "back") return true;
		totFormErrors=0;
		formErrorsEng = "";
		if (!reqFormEmail('emailID') || document.getElementById('emailID').value.trim() == '') 
		{
			setFormLabelRed('001'); 
			totFormErrors++;
		}
		else
		{
			document.getElementById('emailWarning').style.display='none';
			//alert( "warn: "+document.getElementById('emailWarning').style.display+' - '+"/chkEmail?"+$("#emailID").serialize() );
			$.ajax({
				url: "/chkEmail?"+$("#emailID").serialize(),
				type: 'GET',
				async: false,
				cache: false,
				timeout: 30000,
				error: function(){
					return true;
				},
				success: function(chkData){ 
					if (chkData == 'found')
					{
						document.getElementById('emailBlockID').value = 1;
						setFormLabelRed('001'); 
						totFormErrors++;
						//document.getElementById('emailWarning').style.display='block';
						$("#emailWarning").slideDown("fast");
					}
					else
					{
						document.getElementById('emailBlockID').value = 0;
						setFormLabelBlack('001');
					}
				}
			});
		}
		if (document.getElementById('password') && document.getElementById('password_confirmation'))
		{
			if (document.getElementById('password').value.trim() == '' || document.getElementById('password').value.trim().length < 6
				|| document.getElementById('password').value != document.getElementById('password_confirmation').value)
			{
				setFormLabelRed('002');
				setFormLabelRed('003');
				totFormErrors++;
			}
			else
			{
				setFormLabelBlack('002');
				setFormLabelBlack('003');
			}
		}
		if (totFormErrors > 0)
		{
			setFormErrs();
			return false;
		}
		else clearFormErrs();
		return true; 
	}
	
	function runFormSub()
	{
		blurAllFlds();
		//window.location="#";
		$.ajax({ type: "POST", url: "/sub", data: $("#postNodeForm").serialize(), 
			success: function(data) { $("#ajaxWrap").empty(); document.getElementById("ajaxWrap").innerHTML=""; $("#ajaxWrap").append(data); }, 
			error: function(xhr, status, error) { $("#ajaxWrap").append("<div>(error - "+xhr.responseText+")</div>"); }
		});
		resetCheckForm();
		return false;
	}
	$("#nFormBack").click(function() { document.getElementById("jumpToID").value = 1; return runFormSub(); });
	$("#nFormNext").click(function() { if (checkNodeForm()) { return document.postNode.submit(); } return false; });
	$(document).on("click", "a.navJump", function() { document.getElementById("jumpToID").value = $(this).attr("id").replace("jump", ""); return runFormSub(); });
});
</script></form>

<div class="fC p20"></div>