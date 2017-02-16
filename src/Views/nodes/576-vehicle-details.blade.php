<!-- resources/views/vendor/openpolice/nodes/576-vehicle-details.blade.php -->

<input type="hidden" name="previouslyAlreadyDescribed" value="{{ $alreadyDescribed }}">
<input type="hidden" name="previouslyVehicleWhich" value="{{ $whichVehic }}">

<input type="hidden" name="n{{ $nodes[0] }}Visible" id="n{{ $nodes[0] }}VisibleID" value="0">
<div class="fC"></div>


<div id="node{{ $nodes[0] }}" class="nodeWrap">
    <div id="nLabel{{ $nodes[0] }}" class="nPrompt">
        <h2>Have you already described this vehicle?</h2>
    </div>
    <div class="nFld" style="margin-top: 20px;">
        <label for="n{{ $nodes[0] }}fld0" id="n{{ $nodes[0] }}fld0lab" 
            class=" @if ($alreadyDescribed == 'Y') fingerAct @else finger @endif ">
            <div class="disIn mR5"><input id="n{{ $nodes[0] }}fld0" value="Y" type="radio" name="n{{ $nodes[0] }}fld" 
                @if ($alreadyDescribed == 'Y') CHECKED @endif
                autocomplete="off" onClick="checkNodeUp({{ $nodes[0] }}, 0, 1);" ></div> Yes
        </label>
        <label for="n{{ $nodes[0] }}fld1" id="n{{ $nodes[0] }}fld1lab" 
            class=" @if ($alreadyDescribed == 'N' || sizeof($vehicles) == 0) fingerAct @else finger @endif ">
            <div class="disIn mR5"><input id="n{{ $nodes[0] }}fld1" value="N" type="radio" name="n{{ $nodes[0] }}fld" 
                @if ($alreadyDescribed == 'N' || sizeof($vehicles) == 0) CHECKED @endif
                autocomplete="off" onClick="checkNodeUp({{ $nodes[0] }}, 1, 1);" ></div> No
        </label>
    </div>
</div> <!-- end #node{{ $nodes[0] }} -->
<div class="nodeGap"></div>

<div id="node{{ $nodes[0] }}kidsY" class="nKids @if ($alreadyDescribed == 'Y') disBlo @else disNon @endif ">
    <input type="hidden" name="n{{ $nodes[1] }}Visible" id="n{{ $nodes[1] }}VisibleID" 
        @if ($alreadyDescribed == 'Y') value="1" @else value="0" @endif >
    <div class="fC"></div>
    <div id="node{{ $nodes[1] }}" class="nodeWrap">
        <div id="nLabel{{ $nodes[1] }}" class="nPrompt">
            <h2>Which vehicle was it?</h2>
        </div>
        <div class="pL15"><div class="nFld" style="margin-top: 20px;">
            @forelse ($vehicles as $i => $v)
                <label for="n{{ $nodes[1] }}fld{{ $i }}" id="n{{ $nodes[1] }}fld0lab" 
                    class=" @if ($whichVehic == $v[0]) fingerAct @else finger @endif ">
                    <div class="disIn mR5"><input id="n{{ $nodes[1] }}fld{{ $i }}" 
                        value="{!! $v[0] !!}" type="radio" name="n{{ $nodes[1] }}fld" autocomplete="off" 
                        @if ($whichVehic == $v[0]) CHECKED @endif onClick="checkNodeUp();" ></div> 
                    {!! $v[1] !!}
                </label>
            @empty
            @endforelse
        </div></div>
    </div> <!-- end #node{{ $nodes[1] }} -->
    <div class="nodeGap"></div>
</div>
<div id="node{{ $nodes[0] }}kidsN" class="nKids @if ($alreadyDescribed == 'N') disBlo @else disNon @endif ">
    {!! $vehicDeets !!}
</div>

<script type="text/javascript">
addFld("n{{ $nodes[0] }}fld0"); addFld("n{{ $nodes[0] }}fld1");

$(function() {
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
});
        
</script>