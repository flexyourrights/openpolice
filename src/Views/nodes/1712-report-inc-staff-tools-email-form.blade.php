<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-email-form.blade.php -->

<div class="nodeAnchor"><a name="emailer"></a></div>

<form name="complaintEmailForm" id="complaintEmailFormID">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="cid" value="{{ $complaintRec->com_id }}">
<input type="hidden" name="emailID" value="{{ $emailID }}">
<input type="hidden" name="view" value="emails">
<input type="hidden" name="refresh" value="1">
<input type="hidden" name="send" value="1">
<input type="hidden" name="ajax" value="1">
<input type="hidden" name="d" id="emailSubDeptID" 
    @if (!$GLOBALS['SL']->REQ->has('d')) value="0"
    @else value="{{ intVal($GLOBALS['SL']->REQ->get('d')) }}"
    @endif >

<div id="analystEmailComposer" 
    class=" @if (intVal($emailID) > 0) disBlo @else disNon @endif ">

@if ($emailID == 12 && $deptID > 0)
    @if (!isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_op_compliant) 
        || intVal($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_op_compliant) != 1)
        <div class="alert alert-danger mT15" role="alert"><b>
            Please don't send this email. The 
            {{ $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_name }} 
            doesn't accept complaints that are emailed or filed on third-party formats.
        </b></div>
    @endif
@endif

    <div class="nFld m0">
    @forelse ($currEmail as $j => $email)
        @if ($j > 0) <div class="pT20"><hr></div> 
        @else <div class="p5"></div> 
        @endif

        Send To
        @if (isset($email['rec']->email_type)
            && isset($emailsTo[$email['rec']->email_type])
            && sizeof($emailsTo[$email['rec']->email_type]) > 0)
            <select class="form-control form-control-lg w100 changeEmailTo" 
                name="emailTo{{ $j }}" id="emailTo{{ $j }}ID"
                autocomplete=off >
            @foreach ($emailsTo[$email["rec"]->email_type] as $i => $ema)
                <option value="{{ $ema[0] }}" @if ($ema[2]) SELECTED @endif 
                    >{{ $ema[1] }} ({{ $ema[0] }}) </option>
            @endforeach
                <option value="--CUSTOM--">Type in custom email address:</option>
            </select>
            <div class="p10"></div>
        @endif

        <div id="emailTo{{ $j }}CustID" class=" 
            @if (isset($email['rec']->email_type)
                && isset($emailsTo[$email['rec']->email_type])
                && sizeof($emailsTo[$email['rec']->email_type]) > 0) disNon 
            @else disBlo 
            @endif ">
            <div class="row">
                <div class="col-6">
                    Recipient Name
                    <input type="text" name="emailTo{{ $j }}CustName" 
                        id="emailTo{{ $j }}CustNameID" autocomplete=off 
                        class="form-control form-control-lg" >
                </div>
                <div class="col-6">
                    Recipient Email
                    <input type="text" name="emailTo{{ $j }}CustEmail" 
                        id="emailTo{{ $j }}CustEmailID" autocomplete=off
                        class="form-control form-control-lg" >
                </div>
            </div>
            <div class="p10"></div>
        </div>

        <div class="row">
            <div class="col-6">
                CC (in addition to yourself)
                <input type="text" class="form-control form-control-lg w100" 
                    name="emailCC{{ $j }}" id="emailCC{{ $j }}ID" 
                    value="{{ $email['cc'] }}" autocomplete=off >
                <div class="p10"></div>
            </div>
            <div class="col-6">
                BCC
                <input type="text" class="form-control form-control-lg w100" 
                    name="emailBCC{{ $j }}" id="emailBCC{{ $j }}ID" 
                    value="{{ $email['bcc'] }}" autocomplete=off >
                <div class="p10"></div>
            </div>
        </div>

        Attachment
        <select class="form-control form-control-lg w100" 
            name="attachType{{ $j }}" id="attachType{{ $j }}ID" 
            autocomplete=off >
            <option value=""
                @if (!isset($email['rec']->email_attach) 
                    || trim($email['rec']->email_attach) == '') 
                    SELECTED
                @endif >Select type ...</option>
            @foreach ($reportUploadTypes as $i => $type)
                @if (in_array($type[0], ['sensitive', 'public']))
                    <option value="{{ $type[0] }}"
                        @if (isset($email['rec']->email_attach)
                            && $email['rec']->email_attach == $type[0]) SELECTED @endif 
                        >{{ $type[1] }} PDF</option>
                @endif
            @endforeach
        </select>
        <div class="p10"></div>

        Email Subject
        <input type="text" class="form-control form-control-lg w100" 
            name="emailSubj{{ $j }}" id="emailSubj{{ $j }}ID" 
            value="{{ $email['subject'] }}" autocomplete=off >
        <div class="p10"></div>

        @if ($emailID == 12)
            <div class="alert alert-danger mT10" role="alert">
                <i class="fa fa-exclamation-triangle mR5" aria-hidden="true"></i>
                <b>DO NOT use the custom Key Code for testing. 
                This will "use it up" — break it — 
                before the investigative agency receives it.</b>
            </div>
        @endif

        Email Body
        <textarea name="emailBodyCust{{ $j }}" id="emailBodyCust{{ $j }}ID" 
            class="w100" style="height: 500px;" autocomplete=off 
            >{!! $email["body"] !!}</textarea>

        <script type="text/javascript">
        $(document).ready(function(){
            $("#emailBodyCust{{ $j }}ID").summernote({ height: 350 });
        });
        </script>
    @empty
    @endforelse
    </div>
    
    @if ($emailID == 12 && $deptID > 0)
        @if (!isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_op_compliant) 
            || intVal($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_op_compliant) != 1)
            <div class="alert alert-danger mT30" role="alert"><b>
                Please don't send this email. The 
                {{ $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_name }} 
                doesn't accept complaints that are emailed or filed on third-party formats.
            </b></div>
        @endif
    @endif
    
    <div class="mT20 mB20">
        <input type="submit" class="btn btn-lg btn-primary" 
            id="stfBtn9" value="Send Email">
    </div>
    
</div>
    
</form>


<script type="text/javascript"> $(document).ready(function(){

/*
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
*/
    function postToolboxSendEmail() {
        if (document.getElementById('complaintToolbox')) {
            var formData = new FormData($('#complaintEmailFormID').get(0));
            document.getElementById('complaintToolbox').innerHTML = getSpinner();
            window.scrollTo(0, 0);
            $.ajax({
                url: "/complaint-toolbox",
                type: "POST", 
                data: formData, 
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#complaintToolbox").empty();
                    $("#complaintToolbox").append(data);
                },
                error: function(xhr, status, error) {
                    $("#complaintToolbox").append("<div>(error - "+xhr.responseText+")</div>");
                }
            });
        }
        return false;
    }

    $("#stfBtn9").click(function() {
        postToolboxSendEmail();
    });

    $("#complaintEmailFormID").submit(function( event ) {
        postToolboxSendEmail();
        event.preventDefault();
    });

    $(document).on("change", ".changeEmailTo", function() { 
        var emaInd = $(this).attr("name").replace("emailTo", "");
        if (document.getElementById("emailTo"+emaInd+"ID") && document.getElementById("emailTo"+emaInd+"ID").value == "--CUSTOM--") {
            $("#emailTo"+emaInd+"CustID").slideDown("fast");
        } else {
            $("#emailTo"+emaInd+"CustID").slideUp("fast"); 
        }
    });


}); </script>
