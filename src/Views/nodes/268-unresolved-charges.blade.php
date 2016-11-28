<!-- resources/views/vendor/openpolice/nodes/268-unresolved-charges.blade.php -->

<input type="hidden" name="n268Visible" id="n268VisibleID" value="1">
<div class="fC"></div>
<div id="node268" class="nodeWrap">

	<div id="nLabel268" class="nPrompt">
		<div class="slBlueDark mBn10">Does <b>anyone</b> involved have any unresolved criminal charges related to this incident?</div>
		<small class="red pL10 mTn10">*required</small>
	</div>
	<div class="nFld pB20">
		<div><nobr><label for="n268fld0" class="mR10">
			<div class="disIn mR5"><input id="n268fld0" value="Y" type="radio" name="n268fld" class="n268fldCls" autocomplete="off" onClick="unresolveUpdate(this.value);" 
				@if ($ComUnresolvedCharges == 'Y') CHECKED @endif ></div> Yes
		</label></nobr></div>
		<div><label for="n268fld2" class="mR10">
			<div class="disIn mR5"><input id="n268fld2" value="N" type="radio" name="n268fld" class="n268fldCls" autocomplete="off" onClick="unresolveUpdate(this.value);" 
				@if ($ComUnresolvedCharges == 'N') CHECKED @endif ></div> No unresolved charges <span class="fPerc80">(or my attorney said I could use Open Police Complaints)</span>
		</label></div>
		<div><nobr><label for="n268fld1" class="mR10">
			<div class="disIn mR5"><input id="n268fld1" value="?" type="radio" name="n268fld" class="n268fldCls" autocomplete="off" onClick="unresolveUpdate(this.value);" 
				@if ($ComUnresolvedCharges == '?') CHECKED @endif ></div> Not sure
		</label></nobr></div>
	</div>
	
	<input type="hidden" name="n439Visible" id="n439VisibleID" 
		@if ($ComUnresolvedCharges == 'N')
			value="0"
		@else
			value="1"
		@endif
		>
	<div id="node439" class="nodeWrap jumbotron @if (in_array($ComUnresolvedCharges, ['Y', '?'])) disBlo @else disNon @endif ">
		<div class="alert alert-danger" role="alert">
			<div class="row">
				<div class="col-md-1 taC">
					<h1 class="m0"><i class="fa fa-hand-paper-o"></i></h1>
				</div>
				<div class="col-md-11 pT10">
					<h3 id="StopY" class="m0 @if ($ComUnresolvedCharges == 'Y') disBlo @else disNon @endif "
						>You should probably stop now and consult with a criminal attorney to ask for legal advice before submitting this complaint.</h3> 
					<h3 id="StopQ" class="m0 @if ($ComUnresolvedCharges == '?') disBlo @else disNon @endif "
						>Before submitting a complaint, you should try to check with everyone involved to ensure they do not have any unresolved criminal charges.</h3> 
				</div>
			</div>
		</div>
		<p class="pB10">
		<span class="f26">This is important because information provided in this complaint could hurt a criminal defense. 
		Let's avoid this risk of publicly publishing your complaint on the internet.</span>
		</p>
		<p class="pB20">
		<span class="f22">Perhaps you should <a href="javascript:void(0)" class="showStoryCopy"><u>copy the narrative you wrote</u></a> and paste it into an email that you send to yourself. 
		Then you can come back with it later when all charges have been resolved, or after checking with all lawyers involved.</span>
		</p>
		<div class="p10"></div>
		<div class="alert alert-success" role="alert">
			<div class="row">
				<div class="col-md-1 taC">
					<h1 class="m0"><i class="fa fa-hand-pointer-o fa-rotate-90"></i></h1>
				</div>
				<div class="col-md-11 pT10">
					<h3 class="m0">Or you could still use Open Police Complaints right now in a couple ways...</h3>
				</div>
			</div>
		</div>
		<div class="row pT10">
			<div class="col-md-8 pR20">
				<p class="pB10">
					<span class="f22"><span class="slBlueDark">You could keep going to create a high-quality complaint right now while memories are freshest,</span> 
					then just print it out for your attorney or legal aid service. 
					After you logout, we will delete your complaint from our database except for some anonymous multiple-choice answers. 
					Only that anonymous information will be published publicly.</span>
				</p>
				<p>
					<span class="f28 slBlueDark">This anonymous data will greatly help with efforts to improve police accountability, both in your neighborhood and nationwide. 
					<i class="fa fa-heart-o"></i></span>
				</p>
			</div>
			<div class="col-md-4 pT20 pL20 pB10">
				<div class="p20 brd round5">
					<label for="n439fld0">
						<div class="row">
							<div class="col-md-1 pR5 pT20">
								<input id="n439fld0" value="298" type="radio" name="n439fld" autocomplete="off" 
									@if ($ComUnresolvedChargesActions == 298) CHECKED @endif >
							</div>
							<div class="col-md-11 blk f22">
								<label for="n439fld0">Create a full complaint to print or save</label>
							</div>
						</div>
					</label>
					<label for="n439fld1">
						<div class="row pT20">
							<div class="col-md-1 pR5 pT20">
								<input id="n439fld1" value="299" type="radio" name="n439fld" autocomplete="off" 
									@if ($ComUnresolvedChargesActions == 299) CHECKED @endif >
							</div>
							<div class="col-md-11 blk f22">
								<label for="n439fld1">Just provide anonymous complaint information</label>
							</div>
						</div>
					</label>
				</div>
			</div>
		</div>
	</div> <!-- end #node439 -->
	
</div> <!-- end #node268 -->

<script type="text/javascript">
addFld("n268fld0"); addFld("n268fld1"); addFld("n268fld2"); 
addFld("n439fld0"); addFld("n439fld1"); 
function unresolveUpdate(newVal) {
	if (newVal == 'N') {
		document.getElementById('node439').style.display='none';
		setNodeVisib(439, false);
		return true;
	}
	document.getElementById('node439').style.display='block';
	setNodeVisib(439, true);
	if (newVal == 'Y') {
		document.getElementById('StopY').style.display='block';
		document.getElementById('StopQ').style.display='none';
	}
	else {
		document.getElementById('StopY').style.display='none';
		document.getElementById('StopQ').style.display='block';
	}
	return true;
}
</script>