<!-- resources/views/vendor/openpolice/volun/inc-oversightComplaints.blade.php -->

<div class="row gry9">
	<div class="col-md-6">
		<h2 class="slBlueDark m0">Web Presence &amp; Complaint Info</h2>
		<div class="gry9 pB10"><b>Note:</b> Leave fields blank if the department doesn't have the thing.</div>
		
		<a href="https://www.google.com/search?as_q={{ $deptRow->DeptName }}, {{ $deptRow->DeptAddressState }} {{ $deptRow->DeptAddressZip }}" 
			class="btn btn-default slBlueDark mR10 mB5" target="_blank">Dept Search&nbsp;&nbsp;
			<span class=""><i class="fa fa-google"></i></span></a>
		<a href="https://www.google.com/search?as_q={{ $deptRow->DeptName }} file complaint against" 
			class="btn btn-default slBlueDark mR10 mB5" target="_blank">Complaints Search&nbsp;&nbsp;
			<span class=""><i class="fa fa-google"></i></span></a>
		
		<fieldset class="form-group">
			<label for="{{ $overType }}OverWebsiteID">Website URL</label>
			@if ($overType == 'IA') <span class="slBlueDark f10">{{ $deptPoints["Website"] }}</span> @endif
			<input id="{{ $overType }}OverWebsiteID" name="{{ $overType }}OverWebsite" value="{{ $overRow->OverWebsite }}" 
				type="text" class="form-control" onKeyUp="checkScore();" onChange="checkScore();" > 
		</fieldset>
		<fieldset class="form-group">
			<label for="{{ $overType }}OverFacebookID">Facebook URL</label>
			@if ($overType == 'IA') <span class="slBlueDark f10">{{ $deptPoints["FB"] }}</span> @endif
			<input id="{{ $overType }}OverFacebookID" name="{{ $overType }}OverFacebook" value="{{ $overRow->OverFacebook }}" 
				type="text" class="form-control" onKeyUp="checkScore();" onChange="checkScore();" > 
		</fieldset>
		<fieldset class="form-group">
			<label for="{{ $overType }}OverTwitterID">Twitter URL</label>
			@if ($overType == 'IA') <span class="slBlueDark f10">{{ $deptPoints["Twit"] }}</span> @endif
			<input id="{{ $overType }}OverTwitterID" name="{{ $overType }}OverTwitter" value="{{ $overRow->OverTwitter }}" 
				type="text" class="form-control" onKeyUp="checkScore();" onChange="checkScore();" > 
		</fieldset>
		<fieldset class="form-group">
			<label for="{{ $overType }}OverYouTubeID">YouTube Channel URL</label>
			@if ($overType == 'IA') <span class="slBlueDark f10">{{ $deptPoints["YouTube"] }}</span> @endif
			<input id="{{ $overType }}OverYouTubeID" name="{{ $overType }}OverYouTube" value="{{ $overRow->OverYouTube }}" 
				type="text" class="form-control" onKeyUp="checkScore();" onChange="checkScore();" > 
		</fieldset>
		@if ($overType == 'IA')
			<fieldset class="form-group">
				<label for="DeptEmailID">Department Main Email Address</label>
				<input id="DeptEmailID" name="DeptEmail" value="{{ $deptRow->DeptEmail }}" type="text" class="form-control" > 
			</fieldset>
		@endif
	</div>
	
	<div class="col-md-1"></div>
	
	<div class="col-md-5 blk f16">
		<div>Link to Complaint Info from Home Page? <span class="slBlueDark f10">{{ $deptPoints["ComplaintInfoHomeLnk"] }}</span></div>
		<div class="radio-inline blk mR20">
			<label class="nobld">
				<input type="radio" name="{{ $overType }}OverHomepageComplaintLink" id="{{ $overType }}OverHomepageComplaintLinkA" value="Y" 
				@if (isset($overRow->OverHomepageComplaintLink) && $overRow->OverHomepageComplaintLink == 'Y') CHECKED @endif
				onClick="checkScore();" onChange="checkScore();"> Yes
			</label>
		</div>
		<div class="radio-inline blk mL20">
			<label class="nobld">
				<input type="radio" name="{{ $overType }}OverHomepageComplaintLink" id="{{ $overType }}OverHomepageComplaintLinkB" value="N" 
				@if (isset($overRow->OverHomepageComplaintLink) && $overRow->OverHomepageComplaintLink == 'N') CHECKED @endif
				onClick="checkScore();" onChange="checkScore();"> No, or I Can't See It
			</label>
		</div>
		<fieldset class="form-group">
			<label for="{{ $overType }}OverWebComplaintInfoID">Complaint Info URL</label>
			@if ($overType == 'IA') <span class="slBlueDark f10">{{ $deptPoints["ComplaintInfo"] }}</span> @endif
			<input id="{{ $overType }}OverWebComplaintInfoID" name="{{ $overType }}OverWebComplaintInfo" value="{{ $overRow->OverWebComplaintInfo }}" 
				type="text" class="form-control" onKeyUp="checkScore();" onChange="checkScore();" > 
		</fieldset>
		<fieldset class="form-group">
			<label for="{{ $overType }}OverComplaintPDFID">Complaint Form PDF</label>
			@if ($overType == 'IA') <span class="slBlueDark f10">{{ $deptPoints["FormPDF"] }}</span> @endif
			<input id="{{ $overType }}OverComplaintPDFID" name="{{ $overType }}OverComplaintPDF" value="{{ $overRow->OverComplaintPDF }}" 
				type="text" class="form-control" onKeyUp="checkScore();" onChange="checkScore();" > 
		</fieldset>
		
		<h4 class="gry9 mT20">How Can Complaints Be Submitted (AND Investigated)?</h4>
		@foreach ($ways as $i => $w)
			<div class="checkbox">
				<label>
					<input type="checkbox" name="{{ $overType }}{{ $waysFlds[$i] }}" id="{{ $overType }}{{ $waysFlds[$i] }}ID" 
						@if ($waysChecked[$i]) CHECKED @endif 
						value="1" onClick="checkScore();" onChange="checkScore();
						@if (in_array($waysFlds[$i], array('OverWaySubOnline', 'OverWaySubEmail', 'OverWaySubVerbalPhone', 'OverWaySubPaperMail')))
							if (this.checked) { document.getElementById('{{ $overType }}OverWaySubPaperInPersonID').checked = false; }
						@elseif ($waysFlds[$i] == 'OverWaySubPaperInPerson')
							if (this.checked) { document.getElementById('{{ $overType }}OverWaySubOnlineID').checked = false; document.getElementById('{{ $overType }}OverWaySubEmailID').checked = false; document.getElementById('{{ $overType }}OverWaySubVerbalPhoneID').checked = false; document.getElementById('{{ $overType }}OverWaySubPaperMailID').checked = false; }
						@endif "> {{ $w }} 
					@if ($overType == 'IA') <span class="slBlueDark f10">{{ $wayPoints[$i] }}</span> @endif 
					@if ($waysFlds[$i] == 'OverWaySubOnline')
						<small class="text-muted">(<i>This is rare.</i> <a href="https://www.phillypolice.com/forms/official-complaint-form/" target="_blank"><i>Here's an example</i></a>.)</small>
					@endif
				</label>
			</div>
			@if ($waysFlds[$i] == 'OverWaySubOnline')
				<fieldset class="form-group pL20 mTn10">
					<div class="row">
						<div class="col-md-1">
							<label for="{{ $overType }}OverComplaintWebFormID">URL:</label>
						</div>
						<div class="col-md-11">
							<input id="{{ $overType }}OverComplaintWebFormID" name="{{ $overType }}OverComplaintWebForm" value="{{ $overRow->OverComplaintWebForm }}" type="text" 
								onKeyUp="checkScore(); if (this.value != '') { document.getElementById('OverWaySubOnlineID').checked = true; }" onChange="checkScore();" class="form-control" > 
						</div>
					</div>
				</fieldset>
			@endif
		@endforeach
		<fieldset class="form-group">
			<label for="{{ $overType }}OverSubmitDeadlineID">Complaint must be submitted within how many days</label>
			<div class="row">
				<div class="col-md-3">
					<input id="{{ $overType }}OverSubmitDeadlineID" name="{{ $overType }}OverSubmitDeadline" 
						@if (intVal($overRow->OverSubmitDeadline) >= 0) value="{{ $overRow->OverSubmitDeadline }}" @else value="" @endif
						type="number" class="form-control @if (intVal($overRow->OverSubmitDeadline) == -1) disNon @else disBlo @endif "> 
				</div>
				<div class="col-md-3 pT10 f18">
					<div id="{{ $overType }}OverSubmitDeadLabel" class=" @if (intVal($overRow->OverSubmitDeadline) == -1) disNon @else disBlo @endif ">days</div>
				</div>
				<div class="col-md-6 taR">
					<div class="checkbox mTn10">
						<label>
							<input type="checkbox" name="{{ $overType }}OverSubmitAnytime" id="{{ $overType }}OverSubmitAnytimeID" value="-1" 
								onClick="if (this.checked) { document.getElementById('{{ $overType }}OverSubmitDeadlineID').style.display='none'; document.getElementById('{{ $overType }}OverSubmitDeadLabel').style.display='none'; }
										 else { document.getElementById('{{ $overType }}OverSubmitDeadlineID').style.display='block'; document.getElementById('{{ $overType }}OverSubmitDeadLabel').style.display='block'; }"
								@if (intVal($overRow->OverSubmitDeadline) == -1) CHECKED @endif > no time limit
						</label>
					</div>
				</div>
			</div>
		</fieldset>
	</div>
</div>