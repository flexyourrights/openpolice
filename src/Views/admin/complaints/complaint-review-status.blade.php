<!-- resources/views/vendor/openpolice/admin/complaints/complaint-review-status.blade.php -->


<select name="revStatus" id="revStatusID" class="form-control f22" autocomplete=off onChange="updateStatusDrop();" >
	
	<option value="" @if ($firstReview) SELECTED @endif  ></option>
	
	<option value="Submitted to Oversight" class="bgGrn" 
		@if (!$firstReview && isset($latestReview->ComRevStatus) && trim($latestReview->ComRevStatus) == 'Submitted to Oversight')
			SELECTED
		@endif
		>(Publish) - Submit to Oversight Agency</option>
		
	@if ($settings["Complaint Evaluations"] == 'Y' || true)

		<option value="Hold: Go Gold" class="bgYel" 
			@if (!$firstReview && isset($latestReview->ComRevStatus) && trim($latestReview->ComRevStatus) == 'Hold: Go Gold')
				SELECTED
			@endif
			>(Publish) - Hold: Request To "Go Gold"</option>
			
		<option value="Pending Attorney: Needed" class="bgRed" 
			@if (!$firstReview && isset($latestReview->ComRevStatus) && trim($latestReview->ComRevStatus) == 'Pending Attorney: Needed')
				SELECTED
			@endif
			>(Un-Publish) - Hold: Attorney Needed</option>
			
		<option value="Pending Attorney: Hook-Up" class="bgRed" 
			@if (!$firstReview && isset($latestReview->ComRevStatus) && trim($latestReview->ComRevStatus) == 'Pending Attorney: Hook-Up')
				SELECTED
			@endif
			>(Un-Publish) - Hold: Attorney Hook-Up</option>
			
		<option value="Attorney'd" class="bgYel" 
			@if (!$firstReview && isset($latestReview->ComRevStatus) && trim($latestReview->ComRevStatus) == "Attorney'd")
				SELECTED
			@endif
			>(Un-Publish) - Hold: Has Attorney</option>
			
	@endif
		
	<option value="Incomplete" class="bgYel" 
		@if (!$firstReview && isset($latestReview->ComRevStatus) && trim($latestReview->ComRevStatus) == 'Incomplete')
			SELECTED
		@endif
		>(Un-Publish) - Incomplete</option>
		
	<option value="Hold: Not Sure" class="bgRed" 
		@if (!$firstReview && isset($latestReview->ComRevStatus) && trim($latestReview->ComRevStatus) == 'Hold: Not Sure')
			SELECTED
		@endif
		>(Un-Publish) - Not Sure: requires more review</option>
		
	@if (isset($fullList) && $fullList)
	
		<option value=""  ></option>
		@foreach (array('Received by Oversight', 'Pending Oversight Investigation', 'Declined To Investigate (Closed)', 'Investigated (Closed)', 'Closed') as $status)
			<option value="{{ $status }}" class="bgGrn" 
				@if (!$firstReview && isset($latestReview->ComRevStatus) && trim($latestReview->ComRevStatus) == $status)
					SELECTED
				@endif
				>(Publish) - {{ $status }}</option>
		@endforeach
		
	@endif
	
</select>

<script type="text/javascript">
function updateStatusDrop() 
{
	var newClass = 'form-control f22';
	if (document.getElementById('revStatusID').value == 'Submitted to Oversight'
		|| document.getElementById('revStatusID').value == 'Received by Oversight'
		|| document.getElementById('revStatusID').value == 'Pending Oversight Investigation'
		|| document.getElementById('revStatusID').value == 'Declined To Investigate (Closed)'
		|| document.getElementById('revStatusID').value == 'Investigated (Closed)'
		|| document.getElementById('revStatusID').value == 'Closed')
	{
		newClass += ' bgGrn';
	}
	else if (document.getElementById('revStatusID').value == 'Hold: Go Gold'
		|| document.getElementById('revStatusID').value == "Attorney'd"
		|| document.getElementById('revStatusID').value == 'Incomplete')
	{
		newClass += ' bgYel';
	}
	else if (document.getElementById('revStatusID').value == 'Pending Attorney: Needed'
		|| document.getElementById('revStatusID').value == 'Pending Attorney: Hook-Up'
		|| document.getElementById('revStatusID').value == 'Hold: Not Sure')
	{
		newClass += ' bgRed';
	}
	document.getElementById('revStatusID').className = newClass;
	return true;
}
updateStatusDrop();
</script>

