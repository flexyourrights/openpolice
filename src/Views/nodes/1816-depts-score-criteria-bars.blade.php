<!-- resources/views/vendor/openpolice/nodes/1816-depts-score-criteria-bars.blade.php -->
<div class="mT5">
@foreach ($datOut as $i => $row)
    <div class="barBlock">
        <div class="barLab">
            <nobr>
        @if (trim($row[2]) != '') 
            <div class="pull-left pL10">{!! $row[2] !!}</div>
        @endif
            <div class="pull-left pL10"><b>{{ $row[1] }}%</b></div>
            <div class="pull-left pL10">{!! $row[0] !!}</div>
            </nobr>
        </div>
        <table border="0" cellspacing="0" class="w100 barTbl"><tr>
            <td class="barGood" style="width: {{ $row[1] }}%;"></td>
            <td class="barBad" style="width: {{ (100-$row[1]) }}%;"></td>
        </tr></table>
    </div>
@endforeach
</div>
<style>

#blockWrap1807 { 
   padding-top: 0px;
   padding-bottom: 30px;
   margin-bottom: 30px;
}

.barBlock {
    width: 100%;
    margin-bottom: 20px;
}
.barLab {
    display: block;
    margin-bottom: 2px;
}
.barTbl, .barGood, .barBad {
    height: 5px;
}
.barGood { background: {{ $colorG }}; }
.barBad { background: {{ $colorB }}; }

@media screen and (max-width: 480px) {
    .barLab { top: 5px; font-size: 13px; }
}

</style>