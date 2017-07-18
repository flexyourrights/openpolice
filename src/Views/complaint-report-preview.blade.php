<!-- Stored in resources/views/openpolice/complaint-report-preview.blade.php -->
<h3 class="mT0">{!! str_replace('', '', substr($deptList, 1)) !!}</h3>
<div class="row slReportPreview">
    <div class="col-md-6">
        <p class="fPerc125">{{ $storyPrev }}</p>
        <p><a href="/complaint-report/{{ $complaintID }}" class="btn btn-primary"
            >Read Complaint #{{ $complaintID }}</a></p>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-5 pB10">
        <b class="fPerc125">Incident:</b><ul>
        @if (in_array($complaint->ComPrivacy, [306, 307]))
            <li>{{ date('F Y', strtotime($incident->IncTimeStart)) }}</li>
        @else
            <li>{{ date('n/j/Y', strtotime($incident->IncTimeStart)) }}</li>
        @endif
        <li>{{ $incident->IncAddressCity }}, {{ $incident->IncAddressState }}</li>
        </ul>
        <b class="fPerc125">Allegations:</b><ul>{!! $basicAllegationListF !!}</ul>
    </div>
</div>