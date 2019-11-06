<!-- resources/views/vendor/openpolice/nodes/1816-depts-score-criteria-bars.blade.php -->
@foreach ($datOut as $i => $row)
<div class="barBlock relDiv w100">
    <div class="barLab absDiv">
        <nobr> @if (trim($row[2]) != '') <div class="disIn pL10">{!! $row[2] !!}</div> @endif
        <div class="disIn pL10 pR5"><b>{{ $row[1] }}%</b></div>
        {!! $row[0] !!}</nobr>
    </div>
    <div class="barGood" style="width: {{ $row[1] }}%;"></div>
    <div class="barBad taR" style="width: {{ (100-$row[1]) }}%;"></div>
    <div class="fC"></div>
</div>
@endforeach
<style>
.barBlock {
    margin-bottom: 2px;
}
.barLab {
    top: 4px;
    left: 5px;
    color: #FFF;
}
.barGood, .barBad {
    display: inline;
    float: left;
    height: 30px;
}
.barGood { background: {{ $colorG }}; }
.barBad { background: {{ $colorB }}; }

@media screen and (max-width: 480px) {
.barLab { top: 5px; font-size: 13px; }
}
</style>