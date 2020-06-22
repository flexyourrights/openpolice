<!-- resources/views/vendor/openpolice/nodes/2804-depts-accessibility-overview-public.blade.php -->
<div id="blockWrap{{ $nID }}" class="w100">
<div class="container" id="treeWrap{{ $nID }}">
<div class="fC"></div>
<div class="nodeAnchor"><a id="n{{ $nID }}" name="n{{ $nID }}"></a></div>
<div id="node{{ $nID }}">

    <div class="row">
        <div class="col-lg-5 col-md-12">
            <p>&nbsp;</p><p>&nbsp;</p>
            <h3>Top 20 Most Accessible Departments</h3>
            <div class="mT15 pB15 pL5 pR5 brdBot">
                <div class="row">
                    <div class="col-8">
                        <b>Department Name</b>
                    </div>
                    <div class="col-4">
                        <b>Accessibility <nobr>Score & Grade</nobr></b>
                    </div>
                </div>
            </div>
    @if ($deptScores->scoreDepts
        && $deptScores->scoreDepts->isNotEmpty())
        @foreach ($deptScores->scoreDepts as $i => $dept)
            @if ($i < 20)
                <div class="pT15 pB15 pL5 pR5 @if ($i%2 == 0) row2 @endif ">
                    <div class="row">
                        <div class="col-8">
                            <a href="/dept/{{ $dept->dept_slug }}">{{ 
                                $deptScores->deptNames[$dept->dept_id] }}</a>
                        </div>
                        <div class="col-2">
                            {{ $dept->dept_score_openness }}
                        </div>
                        <div class="col-2">
                            {{ $GLOBALS["SL"]->calcGrade($dept->dept_score_openness) }}
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @else
            <p>&nbsp;</p><p><i>No departments found.</i></p><p>&nbsp;</p>
    @endif

        </div>
        <div class="col-lg-2 col-md-12"></div>
        <div class="col-lg-5 col-md-12">
            <p>&nbsp;</p><p>&nbsp;</p>

            <h3>Top 20 Largest Departments</h3>
            <div class="mT15 pB15 pL5 pR5 brdBot">
                <div class="row">
                    <div class="col-5">
                        <b>Department Name</b>
                    </div>
                    <div class="col-3">
                        <nobr><b>Employees</b>
                        <a id="hidivBtnInfoEmply" href="javascript:;"
                            class="hidivBtn mL3 slGrey"
                            ><i class="fa fa-info-circle" aria-hidden="true"></i></a></nobr>
                        <div id="hidivInfoEmply" class="disNon slGrey">
                            Total employees, not just officers.
                        </div>
                    </div>
                    <div class="col-4">
                        <b>Accessibility <nobr>Score & Grade</nobr></b>
                    </div>
                </div>
            </div>
    @if (isset($biggest) && sizeof($biggest) > 0)
        @foreach ($biggest as $i => $d)
            @if ($i < 20)
                <div class="pT15 pB15 pL5 pR5 @if ($i%2 == 0) row2 @endif ">
                    <div class="row">
                        <div class="col-5">
                            <a href="/dept/{{ $deptScores->scoreDepts[$d['ind']]->dept_slug 
                                }}">{{ $deptScores->deptNames[$deptScores->scoreDepts[$d["ind"]]->dept_id] }}</a>
                        </div>
                        <div class="col-3">
                        {{ number_format(
                            $deptScores->scoreDepts[$d["ind"]]->dept_tot_officers
                        ) }}
                        </div>
                        <div class="col-2">
                            {{ $deptScores->scoreDepts[$d["ind"]]->dept_score_openness }}
                        </div>
                        <div class="col-2">
                        {{ $GLOBALS["SL"]->calcGrade(
                            $deptScores->scoreDepts[$d["ind"]]->dept_score_openness
                        ) }}
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @else
        <p>&nbsp;</p><p><i>No departments found.</i></p><p>&nbsp;</p>
    @endif

        </div>
    </div>

    <p>&nbsp;</p><hr><p>&nbsp;</p>

</div> <!-- end #node{{ $nID }} -->
</div>
</div>
