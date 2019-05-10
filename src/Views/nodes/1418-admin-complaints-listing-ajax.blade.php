/* resources/views/vendor/openpolice/nodes/1418-admin-complaints-listing-ajax.blade.php */

$(".applyFilts").on('change', function(event) {
    var url = "/";
    if (document.getElementById("baseUrlID")) url = document.getElementById("baseUrlID").value;
    var params = new Array();
    if (document.getElementById("fltViewID")) params[params.length] = "fltView="+document.getElementById("fltViewID").value;
    if (document.getElementById("fltStatusID") && document.getElementById("fltStatusID").value > 0) {
        params[params.length] = "fltStatus="+document.getElementById("fltStatusID").value;
    }
    if (params.length > 0) {
        url += "?"+params[0];
        for (var i = 1; i < params.length; i++) url += "&"+params[i];
    }
    window.location=url;
    return true;
});

@forelse ($ajaxRefreshs as $i => $c)
    setTimeout(function() {
        document.getElementById('hidFrameID').src="/complaint/read-{{ $c }}?refresh=1";
    }, {{ (3000*$i) }});
@empty @endforelse