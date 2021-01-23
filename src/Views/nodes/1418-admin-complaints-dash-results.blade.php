<!-- resources/views/vendor/openpolice/nodes/1418-admin-complaints-dash-results.blade.php -->
<?php $sortCaret = (($sortDir == 'desc') ? 'down' : 'up'); ?>
<div class="pB10 slGrey">
    <a data-sort-type="first-name" 
    @if ($sortLab != 'first-name') data-sort-dir="asc"
    @else @if ($sortDir != 'desc') data-sort-dir="desc" @else data-sort-dir="asc" @endif
    @endif
        class="fltSortTypeBtn @if ($sortLab == 'first-name') bld @endif "
        href="javascript:;" >First Name
    @if ($sortLab == 'first-name') 
        <i class="fa fa-caret-{{ $sortCaret }}" aria-hidden="true"></i>
    @endif
    </a> |
    <a data-sort-type="last-name" 
    @if ($sortLab != 'last-name') data-sort-dir="asc"
    @else @if ($sortDir != 'desc') data-sort-dir="desc" @else data-sort-dir="asc" @endif
    @endif
        class="fltSortTypeBtn @if ($sortLab == 'last-name') bld @endif "
        href="javascript:;" >Last Name
    @if ($sortLab == 'last-name') 
        <i class="fa fa-caret-{{ $sortCaret }}" aria-hidden="true"></i>
    @endif
    </a> |
    <a data-sort-type="city" 
    @if ($sortLab != 'city') data-sort-dir="asc"
    @else @if ($sortDir != 'desc') data-sort-dir="desc" @else data-sort-dir="asc" @endif
    @endif
        class="fltSortTypeBtn @if ($sortLab == 'city') bld @endif "
        href="javascript:;" >Incident City
    @if ($sortLab == 'city') 
        <i class="fa fa-caret-{{ $sortCaret }}" aria-hidden="true"></i>
    @endif
    </a><br />

    ID# |
    <a data-sort-type="urgency" 
    @if ($sortLab != 'urgency') data-sort-dir="desc"
    @else @if ($sortDir != 'desc') data-sort-dir="desc" @else data-sort-dir="asc" @endif
    @endif
        class="fltSortTypeBtn @if ($sortLab == 'urgency') bld @endif "
        href="javascript:;" >Status
    @if ($sortLab == 'urgency') 
        <i class="fa fa-caret-{{ $sortCaret }}" aria-hidden="true"></i>
    @endif
    </a> |
    <a data-sort-type="date" 
        @if ($sortLab != 'date') data-sort-dir="desc"
        @else 
            @if ($sortDir != 'desc') data-sort-dir="desc" 
            @else data-sort-dir="asc" 
            @endif
        @endif
        class="fltSortTypeBtn @if ($sortLab == 'date') bld @endif "
        href="javascript:;" >Date Submitted to OpenPolice.org
    @if ($sortLab == 'date') 
        <i class="fa fa-caret-{{ $sortCaret }}" aria-hidden="true"></i>
    @endif
    </a>
</div>
<?php $cnt = 0; ?>
@if (sizeof($complaints) > 0)
    @foreach ($complaints as $j => $com)
        <div id="dashResultsAnim{{ $cnt }}" 
            class="dashResultsAnim" style="display: none;">
            <div id="comRow{{ $com->com_id }}" class="complaintRowWrp">
                <?php $cnt++; ?>
                {!! view(
                    'vendor.openpolice.nodes.1418-admin-complaints-listing-row', 
                    [
                        "com" => $com,
                        "inc"  => $com,
                        "prsn" => $com
                    ]
                )->render() !!}
            </div>
        </div>
    @endforeach
    <div id="dashResultsSpin">{!! $GLOBALS["SL"]->spinner() !!}</div>
@else
    <div id="dashResultsNone" class="pL15">
        No complaints found in this filter
    </div>
@endif

<script type="text/javascript"> $(document).ready(function(){

dashResultCnt = {{ sizeof($complaints) }};

function loadComplaint(idPub, id) {
    if (id > 0) {
        currRightPane = 'preview';
        updateRightPane();
        loadResultSpinner(id);
        if (document.getElementById("reportAdmPreview")) {
            document.getElementById("reportAdmPreview").innerHTML=getSpinner();
            var url = "/complaint/readi-"+id+"/full?ajax=1&wdg=1";
            if (idPub > 0) {
                url = "/complaint/read-"+idPub+"/full?ajax=1&wdg=1";
            }
            setTimeout(function() { $("#reportAdmPreview").load(url); }, 1);
        }
        /*
        if (document.getElementById("reportAdmPreview")) {
            //document.getElementById("reportAdmPreview").innerHTML=getSpinnerPadded();
            //setTimeout("document.getElementById('reportAdmPreview').src='/spinner'", 1);
            var url = "/complaint/readi-"+id+"/full?frame=1&wdg=1";
            if (idPub > 0) {
                url = "/complaint/read-"+idPub+"/full?frame=1&wdg=1";
            }
            setTimeout("document.getElementById('reportAdmPreview').src='"+url+"'", 100);
        }
        */
    @forelse ($complaints as $j => $com)
        $("#comRow{{ $com->com_id }}").removeClass( "complaintRowWrpActive" );
        $("#comRow{{ $com->com_id }}").addClass( "complaintRowWrp" );
    @empty
    @endforelse
        $("#comRow"+id+"").removeClass( "complaintRowWrp" );
        $("#comRow"+id+"").addClass( "complaintRowWrpActive" );
    }
    return true;
}
setTimeout(function() { loadComplaint({{ $firstComplaint[0] }}, {{ $firstComplaint[1] }}); }, 10);
$(document).on("click", ".complaintRowA", function() {
    var idPub = $(this).attr("data-com-pub-id");
    var id = $(this).attr("data-com-id");
    loadComplaint(idPub, id);
});

function loadResultSpinner(id) {
    if (document.getElementById("resultSpin"+id+"")) {
        document.getElementById("resultSpin"+id+"").style.display="block";
        document.getElementById("resultSpin"+id+"").innerHTML="<i>Loading...</i>";
// <i class=\"fa fa-circle-o-notch fa-spin fa-fw margin-bottom\" aria-hidden=\"true\"></i>
        chkResultLoaded(id, 0);
    }
    return true;
}
function chkResultLoaded(id, cnt) {
    if (document.getElementById("reportAdmPreview")) {
        if (document.getElementById("resultLoadedID")) {
            resultLoaded = document.getElementById("resultLoadedID").value;
        }
        if (resultLoaded == id) {
            return clearResultSpinner(id);
        }
        if (cnt < 360) { // times out after two minutes
            setTimeout(function() { chkResultLoaded(id, (1+cnt)); }, 300);
        }
    }
}
function clearResultSpinner(id) {
    if (document.getElementById("resultSpin"+id+"")) {
        setTimeout(function() { 
            $("#resultSpin"+id+"").fadeOut(2000);
            setTimeout(function() { 
                document.getElementById("resultSpin"+id+"").innerHTML=""; 
            }, 2001);
        }, 2000);
    }
    return true;
}

setTimeout(function() {
    document.getElementById("searchFoundCnt").innerHTML={!! json_encode($complaintFiltDescPrev) !!};
}, 100);


$(document).on("click", ".updateSearchFilts", function() {
    document.getElementById("dashResultsWrap").innerHTML=getSpinner();
    document.getElementById("reportAdmPreview").innerHTML=getSpinner();
    document.getElementById("searchFoundCnt").innerHTML='';
    currRightPane = 'preview';
    updateRightPane();
});


}); </script>