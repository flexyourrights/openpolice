<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-tools-progress-dates.blade.php -->

@if (isset($comDepts) && sizeof($comDepts) > 0)
    @foreach ($comDepts as $c => $dept)
        @if (isset($dept["deptRow"]) && isset($dept["deptRow"]->DeptName))
            <p><hr></p>
            <p><b>Status with the {!! str_replace("Police Department", "PD", 
                str_replace("Sheriff's Office", "Sheriff", $dept["deptRow"]->DeptName)) !!}</b></p>
            <div class="nFld mT0">
            @foreach ($oversightDates as $d => $date)
                <div class="row">
                    <div class="col-8">
                        <label class="finger">
                            <input type="checkbox" id="over{{ $dept['id'] }}Status{{ $d }}" 
                                name="over{{ $dept['id'] }}Status[]" value="{{ $d }}"
                                @if (isset($dept["overDates"]->{ $date[0] }) 
                                    && trim($dept["overDates"]->{ $date[0] }) != '') CHECKED @endif
                                class="chkOverStatus" autocomplete="off"> 
                            <span class="mL5">{{ $date[1] }}</span>
                        </label>
                    </div>
                    <div class="col-4">
                        <div id="over{{ $dept['id'] }}Status{{ $d }}dateFlds"  class="disNon pT5">
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
            } else {
                document.getElementById(fldID+"dateFlds").style.display="none";
            }
        }
    }
    function chkOverStatus() {
@if (isset($comDepts) && sizeof($comDepts) > 0)
    @foreach ($comDepts as $c => $dept)
        @if (isset($dept["deptRow"]) && isset($dept["deptRow"]->DeptName))
            @foreach ($oversightDates as $d => $date)
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