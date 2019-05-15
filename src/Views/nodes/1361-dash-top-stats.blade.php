<!-- resources/views/vendor/openpolice/nodes/1361-dash-top-stats.blade.php -->
<table class="table table-striped w100">
<tr class="slBlueDark">
    @foreach ($statRanges as $j => $range)
        <td>{!! str_replace('All-Time', '<nobr>All-Time</nobr>', 
            str_replace('24 Hrs', '<nobr>24 Hrs</nobr>', $range[0])) !!}</td>
    @endforeach
    <td>Complaint Status</td>
</tr>
<tr>
    @foreach ($statRanges as $j => $range)
        <td>{{ $dashBetaStats[$j] }}</td>
    @endforeach
    <td>Beta Tester Invites</td>
</tr>
@foreach ($statusDefs as $def)
    <tr>
        @foreach ($statRanges as $j => $range)
            <td>{{ $dashTopStats[$j][$def->DefID] }}</td>
        @endforeach
        <td>{{ $def->DefValue }}</td>
    </tr>
@endforeach
</table>