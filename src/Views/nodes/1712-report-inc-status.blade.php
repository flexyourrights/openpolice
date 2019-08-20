<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-status.blade.php -->
<option value="Hold: Go Gold" @if (!$firstReview && isset($lastReview->ComRevStatus) 
    && trim($lastReview->ComRevStatus) == 'Hold: Go Gold') SELECTED @endif 
    >Invite To Go Gold (On Hold)</option>
<option value="Needs More Work" @if (!$firstReview && isset($lastReview->ComRevStatus) 
    && trim($lastReview->ComRevStatus) == 'Needs More Work') SELECTED @endif 
    >Needs More Work (On Hold)</option>
<option DISABLED ></option>
<option value="Pending Attorney: Needed" @if (!$firstReview && isset($lastReview->ComRevStatus) 
    && trim($lastReview->ComRevStatus) == 'Pending Attorney: Needed') SELECTED @endif 
    >Defense Attorney Needed (Un-Publish, On Hold)</option>
<option value="Pending Attorney: Hook-Up" @if (!$firstReview && isset($lastReview->ComRevStatus) 
    && trim($lastReview->ComRevStatus) == 'Pending Attorney: Hook-Up') SELECTED @endif 
    >Civil Rights Attorney Needed (Un-Publish, On Hold)</option>
<option value="Attorney'd" @if (!$firstReview && isset($lastReview->ComRevStatus) 
    && trim($lastReview->ComRevStatus) == "Attorney'd") SELECTED @endif 
    >Has Attorney (Un-Publish, On Hold)</option>
<option DISABLED ></option>
<option value="OK to Submit to Oversight" @if (!$firstReview && isset($lastReview->ComRevStatus) 
    && trim($lastReview->ComRevStatus) == 'OK to Submit to Oversight') SELECTED @endif 
    >OK to Submit to Investigative Agency (Reviewed)</option>
<option value="Submitted to Oversight" @if (!$firstReview && isset($lastReview->ComRevStatus) 
    && trim($lastReview->ComRevStatus) == 'Submitted to Oversight') SELECTED @endif 
    >Submitted to Investigative Agency (Full Transparency Public, Email Sent or Complainant Confirmed)</option>
<option value="Received by Oversight" @if (!$firstReview && isset($lastReview->ComRevStatus) 
    && trim($lastReview->ComRevStatus) == 'Received by Oversight') SELECTED @endif 
    >Received by Investigative Agency (Full Transparency Public, Confirmed)</option>
<option value="Pending Oversight Investigation" @if (!$firstReview && isset($lastReview->ComRevStatus) 
    && trim($lastReview->ComRevStatus) == 'Pending Oversight Investigation') SELECTED @endif 
    >Being Investigated (Full Transparency Public, Presumably)</option>
<option value="Investigated (Closed)" @if (!$firstReview && isset($lastReview->ComRevStatus) 
    && trim($lastReview->ComRevStatus) == 'Investigated (Closed)') SELECTED @endif 
    >Investigated (Full Transparency Public, Closed)</option>
<option value="Declined To Investigate (Closed)" @if (!$firstReview && isset($lastReview->ComRevStatus) 
    && trim($lastReview->ComRevStatus) == 'Declined To Investigate (Closed)') SELECTED @endif 
    >Declined To Investigate (Full Transparency Public, Closed)</option>
<option value="Closed" @if (!$firstReview && isset($lastReview->ComRevStatus) 
    && trim($lastReview->ComRevStatus) == 'Closed') SELECTED @endif 
    >Otherwise Closed (Full Transparency Public, Closed)</option>
<option DISABLED ></option>
<option value="Incomplete" @if (!$firstReview && isset($lastReview->ComRevStatus) 
    && trim($lastReview->ComRevStatus) == 'Incomplete') SELECTED @endif 
    >Incomplete (Un-Publish)</option>
<option value="Hold: Not Sure" @if (!$firstReview && isset($lastReview->ComRevStatus) 
    && trim($lastReview->ComRevStatus) == 'Hold: Not Sure') SELECTED @endif 
    >Not Sure (Requires More Review, On Hold)</option>
