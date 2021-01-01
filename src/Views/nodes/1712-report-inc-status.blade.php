<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-status.blade.php -->
<option value="New" 
    @if (!$firstReview && in_array($status, [0, 196])) SELECTED @endif 
    >New (On Hold)</option>
<?php /*
<option value="Hold: Go Gold" 
    @if (!$firstReview && $lastStatus == 'Hold: Go Gold') SELECTED @endif 
    >Invite To Go Gold (On Hold)</option>
*/ ?>
<option value="Needs More Work" 
    @if (!$firstReview && $status == 627) SELECTED @endif 
    >Needs More Work (On Hold)</option>
<option DISABLED ></option>
<option value="Pending Attorney: Defense Needed" 
    @if (!$firstReview && $status == 198) SELECTED @endif 
    >Defense Attorney Needed (On Hold)</option>
<option value="Pending Attorney: Civil Rights Needed/Wanted" 
    @if (!$firstReview && $status == 727) SELECTED @endif 
    >Civil Rights Attorney Needed/Wanted (On Hold)</option>
<option value="Has Attorney" 
    @if (!$firstReview && $status == 199) SELECTED @endif 
    >Has Attorney (On Hold)</option>
<option DISABLED ></option>
<option value="OK to Submit to Oversight" 
    @if (!$firstReview && $status == 202) SELECTED @endif 
    >OK to File with IA ({{ $transparency }})</option>
<option value="Submitted to Oversight" 
    @if (!$firstReview && $status == 200) SELECTED @endif 
    >Filed with IA ({{ $transparency }})</option>
<option value="Received by Oversight" 
    @if (!$firstReview && $status == 201) SELECTED @endif 
    >Received by IA ({{ $transparency }})</option>
<option value="Investigated (Closed)" 
    @if (!$firstReview && $status == 204) SELECTED @endif 
    >Investigated by IA ({{ $transparency }}, Closed)</option>
<option value="Declined To Investigate (Closed)" 
    @if (!$firstReview && $status == 203) SELECTED @endif 
    >IA Declined to Investigate ({{ $transparency }}, Closed)</option>
<option value="Closed" 
    @if (!$firstReview && $status == 205) SELECTED @endif 
    >Otherwise Closed ({{ $transparency }}, Closed)</option>
<?php /*
<option DISABLED ></option>
<option value="Incomplete" 
    @if (!$firstReview && $lastStatus == 'Incomplete') SELECTED @endif 
    >Incomplete (Un-Publish)</option>
<option value="Hold: Not Sure" 
    @if (!$firstReview && $lastStatus == 'Hold: Not Sure') SELECTED @endif 
    >Not Sure (Requires More Review, On Hold)</option>
*/ ?>