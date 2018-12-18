<!-- resources/views/vendor/openpolice/nodes/1351-volun-graph.blade.php -->
<canvas id="volunEditsGraph" class="w100" style="height: 400px;"></canvas>
<script>
var ctx = document.getElementById("volunEditsGraph");
var myChart = new Chart(ctx, {
    height: 400, 
    type: 'line',
    data: {
        labels: [ @foreach ($axisLabels as $i => $l) "{{ $l }}", @endforeach ],
        datasets: [
            {!! $dataLines !!}
        ]
    }
});
</script>
@if ($isDash)
    <div><a href="/dash/volunteer-edits-history">Full Volunteer Stats Report</a></div>
    <div class="p20"> </div>
@endif