<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-status.blade.php -->

<form name="comUpdate" action="/complaint/read-{{ $complaintRec->ComPublicID }}?save=1" 
    method="post" >
<input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="cID" value="{{ $complaintRec->ComPublicID }}">
<input type="hidden" name="revType" value="Update">
<select name="revStatus" class="form-control form-control-lg" autocomplete="off">
    {!! view('vendor.openpolice.nodes.1712-report-inc-status', [
        "firstReview" => false, 
        "lastReview"  => $lastReview, 
        "fullList"    => true
        ])->render() !!}
</select>

<div class="pT5">
    Complaint Type: <a href="javascript:;" id="legitTypeBtn">{{ 
        $GLOBALS['SL']->def->getVal('OPC Staff/Internal Complaint Type', $complaintRec->ComType)
        }} <i class="fa fa-pencil fPerc66"></i></a>
</div>
<div id="legitTypeDrop" class="disNon">
    <select name="revComplaintType" class="form-control form-control-lg" autocomplete=off >
        <option value="295" @if ($complaintRec->ComType == 295) SELECTED @endif >Unreviewed</option>
        <option value="296" @if ($complaintRec->ComType == 296) SELECTED @endif >Complaint About Police</option>
        <option value="297" @if ($complaintRec->ComType == 297) SELECTED @endif >Not About Police</option>
        <option value="298" @if ($complaintRec->ComType == 298) SELECTED @endif >Abuse</option>
        <option value="299" @if ($complaintRec->ComType == 299) SELECTED @endif >Spam</option>
        <option value="300" @if ($complaintRec->ComType == 300) SELECTED @endif >Test Submission</option>
        <option value="301" @if ($complaintRec->ComType == 301) SELECTED @endif >Not Sure</option>
    </select>
</div>

<h4 class="mT20 mB10">Notes for other evaluators:</h4>
<textarea name="revNote" class="form-control form-control-lg" style="height: 70px;" ></textarea>

<div class="w100 mT20">
    <a href="/switch/1/{{ $complaintRec->ComID }}"
        class="btn btn-lg btn-secondary pull-right"
        ><i class="fa fa-pencil" aria-hidden="true"></i> Edit Complaint</a>
    <a class="btn btn-lg btn-primary" id="stfBtn7" href="javascript:;" style="color: #FFF;" 
        onMouseOver="this.style.color='#2b3493';" onMouseOut="this.style.color='#FFF';"
        onClick="document.comUpdate.submit();" >Save Status Update</a>
</div>
</form>
