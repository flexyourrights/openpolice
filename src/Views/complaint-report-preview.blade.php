<!-- Stored in resources/views/openpolice/complaint-report-preview.blade.php -->
<div class="slReportPreview mB20 pB10">
    <div class="row">
        <div class="col-6 pB10 pT5">
            <p><span class="slGrey">Allegations</span><br /><b class="fPerc125">{!! $allegations !!}</b></p>
        </div>
        <div class="col-1"></div>
        <div class="col-5 pB10">
            <p><span class="slGrey">Incident</span><br />
            {{ $comDate }}, {!! $comWhere !!}<br />{!! $deptList !!}</p>
        </div>
    </div>
    <p class="mTn10">{{ $storyPrev }}</p>
    <div class="row">
        <div class="col-6 pB10">
        @if (isset($complaint->{ $coreAbbr . 'PublicID' }) && intVal($complaint->{ $coreAbbr . 'PublicID' }) > 0)
            <a href="/{{ (($coreAbbr == 'Com') ? 'complaint' : 'compliment') }}/read-{{
                $complaint->{ $coreAbbr . 'PublicID' } }}" class="btn btn-primary fL"
                >Read @if ($coreAbbr == 'Com') Complaint @else Compliment @endif 
                #{{ $complaint->{ $coreAbbr . 'PublicID' } }}</a>
        @else <a href="/{{ (($coreAbbr == 'Com') ? 'complaint' : 'compliment') }}/readi-{{
            $complaint->{ $coreAbbr . 'ID' } }}" class="btn btn-primary fL" >Incomplete 
            @if ($coreAbbr == 'Com') Complaint @else Compliment @endif #{{ $complaint->{ $coreAbbr . 'ID' } }}</a>
        @endif
        </div>
        <div class="col-1"></div>
        <div class="col-5 pB10">
        @if ($uID > 0 && isset($complaint->{ $coreAbbr . 'UserID' }) && $complaint->{ $coreAbbr . 'UserID' } == $uID)
            @if (!isset($complaint->{ $coreAbbr . 'Status' }) || intVal($complaint->{ $coreAbbr . 'Status' }) <= 0 || 
                $GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->{ $coreAbbr . 'Status' }) == 'Incomplete' || 
                $GLOBALS["SL"]->def->getVal('Compliment Status', $complaint->{ $coreAbbr . 'Status' }) == 'Incomplete')
                <h4 class="mT5 slRedDark">Status: Incomplete</h4>
            @else
                <h4 class="mT5 slBlueDark">Status: 
                @if ($coreAbbr == 'Com') {{ $GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->ComStatus) }} 
                @else {{ $GLOBALS["SL"]->def->getVal('Compliment Status', $complaint->CompliStatus) }}
                @endif </h4>
            @endif
        @endif
        </div>
    </div>
</div>