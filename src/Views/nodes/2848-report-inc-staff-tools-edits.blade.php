<!-- resources/views/vendor/openpolice/nodes/2848-report-inc-staff-tools-edits.blade.php -->

<div class="mTn5 pB30 mB30">
{!! view(
    'vendor.openpolice.nodes.1712-report-inc-staff-tools-report-dept', 
    [
        "complaintRec"  => $complaintRec,
        "incidentState" => $incidentState
    ]
)->render() !!}
</div>

<a href="/switch/1/{{ $complaintRec->com_id }}"
    class="btn btn-secondary" target="_blank"
    ><i class="fa fa-pencil mR3" aria-hidden="true"></i> 
    Edit Complaint
</a>
<div class="pT5 pB30">
    <p>
@if (Auth::user() && in_array(Auth::user()->id, [1, 32]))
    <a href="/dashboard/debug-node-saves?tree=1&cidi={{ 
        $complaintRec->com_id }}" target="_blank"
        >Response History</a><br />
@endif
    <a href="?refresh=2{{ $GLOBALS['SL']->getReqParams() }}"
        >Refresh Report</a><br />
    <a href="/complaint/read-{{ $complaintRec->com_public_id 
        }}/full-pdf?pdf=1&refresh=1" target="_blank"
        >Refresh Sensitive PDF</a><br />
    <a href="/complaint/read-{{ $complaintRec->com_public_id 
        }}/full-pdf?pdf=1&refresh=1&publicView=1" target="_blank"
        >Refresh Public PDF</a>
    </p>
</div>
