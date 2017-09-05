<!-- resources/views/vendor/openpolice/complaint-report-inc-owner-tools.blade.php -->
<div class="row2 p20">
    <p>
        As the owner of this complaint, Open Police Complaints (OPC) can provide you access to the complete details,
        potentially including your sensitive information.
        @if (!$GLOBALS["SL"]->REQ->has('publicView'))
            <a href="?publicView=public" class="mL10">Switch To Public View</a>
        @else
            <a href="?" class="mL10">Switch To Owner View</a>
        @endif
    </p>
    <h3 class="disIn">Download Complete Complaint:</h3>
    <a href="/complaint-read/{{ $complaint->ComID }}/pdf/full" class="btn btn-lg btn-primary mL10" target="_blank"
        >Print Complaint (or Save as PDF)</a>
    <a href="/complaint-read/{{ $complaint->ComID }}/xml/full" class="btn btn-lg btn-primary mL10" target="_blank"
        >Download Raw Data File</a>
    <hr>
    <h3 class="disIn mR5">Update Complaint Status:</h3>
    @if (isset($overUpdateRow->LnkComOverReceived) && trim($overUpdateRow->LnkComOverReceived) != '')
        Received by Oversight
    @else
        {{ $GLOBALS["SL"]->getDefValById($complaint->ComStatus) }}
    @endif
    <form method="post" name="accessCode" action="?ownerUpdate=1">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row">
        <div class="col-md-6">
            <div class="nFld mT0">
                <label class="finger">
                    <input type="radio" id="overStatus0" name="overStatus" autocomplete="off" 
                        value="OK to Submit to Oversight" @if ($GLOBALS["SL"]->getDefValById($complaint->ComStatus) 
                            == 'OK to Submit to Oversight') CHECKED @endif > 
                        <span class="mL5">OK To Submit To Oversight Agency</span>
                </label>
                <label class="finger">
                    <input type="radio" id="overStatus1" name="overStatus" autocomplete="off" 
                        value="Submitted to Oversight" @if ($GLOBALS["SL"]->getDefValById($complaint->ComStatus) 
                            == 'Submitted to Oversight' && (!isset($overUpdateRow->LnkComOverReceived) 
                            || trim($overUpdateRow->LnkComOverReceived) == '')) CHECKED @endif > 
                        <span class="mL5">Submitted To Oversight Agency</span>
                </label>
                <label class="finger">
                    <input type="radio" id="overStatus2" name="overStatus" autocomplete="off" 
                        value="Received By Oversight" @if (isset($overUpdateRow->LnkComOverReceived) 
                            && trim($overUpdateRow->LnkComOverReceived) != '') CHECKED @endif > 
                    <span class="mL5">Received By Oversight Agency</span>
                </label>
                <label class="finger">
                    <input type="radio" id="overStatus3" name="overStatus" autocomplete="off" 
                        value="Investigated (Closed)" @if ($GLOBALS["SL"]->getDefValById($complaint->ComStatus) 
                            == 'Investigated (Closed)') CHECKED @endif >
                        <span class="mL5">Investigated By Oversight Agency (Closed)</span>
                </label>
                <label class="finger">
                    <input type="radio" id="overStatus4" name="overStatus" autocomplete="off" 
                        value="Declined To Investigate (Closed)" 
                        @if ($GLOBALS["SL"]->getDefValById($complaint->ComStatus) 
                            == 'Declined To Investigate (Closed)') CHECKED @endif > 
                        <span class="mL5">Oversight Agency Declined To Investigate (Closed)</span>
                </label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="nFld mT0">
                Notes for OPC Staff:<br />
                <textarea name="overNote" class="w100"></textarea>
            </div>
            <input type="submit" value="Save Status Changes" class="btn btn-lg btn-primary disBlo mT20">
            </form>
        </div>
    </div>
</div>
<hr><center><i>
Below, is the complete complaint <b>including sensitive information provided only for investigation</b>. 
</i></center><hr>