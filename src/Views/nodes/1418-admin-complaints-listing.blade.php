<!-- resources/views/vendor/openpolice/nodes/1418-admin-complaints-listing.blade.php -->

<div class="h100">

<div class="admDashTopCard">
    <div class="row">
        <div class="col-md-8">
        @if (isset($fltDept) && intVal($fltDept) > 0)
            <h4>Department Complaints</h4>
            <input type="hidden" name="baseUrl" id="baseUrlID" value="/my-profile">
        @else
            <h4>Manage Complaints</h4>
            <input type="hidden" name="baseUrl" id="baseUrlID" value="/dash/all-complete-complaints">
        @endif
        </div>
        <div class="col-md-4">
            <div class="fR">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary"
                        ><i class="fa fa-th-large" aria-hidden="true"></i></button>
                    <button type="button" class="btn btn-sm btn-secondary"
                        ><i class="fa fa-th-list" aria-hidden="true"></i></button>
                </div>
            </div>
            <div class="fR pR15">
                <button type="button" id="hidivBtnDashFilters"
                    class="btn btn-sm btn-outline-secondary hidivBtn">Filter by
                    <i id="hidivBtnArrDashFilters" class="fa fa-caret-down" aria-hidden="true"></i>
                </button>
            </div>
            <div class="fR pR15">
                <button type="button" id="hidivBtnDashSorts"
                    class="btn btn-sm btn-outline-secondary hidivBtn">Sort by
                    <i id="hidivBtnArrDashSorts" class="fa fa-caret-down" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
</div>


<div id="admDashTableView">
    <div id="dashResultsWrap" class="fL h100 ovrFloY" style="width: 40%;">
        <div class="pB10 pL15 slGrey">
            Incident Location, Complainant Name<br />
            Complaint ID# and Status, Date Submitted to OPC
        </div>
        <div id="dashResultsWrap">
<?php
$cnt = 0;
$first = [0, 0];
?>
@forelse ($complaints as $j => $com)
    <?php
    $cnt++;
    if ($first[0] == 0) {
        $first = [$com->ComPublicID, $com->ComID];
    }
    ?>
    <div id="comRow{{ $com->ComID }}" class="complaintRowWrp">

        <a class="complaintRowA" href="javascript:;"
            data-com-id="{{ $com->ComID }}" data-com-pub-id="{{ $com->ComPublicID }}">
            <div class="float-left complaintAlert">
                <div>&nbsp;
                @if (in_array($GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus), 
                    ['New', 'Hold', 'Reviewed', 'Needs More Work', 
                    'Pending Attorney', 'OK to Submit to Oversight'])) 
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
                    {{ $GLOBALS['SL']->def->getVal('Complaint Status', $com->ComStatus) }}
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
                    &nbsp;<br /><span class="slGrey">{{ date("n/j/y", strtotime($com->ComRecordSubmitted)) }}</span>
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
    No complaints found in this filter
@endforelse
            </div>
    </div>
    <div id="dashPreviewWrap" class="fR h100" style="width: 59%;">
        <div id="hidivDashTools" class="disNon h100 ovrFloY">
            <div id="hidivDashFilters" class="disNon">
                <div class="slCard">
                    <h4>Filter Listings</h4>
                    {!! $listPrintFilters !!}
                    <div class="pT15 pB15">
                        <a class="btn btn-primary btn-lg slTab nFormNext" href="javascript:;"
                            {{ $GLOBALS['SL']->tabInd() }} >Next</a>
                    </div>
                </div>
            </div>
        </div>
        <iframe id="complaintAdmPreview" src="" width="100%" height="100%" frameborder="0"
            class="disBlo"></iframe>
    </div>
    <div class="fC"></div>
</div>
    
</div> <!-- end outer height div -->

<script type="text/javascript">
var resultLoaded = 0;
var dashHeight = 0;
$(document).ready(function(){
    function chkDashHeight() {
        dashHeight = document.body.clientHeight;
        var newHeight = dashHeight-130;
        document.getElementById("dashResultsWrap").style.height=''+newHeight+'px';
        document.getElementById("complaintAdmPreview").style.height=''+newHeight+'px';
        document.getElementById("hidivDashTools").style.height=''+newHeight+'px';
        setTimeout(function() { chkDashHeight(); }, 5000);
        return true;
    }
    setTimeout(function() { chkDashHeight(); }, 1);
    function loadComplaint(idPub, id) {
        if (id > 0) {
            loadResultSpinner(id);
            if (idPub > 0) {
                document.getElementById("complaintAdmPreview").src="/complaint/read-"+idPub+"/full?frame=1&wdg=1";
            } else {
                document.getElementById("complaintAdmPreview").src="/complaint/readi-"+id+"/full?frame=1&wdg=1";
            }
        @forelse ($complaints as $j => $com)
            $("#comRow{{ $com->ComID }}").removeClass( "complaintRowWrpActive" );
            $("#comRow{{ $com->ComID }}").addClass( "complaintRowWrp" );
        @empty
        @endforelse
            $("#comRow"+id+"").removeClass( "complaintRowWrp" );
            $("#comRow"+id+"").addClass( "complaintRowWrpActive" );
        }
        return true;
    }
    setTimeout(function() { loadComplaint({{ $first[0] }}, {{ $first[1] }}); }, 10);
    $(document).on("click", ".complaintRowA", function() {
        var idPub = $(this).attr("data-com-pub-id");
        var id = $(this).attr("data-com-id");
        loadComplaint(idPub, id);
    });

    function loadResultSpinner(id) {
        if (document.getElementById("resultSpin"+id+"")) {
            document.getElementById("resultSpin"+id+"").innerHTML="<i class=\"fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom\"></i>";
            chkResultLoaded(id, 0);
        }
        return true;
    }
    function chkResultLoaded(id, cnt) {
        if (resultLoaded == id) {
            return clearResultSpinner(id);
        }
        if (cnt < 180) { // times out after a minute
            setTimeout(function() { chkResultLoaded(id, (1+cnt)); }, 300);
        }
    }
    function clearResultSpinner(id) {
        if (document.getElementById("resultSpin"+id+"")) {
            document.getElementById("resultSpin"+id+"").innerHTML="";
        }
        return true;
    }

    $(document).on("click", "#hidivBtnDashFilters", function() {
        if (document.getElementById("complaintAdmPreview")) {
            if (document.getElementById("complaintAdmPreview").style.display == "block") {
                document.getElementById("hidivDashTools").style.display = "none";
                document.getElementById("complaintAdmPreview").style.display = "block";
            } else {
                document.getElementById("hidivDashTools").style.display = "block";
                document.getElementById("complaintAdmPreview").style.display = "none";
            }
        }
    });

});
</script>
<style>
body {
    overflow-x: hidden;
    overflow-y: hidden;
}
#mainBody {
    background: #F5FBFF;
}
#hidivBtnAdmFoot {
    display: none;
}
.admDashTopCard {
    position: relative;
    margin-top: 5px;
    margin-bottom: 15px;
}
.admDashTopCard .hidivDashFilters {
    position: absolute;
    top: 40px;
}
.admDashTopCard h4, .admDashTopCard .row .col-md-8 h4 {
    margin-top: 5px;
    margin-left: 15px;
}
#admDashTableView {
    margin-bottom: -40px;
}
.complaintRowWrp, .complaintRowWrpActive {
    position: relative;
    min-height: 75px;
}
.complaintRowWrp .resultSpin, .complaintRowWrpActive .resultSpin {
    position: absolute;
    right: 70px;
    top: 20px;
    font-size: 12px;
    color: #FFF;
}
a.complaintRowA:link .float-left, a.complaintRowA:visited .float-left, 
a.complaintRowA:active .float-left, a.complaintRowA:hover .float-left {
    padding-top: 15px;
}
a.complaintRowA:link .float-left.complaintAlert, a.complaintRowA:visited .float-left.complaintAlert, 
a.complaintRowA:active .float-left.complaintAlert, a.complaintRowA:hover .float-left.complaintAlert {
    padding-left: 15px;
}
a.complaintRowA:link .float-right, a.complaintRowA:visited .float-right, 
a.complaintRowA:active .float-right, a.complaintRowA:hover .float-right {
    padding-top: 15px;
    padding-right: 15px;
    text-align: right;
}
.complaintRowWrp a.complaintRowA:link, .complaintRowWrp a.complaintRowA:visited,
.complaintRowWrp a.complaintRowA:active, .complaintRowWrp a.complaintRowA:hover,
.complaintRowWrpActive a.complaintRowA:link, .complaintRowWrpActive a.complaintRowA:visited,
.complaintRowWrpActive a.complaintRowA:active, .complaintRowWrpActive a.complaintRowA:hover {
    position: absolute;
    display: block;
    width: 100%;
    min-height: 75px;
    text-decoration: none;
    color: #416CBD;
    background: #FFF;
    border-bottom: 1px #CCC solid;
}
.complaintRowWrp a.complaintRowA:hover {
    background: #F5FBFF;
}
.complaintRowWrpActive a.complaintRowA:link, .complaintRowWrpActive a.complaintRowA:visited, .complaintRowWrpActive a.complaintRowA:active, .complaintRowWrpActive a.complaintRowA:hover {
    color: #F5FBFF;
    background: #416CBD;
}
.complaintRowWrpActive a.complaintRowA:hover {
    color: #FFF;
}
.complaintRowWrpActive a.complaintRowA:link .slGrey, .complaintRowWrpActive a.complaintRowA:visited .slGrey, .complaintRowWrpActive a.complaintRowA:active .slGrey, .complaintRowWrpActive a.complaintRowA:hover .slGrey {
    color: #AAA;
}
.complaintAlert {
    width: 30px;
    min-width: 30px;
    max-width: 30px;
}
.complaintAlert .litRedDot {
    margin-top: -16px;
}
.complaintRowWrp a.complaintRowFull:link, .complaintRowWrp a.complaintRowFull:visited, .complaintRowWrp a.complaintRowFull:active, .complaintRowWrp a.complaintRowFull:hover,
.complaintRowWrpActive a.complaintRowFull:link, .complaintRowWrpActive a.complaintRowFull:visited, .complaintRowWrpActive a.complaintRowFull:active, .complaintRowWrpActive a.complaintRowFull:hover {
    position: absolute;
    top: 15px;
    right: 15px;
}
.complaintRowWrpActive a.complaintRowFull:link, .complaintRowWrpActive a.complaintRowFull:visited, .complaintRowWrpActive a.complaintRowFull:active, .complaintRowWrpActive a.complaintRowFull:hover {
    color: #FFF;
}
</style>