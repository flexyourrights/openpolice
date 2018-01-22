<!-- Stored in resources/views/openpolice/complaint-report-inc-deets.blade.php -->
<div class="reportMiniBlockLabel">
    @if (isset($sessData["Departments"]) && sizeof($sessData["Departments"]) > 1) Departments
    @else Department @endif Involved
</div>
<div class="reportMiniBlockDeets">
@if (isset($sessData["Departments"]) && sizeof($sessData["Departments"]) > 0)
    @foreach ($sessData["Departments"] as $dept)
        <a href="/dept/{{ $dept->DeptSlug }}">{{ $dept->DeptName }}</a><br />
    @endforeach
@endif
</div>

<div class="reportMiniBlockLabel">Incident Time & Place</div>
<div class="reportMiniBlockDeets">
    @if ($isOwner || $isAdmin || ($view != 'Anon' && $sessData['Complaints'][0]->ComPrivacy 
        == $GLOBALS["SL"]->getDefID('Privacy Types', 'Submit Publicly')))
        {{ date('n/j/Y', strtotime($sessData["Incidents"][0]->IncDate)) }} 
        @if ($sessData["Incidents"][0]->IncTimeStart !== null) at
            {{ date('g:ia', strtotime($sessData["Incidents"][0]->IncTimeStart)) }}
        @endif
        @if ($sessData["Incidents"][0]->IncTimeEnd !== null) until
            {{ date('g:ia', strtotime($sessData["Incidents"][0]->IncTimeEnd)) }}
        @endif
    @else
        {{ date('F Y', strtotime($sessData["Incidents"][0]->IncDate)) }}
    @endif
    @if ($view == 'Investigate' || $sessData["Incidents"][0]->IncPublic == 'Y')
        <br />{{ $sessData["Incidents"][0]->IncAddress }}
        @if (trim($sessData["Incidents"][0]->IncPublic) != '')
            <br />{{ $sessData["Incidents"][0]->IncAddress2 }}
        @endif
        <br />{{ $sessData["Incidents"][0]->IncAddressCity }}, 
        {{ $sessData["Incidents"][0]->IncAddressState }} {{ $sessData["Incidents"][0]->IncAddressZip }}
    @else
        <br />{{ $sessData["Incidents"][0]->IncAddressCity }}, {{ $sessData["Incidents"][0]->IncAddressState }}
    @endif
    @if ($view == 'Investigate' || trim($sessData["Incidents"][0]->IncLandmarks) != '')
        <br />Landmark: {{ $sessData["Incidents"][0]->IncLandmarks }}
    @endif
</div>
