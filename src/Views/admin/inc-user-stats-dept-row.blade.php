<!-- Stored in resources/views/openpolice/admin/inc-user-stats-dept-row.blade.php -->

<tr>
    <td>
        <nobr>{{ $GLOBALS["SL"]->printTimeZoneShiftStamp($group->timeStart) }} -
        {{ $GLOBALS["SL"]->printTimeZoneShiftStamp($group->timeEnd, -5, 'g:ia') }}</nobr>
    </td>
    <td>
        {{ $GLOBALS["SL"]->sigFigs(($totDur*60), 2) }}
    </td>
    <td @if ($groupStats->tots->origDur == 0) class="slGrey" @endif >
        {{ $GLOBALS["SL"]->sigFigs($groupStats->tots->origDur/60, 2) }}
    </td>
    <td @if (($groupStats->tots->deptDur-$groupStats->tots->origDur) == 0) class="slGrey" @endif >
        {{ $GLOBALS["SL"]->sigFigs((($groupStats->tots->deptDur-$groupStats->tots->origDur)/60), 2) }}
    </td>
    <td @if (sizeof($groupStats->tots->orig) == 0) class="slGrey" @endif >
        {{ sizeof($groupStats->tots->orig) }}
    </td>
    <td @if (sizeof($groupStats->deptIDs)-sizeof($groupStats->tots->orig) == 0) class="slGrey" @endif >
        {{ sizeof($groupStats->deptIDs)-sizeof($groupStats->tots->orig) }}
    </td>
    <td @if ($groupStats->tots->cntOnline == 0) class="slGrey" @endif >
        {{ $groupStats->tots->cntOnline }}
    </td>
    <td @if ($groupStats->tots->cntCallDept == 0) class="slGrey" @endif >
        {{ $groupStats->tots->cntCallDept }}
    </td>
    <td @if ($groupStats->tots->cntCallIA == 0) class="slGrey" @endif >
        {{ $groupStats->tots->cntCallIA }}
    </td>
    <td class="brdLftGrey slGrey">
        <i>{{ number_format(sizeof($group->logInds)) }}</i>
    </td>
</tr>