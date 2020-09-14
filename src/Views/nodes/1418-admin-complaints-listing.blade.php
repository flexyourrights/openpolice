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
                <?php /* {!! $complaintFiltDescPrev !!} */ ?>
            </div>
        </div>
        <div class="col-md-4">
        <?php /*
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
        */ ?>

        <?php /*
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
        */ ?>
        @if ($sView == 'list')
            <div class="fR pR15">
                <button type="button" id="toggleSearchFilts"
                    class="btn btn-sm btn-primary"> Filter by
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
                <div id="complaintResultsWrap" class="w100 pT10">
                    <div class="slCard taC pB30">
                        {!! $GLOBALS["SL"]->spinner() !!}
                        <h4 class="mTn15">
                            We're gathering the data, hold tight!
                        </h4>
                    </div>
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
    
@elseif ($sView == 'list')

    <div id="admDashListView">
        <div id="dashResultsWrap" class="fL h100 ovrFloY brdRgtGrey" style="width: 40%;">
            {!! view(
                'vendor.openpolice.nodes.1418-admin-complaints-dash-results', 
                [ 
                    "complaints"            => $complaints,
                    "sortLab"               => $sortLab,
                    "sortDir"               => $sortDir,
                    "firstComplaint"        => $firstComplaint,
                    "complaintFiltDescPrev" => $complaintFiltDescPrev
                ]
            )->render() !!}
        </div>
        <div id="dashPreviewWrap" class="fL h100" style="width: 60%;">
            <div id="hidivDashTools" class="h100 ovrFloY" style="display: none;">
                <div id="searchFilts" style="display: none;">
                    <div id="filtsCard" class="slCard mR20">
                        {!! $listPrintFilters !!}
                    </div>
                </div>
            </div>
            <div id="reportAdmPreview" class="w100 h100 disBlo ovrFloY"></div>
        </div>
        <div class="fC"></div>
    </div> <!-- end admDashListView -->

@endif

</div> <!-- end outer height div -->

@endif

<div class="relDiv w100">
    <div class="absDiv" style="right: -18px; top: 20px;">
        <a href="?refresh=1" class="slGreenDark"
            ><i class="fa fa-refresh" aria-hidden="true"></i></a>
    </div>
</div>

@if ($sView == 'list') 
<style> 
    body {
        overflow-y: hidden;
    }
    #node2632kids .pT20.pB20 {
        padding-top: 2px;
        padding-bottom: 0px;
    }
</style>
@endif
