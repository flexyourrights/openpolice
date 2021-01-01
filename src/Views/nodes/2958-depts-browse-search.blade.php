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
                <?php /*
                    <div class="col-md-4">
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
                */ ?>
                </div>
            </div> <!-- end slCard -->
        </div>

        <div id="admDashLargeView">
            <div class="row">
                <div class="col-xl-4">
                    <div id="filtsCard" class="slCard nodeWrap">
                        <input name="showPreviews" id="showPreviewsID" type="hidden" value="0">
                        <div id="hidivCompFilts{{ $nID }}" class="disBlo">
                            <?php /*
                            <div class="row">
                                <div class="col-6">
                            */ ?>
                                    <h4>Filter</h4>
                            <?php /*
                                </div><div class="col-6">
                                    <a id="compFiltBtn{{ $nID }}" href="javascript:;" 
                                        class="btn btn-primary btn-sm fR searchDeptDeetFld"
                                        >Apply Filters</a>
                                </div>
                            </div>
                            */ ?>
                            <div class="p0"><br></div>
                            {!! $stateFilts !!}
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="nodeAnchor"><a id="results" name="results"></a></div>
                    <div id="deptResultsWrap" class="w100"></div>
                </div>
            </div>
        </div> <!-- end admDashLargeView -->

    </div>

</div> <!-- end #node{{ $nID }} -->

<style>
#admDashLargeView { 
    margin-top: 25px; 
}
#searchFoundCnt {
    font-size: 16px; 
    font-weight: normal; 
    margin-left: 10px; 
}
</style>

<?php /*

<script type="text/javascript"> $(document).ready(function(){

function applyDeptSearch(forceRun = false) {
    var src = '/ajax/dept-search?ajax=1';
    var hasFilters = forceRun;
    if (document.getElementById("admSrchFld") && document.getElementById("admSrchFld").value.trim() != '') {
        hasFilters = true;
        src += '&deptSearch='+encodeURIComponent(document.getElementById("admSrchFld").value.trim());
    }
    var states = $.map($(':checkbox[name=states\\[\\]]:checked'), function(n, i){
        return n.value;
    }).join(',');
    if (states.trim() != '') {
        hasFilters = true;
        src += '&states='+states;
    }
    if (hasFilters) {
        src += '&sDeptSort='+document.getElementById("sSortID").value+'&sDeptSortDir='+document.getElementById("sSortDirID").value;
        document.getElementById("deptPreviews").innerHTML=getSpinner();
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
$('#admSrchFld').keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') applyDeptSearch(true);
    return false;
});

}); </script>

*/ ?>
