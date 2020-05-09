<!-- resources/views/vendor/openpolice/nodes/1418-admin-complaints-listing.blade.php -->

@if ($GLOBALS["SL"]->x["isHomePage"])

    <div id="complaintPreviews" class="w100">
        @if (sizeof($complaintsPreviews) == 0)
            No complaints found in this filter
        @else
            @foreach ($complaintsPreviews as $prev)
            <div class="slCard nodeWrap">
                <div class="mTn20 mBn10">
                    {!! $prev !!}
                </div>
            </div>
            @endforeach
        @endif
    </div>

@else 

<div class="h100">

<div class="admDashTopCard">
@if ($GLOBALS["SL"]->x["isPublicList"]) <div class="slCard"> @endif
    <div class="row">
        <div class="col-md-8">
        @if (isset($fltDept) && intVal($fltDept) > 0)
            <input name="baseUrl" id="baseUrlID" 
                type="hidden" value="/my-profile">
            <h4 class="disIn mR20">Department Complaints</h4>
        @else
            <input type="hidden" name="baseUrl" id="baseUrlID" 
                value="/dash/all-complete-complaints">
            @if ($GLOBALS["SL"]->x["isPublicList"]) 
                <h4>Browse & Search Complaints</h4>
            @else 
                <h4>Manage Complaints</h4>
            @endif
        @endif
            <div id="searchFoundCnt" class="disIn">
                {!! $complaintFiltDescPrev !!}
            </div>
        </div>
        <div class="col-md-4">
        @if (!$GLOBALS["SL"]->x["isPublicList"])
            <div class="fR pL15">
                <div class="btn-group">
                    <button type="button" id="hidivBtnDashViewList" 
                        class="btn btn-sm 
                        @if ($sView == 'list') btn-secondary 
                        @else btn-outline-secondary @endif "
                        ><i class="fa fa-th-list" aria-hidden="true"></i></button>
                    <button type="button" id="hidivBtnDashViewLrg" class="btn btn-sm 
                        @if ($sView == 'lrg') btn-secondary 
                        @else btn-outline-secondary @endif "
                        ><i class="fa fa-th-large" aria-hidden="true"></i></button>
                </div>
            </div>
        @endif
            <div class="fR">
                <div class="relDiv">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                            type="button" id="dropdownMenu2" data-toggle="dropdown" 
                            aria-haspopup="true" aria-expanded="false">
                            Sort by
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" 
                            aria-labelledby="dropdownMenu2">
                            {!! view(
                                'vendor.openpolice.complaint-listing-sorts', 
                                [
                                    "sortLab" => $sortLab,
                                    "sortDir" => $sortDir
                                ]
                            )->render() !!}
                        </div>
                    </div>

                </div>
            </div>
        @if ($sView == 'list')
            <div class="fR pR15">
                <button type="button" id="toggleSearchFilts"
                    class="btn btn-sm btn-outline-secondary"> Filter by
                    <i id="toggleSearchFiltsArr" class="fa fa-caret-down" 
                        aria-hidden="true" style="margin-left: 5px;"></i>
                </button>
            </div>
        @endif
        </div>
    </div>
@if ($GLOBALS["SL"]->x["isPublicList"]) </div> <!-- end slCard --> @endif
</div>

@if ($sView == 'lrg')

    <div id="admDashLargeView">
        <div class="row">
            <div class="col-lg-4">
                <div id="filtsCard" class="slCard nodeWrap">
                    {!! $listPrintFilters !!}
                </div>
            </div>
            <div class="col-lg-8">
                <div id="complaintPreviews" class="w100">
                    @if (isset($complaintPreviews) && trim($complaintPreviews) != '')
                        {!! $complaintPreviews !!}
                    @else
                        <div class="slCard p20 mT10 taC">
                            {!! $GLOBALS["SL"]->spinner() !!}
                            <h4 class="mTn20 mB20">
                                We're gathering the data, hold tight!
                            </h4>
                        </div>
                    @endif
                </div>
                <?php /* <p><a href="?showPreviews=1{!! $searchFiltsURL !!}" 
                    target="_blank" class="mT30">
                    <i class="fa fa-external-link mR3" 
                        aria-hidden="true"></i> 
                    Open Results In New Window
                </a></p>
                */ ?>
            </div>
        </div>
    </div> <!-- end admDashLargeView -->
    @if (!isset($complaintPreviews) || trim($complaintPreviews) == '')
        <script type="text/javascript">
        $(document).ready(function(){
            //setTimeout(function() {
            $("#complaintPreviews").load("?showPreviews=1&ajax=1{!! $searchFiltsURL !!}");
            //}, 10);
        });
        </script>
    @endif
    
@elseif ($sView == 'list')

    <div id="admDashListView">
        <div id="dashResultsWrap" class="fL h100 ovrFloY" style="width: 40%;">
            <div class="pB10 pL15 slGrey">
                Incident Location, Complainant Name<br />
                Complaint ID# and Status, Date Submitted to OpenPolice.org
            </div>
            <div id="dashResultsWrap">
    <?php $cnt = 0; ?>
    @forelse ($complaints as $j => $com)
        <?php $cnt++; ?>
        <div id="comRow{{ $com->com_id }}" class="complaintRowWrp">

            <a class="complaintRowA" href="javascript:;"
                data-com-id="{{ $com->com_id }}" 
                data-com-pub-id="{{ intVal($com->com_public_id) }}">
                <div class="float-left complaintAlert">
                    <div>&nbsp;
                    @if (in_array($GLOBALS['SL']->def->getVal('Complaint Type', $com->com_type), 
                            ['Unreviewed', 'Not Sure'])
                        || ($GLOBALS['SL']->def->getVal('Complaint Type', $com->com_type) 
                            == 'Police Complaint'
                        && in_array($GLOBALS['SL']->def->getVal('Complaint Status', $com->com_status), 
                            ['New', 'Hold', 'Reviewed'])))
                        <div class="litRedDot"></div>
                    @elseif ($GLOBALS['SL']->def->getVal('Complaint Type', $com->com_type) == 'Police Complaint'
                        && in_array($GLOBALS['SL']->def->getVal('Complaint Status', $com->com_status), 
                            ['Needs More Work', 'Pending Attorney', 'OK to Submit to Oversight']))
                        <div class="litRedDottie"></div>
                    @endif
                    </div>
                </div>
                <div class="float-left">
                    <b>{{ trim($com->inc_address_city) }}, {{ $com->inc_address_state }},
                    {{ $GLOBALS["SL"]->convertAllCallToUp1stChars(
                        $com->prsn_name_first . ' ' . $com->prsn_name_last
                    ) }}</b><br />
                    <span class="slGrey">
                    @if ($com->com_public_id <= 0)
                        #i{{ number_format($com->com_id) }}
                        @if ($com->com_submission_progress > 0 
                            && isset($lastNodes[$com->com_submission_progress]))
                            /{{ $lastNodes[$com->com_submission_progress] }}
                        @endif
                    @else
                        #{{ number_format($com->com_public_id) }}
                        @if ($GLOBALS['SL']->def->getVal('Complaint Type', $com->com_type) 
                            == 'Police Complaint')
                            {{ $GLOBALS['SL']->def->getVal('Complaint Status', $com->com_status) }}
                        @endif
                    @endif
                    @if ($com->com_status != $GLOBALS['SL']->def->getID('Complaint Status', 'Incomplete') 
                        && $com->com_type != $GLOBALS["SL"]->def->getID('Complaint Type',  'Police Complaint'))
                        ({{ $GLOBALS['SL']->def->getVal('Complaint Type', $com->com_type) }})
                    @endif
                    </span>
                </div>
                <div class="float-right">
                    @if (isset($com->com_record_submitted))
                        &nbsp;<br /><span class="slGrey">{{ 
                            date("n/j/y", strtotime($com->com_record_submitted)) 
                        }}</span>
                    @endif
                </div>
                <div class="fC"></div>
            </a>

            <a class="complaintRowFull" 
                @if ($com->com_public_id > 0) 
                    href="/dash/complaint/read-{{ $com->com_public_id }}"
                @else 
                    href="/dash/complaint/readi-{{ $com->com_id }}"
                @endif ><i class="fa fa-arrows-alt" aria-hidden="true"></i></a>
            
            <div id="resultSpin{{ $com->com_id }}" class="resultSpin"></div>

        </div>
    @empty
        <div class="pL15">No complaints found in this filter</div>
    @endforelse
                </div>
        </div>
        <div id="dashPreviewWrap" class="fR h100" style="width: 59%;">
            <div id="hidivDashTools" class="h100 ovrFloY" style="display: none;">
                <div id="searchFilts" style="display: none;">
                    <div id="filtsCard" class="slCard mR20">
                        {!! $listPrintFilters !!}
                    </div>
                </div>
            </div>
            <iframe id="reportAdmPreview" src="" width="100%" height="100%" 
                frameborder="0" style="display: block;"></iframe>
        </div>
        <div class="fC"></div>
    </div> <!-- end admDashListView -->

@endif
    
</div> <!-- end outer height div -->

@endif

<style> 
@if ($sView == 'list') 
    body { overflow-y: hidden; } 
@endif
</style>
