<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-tools-progress-dates.blade.php -->

@if (isset($comDepts) && sizeof($comDepts) > 0)
    @foreach ($comDepts as $c => $dept)
        @if (isset($dept["deptRow"]) 
            && isset($dept["deptRow"]->dept_name)
            && strpos(strtolower($dept["deptRow"]->dept_name), 'not sure') === false)
            <div class="pB10">
                <p><b>Progress with the {!! $dept["deptRow"]->dept_name !!}</b></p>
                <div class="nFld mT0">
                @foreach ($oversightDateLookups as $d => $date)
                    <div class="row mB5">
                        <div class="col-md-8">
                            <label class="finger">
                                <input type="checkbox" value="{{ $d }}" 
                                    id="over{{ $dept['id'] }}Status{{ $d }}" 
                                    name="over{{ $dept['id'] }}Status[]"
                                    @if (isset($dept["overDates"]->{ $date[0] }) 
                                        && trim($dept["overDates"]->{ $date[0] }) != '') 
                                        CHECKED 
                                    @endif
                                    class="chkOverStatus" autocomplete="off"> 
                                <span class="mL5">{{ $date[1] }}</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <div id="over{{ $dept['id'] }}Status{{ $d }}dateFlds" 
                                class="disNon pT5">
                            {!! $GLOBALS["SL"]->printDatePicker(
                                ((isset($dept["overDates"]->{ $date[0] })) 
                                    ? $dept["overDates"]->{ $date[0] } : ''),
                                'over' . $dept["id"] . 'Status' . $d . 'date'
                            ) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        @endif
    @endforeach
@endif

<script type="text/javascript">

$(document).ready(function(){
    function chkOverStatusBox(deptID, d) {
        var fldID = "over"+deptID+"Status"+d+"";
        if (document.getElementById(fldID) && document.getElementById(fldID+"dateFlds")) {
            if (document.getElementById(fldID).checked) {
                document.getElementById(fldID+"dateFlds").style.display="block";
                if (document.getElementById('over'+deptID+'Status'+d+'dateID') && document.getElementById('over'+deptID+'Status'+d+'dateID').value.trim() == '') {
                    document.getElementById('over'+deptID+'Status'+d+'dateID').value = '{{ date("m/d/Y") }}';
                }
            } else {
                document.getElementById(fldID+"dateFlds").style.display="none";
            }
        }
    }
    function chkOverStatus() {
@if (isset($comDepts) && sizeof($comDepts) > 0)
    @foreach ($comDepts as $c => $dept)
        @if (isset($dept["deptRow"]) && isset($dept["deptRow"]->dept_name))
            @foreach ($oversightDateLookups as $d => $date)
                chkOverStatusBox({{ $dept["id"] }}, {{ $d }});
            @endforeach
        @endif
    @endforeach
@endif
    }
    setTimeout(function() { chkOverStatus(); }, 10);
    $(".chkOverStatus").on('click', function(event) { chkOverStatus(); });

});

</script>