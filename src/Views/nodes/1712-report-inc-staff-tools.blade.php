<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools.blade.php -->
<div style="margin-top: -10px;"></div>

@if (!isset($complaintRec->ComStatus) || intVal($complaintRec->ComStatus) <= 0 || 
    $GLOBALS["SL"]->def->getVal('Complaint Status', $complaintRec->ComStatus) == 'Incomplete')


@elseif ($firstReview)
    
    <div class="brdTopGrey" style="padding: 15px 0px 25px 0px;">
    {!! $GLOBALS["SL"]->printAccordian(
        '#' . $complaintRec->ComPublicID . ': Is this a complaint?',
        view('vendor.openpolice.nodes.1712-report-inc-staff-tools-first-review')->render(),
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
            view('vendor.openpolice.nodes.1712-report-inc-history', [
                "history" => $history
            ])->render(),
            false,
            false,
            'text'
        ) !!}
        </div>

        <div class="brdTopGrey" style="padding: 15px 0px 25px 0px;">
        {!! $GLOBALS["SL"]->printAccordian(
            (($firstRevDone) ? 'Next, Update Complaint Status:' : 'Update Complaint Status'),
            view('vendor.openpolice.nodes.1712-report-inc-staff-tools-status', [
                "complaintRec"   => $complaintRec,
                "lastReview"     => $lastReview,
                "comDepts"       => $comDepts,
                "oversightDates" => $oversightDateLookups
            ])->render(),
            (($firstRevDone) ? true : false),
            false,
            'text'
        ) !!}
        </div>

        <div class="brdTopGrey" style="padding: 15px 0px 25px 0px;">
        {!! $GLOBALS["SL"]->printAccordian(
            'Select Email Template',
            view('vendor.openpolice.nodes.1712-report-inc-staff-tools-choose-email', [
                "complaintRec" => $complaintRec,
                "emailList"    => $emailList,
                "emailID"      => $emailID
            ])->render(),
            false,
            false,
            'text'
        ) !!}
        </div>
        
        @if (intVal($emailID) > 0 && sizeof($currEmail) > 0)
            <div class="brdTopGrey" style="padding: 15px 0px 25px 0px;">
            {!! $GLOBALS["SL"]->printAccordian(
                'Send Email',
                view('vendor.openpolice.nodes.1712-report-inc-staff-tools-email', [
                    "complaintRec" => $complaintRec,
                    "currEmail"    => $currEmail,
                    "emailID"      => $emailID,
                    "emailsTo"     => $emailsTo
                ])->render(),
                true,
                false,
                'text'
            ) !!}
            </div>
        @endif

    </div>
        
@endif
