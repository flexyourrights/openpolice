<!-- Stored in resources/views/openpolice/complaint-report-preview.blade.php -->
<div class="slReportPreview">

@if ($uID > 0 
    && $editable
    && (isset($complaint->{ $coreAbbr . 'user_id' }) 
    && $complaint->{ $coreAbbr . 'user_id' } == $uID))

    @if (!isset($complaint->{ $coreAbbr . 'status' }) 
        || intVal($complaint->{ $coreAbbr . 'status' }) <= 0 
        || $GLOBALS["SL"]->def->getVal('Complaint Status', 
            $complaint->{ $coreAbbr . 'status' }) == 'Incomplete' 
        || $GLOBALS["SL"]->def->getVal('Compliment Status', 
            $complaint->{ $coreAbbr . 'status' }) == 'Incomplete')
        <div class="alert alert-warning" style="padding: 10px 15px;">
            Please finish your complaint. We will help you with the next steps.
        </div>
    @endif

@endif

    <div class="row">
    @if ($comUser && isset($comUser->id))
        <div class=" @if ($GLOBALS['SL']->REQ->has('d')) col-lg-5 col-md-4
            @else col-md-3 col-sm-4 
            @endif complaintPrevPic taC">
            <div class="pB20">
                {!! $comUser->profileImg($url) !!}
            @if (isset($titleWho) && trim($titleWho) != '')
                <a href="{{ $comUser->urlSlug() }}"
                    ><h5 class="mT30">{!! $titleWho !!}</h5></a>
            @endif
            </div>
        </div>
    @endif
        <div class=" @if ($GLOBALS['SL']->REQ->has('d')) col-lg-7 col-md-8
            @else col-md-9 col-sm-8
            @endif ">
            <div class="pB10">
                <a href="{{ $url }}"><h4>
                    @if (trim($allegations[0]) == '') {!! $comWhere !!}
                    @else {!! $allegations[0] !!}
                    @endif
                </h4></a>

            @if (trim(strip_tags($deptList)) != '' 
                || trim(strip_tags($allegations[1])) != '') 
                @if (trim(strip_tags($deptList)) != '')
                    <h5>{!! $deptList !!}</h5>
                @endif
                @if (trim(strip_tags($allegations[1])) != '') 
                    <p>Additional Allegations: {!! $allegations[1] !!}</p>
                @endif
            @endif
            </div>
        </div>
    </div>
    <div class="pB15">
        <div class="row mB10">
            <div class="col-6">
                Submitted to OpenPolice.org
            </div><div class="col-6">
                <div class="dateFull">{{ $comDateFile }}</div>
                <div class="dateAbbr">{{ $comDateFileAb }}</div>
            </div>
        </div>
    @if ($comDate != '')
        <div class="row mB10">
            <div class="col-6">
                Incident Date
            </div><div class="col-6">
                <div class="dateFull">{{ $comDate }}</div>
                <div class="dateAbbr">{{ $comDateAb }}</div>
            </div>
        </div>
    @endif
    @if ($uID > 0 
        && ((isset($complaint->{ $coreAbbr . 'user_id' }) 
        && $complaint->{ $coreAbbr . 'user_id' } == $uID) 
            || $GLOBALS["SL"]->isStaffOrAdmin()))
        <div class="row mB10">
            <div class="col-6">
                Complaint Status
            </div><div class="col-6">
            @if (!isset($complaint->{ $coreAbbr . 'status' }) 
                || intVal($complaint->{ $coreAbbr . 'status' }) <= 0 
                || $GLOBALS["SL"]->def->getVal('Complaint Status', $complaint->{ $coreAbbr . 'status' }) 
                    == 'Incomplete' 
                || $GLOBALS["SL"]->def->getVal('Compliment Status', $complaint->{ $coreAbbr . 'status' }) 
                    == 'Incomplete')
                <span class="txtDanger">Incomplete</span>
            @else
                @if ($coreAbbr == 'com_') 
                    {{ str_replace('Oversight', 'Investigative Agency', 
                        $GLOBALS["SL"]->def->getVal('Complaint Status', 
                        $complaint->com_status)) }} 
                @else {{ $GLOBALS["SL"]->def->getVal('Compliment Status', $complaint->compli_status) }}
                @endif
            @endif
            </div>
        </div>
    @endif
    </div>
@if (trim(strip_tags($storyPrev)) != '')
    <p>"{{ $GLOBALS["SL"]->wordLimitDotDotDot($storyPrev, 70) }}"</p>
@endif
</div>
<div>
@if ($editable)

    <a href="/switch/1/{{ $complaint->com_id }}" 
        class="btn btn-primary mR10"
        >Finish Incomplete 
        @if ($coreAbbr == 'com_') Complaint @else Compliment @endif 
        #{{ $complaint->com_id }}</a>

    <a href="/{{ (($coreAbbr == 'com_') ? 'complaint' : 'compliment') }}/readi-{{
        $complaint->{ $coreAbbr . 'id' } }}" class="btn btn-secondary mR10" 
        >View @if ($coreAbbr == 'com_') Complaint @else Compliment @endif </a>

@else

    <a href="{{ $url }}" class="btn btn-secondary"
        >View @if ($coreAbbr == 'com_') Complaint @else Compliment @endif 
        #{{ $complaint->{ $coreAbbr . 'public_id' } }}</a>

@endif
</div>
