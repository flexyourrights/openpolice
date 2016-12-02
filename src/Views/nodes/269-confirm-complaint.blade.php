<!-- resources/views/vendor/openpolice/nodes/269-confirm-complaint.blade.php -->

<input type="hidden" name="n269Visible" id="n269VisibleID" value="1">
<div class="fC"></div>

<div id="node269" class="nodeWrap">
	<div id="nLabel269" class="nPrompt">
		<h1 class="slBlueDark">Confirm Your Complaint</h1>

		<div class="p10">
			Below is a preview of your finished complaint. 
			@if ($complaint->ComPrivacy == 304)
				Remember that we will never voluntarily publish anyone's private information. 
				This includes addresses, phone numbers, emails, etc.
			@else
				Remember that we will never voluntarily publish anything in your private 
				complaint that could be used for personal identification. 
				This includes your story and all open-ended questions.
			@endif
		</div>
		
		<div id="previewOversight" class="jumbotron mT20">
			<h2 class="pB20"><span class="slBlueDark">This is what police oversight investigators will see:</span></h2>
			{!! $previewPrivate !!}
		</div>
		
		<div class="pT5 pB20 mB20">
			<a href="javascript:void(0)" class="btn btn-default btn-block f22" id="previewPublicBtn"
				>Click here to see how your complaint will appear to the public</a> 
			<div id="previewPublicWrap" class="disNon">
				<div id="previewPublic" class="jumbotron mT20">
					<h2 class="pB20"><span class="slBlueDark">This is what visitors to OpenPoliceComplaints.org will see:</span></h2>
					{!! $previewPublic !!}
				</div>
			</div>
		</div>
		
		<div class="p10">
			<span class="fPerc125 slBlueDark"><b>Yes,</b> 
			I want to submit my complaint to the appropriate police oversight agencies.</span> 
			I also want to publish my complaint data on this website.
		</div>
		<div class="p10">
			<span class="fPerc125 slBlueDark"><b>Yes,</b> 
			I hereby certify that the information in this complaint is true and 
			correct to the best of my knowledge and belief.</span> 
		</div>
	</div>
	<div class="nFld p20">
		<h2 class="disIn mR20 slBlueDark">Confirm: </h2>
		<div class=" disIn mR20">
			<label for="n269fld0" class="mR20 disIn">
				<input id="n269fld0" value="Y" type="radio" name="n269fld"  autocomplete="off" onClick="checkNodeUp();" 
					> <span class="nPromptHeader">Yes</span>
			</label>
		</div>
		<div class=" disIn mR20">
			<label for="n269fld1" class="mR20 disIn">
				<input id="n269fld1" value="N" type="radio" name="n269fld"  autocomplete="off" onClick="checkNodeUp();" 
					> <span class="nPromptHeader">No</span>
			</label>
		</div>
		<small class="red ml20">*required</small>
	</div>

</div> <!-- end #node269 -->

<style>
.investigateStatus, .complaintReportTitle, .complaintFooter {
	display: none;
}
</style>
<script type="text/javascript">
$(function() {
	$(document).on("click", "#previewPublicBtn", function() {
		$("#previewPublicWrap").slideToggle("fast");
	});
});
</script>
