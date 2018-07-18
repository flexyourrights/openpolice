/* resources/views/openpolice/nodes/145-ajax-dept-search.blade.php */

if (document.getElementById('nFormNext')) {
    document.getElementById('nFormNext').disabled=true;
    $("#nFormNext").removeClass("btn-primary");
}
function submitAjaxSearch() {
    setTimeout(function() {
        var loadUrl = "/ajax/?policeDept="+encodeURIComponent(document.getElementById("deptNameInID").value)+"&policeState="+encodeURIComponent(document.getElementById("deptStateID").value)+"";
        $("#ajaxSearch").load(loadUrl);
        document.getElementById("ajaxSearch").style.display="block";
        //alert("submitAjaxSearch() " + loadUrl + "");
        return true;
    }, 10);
}
$(document).ready(function(){ // couldn\'t figure out how to call submitAjaxSearch() from here :-\
    var loadUrl = "/ajax/?policeDept="+encodeURIComponent(document.getElementById("deptNameInID").value)+"&policeState="+encodeURIComponent(document.getElementById("deptStateID").value)+"";
    $("#ajaxSearch").load(loadUrl);
    document.getElementById("ajaxSearch").style.display="block";
});
$("#deptNameInID").typeWatch({
    captureLength: 2,
    callback: function(value) { submitAjaxSearch(); }
});
$("#deptNameInID").keypress(function (e) {
    if (e.which == 13) {
        submitAjaxSearch();
        return false;
    }
});
$("#deptStateID").change(function() {
    return submitAjaxSearch();
});
$(document).on("click", "#ajaxSubmit", function() {
    return submitAjaxSearch();
});
$(document).on("click", "a.deptLoad", function() {
    document.getElementById("n{{ $nID }}FldID").value = $(this).attr("id").replace("dept", "");
    document.getElementById("stepID").value="back";
    //alert("New Dept: "+document.getElementById("n{{ $nID }}FldID").value);
    otherFormSub = true;
    return true;
    //return runFormSub();
});

$(document).on("click", "#addNewDept", function() {
    $("#addNewDeptForm").slideToggle("fast");
});
$(document).on("click", "#newDeptSubmit", function() {
    $("#addNewDeptError").slideUp("fast");
    if (!document.getElementById('newDeptNameID') || document.getElementById('newDeptNameID').value.trim() == '' || !document.getElementById('newDeptAddressStateID') || document.getElementById('newDeptAddressStateID').value.trim() == '') {
        $("#addNewDeptError").slideDown("fast");
        return false;
    }
    document.getElementById("n{{ $nID }}FldID").value = -3;
    document.getElementById("stepID").value="back";
    otherFormSub = true;
    return true;
    //return runFormSub();
});

$( "#deptNameInID" ).autocomplete({ 
    select: function(e) { submitAjaxSearch(); }, 
    source: [
        {!! implode(', ', $searchSuggest) !!}
    ]
});