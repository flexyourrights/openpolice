<!-- Stored in resources/views/openpolice/complaint-report-preview.blade.php -->
<div class="slReportPreview">
    <h4 class="slBlueDark">{!! $allegations[0] !!} in {!! $comWhere !!}</h4>
    <p>
        <b>{!! $deptList !!}</b><br />
        Additional Allegations: {!! $allegations[1] !!}
    </p>
    <div class="row mBn10" style="width: 305px;">
        <div class="col-6">
            <p>Incident Date<br />Submitted to OPC
        </div><div class="col-6">
            {{ $comDate }}<br />{{ $comDateFile }}
        </div>
    </div>
    @if ($uID > 0 && (isset($complaint->{ $coreAbbr . 'UserID' }) && $complaint->{ $coreAbbr . 'UserID' } == $uID)
        || $GLOBALS["SL"]->isAdmin)
        <br />Complaint Status: 
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
    @endif
    </p>
    <p>"{{ $GLOBALS["SL"]->wordLimitDotDotDot($storyPrev, 70) }}"</p>
</div>
<div>
@if (isset($complaint->{ $coreAbbr . 'PublicID' }) && intVal($complaint->{ $coreAbbr . 'PublicID' }) > 0)
    <a href="/{{ (($coreAbbr == 'Com') ? 'complaint' : 'compliment') }}/read-{{
        $complaint->{ $coreAbbr . 'PublicID' } }}" class="btn btn-primary btn-sm"
        >Read @if ($coreAbbr == 'Com') Complaint @else Compliment @endif 
        #{{ $complaint->{ $coreAbbr . 'PublicID' } }}</a>
@else <a href="/{{ (($coreAbbr == 'Com') ? 'complaint' : 'compliment') }}/readi-{{
    $complaint->{ $coreAbbr . 'ID' } }}" class="btn btn-primary btn-sm" >Incomplete 
    @if ($coreAbbr == 'Com') Complaint @else Compliment @endif #{{ $complaint->{ $coreAbbr . 'ID' } }}</a>
@endif
</div>