<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-status.blade.php -->

<form name="comUpdate" action="/complaint/read-{{ $complaintRec->ComPublicID }}?save=1" 
    method="post" >
<input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="cID" value="{{ $complaintRec->ComPublicID }}">
<input type="hidden" name="revType" value="Update">

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div id="legitTypeDrop" class="disNon">
            <select name="revComplaintType" class="form-control form-control-lg" autocomplete=off >
                <option value="">Select complaint type...</option>
                <option value="194" @if ($complaintRec->ComStatus == 194) SELECTED @endif 
                    >Incomplete</option>
                <option value="295" @if ($complaintRec->ComType == 295 && $complaintRec->ComStatus != 194) SELECTED @endif 
                    >Unreviewed</option>
                <option value="296" @if ($complaintRec->ComType == 296 && $complaintRec->ComStatus != 194) SELECTED @endif 
                    >Complaint About Police</option>
                <option value="297" @if ($complaintRec->ComType == 297 && $complaintRec->ComStatus != 194) SELECTED @endif 
                    >Not About Police</option>
                <option value="298" @if ($complaintRec->ComType == 298 && $complaintRec->ComStatus != 194) SELECTED @endif 
                    >Abuse</option>
                <option value="299" @if ($complaintRec->ComType == 299 && $complaintRec->ComStatus != 194) SELECTED @endif 
                    >Spam</option>
                <option value="300" @if ($complaintRec->ComType == 300 && $complaintRec->ComStatus != 194) SELECTED @endif 
                    >Test Submission</option>
                <option value="301" @if ($complaintRec->ComType == 301 && $complaintRec->ComStatus != 194) SELECTED @endif 
                    >Not Sure</option>
            </select>
        </div>
        <select name="revStatus" class="form-control form-control-lg mB20" autocomplete="off">
            <option value="">Select complaint status...</option>
            {!! view('vendor.openpolice.nodes.1712-report-inc-status', [
                "firstReview" => false, 
                "lastReview"  => $lastReview, 
                "fullList"    => true
                ])->render() !!}
        </select>
    </div>
    <div class="col-md-4 col-sm-12 pB20">
        Complaint Type:<br />
        <a href="javascript:;" id="legitTypeBtn">{{ 
            $GLOBALS['SL']->def->getVal('OPC Staff/Internal Complaint Type', $complaintRec->ComType)
            }} <i class="fa fa-pencil fPerc66"></i></a>
    </div>
</div>

{!! view('vendor.openpolice.nodes.1712-report-inc-tools-progress-dates', [
    "complaint"      => $complaintRec,
    "comDepts"       => $comDepts,
    "oversightDates" => $oversightDates
])->render() !!}

<p><hr></p>
<p><b>Notes for other evaluators</b></p>
<textarea name="revNote" class="form-control form-control-lg" style="height: 70px;" ></textarea>

<div class="w100 mT20 mB20">
    <a href="/switch/1/{{ $complaintRec->ComID }}"
        class="btn btn-lg btn-secondary pull-right"
        ><i class="fa fa-pencil" aria-hidden="true"></i> Edit Complaint</a>
    <a class="btn btn-lg btn-primary" id="stfBtn7" href="javascript:;" style="color: #FFF;" 
        onMouseOver="this.style.color='#2b3493';" onMouseOut="this.style.color='#FFF';"
        onClick="document.comUpdate.submit();" >Save Status Update</a>
</div>
</form>
