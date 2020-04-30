<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools.blade.php -->

<div style="margin: -15px -15px 0px -15px;"></div>

@if (isset($complaintRec->com_status)
    && $GLOBALS["SL"]->def->getVal('Complaint Status', $complaintRec->com_status) 
        != 'Incomplete')

    <div class="brdTopGrey" style="padding: 15px 0px 15px 0px;"> 
        {!! view(
            'vendor.openpolice.nodes.1712-report-inc-staff-tools-warnings', 
            [ "complaintRec" => $complaintRec ]
        )->render() !!}
    </div>

@endif

@if (!isset($complaintRec->com_status) 
    || intVal($complaintRec->com_status) <= 0)
    
    Complaint status not loading correctly.

@elseif ($firstReview 
    && $GLOBALS["SL"]->def->getVal('Complaint Status', $complaintRec->com_status) 
        != 'Incomplete')
    
    <div class="brdTopGrey" style="padding: 15px 0px 25px 0px;">
    {!! $GLOBALS["SL"]->printAccordian(
        '#' . $complaintRec->com_public_id . ': Is this a complaint?',
        view(
            'vendor.openpolice.nodes.1712-report-inc-staff-tools-first-review',
            [ "complaintRec" => $complaintRec ]
        )->render(),
        true,
        false,
        'text'
    ) !!}
    </div>
    
@elseif (in_array($view, ['', 'history', 'update', 'emails', 'emailsType']))

    <div id="analystHistory">

        <div class="brdTopGrey" style="padding: 15px 0px 25px 0px;">
        {!! $GLOBALS["SL"]->printAccordian(
            'Complaint History',
            view(
                'vendor.openpolice.nodes.1712-report-inc-history', 
                [ "history" => $history ]
            )->render(),
            (($view == 'history' 
                || ($GLOBALS["SL"]->REQ->has('view') 
                    && $GLOBALS["SL"]->REQ->view == 'history'))
                ? true : false),
            false,
            'text'
        ) !!}
        </div>

        <div class="brdTopGrey" style="padding: 15px 0px 25px 0px;">
        {!! $GLOBALS["SL"]->printAccordian(
            (($firstRevDone 
                || ($GLOBALS["SL"]->REQ->has('open') 
                    && $GLOBALS["SL"]->REQ->open == 'status')) 
                ? 'Next, Update Complaint Status' 
                : 'Update Complaint Status'),
            view(
                'vendor.openpolice.nodes.1712-report-inc-staff-tools-status', 
                [
                    "complaintRec"       => $complaintRec,
                    "comStatus"          => $GLOBALS['SL']->def->getVal(
                        'Complaint Status', 
                        $complaintRec->com_status
                    ),
                    "lastReview"         => $lastReview,
                    "comDepts"           => $comDepts,
                    "oversightDates"     => $oversightDateLookups,
                    "reportUploadTypes"  => $reportUploadTypes,
                    "reportUploadFolder" => $reportUploadFolder,
                    "incidentState"      => $incidentState
                ]
            )->render(),
            (($firstRevDone 
                || $GLOBALS['SL']->def->getVal('Complaint Status', $complaintRec->com_status)
                    == 'New'
                || ($GLOBALS["SL"]->REQ->has('open') 
                    && $GLOBALS["SL"]->REQ->open == 'status'))
                ? true : false),
            false,
            'text'
        ) !!}
        </div>

        <div class="brdTopGrey" style="padding: 15px 0px 25px 0px;">
        {!! $GLOBALS["SL"]->printAccordian(
            'Send Email',
            view(
                'vendor.openpolice.nodes.1712-report-inc-staff-tools-email', 
                [
                    "complaintRec"       => $complaintRec,
                    "currEmail"          => $currEmail,
                    "emailList"          => $emailList,
                    "emailID"            => $emailID,
                    "emailsTo"           => $emailsTo,
                    "reportUploadTypes"  => $reportUploadTypes,
                    "reportUploadFolder" => $reportUploadFolder
                ]
            )->render(),
            (($emailID > 0) ? true : false),
            false,
            'text'
        ) !!}
        </div>

    </div>
        
@endif


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




    $(document).on("change", "#emailID", function() { 
        if (document.getElementById('emailFormWrap')) {
            document.getElementById('emailFormWrap').innerHTML = getSpinner();
            var url = "/complaint-toolbox/?cid={{ $complaintRec->com_id }}&ajax=1&refresh=1&ajaxEmaForm=1&email="+document.getElementById("emailID").value+"#emailer";
console.log(url);
            $("#emailFormWrap").load(url);
        }
    });





}); </script>

