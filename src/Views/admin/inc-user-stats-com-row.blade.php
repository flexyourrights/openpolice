<!-- Stored in resources/views/openpolice/admin/inc-user-stats-com-row.blade.php -->

<tr>
    <td>
        <nobr>{{ $GLOBALS["SL"]->printTimeZoneShiftStamp($group->timeStart) }} -
        {{ $GLOBALS["SL"]->printTimeZoneShiftStamp($group->timeEnd, -5, 'g:ia') }}</nobr>
    </td>
    <td>
        {{ $GLOBALS["SL"]->sigFigs(($totDur*60), 2) }}
    </td>
    <td @if (($totDur-($groupStats->tots->deptDur/(60*60))) == 0) class="slGrey" @endif >
        {{ $GLOBALS["SL"]->sigFigs((($totDur*60)-($groupStats->tots->deptDur/60)), 2) }}
    </td>
    <td @if (sizeof($groupStats->comIDs) == 0) class="slGrey" @endif >
        {{ sizeof($groupStats->comIDs) }}
    </td>
    @foreach ($groupStats->cats->emails as $cat => $emaIDs)
        <td @if (sizeof($groupStats->tots->comCats[$cat]) == 0) class="slGrey" @endif >
            {{ sizeof($groupStats->tots->comCats[$cat]) }}
        </td>
    @endforeach
</tr>

