<!-- resources/views/vendor/openpolice/dept-kml-desc.blade.php -->
<div id="deptMapKmlDesc{{ $dept['deptRow']->dept_id }}">
    <a href="/dept/{{ $dept['deptRow']->dept_slug }}">
    <h3 class="slBlueDark m0">
        {{ str_replace('Department', 'Dept.', $dept["deptRow"]->dept_name) }}
    </h3>
    View Department Profile</a><br />
    {{ $GLOBALS["SL"]->printRowAddy($dept["deptRow"], 'dept_') }}
    <div class="relDiv mapMrkLgWrp">
        <img src="/openpolice/uploads/map-marker-redblue-lg-{{ 
            round($dept['deptRow']->dept_score_openness/10) 
            }}.png" border=0 class="mapMrkLg" >
        <div class="absDiv">
            <h4 class="mB0">Accessibility Score</h4>
            <h1 class="m0">{{ $dept["deptRow"]->dept_score_openness }}</h1>
        </div>
    </div>
    <div class="mR5">
        {!! view(
            'vendor.openpolice.dept-inc-scores', 
            [
                "score"  => ((isset($dept["score"])) ? $dept["score"] : []),
                "twocol" => true
            ]
        )->render() !!}
    </div>
</div>
<style>
#deptMapKmlDesc{{ $dept["deptRow"]->dept_id }} { display: none; }
.mapMrkLgWrp { margin-top: 15px; }
.mapMrkLg { width: 55px; }
.mapMrkLgWrp .absDiv { top: 2px; left: 69px; }
</style>
<script type="text/javascript">
$(document).ready(function(){
    setTimeout(function() { $("#deptMapKmlDesc{{ $dept['deptRow']->dept_id }}").slideDown(900); }, 50);
});
</script>