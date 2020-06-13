<!-- resources/views/vendor/openpolice/nodes/2804-depts-accessibility-overview-public.blade.php -->
<div id="blockWrap{{ $nID }}" class="w100">
<div class="container" id="treeWrap{{ $nID }}">
<div class="fC"></div>
<div class="nodeAnchor"><a id="n{{ $nID }}" name="n{{ $nID }}"></a></div>
<div id="node{{ $nID }}">

    <p>&nbsp;</p><p>&nbsp;</p>

    <div class="row">
        <div class="col-lg-5 col-md-12">
            <h3>Top 20 Most Accessible</h3>
            <p>Out of all departments researched.</p>
            <table class="table table-striped w100">
                <tr>
                    <td colspan=2 >Score</td>
                    <td>Department</td>
                    <td class="taR">Employees</td>
                </tr>
    @if ($deptScores->scoreDepts->isNotEmpty())
        @foreach ($deptScores->scoreDepts as $i => $dept)
            @if ($i < 20)
                <tr>
                    <td>{{ $GLOBALS["SL"]->calcGrade($dept->dept_score_openness) }}</td>
                    <td>{{ $dept->dept_score_openness }}</td>
                    <td>
                        <a href="/dept/{{ $dept->dept_slug }}">{{ 
                            str_replace('Department', 'Dept', 
                                $deptScores->deptNames[$dept->dept_id]) }}</a>
                    </td>
                @if ($dept->dept_tot_officers > 0)
                    <td class="taR">{{ number_format($dept->dept_tot_officers) }}</td>
                @else
                    <td class="slGrey taR">?</td>
                @endif
                </tr>
            @endif
        @endforeach
            </table>
    @else
            <p>&nbsp;</p><p><i>No departments found.</i></p><p>&nbsp;</p>
    @endif

        </div>
        <div class="col-lg-2 col-md-12"></div>
        <div class="col-lg-5 col-md-12">

            <h3>Top 20 Largest Departments</h3>
            <p>Based on number of employees, not just officers.</p>
            <table class="table table-striped w100">
                <tr>
                    <td colspan=2 >Score</td>
                    <td>Department</td>
                    <td class="taR">Employees</td>
                </tr>
    @if (isset($biggest) && sizeof($biggest) > 0)
        @foreach ($biggest as $i => $d)
            @if ($i < 20)
                <tr>
                    <td>{{ $GLOBALS["SL"]->calcGrade(
                        $deptScores->scoreDepts[$d["ind"]]->dept_score_openness
                    ) }}</td>
                    <td>{{ $deptScores->scoreDepts[$d["ind"]]->dept_score_openness }}</td>
                    <td>
                        <a href="/dept/{{ $deptScores->scoreDepts[$d['ind']]->dept_slug 
                            }}">{{ str_replace('Department', 'Dept', $d["name"]) }}</a>
                    </td>
                    <td class="taR">{{ number_format(
                        $deptScores->scoreDepts[$d["ind"]]->dept_tot_officers
                    ) }}</td>
                </tr>
            @endif
        @endforeach
            </table>
    @else
        <p>&nbsp;</p><p><i>No departments found.</i></p><p>&nbsp;</p>
    @endif

        </div>
    </div>

    <p>&nbsp;</p><hr><p>&nbsp;</p>

</div> <!-- end #node{{ $nID }} -->
</div>
</div>
