<!-- resources/views/vendor/openpolice/admin/complaints/complaint-review-status.blade.php -->

<div class="nFld">
<label for="revStatus0" class="finger"><div class="disIn mR5">
    <input id="revStatus0" type="radio" name="revStatus" autocomplete="off" value="Hold: Go Gold" 
        @if (!$firstReview && isset($lastReview->ComRevStatus) 
            && trim($lastReview->ComRevStatus) == 'Hold: Go Gold') SELECTED
        @endif ></div> Invite To Go Gold <span class="fPerc66 slGrey pull-right">(On Hold)</span>
</label>
<label for="revStatus1" class="finger"><div class="disIn mR5">
    <input id="revStatus1" type="radio" name="revStatus" autocomplete="off" value="Pending Attorney: Needed" 
        @if (!$firstReview && isset($lastReview->ComRevStatus) 
            && trim($lastReview->ComRevStatus) == 'Pending Attorney: Needed') SELECTED
        @endif ></div> Defense Attorney Needed <span class="fPerc66 slGrey pull-right">(Un-Publish, On Hold)</span>
</label>
<label for="revStatus2" class="finger"><div class="disIn mR5">
    <input id="revStatus2" type="radio" name="revStatus" autocomplete="off" value="Pending Attorney: Hook-Up" 
        @if (!$firstReview && isset($lastReview->ComRevStatus) 
            && trim($lastReview->ComRevStatus) == 'Pending Attorney: Hook-Up') SELECTED
        @endif ></div> Civil Rights Attorney Needed <span class="fPerc66 slGrey pull-right">(Un-Publish, On Hold)</span>
</label>
<label for="revStatus3" class="finger"><div class="disIn mR5">
    <input id="revStatus3" type="radio" name="revStatus" autocomplete="off" value="Attorney'd" 
        @if (!$firstReview && isset($lastReview->ComRevStatus) 
            && trim($lastReview->ComRevStatus) == "Attorney'd") SELECTED
        @endif ></div> Has Attorney <span class="fPerc66 slGrey pull-right">(Un-Publish, On Hold)</span>
</label>

<div class="p10"></div>

<label for="revStatus4" class="finger"><div class="disIn mR5">
    <input id="revStatus4" type="radio" name="revStatus" autocomplete="off" value="OK to Submit to Oversight" 
        @if (!$firstReview && isset($lastReview->ComRevStatus) 
            && trim($lastReview->ComRevStatus) == 'OK to Submit to Oversight') SELECTED
        @endif ></div> <h4 class="disIn">OK to Submit to Oversight</h4> 
        <span class="fPerc66 slGrey pull-right">(Reviewed)</span>
</label>
<label for="revStatus5" class="finger"><div class="disIn mR5">
    <input id="revStatus5" type="radio" name="revStatus" autocomplete="off" value="Submitted to Oversight" 
        @if (!$firstReview && isset($lastReview->ComRevStatus) 
            && trim($lastReview->ComRevStatus) == 'Submitted to Oversight') SELECTED
        @endif ></div> <h4 class="disIn">Submitted to Oversight</h4> 
        <span class="fPerc66 slGrey pull-right taR">(Full Transparency Public,<br />
        Email Sent or Complainant Confirmed)</span>
</label>

<div class="p10"></div>

<label for="revStatus6" class="finger"><div class="disIn mR5">
    <input id="revStatus6" type="radio" name="revStatus" autocomplete="off" value="Received by Oversight" 
        @if (!$firstReview && isset($lastReview->ComRevStatus) 
            && trim($lastReview->ComRevStatus) == 'Received by Oversight') SELECTED
        @endif ></div> Received by Oversight 
        <span class="fPerc66 slGrey pull-right">(Full Transparency Public, Confirmed)</span>
</label>
<label for="revStatus7" class="finger"><div class="disIn mR5">
    <input id="revStatus7" type="radio" name="revStatus" autocomplete="off" value="Pending Oversight Investigation" 
        @if (!$firstReview && isset($lastReview->ComRevStatus) 
            && trim($lastReview->ComRevStatus) == 'Pending Oversight Investigation') SELECTED
        @endif ></div> Being Investigated 
        <span class="fPerc66 slGrey pull-right">(Full Transparency Public, Presumably)</span>
</label>
<label for="revStatus8" class="finger"><div class="disIn mR5">
    <input id="revStatus8" type="radio" name="revStatus" autocomplete="off" value="Investigated (Closed)" 
        @if (!$firstReview && isset($lastReview->ComRevStatus) 
            && trim($lastReview->ComRevStatus) == 'Investigated (Closed)') SELECTED
        @endif ></div> Investigated 
        <span class="fPerc66 slGrey pull-right">(Full Transparency Public, Closed)</span>
</label>

<div class="p10"></div>

<label for="revStatus9" class="finger"><div class="disIn mR5">
    <input id="revStatus9" type="radio" name="revStatus" autocomplete="off" value="Declined To Investigate (Closed)" 
        @if (!$firstReview && isset($lastReview->ComRevStatus) 
            && trim($lastReview->ComRevStatus) == 'Declined To Investigate (Closed)') SELECTED
        @endif ></div> Declined To Investigate 
        <span class="fPerc66 slGrey pull-right">(Full Transparency Public, Closed)</span>
</label>
<label for="revStatus10" class="finger"><div class="disIn mR5">
    <input id="revStatus10" type="radio" name="revStatus" autocomplete="off" value="Closed" 
        @if (!$firstReview && isset($lastReview->ComRevStatus) 
            && trim($lastReview->ComRevStatus) == 'Closed') SELECTED
        @endif ></div> Otherwise Closed 
        <span class="fPerc66 slGrey pull-right">(Full Transparency Public, Closed)</span>
</label>

<div class="p10"></div>

<label for="revStatus13" class="finger"><div class="disIn mR5">
    <input id="revStatus13" type="radio" name="revStatus" autocomplete="off" value="Incomplete" 
        @if (!$firstReview && isset($lastReview->ComRevStatus) 
            && trim($lastReview->ComRevStatus) == 'Incomplete') SELECTED
        @endif ></div> Incomplete <span class="fPerc66 slGrey pull-right">(Un-Publish)</span>
</label>
<label for="revStatus14" class="finger"><div class="disIn mR5">
    <input id="revStatus14" type="radio" name="revStatus" autocomplete="off" value="Hold: Not Sure" 
        @if (!$firstReview && isset($lastReview->ComRevStatus) 
            && trim($lastReview->ComRevStatus) == 'Hold: Not Sure') SELECTED
        @endif ></div> Not Sure <span class="fPerc66 slGrey pull-right">(Requires More Review, On Hold)</span>
</label>

<div class="p10"></div>
</div> <!-- end nFld -->
