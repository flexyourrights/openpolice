<!-- resources/views/vendor/openpolice/nodes/1893-profile-complaints-compliments.blade.php -->

@if ($complaints->isNotEmpty())
    <div class="pT30 pB30">
        <h2 class="slBlueDark m0">Complaints</h2>
        <div id="n{{ $nID }}ajaxLoadA" class="w100">
            {!! $GLOBALS["SL"]->sysOpts["spinner-code"] !!}
        </div>
    </div>
@else
    <div class="pB30"><i>No Complaints</i></div>
@endif

@if ($compliments->isNotEmpty())
    <div class="pT30 pB30">
        <h2 class="slBlueDark m0">Compliments</h2>
        <div id="n{{ $nID }}ajaxLoadB" class="w100">
            {!! $GLOBALS["SL"]->sysOpts["spinner-code"] !!}
        </div>
    </div>
@else
    <!-- <div class="pB30"><i>No Compliments</i></div> -->
@endif

<style>
.reportPreview {
    padding-top: 30px; 
    padding-bottom: 60px; 
    margin-bottom: 30px; 
    border-bottom: 1px #DDD solid; 
}
#node2048kids {
    border-top: 1px #DDD solid;
}
</style>