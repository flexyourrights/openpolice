/* resources/views/vendor/openpolice/nodes/2958-depts-browse-search-ajax.blade.php */

function chkDeptSearchSetup(delay) {
    if (document.getElementById('sResultsDivID')) {
        document.getElementById('sResultsDivID').value='deptResultsWrap';
        document.getElementById('sResultsUrlID').value='/ajax/dept-search?ajax=1&limit=1000';
        setTimeout(function() { autoRunDashResults=true; }, 50);
        if (document.getElementById('srchBarDataSet0') && document.getElementById('srchBarDataSet0').checked) {
            document.getElementById('srchBarDataSet0').checked=false;
        }
    } else {
        delay *= 1.5;
        setTimeout(function() { chkDeptSearchSetup(delay); }, delay);
    }
}
setTimeout(function() { chkDeptSearchSetup(1000); }, 100);

sView = 'list';

function logSrchFilts() {
    if (document.getElementById("sFiltID")) {
        var filts = "";
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
        document.getElementById("sFiltID").value = filts;
//console.log("logSrchFilts: "+document.getElementById("sFiltID").value);
    }
    setTimeout(function() { logSrchFilts(); }, 1000);
    return true;
}
setTimeout(function() { logSrchFilts(); }, 1);
$(document).on("click", ".searchDeetFld", function() {
    setTimeout(function() { logSrchFilts(); }, 10);
});

$(document).on("click", ".fltDeptSortTypeBtn", function() {
    var srtType = $(this).attr("data-sort-type");
    if (document.getElementById("sSortID") && srtType) {
        document.getElementById("sSortID").value=srtType;
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
            document.getElementById("sSortDirID").value='desc';
            updateDeptSortDir('desc');
        }
        autoRunDashResults=true;
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
    autoRunDashResults=true;
}
$(document).on("click", ".fltDeptSortDirBtn", function() {
    var srtDir = $(this).attr("data-sort-dir");
    if (document.getElementById("sSortDirID") && srtDir) {
        document.getElementById("sSortDirID").value=srtDir;
        updateDeptSortDir(srtDir);
    }
});

