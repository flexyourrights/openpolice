<!-- resources/views/vendor/openpolice/nodes/1418-admin-complaints-listing.blade.php -->

@if (isset($fltDept) && intVal($fltDept) > 0) 
    <h2 class="mT0">All Department Complaints</h2>
    <input type="hidden" name="baseUrl" id="baseUrlID" value="/my-profile">
@elseif ($listView == 'incomplete')
    <h2>All Incomplete Complaints</h2>
    <input type="hidden" name="baseUrl" id="baseUrlID" value="/dash/all-incomplete-complaints">
@else
    <h2>All Complete Complaints</h2>
    <input type="hidden" name="baseUrl" id="baseUrlID" value="/dash/all-complete-complaints">
@endif

@if ($listView != 'incomplete')
    <div class="row">
    @if (isset($fltDept) && intVal($fltDept) > 0)
        <div class="col-md-8">
            <select name="fltStatus" id="fltStatusID" class="form-control input-lg" autocomplete="off">
                <option value="0" @if (!isset($fltStatus) || intVal($fltStatus) <= 0) SELECTED @endif 
                    >Any Status</option>
                {!! $GLOBALS["SL"]->def->getSetDrop('Complaint Status', $fltStatus,
                    ((isset($statusSkips)) ? $statusSkips : [])) !!}
            </select>
        </div>
        <div class="col-md-4">
            <a id="applyFilts" class="btn btn-default btn-lg w100" href="javascript:;">Apply Filter</a>
        </div>
    @else
        <div class="col-md-4">
            <select name="fltView" id="fltViewID" class="form-control input-lg" autocomplete="off">
                <option value="all" @if ($listView == 'all') SELECTED @endif 
                    >All Complete Complaints</option>
                <option value="review" @if ($listView == 'review') SELECTED @endif 
                    >New or Require Other Action</option>
                <option value="flagged" @if ($listView == 'flagged') SELECTED @endif 
                    >Flagged for Extra Review</option>
                <option value="waiting" @if ($listView == 'waiting') SELECTED @endif 
                    >Waiting for Investigation</option>
                <option value="mine" @if ($listView == 'mine') SELECTED @endif 
                    >Assigned To Me</option>
            </select>
        </div>
        <div class="col-md-4">
            <select name="fltStatus" id="fltStatusID" class="form-control input-lg" autocomplete="off">
                <option value="0" @if (!isset($fltStatus) || intVal($fltStatus) <= 0) SELECTED @endif 
                    >Any Status</option>
                {!! $GLOBALS["SL"]->def->getSetDrop('Complaint Status', $fltStatus,
                    ((isset($statusSkips)) ? $statusSkips : [])) !!}
            </select>
        </div>
        <div class="col-md-4">
            <a id="applyFilts" class="btn btn-default btn-lg w100" href="javascript:;">Apply Filters</a>
        </div>
    @endif
    </div>
@endif

<div class="p5"></div>

<table class="table">
<tr><th class="taC"><i>ID#</i></th><th>Status @if ($listView == 'incomplete') Last Page @endif
    <div class="slGrey">Level - Allegations</div></th><th>Department(s)</th>
    <th>City <div class="slGrey">Narrative</div></th><th>Complainant</th><th>Privacy</th>
    <th>Incident</th><th>Submitted</th></tr>
@forelse ($complaints as $j => $com)
    <tr id="clkBox{{ $com->ComPublicID }}a" class="clkBox crsrPntr" data-cid="{{ $com->ComPublicID }}" 
        data-url="/complaint/read-{{ $com->ComPublicID }}">
        <td rowspan=2 class="taC fPerc125" >
            #{{ number_format($com->ComPublicID) }}
            <br /><i class="fa fa-pencil slBlueDark" aria-hidden="true"></i>
        </td>
        <td class=" @if (in_array($GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus), 
                ['New', 'Hold']))
                slRedDark
            @elseif (in_array($GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus),
                ['Reviewed', 'Pending Attorney', 'OK to Submit to Oversight'])) 
                slRedLight
            @elseif (in_array($GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus),
                ['Attorney\'d', 'Submitted to Oversight'])) 
                slGreenLight
            @elseif (in_array($GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus),
                ['Received by Oversight', 'Declined To Investigate (Closed)', 'Investigated (Closed)', 'Closed'])) 
                slGreenDark
            @endif " ><nobr><span class="fPerc125">
            @if ($GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus) == 'New')
                <i class="fa fa-star"></i>
            @endif
            {{ $GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus) }}
            </span></nobr>
            @if ($com->ComStatus == $GLOBALS['SL']->def->getID('Complaint Status', 'Incomplete'))
                @if ($com->ComSubmissionProgress > 0 && isset($lastNodes[$com->ComSubmissionProgress]))
                    <a href="/u/complaint/{{ $lastNodes[$com->ComSubmissionProgress] }}?preview=1" target="_blank"
                        class="fPerc80">/{{ $lastNodes[$com->ComSubmissionProgress] }}</a>
                @endif
            @elseif ($com->ComType 
                != $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type',  'Police Complaint'))
                ({{ $GLOBALS['SL']->def->getVal('OPC Staff/Internal Complaint Type', $com->ComType) }})
            @endif
        </td>
        <td> @if (isset($comInfo[$com->ComID][1])) {{ $comInfo[$com->ComID][1] }} @endif </td>
        <td>{{ $com->IncAddressCity }}, {{ $com->IncAddressState }}</td>
        <td>{{ $com->PrsnNameFirst }} {{ $com->PrsnNameLast }}</td>
        <td>
            @if ($com->ComPrivacy == 304) Open
            @elseif ($com->ComPrivacy == 305) No Names
            @elseif ($com->ComPrivacy == 306) Anonymous
            @elseif ($com->ComPrivacy == 307) Anonymized
            @endif
        </td>
        <td> @if (trim($com->IncTimeStart) != '') {{ date("n/j/Y", strtotime($com->IncTimeStart)) }} @endif </td>
        <td> @if (isset($comInfo[$com->ComID][0])) {{ $comInfo[$com->ComID][0] }} @endif </td>
    </tr>
    <tr id="clkBox{{ $com->ComPublicID }}b" class="clkBox crsrPntr" data-cid="{{ $com->ComPublicID }}"
        data-url="/complaint/read-{{ $com->ComPublicID }}">
        <td colspan=2 style="border-top: 0px none; padding-top: 0px;">
            <div class="slGrey mTn5 mB20">
            {{ $com->ComAwardMedallion }} - {!! $com->ComAllegList !!}
            </div>
        </div>
        <td colspan=5 style="border-top: 0px none; padding-top: 0px;">
            <div class="slGrey mTn5 mB20">
            @if (isset($com->ComSummary) && trim($com->ComSummary) != '')
                @if (strlen(strip_tags($com->ComSummary)) > 150) {{ substr(strip_tags($com->ComSummary), 0, 150) }}...
                @else {{ $com->ComSummary }} @endif
            @endif
            </div>
        </td>
    </tr>
@empty
    <tr><td colspan=6 ><i>No complaints found in this filter</i></td></tr>
@endforelse
</table>
