<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-status.blade.php -->

@if ($comStatus == 'New')
    <div class="alert alert-danger fade in alert-dismissible show mB30" 
        style="padding: 10px 15px;">
        <h4>
            Is this complaint OK to Submit 
            <nobr>to Investigative Agency?</nobr>
        </h4>
    </div>
@endif

<form name="comUpdate" id="comUpdateID" method="post">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="cid" value="{{ $complaintRec->com_id }}">
<input type="hidden" name="ajax" value="1">
<input type="hidden" name="save" value="1">
<input type="hidden" name="refresh" value="1">
<input type="hidden" name="revType" value="Update">

<h4>Update Complaint Status</h4>

<div class="row mT15 mB10">
    <div class="col-md-8 col-sm-12">
        <div id="hidivlegitType" class="
            @if ($comStatus == 'Incomplete') disBlo @else disNon @endif ">
            <select name="revComplaintType" id="revComplaintTypeID" 
                autocomplete="off" class="form-control form-control-lg mB10">
                <option value="">Select complaint type...</option>
                <option value="194" 
                    @if ($complaintRec->com_status == 194) SELECTED @endif 
                    >Incomplete</option>
                <option value="295" @if ($complaintRec->com_type == 295 
                    && $complaintRec->com_status != 194) SELECTED @endif 
                    >Unreviewed</option>
                <option value="296" @if ($complaintRec->com_type == 296 
                    && $complaintRec->com_status != 194) SELECTED @endif 
                    >Complaint About Police</option>
                <option value="297" @if ($complaintRec->com_type == 297 
                    && $complaintRec->com_status != 194) SELECTED @endif 
                    >Not About Police</option>
                <option value="298" @if ($complaintRec->com_type == 298 
                    && $complaintRec->com_status != 194) SELECTED @endif 
                    >Abuse</option>
                <option value="299" @if ($complaintRec->com_type == 299 
                    && $complaintRec->com_status != 194) SELECTED @endif 
                    >Spam</option>
                <option value="300" @if ($complaintRec->com_type == 300 
                    && $complaintRec->com_status != 194) SELECTED @endif 
                    >Test Submission</option>
                <option value="301" @if ($complaintRec->com_type == 301 
                    && $complaintRec->com_status != 194) SELECTED @endif 
                    >Not Sure</option>
            </select>
        </div>
        <div id="hidivlegitStatus" class="
            @if ($comStatus == 'Incomplete') disNon @else disBlo @endif ">
            <select name="revStatus" id="revStatusID" 
                autocomplete="off"
                class="form-control form-control-lg mB10">
                <option value="">Select complaint status...</option>
                {!! view(
                    'vendor.openpolice.nodes.1712-report-inc-status', 
                    [
                        "firstReview"  => false, 
                        "lastReview"   => $lastReview, 
                        "fullList"     => true
                    ]
                )->render() !!}
            </select>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 pB20">
        <div class=" @if ($comStatus != 'Incomplete') disBlo @else disNon @endif ">
            Complaint Type:<br />
            <a class="hidivBtn" id="hidivBtnlegitType" 
                href="javascript:;">{{ $GLOBALS['SL']->def->getVal(
                    'Complaint Type', 
                    $complaintRec->com_type
                ) }} <i class="fa fa-pencil fPerc66"></i>
            </a>
        </div>
    </div>
</div>

@if (!in_array($comStatus, ['Incomplete', 'New'])) 
    <div id="progDates" class="disBlo">
        {!! view(
            'vendor.openpolice.nodes.1712-report-inc-tools-progress-dates', 
            [
                "complaint"      => $complaintRec,
                "comDepts"       => $comDepts,
                "oversightDates" => $oversightDates
            ]
        )->render() !!}
        <p><hr></p>
    </div>
@endif

<p><b>Notes for other evaluators</b></p>
<textarea name="revNote" class="form-control form-control-lg" 
    style="height: 90px;"></textarea>

<div class="w100 mT20 mB20">
    <a class="btn btn-lg btn-primary" id="stfBtn7" 
        href="javascript:;" style="color: #FFF;" 
        onMouseOver="this.style.color='#2b3493';" 
        onMouseOut="this.style.color='#FFF';"
        >Save Status Update</a>
</div>

</form>

<div id="docUploads" class="
    @if ($comStatus == 'Incomplete') disNon @else disBlo @endif ">
    <div class="p20"> </div>
{!! view(
    'vendor.openpolice.nodes.1712-report-inc-staff-tools-report-upload', 
    [
        "complaintRec"       => $complaintRec,
        "reportUploadTypes"  => $reportUploadTypes,
        "reportUploadFolder" => $reportUploadFolder
    ]
)->render() !!}
</div>

<div class="p20"> </div>
{!! view(
    'vendor.openpolice.nodes.1712-report-inc-staff-tools-report-dept', 
    [
        "complaintRec"  => $complaintRec,
        "incidentState" => $incidentState
    ]
)->render() !!}

<div class="pT20 mT20 pB20">
    <a href="?refresh=2{{ $GLOBALS['SL']->getReqParams() }}"
        class="btn btn-lg btn-secondary pull-left mR10"
        ><i class="fa fa-refresh mR3" aria-hidden="true"></i> 
        Refresh Report
    </a>
    <a href="/switch/1/{{ $complaintRec->com_id }}"
        class="btn btn-lg btn-secondary pull-left mR10"
        ><i class="fa fa-pencil mR3" aria-hidden="true"></i> 
        Edit Complaint
    </a>
</div>
<div class="p20"> </div>
