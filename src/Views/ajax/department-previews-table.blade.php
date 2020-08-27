<!-- Stored in resources/views/openpolice/ajax/department-previews-table.blade.php -->
<div id="deptResultsHead" style="display: none;"
    class="pB15 pL5 pR5 brdBot relDiv">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            Department Name
        </div>
        <div class="col-md-3 col-8">
            City, State
        </div>
        <div class="col-md-3 col-12">
            <div id="colHeadDeptScore">
                Accessibility <nobr>Score & Grade</nobr>
            </div>
        </div>
    </div>
</div>
@if (sizeof($depts) > 0)
    @foreach ($depts as $i => $dept)
        @if ($i < $limit)
            <div id="deptResultsAnim{{ $i }}" style="display: none;"
                class="pT15 pB15 pL5 pR5 @if ($i%2 == 0) row2 @endif ">
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
                    <div class="col-md-3 col-4 slGrey">Not score yet</div>
                @endif
                </div>
            </div>
        @endif
    @endforeach
@else
    <div id="deptResultsNone" class="pT15 pB15 slGrey">No departments found.</div>
@endif
@if (sizeof($depts) >= 1000)
    {{ number_format(sizeof($depts)-1000) }} more results found
@endif
<div id="deptResultsSpin">{!! $GLOBALS["SL"]->spinner() !!}</div>

<script type="text/javascript">
addResultAnimBase("deptResults");
</script>
