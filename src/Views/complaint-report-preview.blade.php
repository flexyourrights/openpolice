<!-- Stored in resources/views/openpolice/complaint-report-preview.blade.php -->
<div class="row slReportPreview">
    <div class="col-md-6">
        <h3 class="mT0">{!! str_replace('', '', substr($deptList, 1)) !!}</h3>
        <p><b class="fPerc125">Incident:</b> 
        @if (in_array($complaint->ComPrivacy, [306, 307]))
            {{ date('F Y', strtotime($incident->IncTimeStart)) }},
        @else
            {{ date('n/j/Y', strtotime($incident->IncTimeStart)) }},
        @endif
        {{ $incident->IncAddressCity }}, {{ $incident->IncAddressState }}
        </p>
        <b class="fPerc125">Allegations:</b><ul>{!! $basicAllegationListF !!}</ul>
    </div>
    <div class="col-md-6 pB10">
        <p class="fPerc125">{{ $storyPrev }}</p>
        <p><a href="/complaint-report/{{ $complaintID }}" class="btn btn-primary w100"
            >Read Misconduct Complaint #{{ $complaintID }}</a></p>
    </div>
</div>