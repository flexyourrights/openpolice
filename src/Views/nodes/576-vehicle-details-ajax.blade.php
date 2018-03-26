/* resources/views/vendor/openpolice/nodes/576-vehicle-details-ajax.blade.php */

function checkVehicDeets() {
    if (document.getElementById("n{{ $nodes[0] }}fld0").checked) {
        $("#node{{ $nodes[0] }}kidsY").slideDown("50");
        $("#node{{ $nodes[0] }}kidsN").slideUp("50");
        kidsVisible({{ $nodes[1] }}, true, false); 
        kidsVisible({{ $nodes[2] }}, false, false); 
        kidsVisible({{ $nodes[3] }}, false, false); 
        kidsVisible({{ $nodes[4] }}, false, false); 
        kidsVisible({{ $nodes[5] }}, false, false); 
        kidsVisible({{ $nodes[6] }}, false, false); 
        @if ($nodes[7] > 0) kidsVisible({{ $nodes[7] }}, false, false); @endif
    }
    else if (document.getElementById("n{{ $nodes[0] }}fld1").checked) {
        $("#node{{ $nodes[0] }}kidsY").slideUp("50");
        $("#node{{ $nodes[0] }}kidsN").slideDown("50");
        kidsVisible({{ $nodes[1] }}, false, false); 
        kidsVisible({{ $nodes[2] }}, true, false); 
        kidsVisible({{ $nodes[3] }}, true, false); 
        kidsVisible({{ $nodes[4] }}, true, false); 
        kidsVisible({{ $nodes[5] }}, true, false); 
        kidsVisible({{ $nodes[6] }}, true, false); 
        @if ($nodes[7] > 0) kidsVisible({{ $nodes[7] }}, true, false); @endif
    }
    else {
        $("#node{{ $nodes[0] }}kidsY").slideUp("50");
        $("#node{{ $nodes[0] }}kidsN").slideUp("50");
        kidsVisible({{ $nodes[1] }}, false, false); 
        kidsVisible({{ $nodes[2] }}, false, false); 
        kidsVisible({{ $nodes[3] }}, false, false); 
        kidsVisible({{ $nodes[4] }}, false, false); 
        kidsVisible({{ $nodes[5] }}, false, false); 
        kidsVisible({{ $nodes[6] }}, false, false); 
        @if ($nodes[7] > 0) kidsVisible({{ $nodes[7] }}, false, false); @endif
    }
    return true;
}

$( window ).load( checkVehicDeets );

$(".n{{ $nodes[0] }}fldCls").click(function(){ checkVehicDeets(); });
