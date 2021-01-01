<!-- resources/views/vendor/openpolice/ajax/redir-complaint-refresh.blade.php -->
<script type="text/javascript"> $(document).ready(function(){

function refreshComplaint(coreID, pubID) {
    var url = "/complaint/readi-"+coreID+"/full?ajax=1&wdg=1&refresh=1";
    if (pubID > 0) {
        url = "/complaint/read-"+pubID+"/full?ajax=1&wdg=1&refresh=1";
    }
    if (document.getElementById("reportAdmPreview")) {
        document.getElementById("reportAdmPreview").innerHTML=getSpinner();
        $("#reportAdmPreview").load(url);
        setTimeout(function() { window.location="#top"; }, 1);
    } else if (document.getElementById("admDashReportWrap")) {
        document.getElementById("admDashReportWrap").innerHTML=getSpinner();
        $("#admDashReportWrap").load(url);
        setTimeout(function() { window.location="#top"; }, 1);
    } else {
        if (document.getElementById("blockWrap1385")) {
            document.getElementById("blockWrap1385").innerHTML=getSpinner();
        }
        url = "/complaint/readi-"+coreID+"/full?refresh=1";
        if (pubID > 0) {
            url = "/complaint/read-"+pubID+"/full?refresh=1";
        }
        setTimeout(function() { window.location=url; }, 1);
    }
    return true;
}
setTimeout(function() { refreshComplaint({{ $coreID }}, {{ $pubID }}); }, 1);

}); </script>
