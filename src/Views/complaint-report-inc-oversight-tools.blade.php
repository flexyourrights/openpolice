<!-- resources/views/vendor/openpolice/complaint-report-inc-oversight-tools.blade.php -->
<div class="row2 p20">
    <p>You are logged in with an oversight agency responsible for this complaint (tied to {{ $user->email }}).
        Open Police Complaints (OPC) provides you access to the complete details of this complaint, 
        potentially including sensitive information required for your investigation of the incident.</p>
    <h3 class="disIn">Download Complete Complaint for Investigation:</h3>
    <a href="/complaint-read/{{ $complaint->ComID }}/pdf/full" class="btn btn-lg btn-primary mL10" target="_blank"
        >Print Complaint (or Save as PDF)</a>
    <a href="/complaint-read/{{ $complaint->ComID }}/xml/full" class="btn btn-lg btn-primary mL10" target="_blank"
        >Download Raw Data File</a>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <h3 class="mT0">Update Complaint Status:</h3>
        </div>
        <div class="col-md-6">
            <div class="pT5">Current Status: &nbsp;&nbsp;&nbsp;
            {{ $GLOBALS["SL"]->getDefValById($complaint->ComStatus) }}</div>
        </div>
    </div>
    <form method="post" name="accessCode" action="?overUpdate=1">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row">
        <div class="col-md-6">
            <div class="nFld mT0">
                <label class="finger">
                    <input type="checkbox" id="overReceiveID" name="overReceive" autocomplete="off" value="1"
                        @if (isset($overUpdateRow->LnkComOverReceived) 
                            && trim($overUpdateRow->LnkComOverReceived) != '') CHECKED @endif > 
                    <span class="mL5">Received By Your Oversight Agency</span>
                </label>
                <br />
                <a id="notCompliantLnk" href="javascript:;" class="fPerc80"><br />
                    Does your oversight agency require complaints to be submitted another way to be investigated?</a>
                <div id="notCompliant" class="disNon">
                    <label class="finger">
                        <input type="checkbox" id="notCompliantChkID" name="notCompliantChk" autocomplete="off" 
                            value="1"> <span class="mL5">This oversight agency cannot investigate complaints electronically 
                            submitted by OpenPolice.org. OPC staff will have to advise the complainant to submit their 
                            complaint another way.</span>
                    </label>
                    Notes for OPC Staff:<br />
                    <textarea name="overNote" class="w100"></textarea>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="nFld mT0">
                <label class="finger">
                    <input type="radio" id="overStatus0" name="overStatus" autocomplete="off" 
                        value="Submitted to Oversight" @if ($GLOBALS["SL"]->getDefValById($complaint->ComStatus) 
                            == 'Submitted to Oversight') CHECKED @endif > 
                        <span class="mL5">Submitted To Your Oversight Agency</span>
                </label>
                <label class="finger">
                    <input type="radio" id="overStatus1" name="overStatus" autocomplete="off" 
                        value="Investigated (Closed)" @if ($GLOBALS["SL"]->getDefValById($complaint->ComStatus) 
                            == 'Investigated (Closed)') CHECKED @endif >
                        <span class="mL5">Investigated By Your Oversight Agency (Closed)</span>
                </label>
                <label class="finger">
                    <input type="radio" id="overStatus2" name="overStatus" autocomplete="off" 
                        value="Declined To Investigate (Closed)" 
                        @if ($GLOBALS["SL"]->getDefValById($complaint->ComStatus) 
                            == 'Declined To Investigate (Closed)') CHECKED @endif > 
                        <span class="mL5">Your Oversight Agency Declined To Investigate (Closed)</span>
                </label>
            </div>
            <input type="submit" value="Save Status Changes" class="btn btn-lg btn-primary disBlo mT20">
            </form>
        </div>
    </div>
</div>
<hr><center><i>
Below, is the complete complaint <b>including sensitive information provided only for investigation</b>. 
</i></center><hr>