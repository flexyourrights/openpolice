<!-- Stored in resources/views/openpolice/admin/inc-user-stats.blade.php -->

<h3 class="slBlueDark">Time Period Totals</h3>
<p>
    Here, complaints are grouped by the furthest they've progressed in our workflow.
    So the "Initial Reviews" category tracks records that did not make it any further
    during this user's sessions. Estimated minutes per department are possbily
    overlapping with estimated minutes per complaint.
</p>

<table class="table table-striped w100">
<tr>
    <th>Time Period</th>
    <th>Total Session Hours</th>
    <th>Complaints Loaded</th>
    @foreach ($groupStats[0]->cats->emails as $cat => $emaIDs)
        <th>{{ $cat }}</th>
    @endforeach
</tr>
@foreach ($calcs as $timeCalcs)
<tr>
    <th><nobr>{{ $timeCalcs->title }}</nobr></th>
    <td @if ($timeCalcs->sessDur == 0) class="slGrey" @endif >
        {{ $GLOBALS["SL"]->sigFigs($timeCalcs->sessDur, 2) }}
    </td>
    <td @if (sizeof($timeCalcs->comIDs) == 0) class="slGrey" @endif >
        {{ sizeof($timeCalcs->comIDs) }}
    </td>
    @foreach ($groupStats[0]->cats->emails as $cat => $emaIDs)
        <td @if (!isset($timeCalcs->comCats[$cat]) || sizeof($timeCalcs->comCats[$cat]) == 0)
                class="slGrey" @endif >
        @if (isset($timeCalcs->comCats[$cat]))
            {{ sizeof($timeCalcs->comCats[$cat]) }}
        @endif
        </td>
    @endforeach
</tr>
@endforeach
</table>

<p><br /></p>

<table class="table table-striped w100">
<tr>
    <th>Time Period</th>
    <th>Total Session Hours</th>
    <th><nobr>Hours On</nobr> <nobr>New Depts</nobr></th>
    <th><nobr>Hours On</nobr> <nobr>Old Depts</nobr></th>

    <th><nobr>Minutes Per</nobr> <nobr>New Dept</nobr></th>
    <th><nobr>Minutes Per</nobr> <nobr>Old Dept</nobr></th>
    <th>Average Min <nobr>Per Dept</nobr></th>
</tr>
@foreach ($calcs as $timeCalcs)
<tr>
    <th><nobr>{{ $timeCalcs->title }}</nobr></th>
    <td @if ($timeCalcs->sessDur == 0) class="slGrey" @endif >
        {{ $GLOBALS["SL"]->sigFigs($timeCalcs->sessDur, 2) }}
    </td>
    <td @if ($timeCalcs->origDur == 0) class="slGrey" @endif >
        {{ $GLOBALS["SL"]->sigFigs($timeCalcs->origDur/(60*60), 2) }}
    </td>
    <td @if (($timeCalcs->deptDur-$timeCalcs->origDur) == 0) class="slGrey" @endif >
        {{ $GLOBALS["SL"]->sigFigs(($timeCalcs->deptDur-$timeCalcs->origDur)/(60*60), 2) }}
    </td>

    <td @if ($timeCalcs->getMinutesPerNewDept() == 0) class="slGrey" @endif >
        {{ $GLOBALS["SL"]->sigFigs($timeCalcs->getMinutesPerNewDept(), 2) }}
    </td>
    <td @if ($timeCalcs->getMinutesPerPastDept() == 0) class="slGrey" @endif >
        {{ $GLOBALS["SL"]->sigFigs($timeCalcs->getMinutesPerPastDept(), 2) }}
    </td>
    <td @if ($timeCalcs->getAvgMinutesPerDept() == 0) class="slGrey" @endif >
        {{ $GLOBALS["SL"]->sigFigs($timeCalcs->getAvgMinutesPerDept(), 2) }}
    </td>
</tr>
@endforeach
</table>

<p><br /></p>

<table class="table table-striped w100">
<tr>
    <th>Time Period</th>
    <th>Total Session Hours</th>
    <th>Average Minutes <nobr>Per Dept</nobr></th>
    @foreach ($groupStats[0]->cats->emails as $cat => $emaIDs)
        @if (strpos($cat, 'Followup') === false)
            <th>Average Minutes Per {{ $cat }}</th>
        @endif
    @endforeach
</tr>
@foreach ($calcs as $timeCalcs)
<tr>
    <th><nobr>{{ $timeCalcs->title }}</nobr></th>
    <td @if ($timeCalcs->sessDur == 0) class="slGrey" @endif >
        {{ $GLOBALS["SL"]->sigFigs($timeCalcs->sessDur, 2) }}
    </td>
    <td @if ($timeCalcs->getAvgMinutesPerDept() == 0) class="slGrey" @endif >
        {{ $GLOBALS["SL"]->sigFigs($timeCalcs->getAvgMinutesPerDept(), 2) }}
    </td>
    @foreach ($groupStats[0]->cats->emails as $cat => $emaIDs)
        @if (strpos($cat, 'Followup') === false)
            <td @if (!isset($timeCalcs->catsDur[$cat]) || $timeCalcs->catsDur[$cat] == 0)
                class="slGrey" @endif >
            @if (isset($timeCalcs->comCats[$cat]) && sizeof($timeCalcs->comCats[$cat]) > 0)
                {{ $GLOBALS["SL"]->sigFigs((($timeCalcs->catsDur[$cat]/60)
                    /sizeof($timeCalcs->comCats[$cat])), 2) }}
            @else 0
            @endif
            </td>
        @endif
    @endforeach
</tr>
@endforeach
</table>

<p><br /></p>

<table class="table table-striped w100">
<tr>
    <th>Time Period</th>
    <th>Total Session Hours</th>
    <th>Hours <nobr>On Depts</nobr></th>
    @foreach ($groupStats[0]->cats->emails as $cat => $emaIDs)
        @if (strpos($cat, 'Followup') === false)
            <th>Hours On {{ $cat }}</th>
        @endif
    @endforeach
</tr>
@foreach ($calcs as $timeCalcs)
<tr>
    <th><nobr>{{ $timeCalcs->title }}</nobr></th>
    <td @if ($timeCalcs->sessDur == 0) class="slGrey" @endif >
        {{ $GLOBALS["SL"]->sigFigs($timeCalcs->sessDur, 2) }}
    </td>
    <td @if (($timeCalcs->deptDur/(60*60)) == 0) class="slGrey" @endif >
        {{ $GLOBALS["SL"]->sigFigs(($timeCalcs->deptDur/(60*60)), 2) }}
    </td>
    @foreach ($groupStats[0]->cats->emails as $cat => $emaIDs)
        @if (strpos($cat, 'Followup') === false)
            <td @if (!isset($timeCalcs->catsDur[$cat]) || $timeCalcs->catsDur[$cat] == 0)
                    class="slGrey" @endif >
            @if (isset($timeCalcs->catsDur[$cat]))
                {{ $GLOBALS["SL"]->sigFigs(($timeCalcs->catsDur[$cat]/(60*60)), 2) }}
            @endif
            </td>
        @endif
    @endforeach
</tr>
@endforeach
</table>

<p><br /></p>


<table class="table table-striped w100">
<tr>
    <th>Time Period</th>
    <th>Total Session Hours</th>
    <th>New Depts</th>
    <th>Old Depts</th>
    <th>Online Research</th>
    <th>Dept Calls</th>
    <th>IA Calls</th>

</tr>
@foreach ($calcs as $timeCalcs)
<tr>
    <th><nobr>{{ $timeCalcs->title }}</nobr></th>
    <td @if ($timeCalcs->sessDur == 0) class="slGrey" @endif >
        {{ $GLOBALS["SL"]->sigFigs($timeCalcs->sessDur, 2) }}
    </td>
    <td @if (sizeof($timeCalcs->orig) == 0) class="slGrey" @endif >
        {{ sizeof($timeCalcs->orig) }}
    </td>
    <td @if (sizeof($timeCalcs->deptIDs) == 0) class="slGrey" @endif >
        {{ (sizeof($timeCalcs->deptIDs)-sizeof($timeCalcs->orig)) }}
    </td>
    <td @if ($timeCalcs->cntOnline == 0) class="slGrey" @endif >
        {{ $timeCalcs->cntOnline }}
    </td>
    <td @if ($timeCalcs->cntCallDept == 0) class="slGrey" @endif >
        {{ $timeCalcs->cntCallDept }}
    </td>
    <td @if ($timeCalcs->cntCallIA == 0) class="slGrey" @endif >
        {{ $timeCalcs->cntCallIA }}
    </td>

</tr>
@endforeach
</table>

<p><br /></p>
<p><br /></p>



<h3 class="slBlueDark">All Session Totals: Complaints</h3>


<table class="table table-striped w100">
<tr>
    <th>Session</th>
    <th>Total Minutes</th>
    <th><nobr>Minutes Not</nobr> <nobr>On Depts</nobr></th>
    <th>Complaints Loaded</th>
    @foreach ($groupStats[0]->cats->emails as $cat => $emaIDs)
        <th>{{ $cat }}</th>
    @endforeach
</tr>
{!! $tblInnerComs !!}
</table>

<p><br /></p>

<h3 class="slBlueDark">All Session Totals: Departments</h3>

<table class="table table-striped w100">
<tr>
    <th>Session</th>
    <th>Total Minutes</th>
    <th><nobr>Minutes On</nobr> <nobr>New Depts</nobr></th>
    <th><nobr>Minutes On</nobr> <nobr>Old Depts</nobr></th>
    <th>New Depts</th>
    <th>Old Depts</th>
    <th>Online Research</th>
    <th>Dept Calls</th>
    <th>IA Calls</th>
    <th class="brdLftGrey slGrey"><i>All Session Logs</i></th>
</tr>
{!! $tblInnerDepts !!}
</table>

<p><br /></p>
