<!-- resources/views/vendor/openpolice/nodes/859-depts-overview-public.blade.php -->
<div id="blockWrap{{ $nID }}" class="w100" style="overflow: visible;">
<div class="container" id="treeWrap{{ $nID }}">
<div class="fC"></div><div class="nodeAnchor"><a id="n{{ $nID }}" name="n{{ $nID }}"></a></div>
<div id="node{{ $nID }}" class="slReport" @if ($nID == 1454) style="max-width: 940px;" @endif >

<div id="deptScrHead" class="deptScrHead">
    <p>&nbsp;</p>
    <div class="deptScrHead1">
        <div class="deptScrMore" data-dept="chartHeaders">
            <div class="row">
                <div class="col-3"><div id="fldLabA" class="mT0"></div>
                    {{ $deptScores->scoreDepts->count() }} Departments 
                    <span class="fPerc80 slGrey">(click for details)</span>
                </div>
                <div class="col-1"><div id="fldLabB" class="mT0"></div>Grade</div>
                <div class="col-8"><div class="deptHeadWrap">
                @foreach ($deptScores->chartFlds as $i => $fld) 
                    <div class="fldLab"><div id="fldLab{{ $i }}" class="mT0"></div>{!! $fld[0] !!}</div>
                 @endforeach
                </div></div>
            </div>
            <div class="row mT5 mB10">
                <div class="col-3">
                    <select id="deptStateDrop" class="form-control" 
                        onChange="window.location='/department-accessibility?state='+this.value;">
                        
                    {!! $GLOBALS["SL"]->states->stateDrop($state, true) !!}
                    </select>
                </div>
                <div class="col-1"></div>
                <div class="col-8"><div class="deptHeadWrap">
                @foreach ($deptScores->chartFlds as $i => $fld) <div class="fldLabIco">{!! $fld[2] !!}</div> @endforeach
                </div></div>
            </div>
        </div>
    </div>
    <div class="deptScrHead2">
        <div class="pL10">{{ $deptScores->scoreDepts->count() }} Departments, 
        Accessibility Grade <span class="fPerc80 slGrey">(click anywhere for details)</span></div>
    </div>
</div>
@if ($deptScores->scoreDepts->isNotEmpty())
    @foreach ($deptScores->scoreDepts as $i => $dept)
        <div class="deptScrMore @if ($i%2 == 0) row2 @endif " data-dept="{{ $dept->DeptID }}">
            <div class="row @if ($i%2 == 0) row2 @endif ">
                <div class="col-md-3 col-sm-12 
                    <?php /* @if (strlen($deptScores->deptNames[$dept->DeptID]) > 33) deptScrLab2 @else */ ?> 
                    deptScrLab <?php /* @endif */ ?> ">
                    <b>{{ $deptScores->deptNames[$dept->DeptID] }}</b>
                </div>
                <div class="col-md-1 col-sm-12 deptScrLab deptScrLabNum">
                    {{ $GLOBALS["SL"]->calcGrade($dept->DeptScoreOpenness) }}
                </div>
                <div class="col-md-8 col-sm-12">
                @foreach ($deptScores->chartFlds as $i => $fld)
                    <?php
                    $good = false;
                    if ($fld[1] == 'Notary') {
                        $good = !$deptScores->checkRecFld($deptScores->vals[$fld[1]], $dept->DeptID);
                    } else {
                        $good = $deptScores->checkRecFld($deptScores->vals[$fld[1]], $dept->DeptID);
                    }
                    ?>
                    @if (!$good)
                        <div class="fldBad"><div>
                            <i class="fa fa-times" aria-hidden="true"></i>
                            <span class="chrtIco">{!! $fld[2] !!}&nbsp;&nbsp;{!! strip_tags($fld[0]) !!}</span>
                        </div></div>
                    @else
                        <div class="fldGood"><div>
                            <i class="fa fa-check" aria-hidden="true"></i>
                            <span class="chrtIco">{!! $fld[2] !!}&nbsp;&nbsp;{!! strip_tags($fld[0]) !!}</span>
                        </div></div>
                    @endif
                @endforeach
                </div>
            </div>
            <div id="deptScrMore{{ $dept->DeptID }}" class="disNon p10 @if ($i%2 == 0) row2 @endif ">
                <a href="/dept/{{ $dept->DeptSlug }}" class="deptScrLnk"><h4 class="mB0">{{ 
                        str_replace('Department', 'Dept', $dept->DeptName) }}</h4></a>
                <div class="row">
                    <div class="col-6">
                        {!! $GLOBALS["SL"]->printRowAddy($dept, 'Dept', true) !!}<br />
                        {{ $dept->DeptAddressCounty }} County
                    </div><div class="col-6">
                        OPC Accessibility Score: <b class="slBlueDark">{{ $dept->DeptScoreOpenness }}</b><br />
                        @if (isset($dept->DeptType) && intVal($dept->DeptType) > 0)
                            {{ $GLOBALS["SL"]->def->getVal('Department Types', $dept->DeptType) }},
                        @endif
                        @if ($dept->DeptTotOfficers > 0) {{ number_format($dept->DeptTotOfficers) }} officers @endif
                        @if (isset($dept->DeptVerified) && trim($dept->DeptVerified) != '')
                            <div class="slGrey">updated {{ date('n/j/y', strtotime($dept->DeptVerified)) }}</div>
                        @endif
                    </div>
                </div>
                <div class="row"><div class="col-lg-6 col-md-12">
                <?php $cnt = 0; ?>
                @foreach ($deptScores->vals as $type => $specs)
                    @if ($cnt == floor(sizeof($deptScores->vals)/2)) </div><div class="col-lg-6 col-md-12"> @endif
                    @if ($deptScores->checkRecFld($specs, $dept->DeptID) != 0)
                        <div class=" @if ($cnt%2 == 0) row2 @else bgWht @endif scoreRowOn"><div class="row">
                        <div class="col-1 taR"><i class="fa fa-check-circle mL5" aria-hidden="true"></i></div>
                    @else
                        <div class=" @if ($cnt%2 == 0) row2 @else bgWht @endif scoreRowOff"><div class="row">
                        <div class="col-1">&nbsp;</div>
                    @endif
                        <div class="col-1 taR @if (intVal($specs->score) < 0) scoreNeg @endif ">
                            {{ $specs->score }}
                        </div>
                        <div class="col-9">{{ $specs->label }}</div>
                    </div></div>
                    <?php $cnt++; ?>
                @endforeach
                </div></div>
            </div>
        
            <?php /* <pre>{!! print_r($deptScores->deptOvers[$dept->DeptID]) !!}</pre> */ ?>
            
        </div>
    @endforeach
@else
    <p>&nbsp;</p><p><i>No departments found.</i></p><p>&nbsp;</p>
@endif

@if (isset($deptScores->stats["scoreAvg"]) && intVal($deptScores->stats["scoreAvg"]) > 0)
    <p>&nbsp;</p><p>&nbsp;</p>
    <h1 class="slBlueDark">Average
    @if (isset($state) && trim($state) != '') {{ $GLOBALS["SL"]->getState($state) }} @endif
    Accessibility Score: {{ round($deptScores->stats["scoreAvg"]) }}</h1>
    <span class="slGrey">(out of 100)</span>
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
    padding: 10px 20px 0px 20px;
    width: 1150px;
    margin: @if (isset($state) && trim($state) != '') -509px @else -948px @endif 0px 0px -20px;
    background: #FFF;
}
@-moz-document url-prefix() {
    .deptScrHeadScroll {
        margin-top: @if (isset($state) && trim($state) != '') -509px @else -957px @endif ;
    }
}
.deptScrHead1, .deptScrHead2 { background: #FFF; width: 100%; margin-left: -6px; }
.deptScrHead1 { display: block; }
.deptScrHead2 { display: none; }
.deptScrMore { width: 100%; margin-bottom: 1px; cursor: pointer; }
.deptScrLab, .deptScrLab2 { padding-top: 10px; padding-left: 10px; }
.deptScrLab2 { padding-top: 0px; padding-bottom: 0px; margin-bottom: -2px; }
.deptHeadWrap { padding-left: 5px; }
.fldGood, .fldBad, .fldLab, .fldLabIco {
    display: block;
    text-align: center;
    font-size: 24px;
    margin-right: 1px;
    margin-bottom: 1px;
    width: 89px;
    float: left;
}
.fldGood { background: #2B3493; color: #FFF; }
.fldBad { background: #EC2327; color: #FFF; }
.fldGood div, .fldBad div { padding: 2px 0px; }
.fldLab { font-size: 14px; padding: 0px 2px; }
#fldLab0, #fldLab2, #fldLab3, #fldLab4 { margin-top: 19px; }
#fldLabA, #fldLabB, #fldLab5, #fldLab6 { margin-top: 39px; }
.chrtIco { display: none; margin-left: 20px; }
.deptScrScr { display: block; }
a.deptScrLnk:link, a.deptScrLnk:visited, a.deptScrLnk:active, a.deptScrLnk:hover { font-size: 14pt; }
    
@media screen and (max-width: 1200px) {
    .deptScrHeadScroll {
        width: 965px;
        margin-top: @if (isset($state) && trim($state) != '') -535px; @else -970px; @endif
    }
    @-moz-document url-prefix() {
        .deptScrHeadScroll {
            margin-top: @if (isset($state) && trim($state) != '') -509px; @else -980px; @endif
        }
    }
    .deptScrHeadScroll .deptScrHead1 .deptScrMore .row .col-8 .deptHeadWrap {
        padding-left: 3px;
    }
    @-moz-document url-prefix() {
        .deptScrHeadScroll .deptScrHead1 .deptScrMore .row .col-8 .deptHeadWrap {
            padding-left: 28px; margin-right: -4px;
        }
    }
    .fldGood, .fldBad, .fldLab, .fldLabIco { width: 74px; }
    #fldLab3 { margin-top: 0px; }
    #fldLab5 { margin-top: 19px; }
}
@media screen and (max-width: 991px) {

    #blockWrap1808 { margin-bottom: -120px; }
    #node1815kids { margin-bottom: 40px; }
    .deptScrHeadScroll {
        position: relative;
        margin: 10px 0px 0px -20px; 
        width: 100%;
    }
    .deptScrHead1 { display: none; }
    .deptScrHead2 { display: block; }
    .deptScrLab, .deptScrLab2 { font-size: 20pt; }
    .deptScrMore { margin-top: 20px; }
    .fldGood, .fldBad {
        display: block;
        width: 90%;
        margin-left: 5%;
        text-align: left;
        font-size: 18px;
    }
    .fldGood div, .fldBad div { padding-left: 20px; }
    .chrtIcoFirst { margin-top: -42px; }
    .fldGood div i, .fldBad div i, .fldGood div span i, .fldBad div span i { width: 26px; }
    .chrtIco { display: inline; }
    .deptScrScr { margin: -22px 0px 0px 210px; }
    .scoreRowOn, .scoreRowOff { height: 40px; }
    a.deptScrLnk:link, a.deptScrLnk:visited, a.deptScrLnk:active, a.deptScrLnk:hover { font-size: 18pt; }
    .icoScoreVal, .nPrompt .icoScoreVal { margin: -50px 0px 0px 100px; }
    
}
@media screen and (max-width: 768px) {
    
    .deptScrLabNum { margin-top: -10px; margin-bottom: -30px; }
    .fldGood, .fldBad { width: 90%; margin-left: 8%; font-size: 16px; }
    .chrtIco { margin-left: 5px; }
    .chrtIco i { margin-right: -10px; }
    
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
    .scoreRowOn .col-1 i { margin-left: -2px; }
    .opcAccScr { font-size: 133%; }
    
}

@if ($GLOBALS["SL"]->REQ->has('state') && trim($GLOBALS["SL"]->REQ->get('state')) != '')
    #blockWrap1864 { display: none; }
@endif
</style>
<script type="text/javascript">
function bodyOnScroll() {
    @if (!$GLOBALS["SL"]->REQ->has('state') || trim($GLOBALS["SL"]->REQ->get('state')) == '')
        var currScroll = window.pageYOffset;
        if (document.getElementById("deptScrHead")) {
            if (currScroll < 630) {
                document.getElementById("deptScrHead").className="deptScrHead";
            } else {
                document.getElementById("deptScrHead").className="deptScrHeadScroll";
            }
        }
    @endif
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