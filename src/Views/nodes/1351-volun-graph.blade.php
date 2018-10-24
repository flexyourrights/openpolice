<!-- resources/views/vendor/openpolice/nodes/1351-volun-graph.blade.php -->
<div class="row">
    <div class="col-8">
        <h1><i class="fa fa-users"></i> Department Data Mining Stats</h1>
        <canvas id="volunEditsGraph" class="w100"></canvas>
        <script>
        var ctx = document.getElementById("volunEditsGraph");
        var myChart = new Chart(ctx, {
            height: 300, 
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
    </div>
    <div class="col-4">
        <div class="p20 m20"></div>
        <table class="table table-striped">
        <tr class="slBlueDark">
            <th>{{ $statTots[0][0] }}</th>
            <th>{{ $statTots[1][0] }}</th>
            <th>{{ $statTots[2][0] }}</th>
            <th>&nbsp;</th>
        </tr>
        <tr>
            @foreach ($statTots as $statTot)
                <td><?= number_format($statTot[1]) ?></td>
            @endforeach
            <th>Distinct Volunteers</th>
        </tr>
        <tr>
            @foreach ($statTots as $statTot)
                <td><?= number_format($statTot[2]) ?></td>
            @endforeach
            <th>Distinct Departments</th>
        </tr>
        <tr>
            @foreach ($statTots as $statTot)
                <td><?= number_format($statTot[3]) ?></td>
            @endforeach
            <th>Online Research</th>
        </tr>
        <tr>
            @foreach ($statTots as $statTot)
                <td><?= number_format($statTot[4]) ?></td>
            @endforeach
            <th>Department Calls</th>
        </tr>
        <tr>
            @foreach ($statTots as $statTot)
                <td><?= number_format($statTot[5]) ?></td>
            @endforeach
            <th>Internal Affairs Calls</th>
        </tr>
        <tr>
            @foreach ($statTots as $statTot)
                <td><?= number_format($statTot[6]) ?></td>
            @endforeach
            <th>Total Edits/Saves</th>
        </tr>        
        </table>
    </div>
</div>