<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools.blade.php -->
<div style="margin-top: -10px;"></div>

@if (isset($complaintRec->ComStatus)
    && $GLOBALS["SL"]->def->getVal('Complaint Status', 
        $complaintRec->ComStatus) != 'Incomplete')

    <div class="brdTopGrey" style="padding: 15px 0px 25px 0px;"> 
        <div class="row">
            <div class="col-md-6">
    {!! view(
        'vendor.openpolice.nodes.1712-report-inc-staff-tools-warnings', 
        [
            "complaintRec" => $complaintRec
        ]
    )->render() !!}
            </div>
            <div class="col-md-6">
    {!! view(
        'vendor.openpolice.nodes.1712-report-inc-staff-tools-warnings', 
        [
            "complaintRec" => $complaintRec
        ]
    )->render() !!}
            </div>
        </div>
    </div>

@endif

@if (!isset($complaintRec->ComStatus) 
    || intVal($complaintRec->ComStatus) <= 0)
    
    Complaint status not loading correctly.

@elseif ($firstReview && $GLOBALS["SL"]->def
    ->getVal('Complaint Status', $complaintRec->ComStatus) != 'Incomplete')
    
    <div class="brdTopGrey" style="padding: 15px 0px 25px 0px;">
    {!! $GLOBALS["SL"]->printAccordian(
        '#' . $complaintRec->ComPublicID . ': Is this a complaint?',
        view(
            'vendor.openpolice.nodes.1712-report-inc-staff-tools-first-review',
            [
                "complaintRec" => $complaintRec
            ]
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
                [
                    "history" => $history
                ]
            )->render(),
            false,
            false,
            'text'
        ) !!}
        </div>

        <div class="brdTopGrey" style="padding: 15px 0px 25px 0px;">
        {!! $GLOBALS["SL"]->printAccordian(
            (($firstRevDone) ? 'Next, Update Complaint Status' : 'Update Complaint Status'),
            view(
                'vendor.openpolice.nodes.1712-report-inc-staff-tools-status', 
                [
                    "complaintRec"       => $complaintRec,
                    "comStatus"          => $GLOBALS['SL']->def
                        ->getVal('Complaint Status', $complaintRec->ComStatus),
                    "lastReview"         => $lastReview,
                    "comDepts"           => $comDepts,
                    "oversightDates"     => $oversightDateLookups,
                    "reportUploadTypes"  => $reportUploadTypes,
                    "reportUploadFolder" => $reportUploadFolder,
                    "incidentState"      => $incidentState
                ]
            )->render(),
            (($firstRevDone) ? true : false),
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
