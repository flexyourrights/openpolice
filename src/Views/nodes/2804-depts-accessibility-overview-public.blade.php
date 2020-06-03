<!-- resources/views/vendor/openpolice/nodes/2804-depts-accessibility-overview-public.blade.php -->
<div id="blockWrap{{ $nID }}" class="w100">
<div class="container" id="treeWrap{{ $nID }}">
<div class="fC"></div>
<div class="nodeAnchor"><a id="n{{ $nID }}" name="n{{ $nID }}"></a></div>
<div id="node{{ $nID }}">

@if (!$GLOBALS["SL"]->REQ->has('state') 
    || trim($GLOBALS["SL"]->REQ->get('state')) == '')

    <p>&nbsp;</p><p>&nbsp;</p>

    <div class="row">
        <div class="col-lg-5 col-md-12">
            <h3>Top 50 Most Accessible</h3>
            <p>Out of all departments researched.</p>
            <table class="table table-striped w100">
                <tr>
                    <td colspan=2 >Score</td>
                    <td>Department</td>
                    <td class="taR">Employees</td>
                </tr>
    @if ($deptScores->scoreDepts->isNotEmpty())
        @foreach ($deptScores->scoreDepts as $i => $dept)
            @if ($i < 50)
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

            <h3>Top 50 Largest Departments</h3>
            <p>Based on number of employees, not just officers.</p>
            <table class="table table-striped w100">
                <tr>
                    <td colspan=2 >Score</td>
                    <td>Department</td>
                    <td class="taR">Employees</td>
                </tr>
    @if (isset($biggest) && sizeof($biggest) > 0)
        @foreach ($biggest as $i => $d)
            @if ($i < 50)
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

    <h2 class="slBlueDark">
        {{ number_format($deptScores->scoreDepts->count()) }} 
        Departments Researched on OpenPolice.org
    </h2>
    <p>
        <a href="#states">
            <i class="fa fa-arrow-circle-down mR3" aria-hidden="true"></i>
            Below, you can find any department in 
            any state</a>, whether or not it has been researched yet.

    </p>
    <div class="row">
        <div class="col-lg-5 col-md-12">
    @if (isset($nameSort) && sizeof($nameSort) > 0)
            <table class="table table-striped w100">
                <tr>
                    <td colspan=2 >Score</td>
                    <td>Department</td>
                </tr>
        @foreach ($nameSort as $i => $d)
            @if ($i == floor(sizeof($nameSort)/2))
            </table>
        </div>
        <div class="col-lg-2 col-md-12"></div>
        <div class="col-lg-5 col-md-12">
            <table class="table table-striped w100">
                <tr>
                    <td colspan=2 >Score</td>
                    <td>Department</td>
                </tr>
            @endif
                <tr>
                    <td>{{ $GLOBALS["SL"]->calcGrade(
                        $deptScores->scoreDepts[$d["ind"]]->dept_score_openness
                    ) }}</td>
                    <td>{{ $deptScores->scoreDepts[$d["ind"]]->dept_score_openness }}</td>
                    <td>
                        <a href="/dept/{{ $deptScores->scoreDepts[$d['ind']]->dept_slug 
                            }}">{{ str_replace('Department', 'Dept', $d["name"]) }}</a>
                    </td>
                </tr>
        @endforeach
            </table>
    @else
            <p>&nbsp;</p><p><i>No departments found.</i></p><p>&nbsp;</p>
    @endif
        </div>
    </div>

    @if (isset($deptScores->stats["scoreAvg"]) 
        && intVal($deptScores->stats["scoreAvg"]) > 0)
        <h2 class="slBlueDark">
            Average Accessibility Score:
            {{ round($deptScores->stats["scoreAvg"]) }}
        </h2>
        <p>(Highest score is 100)</p>
    @endif

    <p>&nbsp;</p>
    <div class="nodeAnchor"><a id="states" name="states"></a></div>
    <hr><p>&nbsp;</p>

    <h2>
        {{ number_format($deptCnt) }} Police Departments by State
    </h2>
    <p>Click any state for a list of all of its police departments,
        whether or not they have been researched yet.</p>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12"><div class="pT15 pB15">
            <a href="?state=US&test=1" 
                class="btn btn-secondary btn-lg btn-block"
                >Federal Law Enforcement</a>
        </div></div>
    <?php $cnt = 0; ?>
    @foreach ($GLOBALS["SL"]->states->stateList as $abbr => $state)
        <div class="col-lg-4 col-md-6 col-sm-12"><div class="pT10 pB10">
            <a href="?state={{ $abbr }}&test=1" 
                class="btn btn-secondary btn-lg btn-block"
                >{{ $state }}</a>
        </div></div>
        <?php $cnt++; ?>
    @endforeach
    </div>

@else <!-- Filtered for State -->

    <h2>All {{ 
        $GLOBALS["SL"]->getState($GLOBALS["SL"]->REQ->get('state'))
        }} Departments</h2>
    <p>All of police departments in the OpenPolice.org database,
        whether or not they have been researched yet.</p>
    <div class="row">
        <div class="col-lg-5 col-md-12">
    @if ($stateLists && $stateLists->isNotEmpty())
            <table class="table table-striped w100">
                <tr>
                    <td colspan=2 >Score</td>
                    <td>Department</td>
                </tr>
        @foreach ($stateLists as $i => $dept)
            @if ($i == floor($stateLists->count()/2))
            </table>
        </div>
        <div class="col-lg-2 col-md-12"></div>
        <div class="col-lg-5 col-md-12">
            <table class="table table-striped w100">
                <tr>
                    <td colspan=2 >Score</td>
                    <td>Department</td>
                </tr>
            @endif
                <tr>
                @if (isset($dept->dept_verified) 
                    && trim($dept->dept_verified) != '')
                    <td>{{ $GLOBALS["SL"]->calcGrade(
                        $dept->dept_score_openness
                    ) }}</td>
                    <td>{{ $dept->dept_score_openness }}</td>
                @else
                    <td colspan=2 >&nbsp;</td>
                @endif
                    <td>
                        <a href="/dept/{{ $dept->dept_slug }}">{{ 
                            str_replace('Department', 'Dept', $dept->dept_name) 
                        }}</a>
                    </td>
                </tr>
        @endforeach
            </table>
    @else
            <p>&nbsp;</p><p><i>No departments found.</i></p><p>&nbsp;</p>
    @endif
        </div>
    </div>

    @if (isset($stateAvg) && intVal($stateAvg) > 0)
        <p>&nbsp;</p><hr><p>&nbsp;</p>
        <h2 class="slBlueDark">
            {{ $GLOBALS["SL"]->getState($GLOBALS["SL"]->REQ->get('state')) }}
            Average Accessibility Score: {{ round($stateAvg) }}
        </h2>
        <p>(Highest score is 100)</p>
    @endif

    <a href="/department-accessibility?test=1&refresh=1#states"
        class="btn btn-secondary btn-lg">Back to list of all states</a>

    <style>
    #blockWrap1864 { display: none; }
    </style>

@endif
    



</div> <!-- end #node{{ $nID }} -->
</div>
</div>

