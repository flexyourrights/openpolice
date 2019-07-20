<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools.blade.php -->
<div class="pT5">

@if (!isset($complaintRec->ComStatus) || intVal($complaintRec->ComStatus) <= 0 || 
    $GLOBALS["SL"]->def->getVal('Complaint Status', $complaintRec->ComStatus) == 'Incomplete')

@elseif ($firstReview)
    
    <div class="pB20">
    {!! $GLOBALS["SL"]->printAccard(
        '#' . $complaintRec->ComPublicID . ': Is this a complaint?',
        view('vendor.openpolice.nodes.1712-report-inc-staff-tools-first-review')->render(),
        true
    ) !!}
    </div>
    
@elseif (in_array($view, ['', 'history', 'update', 'emails', 'emailsType']))

    <div id="analystHistory">
        
        @if (intVal($emailID) > 0 && sizeof($currEmail) > 0)
            
            <div class="pB20">
            {!! view('vendor.openpolice.nodes.1712-report-inc-staff-tools-email', [
                "complaintRec" => $complaintRec,
                "currEmail"    => $currEmail,
                "emailID"      => $emailID,
                "emailsTo"     => $emailsTo
            ])->render() !!}
            </div>

        @endif

        <div class="pB20">
        {!! $GLOBALS["SL"]->printAccard(
            'Complaint History',
            view('vendor.openpolice.nodes.1712-report-inc-history', [
                "history" => $history
            ])->render()
        ) !!}
        </div>
            
        <div class="pB20">
        {!! $GLOBALS["SL"]->printAccard(
            (($firstRevDone) ? 'Next, Update Complaint Status:' : 'Update Complaint Status'),
            view('vendor.openpolice.nodes.1712-report-inc-staff-tools-status', [
                "complaintRec" => $complaintRec,
                "lastReview"   => $lastReview
            ])->render(),
            (($firstRevDone) ? true : false)
        ) !!}
        </div>
            
        <div class="pB20">
        {!! $GLOBALS["SL"]->printAccard(
            'Send Email',
            view('vendor.openpolice.nodes.1712-report-inc-staff-tools-choose-email', [
                "complaintRec" => $complaintRec,
                "emailList"    => $emailList,
                "emailID"      => $emailID
            ])->render()
        ) !!}
        </div>
        
@endif

</div>