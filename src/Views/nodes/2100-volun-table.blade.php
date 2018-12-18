<!-- resources/views/vendor/openpolice/nodes/2100-volun-table.blade.php -->
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