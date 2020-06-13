<!-- resources/views/vendor/openpolice/nodes/2958-depts-browse-search.blade.php -->
<div class="nodeAnchor"><a id="n{{ $nID }}" name="n{{ $nID }}"></a></div>
<div id="node{{ $nID }}">

    <div class="h100 pT15">

        <div class="admDashTopCard">
            <div class="slCard">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="mB0">
                            Browse & Search Police Departments
                            <div id="searchFoundCnt" class="disIn"></div>
                        </h4>
                    </div>
                    <div class="col-md-4">

                        <input type="hidden" id="sDeptSortID" name="sDeptSort" value="">
                        <input type="hidden" id="sDeptSortDirID" name="sDeptSortDir" value="">
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
                                            'vendor.openpolice.department-listing-sorts', 
                                            [
                                                "sortLab" => $sortLab,
                                                "sortDir" => $sortDir
                                            ]
                                        )->render() !!}
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div> <!-- end slCard -->
        </div>

        <div id="admDashLargeView">
            <div class="row">
                <div class="col-xl-4">
                    <div id="filtsCard" class="slCard nodeWrap">
                    <input name="showPreviews" id="showPreviewsID" 
                        type="hidden" value="0">

                    <h4>Search</h4>
                    <div class="row">
                        <div class="col-8">
                            <input name="deptSearch" id="deptSearchID"
                                type="text" value="{{ $deptSearch }}" 
                                class="form-control form-control-sm w100">
                        </div><div class="col-4">
                            <a id="compFiltBtn{{ $nID }}" href="javascript:;" 
                                class="btn btn-secondary btn-sm btn-block searchDeptDeetFld"
                                ><i class="fa fa-search" aria-hidden="true"></i></a>
                        </div>
                    </div>

                    <div class="pT15 pB15"><hr></div>

                    <div id="hidivCompFilts{{ $nID }}" class="disBlo">
                        <div class="row">
                            <div class="col-6">
                                <h4>Filter</h4>
                            </div><div class="col-6">
                                <a id="compFiltBtn{{ $nID }}" href="javascript:;" 
                                    class="btn btn-secondary btn-sm btn-block searchDeptDeetFld"
                                    >Apply Filters</a>
                            </div>
                        </div>
                        <div class="p0"><br></div>
                        {!! $stateFilts !!}
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="nodeAnchor"><a id="results" name="results"></a></div>
                <div id="deptPreviews" class="w100"></div>
            </div>
        </div>
    </div> <!-- end admDashLargeView -->


</div> <!-- end #node{{ $nID }} -->

<script type="text/javascript"> $(document).ready(function(){

function applyDeptSearch(forceRun = false) {
    var src = '/ajax/dept-search?ajax=1';
    var hasFilters = forceRun;
    if (document.getElementById("deptSearchID") && document.getElementById("deptSearchID").value.trim() != '') {
        hasFilters = true;
        src += '&deptSearch='+encodeURIComponent(document.getElementById("deptSearchID").value.trim());
    }
    var states = $.map($(':checkbox[name=states\\[\\]]:checked'), function(n, i){
        return n.value;
    }).join(',');
    if (states.trim() != '') {
        hasFilters = true;
        src += '&states='+states;
    }
    if (hasFilters) {
        src += '&sDeptSort='+document.getElementById("sDeptSortID").value+'&sDeptSortDir='+document.getElementById("sDeptSortDirID").value;
        document.getElementById("deptPreviews").innerHTML='<center>'+getSpinner()+'</center>';
        console.log('dept search: '+src+'');
        $("#deptPreviews").load(src);
    }

}
setTimeout(function() { applyDeptSearch(true); }, 20);
$(document).on("click", ".searchDeptDeetFld", function() {
    setTimeout(function() { applyDeptSearch(true); }, 20);
});
$(document).on("click", ".fltStates", function() {
    setTimeout(function() { applyDeptSearch(); }, 20);
});
$('#deptSearchID').keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') applyDeptSearch(true);
});


$(document).on("click", ".fltDeptSortTypeBtn", function() {
    var srtType = $(this).attr("data-sort-type");
    if (document.getElementById("sDeptSortID") && srtType) {
        document.getElementById("sDeptSortID").value=srtType;
        document.getElementById("fltDeptSortTypeMatch").className="fltDeptSortTypeBtn dropdown-item";
        document.getElementById("fltDeptSortTypeName").className="fltDeptSortTypeBtn dropdown-item";
        document.getElementById("fltDeptSortTypeCity").className="fltDeptSortTypeBtn dropdown-item";
        document.getElementById("fltDeptSortTypeScore").className="fltDeptSortTypeBtn dropdown-item";
        if (srtType == 'match') {
            document.getElementById("fltDeptSortTypeMatch").className+=" active";
        } else if (srtType == 'name') {
            document.getElementById("fltDeptSortTypeName").className+=" active";
        } else if (srtType == 'city') {
            document.getElementById("fltDeptSortTypeCity").className+=" active";
        } else if (srtType == 'score') {
            document.getElementById("fltDeptSortTypeScore").className+=" active";
            document.getElementById("sDeptSortDirID").value='desc';
            updateDeptSortDir('desc');
        }
        applyDeptSearch();
    }
});
function updateDeptSortDir(srtDir) {
    document.getElementById("fltDeptSortDirAsc").className="fltDeptSortDirBtn dropdown-item";
    document.getElementById("fltDeptSortDirDesc").className="fltDeptSortDirBtn dropdown-item";
    if (srtDir == 'asc') {
        document.getElementById("fltDeptSortDirAsc").className+=" active";
    } else if (srtDir == 'desc') {
        document.getElementById("fltDeptSortDirDesc").className+=" active";
    }
}
$(document).on("click", ".fltDeptSortDirBtn", function() {
    var srtDir = $(this).attr("data-sort-dir");
    if (document.getElementById("sDeptSortDirID") && srtDir) {
        document.getElementById("sDeptSortDirID").value=srtDir;
        applyDeptSearch();
        updateDeptSortDir(srtDir);
    }
});


}); </script>


<style>
#admDashLargeView { margin-top: 25px; }
#searchFoundCnt { font-size: 16px; font-weight: normal; margin-left: 10px; }
</style>
