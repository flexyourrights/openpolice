<!-- resources/views/vendor/openpolice/inc-map-dept-access-legend.blade.php -->
<div id="legendScoreWrap">
    <p>Click a police department on the map for more details about its <nobr>Accessibility Score.</nobr>
    <nobr>(Higher is better.)</nobr></p>
    <div id="legendScoreTxt" class="row slBlueDark">
        <div class="col-2">0</div>
        <div class="col-3 taC">-</div>
        <div class="col-2 taC">50</div>
        <div class="col-3 taC"><span class="pL15">-</span></div>
        <div class="col-2 taR">100</div>
    </div>
    @for ($i = 0; $i <= 10; $i++)
        <div class="legendScore"><img src="/openpolice/uploads/map-marker-redblue-{{ $i }}.png"
            alt="Accessibility Score {{ (($i == 0) ? 0 : $i . '0') }} out of 100" ></div>
    @endfor
</div>
<style>
#legendScoreWrap { width: 98%; text-align: left; }
#legendScoreTxt { width: 315px; font-size: 175%; font-weight: bold; }
.legendScore { display: inline; margin-right: 3px; }
.legendScore img { width: 22px; }
@media screen and (max-width: 1201px) {
    #legendScoreTxt { width: 251px; }
    .legendScore { margin-right: 0px; }
    .legendScore img { width: 20px; }
}
@media screen and (max-width: 993px) {
    #legendScoreTxt { width: 420px; margin-top: 20px; }
    .legendScore { margin-right: 5px; }
    .legendScore img { width: 28px; }
}
@media screen and (max-width: 576px) {
    #legendScoreTxt { width: 392px; }
    .legendScore img { width: 26px; }
}
@media screen and (max-width: 481px) {
    #legendScoreTxt { width: 292px; }
    .legendScore { margin-right: 1px; }
    .legendScore img { width: 22px; }
}
</style>