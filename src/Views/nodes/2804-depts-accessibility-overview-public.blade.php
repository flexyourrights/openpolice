<!-- resources/views/vendor/openpolice/nodes/2804-depts-accessibility-overview-public.blade.php -->
<div id="blockWrap{{ $nID }}" class="w100">
<div class="container" id="treeWrap{{ $nID }}">
<div class="fC"></div><div class="nodeAnchor"><a id="n{{ $nID }}" name="n{{ $nID }}"></a></div>
<div id="node{{ $nID }}">

<div class="row">
    <div class="col-lg-5">
        <h3>Top 50 Most Accessible</h3>
        <table class="table table-striped w100">

@if ($deptScores->scoreDepts->isNotEmpty())
    @foreach ($deptScores->scoreDepts as $i => $dept)
        @if ($i < 50)

            <tr>
                <td>{{ $GLOBALS["SL"]->calcGrade($dept->dept_score_openness) }}</td>
                <td>{{ $dept->dept_score_openness }}</td>
                <td>
                    <a href="/dept/{{ $dept->dept_slug }}">{{ str_replace('Department', 'Dept', 
                        $deptScores->deptNames[$dept->dept_id]) }}</a>
                </td>
            </tr>

        @endif
    @endforeach
        </table>
@else
        <p>&nbsp;</p><p><i>No departments found.</i></p><p>&nbsp;</p>
@endif

@if (isset($deptScores->stats["scoreAvg"]) && intVal($deptScores->stats["scoreAvg"]) > 0)
        <p>&nbsp;</p>
        <h1 class="slBlueDark">
            Average Accessibility Score:
            {{ round($deptScores->stats["scoreAvg"]) }}
        </h1>
        <p class="slGrey">(Highest score is 100)</p>
@endif

    </div>
    <div class="col-lg-2"></div>
    <div class="col-lg-5">

        <h3>Top 50 Largest Departments</h3>
        <table class="table table-striped w100">


@if ($deptScores->scoreDepts->isNotEmpty())
    @foreach ($deptScores->scoreDepts as $i => $dept)
        @if ($i >= 100 && $i < 150)

            <tr>
                <td>{{ $GLOBALS["SL"]->calcGrade($dept->dept_score_openness) }}</td>
                <td>{{ $dept->dept_score_openness }}</td>
                <td>
                    <a href="/dept/{{ $dept->dept_slug }}">{{ str_replace('Department', 'Dept', 
                        $deptScores->deptNames[$dept->dept_id]) }}</a>
                </td>
            </tr>

        @endif
    @endforeach
        </table>
@else
    <p>&nbsp;</p><p><i>No departments found.</i></p><p>&nbsp;</p>
@endif

        <p class="slGrey">By estimated jurisdiction population</p>

    </div>
</div>


</div> <!-- end #node{{ $nID }} -->
</div>
</div>

