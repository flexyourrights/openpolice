<!-- resources/views/vendor/openpolice/nodes/859-depts-overview-public.blade.php -->
<div id="blockWrap{{ $nID }}" class="w100" style="overflow: visible;">
<div class="container" id="treeWrap{{ $nID }}">
<div class="fC"></div><div class="nodeAnchor"><a id="n{{ $nID }}" name="n{{ $nID }}"></a></div>
<div id="node{{ $nID }}" class="slReport" @if ($nID == 1454) style="max-width: 940px;" @endif >

<p>&nbsp;</p>

<div id="deptScrHead" class="deptScrHead">
    <div class="deptScrHead1">
        <div class="deptScrMore" data-dept="chartHeaders">
            <div class="row">
                <div class="col-md-4"></div>
                @foreach ($deptScores->chartFlds as $i => $fld)
                    <div class="col-md-1 taC vaB">{!! $fld[0] !!}</div>
                @endforeach
            </div>
            <div class="row mT5 mB10">
                <div class="col-md-3">
                    <div class="pL10">{{ $deptScores->scoreDepts->count() }} Departments 
                        <span class="fPerc80 slGrey">(click to break down score)</span></div>
                </div>
                <div class="col-md-1">Grade</div>
                @foreach ($deptScores->chartFlds as $i => $fld)
                    <div class="col-md-1 taC fPerc133">{!! $fld[2] !!}</div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="deptScrHead2">
        <div class="pL10">{{ $deptScores->scoreDepts->count() }} Departments, 
        Accessibility Grade <span class="fPerc80 slGrey">(click anywhere break down score)</span></div>
    </div>
</div>
@if ($deptScores->scoreDepts->isNotEmpty())
    @foreach ($deptScores->scoreDepts as $i => $dept)
        <div class="deptScrMore @if ($i%2 == 0) row2 @endif " data-dept="{{ $dept->DeptID }}">
            <div class="row">
                <div class="col-md-3">
                    <div class=" @if (strlen($deptScores->deptNames[$dept->DeptID]) > 37) deptScrLab2 
                        @else deptScrLab @endif" ><b>{{ $deptScores->deptNames[$dept->DeptID] }}</b></div>
                </div>
                <div class="col-md-1">
                    <div class="deptScrLab">{{ $GLOBALS["SL"]->calcGrade($dept->DeptScoreOpenness) }}</div>
                </div>
                @foreach ($deptScores->chartFlds as $i => $fld)
                    <div class="col-md-1 @if ($i == 0) chrtIcoFirst @endif ">
                    <?php
                    $good = false;
                    if ($fld[1] == 'Notary') {
                        $good = !$deptScores->checkRecFld($deptScores->vals[$fld[1]], 
                            $deptScores->deptOvers[$dept->DeptID]);
                    } else {
                        $good = $deptScores->checkRecFld($deptScores->vals[$fld[1]], 
                            $deptScores->deptOvers[$dept->DeptID]);
                    }
                    ?>
                    @if (!$good)
                        <div class="fldBad"><div><i class="fa fa-times" aria-hidden="true"></i>
                            <span class="chrtIco">{!! $fld[2] !!}&nbsp;&nbsp;{!! strip_tags($fld[0]) !!}</span>
                        </div></div>
                    @else
                        <div class="fldGood"><div><i class="fa fa-check" aria-hidden="true"></i>
                            <span class="chrtIco">{!! $fld[2] !!}&nbsp;&nbsp;{!! strip_tags($fld[0]) !!}</span>
                        </div></div>
                    @endif
                    </div>
                @endforeach
            </div>
            <div id="deptScrMore{{ $dept->DeptID }}" class="disNon p10"><div class="row">
                <div class="col-md-3">
                    <a href="/dept/{{ $dept->DeptSlug }}" class="deptScrLnk">{{ 
                        str_replace('Department', 'Dept', $dept->DeptName) }}</a><br />
                    {!! $GLOBALS["SL"]->printRowAddy($dept, 'Dept', true) !!}<br />
                    {{ $dept->DeptAddressCounty }} County<br />
                    {{ $GLOBALS["SL"]->def->getVal('Department Types', $dept->DeptType) }},
                    @if ($dept->DeptTotOfficers > 0) {{ number_format($dept->DeptTotOfficers) }} 
                    @else <a href="/volunteer" class="slGrey">?</a> @endif officers
                    @if (isset($dept->DeptVerified) && trim($dept->DeptVerified) != '')
                        <br /><span class="slGrey">updated {{ date('n/j/y', strtotime($dept->DeptVerified)) }}</span>
                    @endif
                </div>
                <div class="col-md-1 opcAccScr">
                    OPC Accessibility Score: <div class="deptScrScr"><b>{{ $dept->DeptScoreOpenness }}</b></div>
                </div>
                <div class="col-md-4">
                <?php $cnt = 0; ?>
                @foreach ($deptScores->vals as $type => $specs)
                    @if ($deptScores->checkRecFld($specs, $deptScores->deptOvers[$dept->DeptID]) != 0)
                        <div class=" @if ($cnt%2 == 0) row2 @else bgWht @endif scoreRowOn"><div class="row">
                        <div class="col-md-1"><i class="fa fa-check-circle mL5" aria-hidden="true"></i></div>
                    @else
                        <div class=" @if ($cnt%2 == 0) row2 @else bgWht @endif scoreRowOff"><div class="row">
                        <div class="col-md-1">&nbsp;</div>
                    @endif
                        <div class="col-md-1 taR @if (intVal($specs->score) < 0) scoreNeg @endif ">
                            {{ $specs->score }}
                        </div>
                        <div class="col-md-9 scoreRowLabWrp"><div class="scoreRowLab">{{ $specs->label }}</div></div>
                    </div></div>
                    @if ($cnt == floor(sizeof($deptScores->vals)/2)) </div><div class="col-md-4"> @endif
                    <?php $cnt++; ?>
                @endforeach
                </div>
            </div></div>
        
            <?php /* <pre>{!! print_r($deptScores->deptOvers[$dept->DeptID]) !!}</pre> */ ?>
            
        </div>
    @endforeach
@endif

</div> <!-- end #node{{ $nID }} -->
</div>
</div>

<style>
    #deptScrHead { padding-top: 10px; }
    .deptScrHead {
        position: relative;
        width: 100%;
        margin: 10px 0px 0px 0px; 
    }
    .deptScrHeadScroll {
        position: fixed;
        z-index: 100;
        padding: 10px 20px 0px 0px;
        width: 1157px;
        margin-top: -450px;
        background: #FFF;
    }
    .deptScrHead1, .deptScrHead2 { background: #FFF; width: 100%; }
    .deptScrHead1 { display: block; }
    .deptScrHead2 { display: none; }
    .deptScrMore { width: 100%; margin-bottom: 1px; cursor: pointer; }
    .deptScrLab, .deptScrLab2 { padding-top: 10px; padding-left: 10px; }
    .deptScrLab2 { padding-top: 0px; padding-bottom: 0px; }
    .fldGood, .fldBad {
        text-align: center;
        font-size: 24px;
        color: #FFF;
        width: 140%;
        padding: 3px 0px;
        margin-left: -20%;
        margin-bottom: 1px;
    }
    .fldGood { background: #2B3493; }
    .fldBad { background: #EC2327; }
    .chrtIco { display: none; margin-left: 20px; }
    .deptScrScr { display: block; }
    a.deptScrLnk:link, a.deptScrLnk:visited, a.deptScrLnk:active, a.deptScrLnk:hover { font-size: 14pt; }
    
@media screen and (max-width: 1200px) {
    .deptScrHeadScroll {
        width: 958px;
        margin-top: -536px;
    }
}
@media screen and (max-width: 991px) {

    #blockWrap1808 { margin-bottom: -120px; }
    #node1815kids { margin-bottom: 40px; }
    .deptScrHeadScroll {
        position: relative;
        margin: 10px 0px 0px 0px; 
        width: 100%;
    }
    .deptScrHead1 { display: none; }
    .deptScrHead2 { display: block; }
    .deptScrLab, .deptScrLab2 { font-size: 24pt; }
    .deptScrMore { margin-top: 20px; }
    .fldGood, .fldBad {
        width: 80%;
        margin-left: 10%;
        text-align: left;
        font-size: 20px;
    }
    .fldGood div, .fldBad div { padding-left: 20px; }
    .chrtIcoFirst { margin-top: -42px; }
    .fldGood div i, .fldBad div i, .fldGood div span i, .fldBad div span i { width: 26px; }
    .chrtIco { display: inline; }
    .deptScrScr { margin: -22px 0px 0px 210px; }
    .scoreRowOn, .scoreRowOff { height: 40px; }
    a.deptScrLnk:link, a.deptScrLnk:visited, a.deptScrLnk:active, a.deptScrLnk:hover { font-size: 18pt; }
    .scoreRowLabWrp { position: relative; }
    .scoreRowLab { position: absolute; width: 80%; margin: -21px 0px 0px 70px; }
    .scoreRowOn .col-md-1.taR, .scoreRowOff .col-md-1.taR { text-align: left; margin: -22px 20px 0px 37px; }
    .scoreRowOn .col-md-1.taR.scoreNeg, .scoreRowOff .col-md-1.taR.scoreNeg { margin: -22px 20px 0px 29px; }
    .icoScoreVal, .nPrompt .icoScoreVal { margin: -50px 0px 0px 100px; }
    
}
@media screen and (max-width: 768px) {
    
    .fldGood, .fldBad {
        width: 66%;
        margin-left: 18%;
        font-size: 16px;
    }
    .chrtIco { margin-left: 5px; }
    .chrtIco i { margin-right: -10px; }
    .scoreRowOn, .scoreRowOff { height: 60px; }
    .scoreRowLab { width: 70%; }
    
    
}
@media screen and (max-width: 480px) {
    
    .fldGood, .fldBad {
        width: 80%;
        margin-left: 55px;
        font-size: 14px;
        letter-spacing: -0.02em;                                                           
    }
    .fldGood div, .fldBad div { padding-left: 10px; }
    .chrtIco { margin-left: -5px; }
    .scoreRowLab { width: 80%; margin: -21px 0px 0px 40px; }
    .scoreRowOn .col-md-1 i { margin-left: -2px; }
    .scoreRowOn .col-md-1.taR, .scoreRowOff .col-md-1.taR { margin: -22px 20px 0px 17px; }
    .scoreRowOn .col-md-1.taR.scoreNeg, .scoreRowOff .col-md-1.taR.scoreNeg { margin: -22px 20px 0px 9px; }
    .opcAccScr { font-size: 133%; }
    
}
</style>
<script type="text/javascript">
function bodyOnScroll() {
    var currScroll = window.pageYOffset;
    if (document.getElementById("deptScrHead")) {
        if (currScroll < 300 || currScroll > 6120) {
            document.getElementById("deptScrHead").className="deptScrHead";
        } else {
            document.getElementById("deptScrHead").className="deptScrHeadScroll";
        }
    }
    return true;
}

$(document).ready(function(){
    $(document).on("click", ".deptScrMore", function() {
		var deptID = $(this).attr("data-dept");
		if (document.getElementById('deptScrMore'+deptID+'')) {
		    if (document.getElementById('deptScrMore'+deptID+'').style.display != 'block') {
		        $("#deptScrMore"+deptID+"").slideDown("fast");
		    } else {
		        $("#deptScrMore"+deptID+"").slideUp("fast");
		    }
		}
    });
});
</script>