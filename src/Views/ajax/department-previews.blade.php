<!-- Stored in resources/views/openpolice/ajax/department-previews.blade.php -->

<div class="slCard mB15">
@if (isset($deptSearch) && trim($deptSearch) != '')
    @if (isset($stateName) && trim($stateName) != '')
        <h4 class="slBlueDark mB10">"{{ $deptSearch }}", {{ $stateName }}</h4>
    @else 
        <h4 class="slBlueDark mB10">"{{ $deptSearch }}"</h4>
    @endif
@elseif (isset($stateName) && trim($stateName) != '')
    <h4 class="slBlueDark mB10">{{ $stateName }}</h4>
@endif
@if (!isset($depts) || !$depts || sizeof($depts) == 0)
    @if ((isset($deptSearch) && trim($deptSearch) != '')
        || (isset($stateName) && trim($stateName) != ''))
        <h3>No search results found</h3>
    @endif
        Type in part of the name of a police department to search for it.
        Or select a state to browse law enforcement agencies in the OpenPolice.org 
        database.
@else
    <div class="pB15 pL5 pR5 brdBot relDiv">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                Department Name
            </div>
            <div class="col-md-3 col-8">
                City, State
            </div>
            <div class="col-md-3 col-12">
                <div id="colHeadDeptScore">
                    Accessibility 
                    <div id="colHeadDeptScoreLn2">
                        <nobr>Score & Grade</nobr>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($depts as $i => $dept)
        @if ($i < 1000)
            <div class="pT15 pB15 pL5 pR5 @if ($i%2 == 0) row2 @endif ">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <a href="/dept/{{ $dept->dept_slug }}">{{ 
                            str_replace('Department', 'Dept', $dept->dept_name) 
                        }}</a>
                    </div>
                    <div class="col-md-3 col-8">
                        {{ $dept->dept_address_city }}, {{ $dept->dept_address_state }}
                    </div>
                @if (isset($dept->dept_verified) && trim($dept->dept_verified) != '')
                    <div class="col-md-3 col-4">
                        <div class="pull-left" style="width: 40px;">
                            {{ $dept->dept_score_openness }}
                        </div>
                        <div class="pull-left">
                            {{ $GLOBALS["SL"]->calcGrade($dept->dept_score_openness) }}
                        </div>
                    </div>
                @else
                    <div class="col-md-3 col-4"> </div>
                @endif
                </div>
            </div>
        @endif
    @endforeach
    @if (sizeof($depts) >= 1000)
        {{ number_format(sizeof($depts)-1000) }} more results found
    @endif

    <script type="text/javascript"> $(document).ready(function(){

    setTimeout(function() {
        if (document.getElementById("searchFoundCnt")) {
            document.getElementById("searchFoundCnt").innerHTML = '<nobr>{{ number_format(sizeof($depts)) }} Found</nobr>';
        }
        var target_offset = $("#results").offset();
        var target_top = target_offset.top;
        $('html, body').animate({scrollTop:target_top}, 1000, 'easeInSine');
    }, 100);

    }); </script>

@endif
</div>

<style>
#colHeadDeptScore { text-align: left; }
#colHeadDeptScoreLn2 { display: inline; }
@media screen and (max-width: 768px) {
    #colHeadDeptScore { position: absolute; top: -45px; right: 15px; text-align: right; }
    #colHeadDeptScoreLn2 { display: block; }
}
</style>
