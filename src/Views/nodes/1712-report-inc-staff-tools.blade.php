<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools.blade.php -->
<?php $defSet = 'Complaint Status'; ?>
@if (isset($complaintRec->com_status)
    && $GLOBALS["SL"]->def->getVal($defSet, $complaintRec->com_status) 
        == 'Incomplete')
    
    <div class="pB15">
        <div class="row" style="color: #333;">
            <div class="col-lg-4">
                <div class="relDiv ovrSho" style="height: 30px;">
                    <div class="absDiv" style="top: 8px; left: 15px;">
                        <div class="vertPrgDone" 
                            style="background: #FF6059;"></div>
                    </div>
                    <div class="absDiv" style="top: 7px; left: 40px;">
                        Incomplete
                    </div>
                </div>
            </div>
        </div>
    </div>

@elseif (isset($complaintRec->com_type)
    && $GLOBALS["SL"]->def->getVal('Complaint Type', $complaintRec->com_type) 
        == 'Police Complaint')

    {!! view(
        'vendor.openpolice.nodes.1712-report-inc-staff-tools-warnings', 
        [ "complaintRec" => $complaintRec ]
    )->render() !!}

@endif

@if ($firstReview 
    && $GLOBALS["SL"]->def->getVal($defSet, $complaintRec->com_status) 
        != 'Incomplete')
    
    <div class="pT15 pB15 slBlueDark">
        <i class="fa fa-check-square-o mL5 mR5" aria-hidden="true"></i>
            <b>Is this a <i>Police</i> Complaint?</b>
            {!! str_replace('Next', 'First', $alertIco) !!}
    </div>
    {!! view(
        'vendor.openpolice.nodes.1712-report-inc-staff-tools-first-review',
        [ "complaintRec" => $complaintRec ]
    )->render() !!}
    
@elseif (in_array($view, ['', 'history', 'update', 'emails', 'emailsType']))

    <div id="analystHistory">

        <div class="brdTopGrey">
        {!! $GLOBALS["SL"]->printAccordian(
            '<i class="fa fa-sliders mL5 mR5" aria-hidden="true"></i> ' . $updateTitle,
            '<div class="pL15 pR15">' . view(
                'vendor.openpolice.nodes.1712-report-inc-staff-tools-status', 
                [
                    "complaintRec"       => $complaintRec,
                    "comStatus"          => $GLOBALS['SL']->def->getVal(
                        $defSet, 
                        $complaintRec->com_status
                    ),
                    "lastReview"         => $lastReview,
                    "comDepts"           => $comDepts,
                    "oversightDates"     => $oversightDateLookups,
                    "reportUploadTypes"  => $reportUploadTypes,
                    "reportUploadFolder" => $reportUploadFolder,
                    "incidentState"      => $incidentState,
                    "uID"                => $uID
                ]
            )->render() . '</div>',
            false,
            false,
            'text',
            $ico
        ) !!}
        </div>

    @if (isset($complaintRec->com_status) && intVal($complaintRec->com_status) > 0)
        <div class="brdTopGrey">
        {!! $GLOBALS["SL"]->printAccordian(
            '<i class="fa fa-envelope-o mL5 mR5" aria-hidden="true"></i>'
                . ' <b>Send Email</b>' . (($emailID > 0) ? $alertIco : ''),
            '<div class="pL15 pR15">' . view(
                'vendor.openpolice.nodes.1712-report-inc-staff-tools-email', 
                [
                    "complaintRec"       => $complaintRec,
                    "currEmail"          => $currEmail,
                    "emailList"          => $emailList,
                    "emailID"            => $emailID,
                    "emailsTo"           => $emailsTo,
                    "comDepts"           => $comDepts,
                    "deptID"             => $deptID,
                    "reportUploadTypes"  => $reportUploadTypes,
                    "reportUploadFolder" => $reportUploadFolder
                ]
            )->render() . '</div>',
            false,
            false,
            'text',
            $ico
        ) !!}
        <?php /* (($emailID > 0) ? true : false), */ ?>
        </div>
    @endif

        <div class="brdTopGrey">
        {!! $GLOBALS["SL"]->printAccordian(
            '<i class="fa fa-pencil-square-o mL5 mR5" aria-hidden="true"></i>'
                . ' <b>Make Complaint Corrections</b>',
            '<div class="pL15 pR15">' . view(
                'vendor.openpolice.nodes.1712-report-inc-staff-tools-edits', 
                [
                    "complaintRec"       => $complaintRec,
                    "comStatus"          => $GLOBALS['SL']->def->getVal(
                        'Complaint Status', 
                        $complaintRec->com_status
                    ),
                    "reportUploadTypes"  => $reportUploadTypes,
                    "reportUploadFolder" => $reportUploadFolder,
                    "incidentState"      => $incidentState
                ]
            )->render() . '</div>',
            false,
            false,
            'text',
            $ico
        ) !!}
        </div>

        <div class="brdTopGrey">
        {!! $GLOBALS["SL"]->printAccordian(
            '<i class="fa fa-comments-o mL5 mR5" aria-hidden="true"></i>'
                . ' <b>Complaint History</b>',
            '<div class="pL15 pR15">' . view(
                'vendor.openpolice.nodes.1712-report-inc-history', 
                [ "history" => $history ]
            )->render() . '</div>',
            (($view == 'history' 
                || ($GLOBALS["SL"]->REQ->has('view') 
                    && $GLOBALS["SL"]->REQ->view == 'history'))
                ? true : false),
            false,
            'text',
            $ico
        ) !!}
        </div>

    </div>
        
@endif

<style>
#blockWrap2336 { display: none; }
@if ($GLOBALS["SL"]->REQ->has('frame') || $GLOBALS["SL"]->REQ->has('wdg'))
    #node2632kids { margin-top: -20px; }
@endif
</style>

<script type="text/javascript"> $(document).ready(function(){

$(document).on("change", "#hidivlegitType", function() {
    if (parseInt($(this).find(":selected").val()) == 194) {
        $("#hidivlegitStatus").slideUp("fast");
        $("#progDates").slideUp("fast");
        if (document.getElementById("docUploads")) {
            $("#docUploads").slideUp("fast");
        }
    } else {
        $("#hidivlegitStatus").slideDown("fast");
        $("#progDates").slideDown("fast");
        if (document.getElementById("docUploads")) {
            $("#docUploads").slideDown("fast");
        }
    }
    return true;
});

function postToolboxUpdateStatus() {
    if (document.getElementById('complaintToolbox')) {
        var formData = new FormData($('#comUpdateID').get(0));
        document.getElementById('complaintToolbox').innerHTML = getSpinner();
        window.scrollTo(0, 0);
        $.ajax({
            url: "/complaint-toolbox",
            type: "POST", 
            data: formData, 
            contentType: false,
            processData: false,
            //headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
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

$("#stfBtn7").click(function() {
    postToolboxUpdateStatus();
});

$("#comUpdateID").submit(function( event ) {
    postToolboxUpdateStatus();
    event.preventDefault();
});


function loadComplaintEmail() {
    if (document.getElementById('emailFormWrap')) {
        document.getElementById('emailFormWrap').innerHTML = getSpinner();
        var url = "/complaint-toolbox/?cid={{ $complaintRec->com_id }}&ajax=1&refresh=1&ajaxEmaForm=1&email="+document.getElementById("emailID").value;
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

function pushDeptID() {
    if (document.getElementById('emailDeptID') && document.getElementById('emailSubDeptID')) {
        document.getElementById('emailSubDeptID').value = document.getElementById('emailDeptID').value;
    }
}
setTimeout(function() { pushDeptID(); }, 100);


}); </script>
