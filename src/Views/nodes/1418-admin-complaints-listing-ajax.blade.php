/* resources/views/vendor/openpolice/nodes/1418-admin-complaints-listing-ajax.blade.php */

sView = '{{ $sView }}';

function logSrchFilts() {
    if (document.getElementById("sFiltID")) {
        var filts = "";
        var comstatus = "";
        for (var i = 0; i < {!! (5+sizeof($GLOBALS["SL"]->def->getSet('Complaint Status'))+sizeof($GLOBALS["SL"]->def->getSet('Complaint Type'))) !!}; i++) {
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
        for (var i = 0; i < {{ sizeof($GLOBALS["CUST"]->worstAllegs) }}; i++) {
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
        var comSetts = "";
        for (var i = 3; i <= 5; i++) {
            if (document.getElementById("filtComSetts"+i+"") && document.getElementById("filtComSetts"+i+"").checked) {
                comSetts += "," + document.getElementById("filtComSetts"+i+"").value;
            }
        }
        if (comSetts.trim() != "") {
            filts += "__comsetts_"+comSetts.substring(1);
        }
        document.getElementById("sFiltID").value = filts;
//console.log("logSrchFilts: "+document.getElementById("sFiltID").value);
    }
    return true;
}
$(document).on("click", ".updateComplaintFilts", function() {
    logSrchFilts();
});
function autoLogSrchFilts() {
    if (document.getElementById("sFiltID")) {
        logSrchFilts();
        setTimeout(function() { autoLogSrchFilts(); }, 1000);
    } else {
        setTimeout(function() { autoLogSrchFilts(); }, 90);
    }
}
setTimeout(function() { autoLogSrchFilts(); }, 1);


$(document).on("click", ".clearComplaintFilts", function() {
    for (var i = 0; i < 20; i++) {
        if (document.getElementById("fltStatus"+i+"")) {
            if (i < 3) {
                document.getElementById("fltStatus"+i+"").checked = true;
            } else {
                document.getElementById("fltStatus"+i+"").checked = false;
            }
        }
        if (document.getElementById("filtAllegs"+i+"")) {
            document.getElementById("filtAllegs"+i+"").checked = false;
        }
        if (document.getElementById("filtVictRace"+i+"")) {
            document.getElementById("filtVictRace"+i+"").checked = false;
        }
        if (document.getElementById("filtOffRace"+i+"")) {
            document.getElementById("filtOffRace"+i+"").checked = false;
        }
    }
    if (document.getElementById("filtVictGendM")) {
        document.getElementById("filtVictGendM").checked = false;
    }
    if (document.getElementById("filtVictGendF")) {
        document.getElementById("filtVictGendF").checked = false;
    }
    if (document.getElementById("filtVictGendT")) {
        document.getElementById("filtVictGendT").checked = false;
    }
    if (document.getElementById("filtOffGendM")) {
        document.getElementById("filtOffGendM").checked = false;
    }
    if (document.getElementById("filtOffGendF")) {
        document.getElementById("filtOffGendF").checked = false;
    }
    @foreach ($GLOBALS["SL"]->states->stateList as $abbr => $state)
        if (document.getElementById("states{{ $abbr }}")) {
            document.getElementById("states{{ $abbr }}").checked = false;
        }
    @endforeach
    logSrchFilts();
});

$(document).on("click", ".fltStatus", function() {
    var status = $(this).val();
    if ($(this).is(':checked')) {
        if (status == 296) {
            for (var i = 3; i < 15; i++) {
                if (document.getElementById("fltStatus"+i+"")) {
                    document.getElementById("fltStatus"+i+"").checked = false;
                }
            }
        } else if (jQuery.inArray(status, [195, 196, 197, 198, 727, 199, 200, 201, 202, 203, 204, 205, 627])) {
            if (document.getElementById("fltStatus0")) {
                document.getElementById("fltStatus0").checked = false;
            }
            if (document.getElementById("fltStatus2")) {
                document.getElementById("fltStatus2").checked = false;
            }
        }
    }
});

setTimeout(function() {
    if (document.getElementById("cachePreloads")) {
    @if ($GLOBALS["SL"]->x["isPublicList"])
        document.getElementById("cachePreloads").src="/ajax/complaint-preloads";
    @else
        document.getElementById("cachePreloads").src="/ajadm/complaint-preloads-staff";
    @endif
    }
}, 20000);
