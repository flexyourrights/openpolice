<!-- resources/views/vendor/openpolice/admin/volun/volunDepts.blade.php -->

@extends('vendor.survloop.admin.admin')

@section('content')

<div class="row">
    <div class="col-md-8">
    
        <h1><i class="fa fa-users"></i> Department Data Mining Stats</h1>
        
        <canvas id="myChart" style="width: 100%; height: 300px;" ></canvas>
        <script src="/survloop/Chart.bundle.min.js"></script>
        <script>
        var ctx = document.getElementById("myChart");
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
        
    </div>
    <div class="col-md-4">
    
        <table class="table table-striped slBlueDark mT20">
        <tr class="f14">
            <th>&nbsp;</th>
            <th class="taC">{{ $statTots[0][0] }}</th>
            <th class="taC">{{ $statTots[1][0] }}</th>
            <th class="taC">{{ $statTots[2][0] }}</th>
        </tr>
        <tr>
            <th class="taC f16">Distinct Volunteers</th>
            @foreach ($statTots as $statTot)
                <td class="taC f22"><?= number_format($statTot[1]) ?></td>
            @endforeach
        </tr>
        <tr>
            <th class="taC f16">Distinct Departments</th>
            @foreach ($statTots as $statTot)
                <td class="taC f22"><?= number_format($statTot[2]) ?></td>
            @endforeach
        </tr>
        <tr>
            <th class="taC f16">Online Research</th>
            @foreach ($statTots as $statTot)
                <td class="taC f22"><?= number_format($statTot[3]) ?></td>
            @endforeach
        </tr>
        <tr>
            <th class="taC f16">Department Calls</th>
            @foreach ($statTots as $statTot)
                <td class="taC f22"><?= number_format($statTot[4]) ?></td>
            @endforeach
        </tr>
        <tr>
            <th class="taC f16">Internal Affairs Calls</th>
            @foreach ($statTots as $statTot)
                <td class="taC f22"><?= number_format($statTot[5]) ?></td>
            @endforeach
        </tr>
        <tr>
            <th class="taC f16">Total Edits/Saves</th>
            @foreach ($statTots as $statTot)
                <td class="taC f22"><?= number_format($statTot[6]) ?></td>
            @endforeach
        </tr>        
        </table>

    </div>
</div>


<div class="p20"></div>
<a name="recentEdits"></a> <h2>100 Most Recent Department Edits</h2>
<table class="table table-striped" border=0 cellpadding=10 >
    <tr><th>Edit Details</th><th>Department Info</th><th>Internal Affairs</th><th>Civilian Oversight</th></tr>
    {!! $recentEdits !!}
</table>

<div class="adminFootBuff"></div>

@endsection