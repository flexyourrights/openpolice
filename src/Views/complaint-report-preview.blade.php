<!-- Stored in resources/views/openpolice/complaint-report-preview.blade.php -->
<div class="slReportPreview">
    <div class="row">
        <div class="col-lg-4">
            <p><span class="slGrey">Incident</span><br />
            {{ $comDate }}, {!! $comWhere !!}<br />{!! $deptList !!}</p>
            <p><span class="slGrey">Complaint Status</span><br />
        @if ($uID > 0 && isset($complaint->{ $coreAbbr . 'UserID' }) && $complaint->{ $coreAbbr . 'UserID' } == $uID)
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
        </div>
        <div class="col-lg-8">
            <p><span class="slGrey">Allegations</span><br />{!! $allegations !!}</p>
            <p><span class="slGrey">Story</span><br />{{ $storyPrev }}</p>
        </div>
    </div>
</div>
<div class="pB20">
@if (isset($complaint->{ $coreAbbr . 'PublicID' }) && intVal($complaint->{ $coreAbbr . 'PublicID' }) > 0)
    <a href="/{{ (($coreAbbr == 'Com') ? 'complaint' : 'compliment') }}/read-{{
        $complaint->{ $coreAbbr . 'PublicID' } }}" class="btn btn-primary"
        >Read @if ($coreAbbr == 'Com') Complaint @else Compliment @endif 
        #{{ $complaint->{ $coreAbbr . 'PublicID' } }}</a>
@else <a href="/{{ (($coreAbbr == 'Com') ? 'complaint' : 'compliment') }}/readi-{{
    $complaint->{ $coreAbbr . 'ID' } }}" class="btn btn-primary" >Incomplete 
    @if ($coreAbbr == 'Com') Complaint @else Compliment @endif #{{ $complaint->{ $coreAbbr . 'ID' } }}</a>
@endif
</div>