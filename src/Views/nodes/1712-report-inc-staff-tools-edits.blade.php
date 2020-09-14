<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-edits.blade.php -->

@if (isset($complaintRec->com_type)
    && in_array($GLOBALS["SL"]->def->getVal('Complaint Type', $complaintRec->com_type), 
        ['Unreviewed', 'Police Complaint', 'Not Sure']))

    {!! view(
        'vendor.openpolice.nodes.1712-report-inc-staff-tools-report-dept', 
        [
            "complaintRec"  => $complaintRec,
            "incidentState" => $incidentState
        ]
    )->render() !!}

    <div class="w100 p20"><center><div class="w50"><hr></div></center></div>

    <a href="?refresh=2{{ $GLOBALS['SL']->getReqParams() }}"
        class="btn btn-lg btn-secondary pull-left mR10"
        ><i class="fa fa-refresh mR3" aria-hidden="true"></i> 
        Refresh Report
    </a>
    <a href="/complaint/read-{{ $complaintRec->com_public_id 
            }}/full-pdf?pdf=1&refresh=1"
        class="btn btn-lg btn-secondary pull-left mR10" target="_blank"
        ><i class="fa fa-refresh mR3" aria-hidden="true"></i> 
        Refresh Sensitive PDF
    </a>
    <a href="/complaint/read-{{ $complaintRec->com_public_id 
            }}/full-pdf?pdf=1&refresh=1&publicView=1"
        class="btn btn-lg btn-secondary pull-left mR10" target="_blank"
        ><i class="fa fa-refresh mR3" aria-hidden="true"></i> 
        Refresh Public PDF
    </a>
    <div class="clearfix pB15"></div>

@if (Auth::user() && in_array(Auth::user()->id, [1, 32]))
    <a href="/dashboard/debug-node-saves?tree=1&cidi={{ $complaintRec->com_id }}"
        class="btn btn-lg btn-secondary pull-left mR10" target="_blank"
        ><i class="fa fa-history mR3" aria-hidden="true"></i>
        Response History
    </a>
@endif
    <a href="/switch/1/{{ $complaintRec->com_id }}" target="_blank"
        class="btn btn-lg btn-secondary pull-left mR10"
        ><i class="fa fa-pencil mR3" aria-hidden="true"></i> 
        Edit Complaint
    </a>
    <div class="clearfix pB30"></div>

@endif
