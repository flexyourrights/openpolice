/* resources/views/vendor/openpolice/nodes/1418-admin-complaints-listing-ajax.blade.php */


function logSrchFilts() {
    if (document.getElementById("sFiltID")) {
        var filts = "";
        var comstatus = "";
        for (var i = 0; i < {!! (7+sizeof($GLOBALS["SL"]->def->getSet('Complaint Status'))) !!}; i++) {
            if (document.getElementById("fltStatus"+i+"") && document.getElementById("fltStatus"+i+"").checked) {
                comstatus += "," + document.getElementById("fltStatus"+i+"").value;
            }
        }
        if (comstatus.trim() != "") {
            filts += "__comstatus_"+comstatus.substring(1);
        }
        var comtype = "";
        for (var i = 0; i < 5; i++) {
            if (document.getElementById("fltType"+i+"") && document.getElementById("fltType"+i+"").checked) {
                comtype += "," + document.getElementById("fltType"+i+"").value;
            }
        }
        if (comtype.trim() != "") {
            filts += "__comtype_"+comtype.substring(1);
        }
        var statesChecked = "";
        var stateAbbrs = getStateList();
        for (var i = 0; i < stateAbbrs.length; i++) {
            if (document.getElementById("states"+stateAbbrs[i]+"") && document.getElementById("states"+stateAbbrs[i]+"").checked) {
                statesChecked += "," + stateAbbrs[i];
            }
        }
        if (statesChecked.trim() != "") {
            filts += "__states_" + statesChecked.substring(1);
        }
        var filtAllegs = "";
        for (var i = 0; i < {{ sizeof($allegTypes) }}; i++) {
            if (document.getElementById("filtAllegs"+i+"") && document.getElementById("filtAllegs"+i+"").checked) {
                filtAllegs += "," + document.getElementById("filtAllegs"+i+"").value;
            }
        }
        if (filtAllegs.trim() != "") {
            filts += "__allegs_"+filtAllegs.substring(1);
        }
        var victGends = "";
        if (document.getElementById("filtVictGendM") && document.getElementById("filtVictGendM").checked) {
            victGends += "," + document.getElementById("filtVictGendM").value;
        }
        if (document.getElementById("filtVictGendF") && document.getElementById("filtVictGendF").checked) {
            victGends += "," + document.getElementById("filtVictGendF").value;
        }
        if (document.getElementById("filtVictGendT") && document.getElementById("filtVictGendT").checked) {
            victGends += "," + document.getElementById("filtVictGendT").value;
        }
        if (victGends.trim() != "") {
            filts += "__victgend_"+victGends.substring(1);
        }
        var victRace = "";
        for (var i = 0; i < {{ sizeof($GLOBALS["SL"]->def->getSet('Races')) }}; i++) {
            if (document.getElementById("filtVictRace"+i+"") && document.getElementById("filtVictRace"+i+"").checked) {
                victRace += "," + document.getElementById("filtVictRace"+i+"").value;
            }
        }
        if (victRace.trim() != "") {
            filts += "__victrace_"+victRace.substring(1);
        }
        var offGends = "";
        if (document.getElementById("filtOffGendM") && document.getElementById("filtOffGendM").checked) {
            offGends += "," + document.getElementById("filtOffGendM").value;
        }
        if (document.getElementById("filtOffGendF") && document.getElementById("filtOffGendF").checked) {
            offGends += "," + document.getElementById("filtOffGendF").value;
        }
        if (offGends.trim() != "") {
            filts += "__offgend_"+offGends.substring(1);
        }
        var offRace = "";
        for (var i = 0; i < {{ sizeof($GLOBALS["SL"]->def->getSet('Races')) }}; i++) {
            if (document.getElementById("filtOffRace"+i+"") && document.getElementById("filtOffRace"+i+"").checked) {
                offRace += "," + document.getElementById("filtOffRace"+i+"").value;
            }
        }
        if (offRace.trim() != "") {
            filts += "__offrace_"+offRace.substring(1);
        }
        document.getElementById("sFiltID").value = filts;
    }
    return true;
}

$(document).on("click", ".fltStatus", function() {
    var status = $(this).val();
    if ($(this).is(':checked')) {
        if (status == 296) {
            for (var i = 3; i < 15; i++) {
                if (document.getElementById("fltStatus"+i+"")) {
                    document.getElementById("fltStatus"+i+"").checked = false;
                }
            }
        } else if (jQuery.inArray(status, [195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 627]) && document.getElementById("fltStatus2")) {
            document.getElementById("fltStatus2").checked = false;
        }
    }
});

function loadComplaint(idPub, id) {
    if (id > 0) {
        currRightPane = 'preview';
        updateRightPane();
        loadResultSpinner(id);
        if (document.getElementById("reportAdmPreview")) {
            if (idPub > 0) {
                document.getElementById("reportAdmPreview").src="/complaint/read-"+idPub+"/full?frame=1&wdg=1";
            } else {
                document.getElementById("reportAdmPreview").src="/complaint/readi-"+id+"/full?frame=1&wdg=1";
            }
        }
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

sView = '{{ $sView }}';

function loadResultSpinner(id) {
    if (document.getElementById("resultSpin"+id+"")) {
        document.getElementById("resultSpin"+id+"").innerHTML="<i class=\"fa fa-spinner fa-pulse fa-fw margin-bottom\"></i>";
        chkResultLoaded(id, 0);
    }
    return true;
}
function chkResultLoaded(id, cnt) {
    if (document.getElementById("reportAdmPreview")) {
        var iframe = document.getElementById("reportAdmPreview");
        if (iframe.contentWindow.document.getElementById("resultLoadedID")) {
            resultLoaded = iframe.contentWindow.document.getElementById("resultLoadedID").value;
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
        document.getElementById("resultSpin"+id+"").innerHTML="";
    }
    return true;
}

function updateSearchDeets() {
    logSrchFilts();
    if (document.getElementById("sViewID")) {
        document.getElementById("sViewID").value="{{ $sView }}";
    }
}
setTimeout(function() { updateSearchDeets(); }, 100);
$(document).on("click", ".searchDeetFld", function() { updateSearchDeets(); });
$(document).on("change", ".searchDeetFld", function() { updateSearchDeets(); });
$(document).on("submit", "#dashSearchFrmID", function() { updateSearchDeets(); });
