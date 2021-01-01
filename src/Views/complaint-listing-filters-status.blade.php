<!-- Stored in resources/views/openpolice/complaint-listing-filters-status.blade.php -->
<div class="mTn20"></div>
@if (!$GLOBALS["SL"]->x["isPublicList"])
    <label class="disBlo pB5 pT5">
        <input type="checkbox" id="fltStatus0" name="fltStatus[]" value="295"
            @if (in_array(295, $srchFilts['comstatus'])) CHECKED @endif 
            class="mR5 searchDeetFld fltStatus" autocomplete="off">
            Unverified Survey Submissions
    </label>
    <label class="disBlo pB5 pT5">
        <input type="checkbox" id="fltStatus2" name="fltStatus[]" value="296"
            @if (in_array(296, $srchFilts['comstatus'])) CHECKED @endif 
            class="mR5 searchDeetFld fltStatus" autocomplete="off">
            Police Complaints
    </label>
@endif

@foreach ($GLOBALS["SL"]->def->getSet('Complaint Status') as $i => $status)
    @if ($status->def_id != 194 && (!$GLOBALS["SL"]->x["isPublicList"] 
        || in_array($status->def_id, [200, 201, 202, 203, 204])))
        <label class="disBlo pB5 pT5">
            <input type="checkbox" id="fltStatus{{ (3+$i) }}" 
                name="fltStatus[]" value="{{ $status->def_id }}" 
                class="mR5 searchDeetFld fltStatus" autocomplete="off"
                @if (in_array($status->def_id, $srchFilts['comstatus'])) CHECKED @endif 
                > @if (!$GLOBALS["SL"]->x["isPublicList"]) - @endif
                {{ $GLOBALS['CUST']->printComplaintStatusAbbr($status->def_id, 1) }}
        </label>
    @endif
@endforeach

@if (!$GLOBALS["SL"]->x["isPublicList"])
    <label class="disBlo pB5 pT5">
        <input type="checkbox" name="fltStatus[]" value="194"
            id="fltStatus{{ (6+sizeof($GLOBALS['SL']->def->getSet('Complaint Status'))) }}" 
            @if (in_array(194, $srchFilts['comstatus'])) CHECKED @endif 
            class="mR5 searchDeetFld fltStatus" autocomplete="off">
            Incomplete Complaints
    </label>
    
    <div class="pT20 mB0 slGrey">Archives:</div>
    <label class="disBlo pB5 pT5">
        <input type="checkbox" name="fltStatus[]" value="297"
            id="fltStatus{{ (7+sizeof($GLOBALS['SL']->def->getSet('Complaint Status'))) }}" 
            @if (in_array(297, $srchFilts['comstatus'])) CHECKED @endif 
            class="mR5 searchDeetFld fltStatus" autocomplete="off">
            Submissions Not About Police
    </label>
    <label class="disBlo pB5 pT5">
        <input type="checkbox" name="fltStatus[]" value="722"
            id="fltStatus{{ (8+sizeof($GLOBALS['SL']->def->getSet('Complaint Status'))) }}" 
            @if (in_array(722, $srchFilts['comstatus'])) CHECKED @endif 
            class="mR5 searchDeetFld fltStatus" autocomplete="off">
            Corrections Case
    </label>
    <label class="disBlo pB5 pT5">
        <input type="checkbox" name="fltStatus[]" value="298"
            id="fltStatus{{ (9+sizeof($GLOBALS['SL']->def->getSet('Complaint Status'))) }}" 
            @if (in_array(298, $srchFilts['comstatus'])) CHECKED @endif 
            class="mR5 searchDeetFld fltStatus" autocomplete="off">
            Abuse Submissions
    </label>
    <label class="disBlo pB5 pT5">
        <input type="checkbox" name="fltStatus[]" value="299"
            id="fltStatus{{ (10+sizeof($GLOBALS['SL']->def->getSet('Complaint Status'))) }}" 
            @if (in_array(299, $srchFilts['comstatus'])) CHECKED @endif 
            class="mR5 searchDeetFld fltStatus" autocomplete="off">
            Spam Submissions
    </label>
    <label class="disBlo pB5 pT5">
        <input type="checkbox" name="fltStatus[]" value="300"
            id="fltStatus{{ (11+sizeof($GLOBALS['SL']->def->getSet('Complaint Status'))) }}" 
            @if (in_array(300, $srchFilts['comstatus'])) CHECKED @endif 
            class="mR5 searchDeetFld fltStatus" autocomplete="off">
            Test Submissions
    </label>
@endif
