<!-- Stored in resources/views/openpolice/complaint-report-preview.blade.php -->
<div class="slReportPreview">

@if ($uID > 0 && (isset($complaint->{ $coreAbbr . 'UserID' }) && $complaint->{ $coreAbbr . 'UserID' } == $uID))

    @if (!isset($complaint->{ $coreAbbr . 'Status' }) || intVal($complaint->{ $coreAbbr . 'Status' }) <= 0 || 
        $GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->{ $coreAbbr . 'Status' }) == 'Incomplete' || 
        $GLOBALS["SL"]->def->getVal('Compliment Status', $complaint->{ $coreAbbr . 'Status' }) == 'Incomplete')
        <div class="alert alert-warning" style="padding: 10px 15px;">
            Please finish your complaint. We will help you with the next steps.
        </div>
    @endif

@endif

    <h4 class="slBlueDark">
        @if (trim($allegations[0]) == '') Incident @else {!! $allegations[0] !!} @endif 
        in {!! $comWhere !!}
    </h4>
@if ($uID > 0 && (isset($complaint->{ $coreAbbr . 'UserID' }) && $complaint->{ $coreAbbr . 'UserID' } == $uID)
    || $GLOBALS["SL"]->isAdmin)
    <p>Complaint Status: 
    @if (!isset($complaint->{ $coreAbbr . 'Status' }) || intVal($complaint->{ $coreAbbr . 'Status' }) <= 0 || 
        $GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->{ $coreAbbr . 'Status' }) == 'Incomplete' || 
        $GLOBALS["SL"]->def->getVal('Compliment Status', $complaint->{ $coreAbbr . 'Status' }) == 'Incomplete')
        <span class="txtDanger">Incomplete</span>
    @else
        <span class="slBlueDark">
        @if ($coreAbbr == 'Com') {{ $GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) }} 
        @else {{ $GLOBALS["SL"]->def->getVal('Compliment Status', $complaint->CompliStatus) }}
        @endif </span>
    @endif
    </p>
@endif
    <div class="row mBn10" style="width: 305px;">
        <div class="col-6">
            <p>Incident Date<br />Submitted to OPC
        </div><div class="col-6">
            {{ $comDate }}<br />{{ $comDateFile }}
        </div>
    </div>
@if (trim(strip_tags($deptList)) != '' || trim(strip_tags($allegations[1])) != '') 
    <p>
    @if (trim(strip_tags($deptList)) != '') 
        <b>{!! $deptList !!}</b><br />
    @endif
    @if (trim(strip_tags($allegations[1])) != '') 
        Additional Allegations: {!! $allegations[1] !!}
    @endif
@endif
    </p>
    <p>"{{ $GLOBALS["SL"]->wordLimitDotDotDot($storyPrev, 70) }}"</p>
</div>
<div>
@if (isset($complaint->{ $coreAbbr . 'PublicID' }) && intVal($complaint->{ $coreAbbr . 'PublicID' }) > 0)

    <a href="/{{ (($coreAbbr == 'Com') ? 'complaint' : 'compliment') }}/read-{{
        $complaint->{ $coreAbbr . 'PublicID' } }}" class="btn btn-secondary"
        >View @if ($coreAbbr == 'Com') Complaint @else Compliment @endif 
        #{{ $complaint->{ $coreAbbr . 'PublicID' } }}</a>

@else

    <a href="/switch/1/{{ $complaint->ComID }}" class="btn btn-primary mR10"
        >Finish Incomplete @if ($coreAbbr == 'Com') Complaint @else Compliment @endif 
        #{{ $complaint->ComID }}</a>

    <a href="/{{ (($coreAbbr == 'Com') ? 'complaint' : 'compliment') }}/readi-{{
        $complaint->{ $coreAbbr . 'ID' } }}" class="btn btn-secondary mR10" 
        >View @if ($coreAbbr == 'Com') Complaint @else Compliment @endif </a>

@endif
</div>
