<!-- resources/views/vendor/openpolice/nodes/1418-admin-complaints-listing.blade.php -->
<div class="slCard nodeWrap">
@if (isset($fltDept) && intVal($fltDept) > 0) 
    <h2 class="mT0">All Department Complaints</h2>
    <input type="hidden" name="baseUrl" id="baseUrlID" value="/my-profile">
@elseif ($listView == 'incomplete')
    <a href="/tree/complaint" target="_blank" class="btn btn-secondary btn-sm pull-right"
        ><i class="fa fa-external-link mR5" aria-hidden="true"></i> Full Survey Map</a>
    <h2>All Incomplete Complaints</h2>
    <input type="hidden" name="baseUrl" id="baseUrlID" value="/dash/all-incomplete-complaints">
@else
    <h2>All Complete Complaints</h2>
    <input type="hidden" name="baseUrl" id="baseUrlID" value="/dash/all-complete-complaints">
@endif
    <p>Click anywhere on a complaint's row to read and take actions on it.</p>
@if ($listView != 'incomplete')
    <div class="row">
    @if (isset($fltDept) && intVal($fltDept) > 0)
        <div class="col-8">
            <select name="fltStatus" id="fltStatusID" class="form-control form-control-lg" autocomplete="off">
                <option value="0" @if (!isset($fltStatus) || intVal($fltStatus) <= 0) SELECTED @endif 
                    >Any Status</option>
                {!! $GLOBALS["SL"]->def->getSetDrop('Complaint Status', $fltStatus,
                    ((isset($statusSkips)) ? $statusSkips : [])) !!}
            </select>
        </div>
        <div class="col-4">
            <a id="applyFilts" class="btn btn-secondary btn-lg w100" href="javascript:;">Apply Filter</a>
        </div>
    @else
        <div class="col-4">
            <select name="fltView" id="fltViewID" class="form-control form-control-lg" autocomplete="off">
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
        <div class="col-4">
            <select name="fltStatus" id="fltStatusID" class="form-control form-control-lg" autocomplete="off">
                <option value="0" @if (!isset($fltStatus) || intVal($fltStatus) <= 0) SELECTED @endif 
                    >Any Status</option>
                {!! $GLOBALS["SL"]->def->getSetDrop('Complaint Status', $fltStatus,
                    ((isset($statusSkips)) ? $statusSkips : [])) !!}
            </select>
        </div>
        <div class="col-4">
            <a id="applyFilts" class="btn btn-secondary btn-lg w100" href="javascript:;">Apply Filters</a>
        </div>
    @endif
    </div>
@endif
</div>

<div class="slCard nodeWrap">
    <div class="w100 p15">
        <div class="row slGrey">
            <div class="col-sm-4 pB5">ID# Status @if ($listView == 'incomplete') - Last Page @endif</div>
            <div class="col-sm-2 pB5">City</div>
            <div class="col-sm-2 pB5">Complainant</div>
            <div class="col-sm-2 pB5">Privacy, Level</div>
            <div class="col-sm-1 pB5">Incident</div>
            <div class="col-sm-1"><nobr>OPC Start</nobr></div>
        </div>
        <div class="row slGrey">
            <div class="col-md-4 pT5">Department(s)<br />Allegations</div>
            <div class="col-md-8 pT5">Narrative</div>
        </div>
    </div>
    
<?php $cnt = 0; ?>
@forelse ($complaints as $j => $com)
    <?php $cnt++; ?>
    <a @if ($com->ComPublicID > 0) href="/complaint/read-{{ $com->ComPublicID }}"
        @else href="/complaint/readi-{{ $com->ComID }}" @endif class="noUnd" style="color: #333;" >
    <div class="w100 p15 mB5 round5 @if ($cnt%2 == 1) row2 @endif " style="border-left: 2px 
        @if (in_array($GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus), 
            ['New', 'Hold']))
            #EC2327
        @elseif (in_array($GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus),
            ['Reviewed', 'Pending Attorney', 'OK to Submit to Oversight'])) 
            #F38C5F
        @elseif (in_array($GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus),
            ['Attorney\'d', 'Submitted to Oversight'])) 
            #006D36
        @elseif (in_array($GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus), 
            ['Received by Oversight', 'Declined To Investigate (Closed)', 'Investigated (Closed)', 'Closed'])) 
            #29B76F
        @else #888888 @endif solid;">
        <div class="row">
            <div class="col-sm-4 pB5 clrLnk">
                @if ($com->ComPublicID <= 0)
                    #i{{ number_format($com->ComID) }}
                    @if ($com->ComSubmissionProgress > 0 && isset($lastNodes[$com->ComSubmissionProgress]))
                        /{{ $lastNodes[$com->ComSubmissionProgress] }}
                    @endif
                @else
                    #{{ number_format($com->ComPublicID) }}
                    @if ($GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus) == 'New')
                        <i class="fa fa-star"></i>
                    @endif
                    {{ $GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus) }}
                @endif
                @if ($com->ComStatus != $GLOBALS['SL']->def->getID('Complaint Status', 'Incomplete') && $com->ComType 
                    != $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type',  'Police Complaint'))
                    ({{ $GLOBALS['SL']->def->getVal('OPC Staff/Internal Complaint Type', $com->ComType) }})
                @endif
            </div>
            <div class="col-sm-2 pB5">{{ $com->IncAddressCity }}, {{ $com->IncAddressState }}</div>
            <div class="col-sm-2 pB5">{{ $com->PrsnNameFirst }} {{ $com->PrsnNameLast }}</div>
            <div class="col-sm-2 pB5">
                @if ($com->ComPrivacy == 304) Open,
                @elseif ($com->ComPrivacy == 305) No Names,
                @elseif ($com->ComPrivacy == 306) Anonymous,
                @elseif ($com->ComPrivacy == 307) Anonymized,
                @endif
                {{ $com->ComAwardMedallion }}
            </div>
            <div class="col-sm-1 pB5">
                @if (trim($com->IncTimeStart) != '') {{ date("n/j/y", strtotime($com->IncTimeStart)) }} @endif
            </div>
            <div class="col-sm-1">
                @if (isset($com->created_at)) {{ date("n/j/y", strtotime($com->created_at)) }} @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 pT5">
                @if (isset($comInfo[$com->ComID]["depts"]) && trim($comInfo[$com->ComID]["depts"]) != '')
                    {{ $comInfo[$com->ComID]["depts"] }}<br />
                @endif 
                {!! $com->ComAllegList !!}
            </div>
            <div class="col-md-8 pT5">
                @if (isset($com->ComSummary) && trim($com->ComSummary) != '')
                    @if (strlen(strip_tags($com->ComSummary)) > 180)
                        {{ substr(strip_tags($com->ComSummary), 0, 180) }}...
                    @else
                        {{ $com->ComSummary }}
                    @endif
                @endif
            </div>
        </div>
    </div>
    </a>
@empty
    No complaints found in this filter
@endforelse
</div>

<style> #mainBody { background: #F5FBFF; } </style>