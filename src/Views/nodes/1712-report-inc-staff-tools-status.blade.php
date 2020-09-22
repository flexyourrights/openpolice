<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-status.blade.php -->

<form name="comUpdate" id="comUpdateID" method="post">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="cid" value="{{ $complaintRec->com_id }}">
<input type="hidden" name="ajax" value="1">
<input type="hidden" name="save" value="1">
<input type="hidden" name="refresh" value="1">
<input type="hidden" name="revType" value="Update">

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div id="hidivlegitType" class="
            @if ($comStatus == 'Incomplete') disBlo @else disNon @endif ">
            <select name="revComplaintType" id="revComplaintTypeID" 
                autocomplete="off" class="form-control form-control-lg mB15">
                <option value="" DISABLED >Assign complaint type...</option>
                <option value="194" 
                    @if ($complaintRec->com_status == 194) SELECTED @endif 
                    >Incomplete</option>
                <option value="295" @if ($complaintRec->com_type == 295 
                    && $complaintRec->com_status != 194) SELECTED @endif 
                    >Unverified</option>
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
            <select name="revStatus" id="revStatusID" autocomplete="off"
                class="form-control form-control-lg mB15">
                <option value="" DISABLED 
                    >Assign complaint status...</option>
                {!! view(
                    'vendor.openpolice.nodes.1712-report-inc-status', 
                    [
                        "firstReview" => false, 
                        "lastStatus"  => ((isset($lastReview->com_rev_status))
                            ? trim($lastReview->com_rev_status) : ''), 
                        "fullList"    => true
                    ]
                )->render() !!}
            </select>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 pB15">
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

<div class="w100 pT15 pB15">
    <a class="btn btn-lg btn-primary" id="stfBtn7" 
        href="javascript:;" style="color: #FFF;" 
        onMouseOver="this.style.color='#2b3493';" 
        onMouseOut="this.style.color='#FFF';"
        >Save Status Update</a>
</div>

@if (!in_array($comStatus, ['Incomplete', 'New'])) 
    <div id="progDates" class="disBlo mB15">
    {!! view(
        'vendor.openpolice.nodes.1712-report-inc-tools-progress-dates', 
        [
            "complaint"      => $complaintRec,
            "comDepts"       => $comDepts,
            "oversightDates" => $oversightDates
        ]
    )->render() !!}
    </div>
@else
    <div class="pB15"></div>
@endif

<label class="w100 mB15">
    <div class="pB15"><b>Notes for other evaluators</b></div>
    <textarea name="revNote" class="form-control form-control-lg" 
        style="height: 90px;"></textarea>
</label>

</form>


@if (isset($complaintRec->com_type)
    && in_array($GLOBALS["SL"]->def->getVal('Complaint Type', $complaintRec->com_type), 
        ['Unverified', 'Police Complaint', 'Not Sure'])
    && $uID == 1)
    <div class="w100 pT30 pB15"><hr></div>

    <div id="docUploads" class="
        @if ($comStatus == 'Incomplete') disNon @else disBlo @endif ">
    {!! view(
        'vendor.openpolice.nodes.1712-report-inc-staff-tools-report-upload', 
        [
            "complaintRec"       => $complaintRec,
            "reportUploadTypes"  => $reportUploadTypes,
            "reportUploadFolder" => $reportUploadFolder
        ]
    )->render() !!}
    </div>
@endif

<div class="p15"> </div>