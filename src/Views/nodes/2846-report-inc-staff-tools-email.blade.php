<!-- resources/views/vendor/openpolice/nodes/2846-report-inc-staff-tools-email.blade.php -->

<div class="disBlo pB30">
    Select Email Template<br />
    <select name="email" id="emailID" autocomplete="off" 
        class="form-control form-control-lg mB5">
        <option value="" >No email right now</option>
    @forelse ($emailList as $i => $email)
        <?php $emaID2 = substr($email->email_name, 0, 2); ?>
        <?php $emaID3 = substr($email->email_name, 2, 1); ?>
        @if ($email->email_type != 'Blurb')
            @if (in_array($emaID2, [ '01', '10', '16' ]) && $emaID3 == '.')
                <option disabled ></option>
            @endif
            @if ($emaID2 != '01' || $email->email_id == 36)
                <option value="{{ $email->email_id }}" 
                    @if ($emailID == $email->email_id) SELECTED @endif
                    >{{ $email->email_name }} 
                    ({{ str_replace('Oversight', 'IA', $email->email_type) }})
                </option>
            @endif
        @endif
    @empty
    @endforelse
    </select>
    <div id="emailChooseDept" class="
        @if ($GLOBALS['SL']->x['deptsCnt'] > 1 && $emailID == 12) disBlo 
        @else disNon 
        @endif ">
        @if ($GLOBALS["SL"]->x["deptsCnt"] > 1)
            <div class="pT30">
                Select Investigative Agency<br />
                <select name="d" id="emailDeptID" autocomplete="off" 
                    class="form-control form-control-lg">
                @forelse ($comDepts as $cnt => $dept)
                    <option value="{{ $dept['id'] }}"
                        @if ($dept['id'] == $deptID) SELECTED @endif
                        >{{ $dept['name'] }}</option>
                @empty
                @endforelse
                </select>
            </div>
        @else
            <input name="d" id="emailDeptID" type="hidden" 
            @if (sizeof($comDepts) > 0 && isset($comDepts[0]['id']))
                value="{{ $comDepts[0]['id'] }}"
            @else
                value=""
            @endif >
        @endif
    </div>

    <div id="emailFormWrap" class="w100 pB15">
    @if (intVal($emailID) > 0 && sizeof($currEmail) > 0)
        {!! view(
            'vendor.openpolice.nodes.1712-report-inc-staff-tools-email-form', 
            [
                "emailID"           => $emailID,
                "currEmail"         => $currEmail, 
                "complaintRec"      => $complaintRec, 
                "emailID"           => $emailID,
                "emailsTo"          => $emailsTo,
                "deptID"            => $deptID,
                "reportUploadTypes" => $reportUploadTypes
            ]
        )->render() !!}
    @endif
    </div>
</div>

<script type="text/javascript"> $(document).ready(function(){


function loadComplaintEmail() {
    if (document.getElementById('emailFormWrap')) {
        document.getElementById('emailFormWrap').innerHTML = getSpinner();
        var url = "/dash/complaint-toolbox-email-form/readi-{{ $complaintRec->com_id }}?ajax=1&ajaxEmaForm=1&email="+document.getElementById("emailID").value;
        var deptID = 0;
        if (document.getElementById('emailDeptID')) {
            deptID = document.getElementById('emailDeptID').value;
        }
        if (deptID > 0) {
            url += "&d="+deptID;
        }
        console.log(url);
        $("#emailFormWrap").load(url);
    }
}

@if ($GLOBALS["SL"]->x["deptsCnt"] > 1)
    $(document).on("change", "#emailID", function() { 
        if (document.getElementById('emailChooseDept')) {
            var emailID = document.getElementById('emailID').value;
            if (emailID == 12) {
                $("#emailChooseDept").slideDown("fast");
            } else {
                $("#emailChooseDept").slideUp("fast");
            }
        }
        loadComplaintEmail();
    });

    $(document).on("change", "#emailDeptID", function() {
        loadComplaintEmail();
    });
@endif

function pushDeptID() {
    if (document.getElementById('emailDeptID') && document.getElementById('emailSubDeptID')) {
        document.getElementById('emailSubDeptID').value = document.getElementById('emailDeptID').value;
    }
}
setTimeout(function() { pushDeptID(); }, 100);

}); </script>
