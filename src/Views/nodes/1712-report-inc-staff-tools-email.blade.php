<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-email.blade.php -->

<form action="?view=emails{{ (($GLOBALS['SL']->REQ->has('frame')) ? '&frame=1' : '') }}" 
    method="post" onSubmit="return chkEmaForm();" >
<input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="cID" value="{{ $complaintRec->ComPublicID }}">
<input type="hidden" name="emailID" value="{{ $emailID }}">
<a name="emailer"></a>
<div id="analystEmailComposer" class=" @if (intVal($emailID) > 0) disBlo @else disNon @endif ">
    <h2 class="mT0"><i class="fa fa-envelope" aria-hidden="true"></i> 
        Complaint #{{ $complaintRec->ComPublicID }}:&nbsp;&nbsp;&nbsp;
        Sending @if (sizeof($currEmail) > 1) Emails @else Email @endif</h2>
    @if ($emailID == 12)
        @forelse ($GLOBALS["SL"]->x["depts"] as $deptID => $stuff)
            @if (!isset($stuff["overUser"]) || !isset($stuff["overUser"]->email))
                <div class="alert alert-danger mT10 fPerc133" role="alert">
                    <strong>{{ $stuff["deptRow"]->DeptName }}</strong> 
                    is <nobr>NOT OPC-Compliant!</nobr><br />Do not send them an email!</div>
            @endif
        @empty
        @endforelse
    @endif

    <div class="nFld m0">
    @forelse ($currEmail as $j => $email)
        @if ($j > 0) <div class="pT20"><hr></div> @else <div class="p5"></div> @endif
        Send To
        <select class="form-control form-control-lg w100 changeEmailTo" autocomplete=off 
            name="emailTo{{ $j }}" id="emailTo{{ $j }}ID">
        @forelse ($emailsTo[$email["rec"]->EmailType] as $i => $ema)
            <option value="{{ $ema[0] }}" @if ($ema[2]) SELECTED @endif 
                >{{ $ema[1] }} ({{ $ema[0] }}) </option>
        @empty
        @endforelse
            <option value="--CUSTOM--">Type in custom email address:</option>
        </select>
        <div id="emailTo{{ $j }}CustID" class="row mT5 disNon">
            <div class="col-6">
                Recipient Name
                <input type="text" name="emailTo{{ $j }}CustName" id="emailTo{{ $j }}CustNameID" 
                    class="form-control form-control-lg" autocomplete=off >
            </div>
            <div class="col-6">
                Recipient Email
                <input type="text" name="emailTo{{ $j }}CustEmail" id="emailTo{{ $j }}CustEmailID" 
                    class="form-control form-control-lg" autocomplete=off >
            </div>
        </div>
        <div class="p10"></div>
        Email Subject
        <input type="text" class="form-control form-control-lg w100" name="emailSubj{{ $j }}" 
            id="emailSubj{{ $j }}ID" value="{{ $email['subject'] }}" autocomplete=off >
        <div class="p10"></div>
        Email Body
        <textarea name="emailBodyCust{{ $j }}" id="emailBodyCust{{ $j }}ID" class="w100" 
            style="height: 500px;" autocomplete=off >{!! $email["body"] !!}</textarea>
    @empty
    @endforelse
    </div>
    
    @if ($emailID == 12)
        @forelse ($GLOBALS["SL"]->x["depts"] as $deptID => $stuff)
            @if (!isset($stuff["overUser"]) || !isset($stuff["overUser"]->email))
                <div class="alert alert-danger mT10 fPerc133" role="alert">
                    <strong>{{ $stuff["deptRow"]->DeptName }}</strong> 
                    is NOT OPC-Compliant!<br />Do not send them an email!</div>
            @endif
        @empty
        @endforelse
    @endif
    
    <div class="m20">
        <input type="submit" class="btn btn-lg btn-primary" id="stfBtn9"
        onMouseOver="this.style.color='#2b3493';" onMouseOut="this.style.color='#FFF';"
        style="color: #FFF;" value="Send Email">
    </div>
    
</div>
    
</form>
<script type="text/javascript">
function chkEmaForm() {
    for (var j=0; j < {{ sizeof($currEmail) }}; j++) {
        if (!document.getElementById('emailTo'+j+'ID') 
            || document.getElementById('emailTo'+j+'ID').value.trim() == '') {
            alert("Please provide an email address to send this message.");
            return false;
        }
    }
    return true;
}
</script>
