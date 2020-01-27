<!-- resources/views/vendor/openpolice/nodes/2717-dept-page-accessibility-score.blade.php -->
@if (isset($d["deptRow"]->dept_score_openness) 
	&& intVal($d["deptRow"]->dept_score_openness) > 0)
    <div class="toggleScoreInfo round10 p20 taC fPerc133
        @if ($d['deptRow']->dept_score_openness >= 70) btn-primary-simple 
        @else btn-danger-simple @endif w100 mB20">
        <div class="pT10 mBn20">Accessibility Grade:</div>
        <div class="icoHuge mBn5">
        	{{ $GLOBALS["SL"]->calcGrade($d["deptRow"]->dept_score_openness) }}
        </div>
    </div>
    
    <a class="toggleScoreInfo btn btn-secondary btn-lg w100 taL" 
    	href="javascript:;"><i id="scoreChev" class="fa fa-chevron-right" 
        	aria-hidden="true" style="width: 18px;"></i>
        Accessibility Score: 
        <b class="mL5" style="font-weight: bold;">{{ 
        $d["deptRow"]->dept_score_openness }}</b></a>
    <div id="toggleScoreInfoDeets" class="disNon">
        {!! view(
            'vendor.openpolice.dept-inc-scores', 
            [ "score" => ((isset($d["score"])) ? $d["score"] : null) ]
        )->render() !!}
        <div class="p5">
            Departments can earn a score of up to 100. 
            <a href="/how-we-rate-departments"
            	>More about how we rate departments.</a>
        </div>
    </div>
@endif
@if (isset($d["deptRow"]->dept_verified) 
	&& trim($d["deptRow"]->dept_verified) != '')
    <div class="mT10 slGrey">
        Department info updated 
        {{ date('n/j/y', strtotime($d["deptRow"]->dept_verified)) }}
    </div>
@endif

<script type="text/javascript">
$(document).ready(function(){
    $(document).on("click", ".toggleScoreInfo", function() {
        if (document.getElementById("toggleScoreInfoDeets") && document.getElementById("scoreChev")) {
            if (document.getElementById("toggleScoreInfoDeets").style.display != "block") {
                document.getElementById("scoreChev").className = "fa fa-chevron-down";
                $("#toggleScoreInfoDeets").slideDown("fast");
            } else {
                document.getElementById("scoreChev").className = "fa fa-chevron-right";
                $("#toggleScoreInfoDeets").slideUp("fast");
            }
        }
    });
});
</script>
