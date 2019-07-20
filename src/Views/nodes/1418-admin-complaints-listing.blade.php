<!-- resources/views/vendor/openpolice/nodes/1418-admin-complaints-listing.blade.php -->

<div class="h100">

<div class="admDashTopCard">
    <div class="row">
        <div class="col-7">
        @if (isset($fltDept) && intVal($fltDept) > 0)
            <input type="hidden" name="baseUrl" id="baseUrlID" value="/my-profile">
            <h4 class="disIn mR20">Department Complaints</h4>
        @else
            <input type="hidden" name="baseUrl" id="baseUrlID" value="/dash/all-complete-complaints">
            <h4 class="disIn mR20">Manage Complaints</h4>
        @endif
            {{ number_format(sizeof($complaints)) }} Found
            @if (isset($filtersDesc) && trim($filtersDesc) != '')
                <span class="mL5 slGrey">{{ trim($filtersDesc) }}</span>
            @endif
        </div>
        <div class="col-5">
            <div class="fR">
                <div class="btn-group">
                    <button type="button" id="hidivBtnDashViewList" class="btn btn-sm 
                        @if ($sView == 'list') btn-secondary @else btn-outline-secondary @endif "
                        ><i class="fa fa-th-list" aria-hidden="true"></i></button>
                    <button type="button" id="hidivBtnDashViewLrg" class="btn btn-sm 
                        @if ($sView == 'lrg') btn-secondary @else btn-outline-secondary @endif "
                        ><i class="fa fa-th-large" aria-hidden="true"></i></button>
                </div>
            </div>
            <div class="fR pR15">
                <div class="relDiv">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                            type="button" id="dropdownMenu2" 
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Sort by
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
                            {!! view('vendor.openpolice.complaint-listing-sorts', [
                                "sortLab" => $sortLab,
                                "sortDir" => $sortDir
                            ])->render() !!}
                        </div>
                    </div>

                </div>
            </div>
        @if ($sView == 'list')
            <div class="fR pR15">
                <button type="button" id="toggleSearchFilts"
                    class="btn btn-sm btn-outline-secondary"> Filter by
                    <i id="toggleSearchFiltsArr" class="fa fa-caret-down" aria-hidden="true"
                        style="margin-left: 5px;"></i>
                </button>
            </div>
        @endif
        </div>
    </div>
</div>

@if ($sView == 'lrg')

    <div id="admDashLargeView">
        <div class="row">
            <div class="col-4">
                <div class="slCard nodeWrap">
                    {!! $listPrintFilters !!}
                </div>
            </div>
            <div class="col-8">
                <div id="complaintPreviews" class="w100">
                    @if (sizeof($complaintsPreviews) == 0)
                        No complaints found in this filter
                    @else
                        @foreach ($complaintsPreviews as $prev)
                        <div class="slCard nodeWrap"><div class="mTn20 mBn10">
                            {!! $prev !!}
                        </div></div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div> <!-- end admDashLargeView -->
    <?php /* @if (sizeof($complaints) > 0)
        <script type="text/javascript">
            $(document).ready(function(){
                $("#complaintPreviews").load("/record-prevs/1?rawids={{  }}");
            });
        </script>
    @endif */ ?>

@elseif ($sView == 'list')

    <div id="admDashListView">
        <div id="dashResultsWrap" class="fL h100 ovrFloY" style="width: 40%;">
            <div class="pB10 pL15 slGrey">
                Incident Location, Complainant Name<br />
                Complaint ID# and Status, Date Submitted to OPC
            </div>
            <div id="dashResultsWrap">
    <?php $cnt = 0; ?>
    @forelse ($complaints as $j => $com)
        <?php $cnt++; ?>
        <div id="comRow{{ $com->ComID }}" class="complaintRowWrp">

            <a class="complaintRowA" href="javascript:;"
                data-com-id="{{ $com->ComID }}" data-com-pub-id="{{ intVal($com->ComPublicID) }}">
                <div class="float-left complaintAlert">
                    <div>&nbsp;
                    @if (in_array($GLOBALS['SL']->def->getVal('OPC Staff/Internal Complaint Type', 
                        $com->ComType), ['Unreviewed', 'Not Sure'])
                        || ($GLOBALS['SL']->def->getVal('OPC Staff/Internal Complaint Type', $com->ComType) 
                            == 'Police Complaint'
                        && in_array($GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus), 
                            ['New', 'Hold', 'Reviewed', 'Needs More Work', 
                            'Pending Attorney', 'OK to Submit to Oversight'])))
                        <div class="litRedDot"></div>
                    @endif
                    </div>
                </div>
                <div class="float-left">
                    <b>{{ trim($com->IncAddressCity) }}, {{ $com->IncAddressState }},
                    {{ $GLOBALS["SL"]->convertAllCallToUp1stChars(
                        $com->PrsnNameFirst . ' ' . $com->PrsnNameLast) }}</b><br />
                    <span class="slGrey">
                    @if ($com->ComPublicID <= 0)
                        #i{{ number_format($com->ComID) }}
                        @if ($com->ComSubmissionProgress > 0 
                            && isset($lastNodes[$com->ComSubmissionProgress]))
                            /{{ $lastNodes[$com->ComSubmissionProgress] }}
                        @endif
                    @else
                        #{{ number_format($com->ComPublicID) }}
                        @if ($GLOBALS['SL']->def->getVal('OPC Staff/Internal Complaint Type', $com->ComType) 
                            == 'Police Complaint')
                            {{ $GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus) }}
                        @endif
                    @endif
                    @if ($com->ComStatus != $GLOBALS['SL']->def->getID('Complaint Status', 'Incomplete') 
                        && $com->ComType 
                        != $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type',  'Police Complaint'))
                        ({{ $GLOBALS['SL']->def->getVal('OPC Staff/Internal Complaint Type', $com->ComType) }})
                    @endif
                    </span>
                </div>
                <div class="float-right">
                    @if (isset($com->ComRecordSubmitted))
                        &nbsp;<br /><span class="slGrey">{{ 
                        date("n/j/y", strtotime($com->ComRecordSubmitted)) }}</span>
                    @endif
                </div>
                <div class="fC"></div>
            </a>

            <a class="complaintRowFull" 
                @if ($com->ComPublicID > 0) href="/dash/complaint/read-{{ $com->ComPublicID }}"
                @else href="/dash/complaint/readi-{{ $com->ComID }}"
                @endif ><i class="fa fa-arrows-alt" aria-hidden="true"></i></a>
            
            <div id="resultSpin{{ $com->ComID }}" class="resultSpin"></div>

        </div>
    @empty
        <div class="pL15">No complaints found in this filter</div>
    @endforelse
                </div>
        </div>
        <div id="dashPreviewWrap" class="fR h100" style="width: 59%;">
            <div id="hidivDashTools" class="h100 ovrFloY" style="display: none;">
                <div id="searchFilts" style="display: none;">
                    <div class="slCard mR20">
                        {!! $listPrintFilters !!}
                    </div>
                </div>
            </div>
            <iframe id="reportAdmPreview" src="" width="100%" height="100%" frameborder="0"
                style="display: block;"></iframe>
        </div>
        <div class="fC"></div>
    </div> <!-- end admDashListView -->

@endif
    
</div> <!-- end outer height div -->

@if ($sView == 'list') <style> body { overflow-y: hidden; } </style> @endif
