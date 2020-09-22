<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-status.blade.php -->
<option value="New" 
    @if (!$firstReview && in_array(trim($lastStatus), ['', 'New', 'Police Complaint'])) SELECTED @endif 
    >New (On Hold)</option>
<?php /*
<option value="Hold: Go Gold" 
    @if (!$firstReview && $lastStatus == 'Hold: Go Gold') SELECTED @endif 
    >Invite To Go Gold (On Hold)</option>
*/ ?>
<option value="Needs More Work" 
    @if (!$firstReview && $lastStatus == 'Needs More Work') SELECTED @endif 
    >Needs More Work (On Hold)</option>
<option DISABLED ></option>
<option value="Pending Attorney: Defense Needed" 
    @if (!$firstReview 
        && in_array($lastStatus, ['Pending Attorney: Needed', 'Pending Attorney: Defense Needed'])) SELECTED @endif 
    >Defense Attorney Needed (Un-Publish, On Hold)</option>
<option value="Pending Attorney: Civil Rights Needed/Wanted" 
    @if (!$firstReview 
        && in_array($lastStatus, ['Pending Attorney: Hook-Up', 'Pending Attorney: Civil Rights Needed/Wanted'])) SELECTED @endif 
    >Civil Rights Attorney Needed/Wanted (Un-Publish, On Hold)</option>
<option value="Has Attorney" 
    @if (!$firstReview && $lastStatus == "Has Attorney") SELECTED @endif 
    >Has Attorney (Un-Publish, On Hold)</option>
<option DISABLED ></option>
<option value="OK to Submit to Oversight" 
    @if (!$firstReview && $lastStatus == 'OK to Submit to Oversight') SELECTED @endif 
    >OK to Submit to Investigative Agency (Reviewed)</option>
<option value="Submitted to Oversight" 
    @if (!$firstReview && $lastStatus == 'Submitted to Oversight') SELECTED @endif 
    >Submitted to Investigative Agency (Full Transparency Public)</option>
<option value="Received by Oversight" 
    @if (!$firstReview && $lastStatus == 'Received by Oversight') SELECTED @endif 
    >Received by Investigative Agency (Full Transparency Public)</option>
<option value="Pending Oversight Investigation" 
    @if (!$firstReview && $lastStatus == 'Pending Oversight Investigation') SELECTED @endif 
    >Being Investigated (Full Transparency Public, Presumably)</option>
<option value="Investigated (Closed)" 
    @if (!$firstReview && $lastStatus == 'Investigated (Closed)') SELECTED @endif 
    >Investigated (Full Transparency Public, Closed)</option>
<option value="Declined To Investigate (Closed)" 
    @if (!$firstReview && $lastStatus == 'Declined To Investigate (Closed)') SELECTED @endif 
    >Declined To Investigate (Full Transparency Public, Closed)</option>
<option value="Closed" 
    @if (!$firstReview && $lastStatus == 'Closed') SELECTED @endif 
    >Otherwise Closed (Full Transparency Public, Closed)</option>
<option DISABLED ></option>
<option value="Incomplete" 
    @if (!$firstReview && $lastStatus == 'Incomplete') SELECTED @endif 
    >Incomplete (Un-Publish)</option>
<option value="Hold: Not Sure" 
    @if (!$firstReview && $lastStatus == 'Hold: Not Sure') SELECTED @endif 
    >Not Sure (Requires More Review, On Hold)</option>
